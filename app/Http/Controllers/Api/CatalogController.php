<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\Restaurant;
use App\Models\ShopCategory;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatalogController extends Controller
{
    // --------------- Helpers ---------------

    private function lang(): string
    {
        $lang = request('lang', 'uz');
        return in_array($lang, ['uz', 'en', 'tr']) ? $lang : 'uz';
    }

    private function localize(?array $field): string
    {
        if (!$field) return '';
        $lang = $this->lang();
        return $field[$lang] ?? $field['uz'] ?? $field['en'] ?? $field['tr'] ?? '';
    }

    private function imageUrl(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }

    private function isRestaurantOpen(Restaurant $r): bool
    {
        if (!$r->open_time || !$r->close_time) return true;

        $now   = Carbon::now()->format('H:i');
        $open  = substr($r->open_time, 0, 5);
        $close = substr($r->close_time, 0, 5);

        // Overnight schedule (e.g. 22:00 - 02:00)
        if ($open > $close) {
            return $now >= $open || $now <= $close;
        }

        return $now >= $open && $now <= $close;
    }

    private function formatBranch(Branch $b): array
    {
        return [
            'id'      => $b->id,
            'name'    => $b->name,
            'address' => $b->address,
            'lat'     => $b->lat,
            'lng'     => $b->lng,
            'phone'   => $b->phone,
            'status'  => $b->status,
        ];
    }

    private function formatRestaurant(Restaurant $r): array
    {
        $data = [
            'id'            => $r->id,
            'name'          => $r->name,
            'description'   => $this->localize($r->description),
            'address'       => $r->address,
            'lat'           => $r->lat,
            'lng'           => $r->lng,
            'logo'          => $this->imageUrl($r->logo),
            'cover_image'   => $this->imageUrl($r->cover_image),
            'phone'         => $r->phone,
            'status'        => $r->status,
            'delivery_time' => $r->delivery_time,
            'delivery_fee'  => $r->delivery_fee ? (float) $r->delivery_fee : null,
            'open_time'     => $r->open_time ? substr($r->open_time, 0, 5) : null,
            'close_time'    => $r->close_time ? substr($r->close_time, 0, 5) : null,
            'is_open'       => $this->isRestaurantOpen($r),
            'rating'        => $r->rating ? (float) $r->rating : 5.0,
        ];

        if ($r->relationLoaded('branches')) {
            $data['branches'] = $r->branches
                ->where('status', 'active')
                ->values()
                ->map(fn ($b) => $this->formatBranch($b))
                ->all();
        }

        return $data;
    }

    private function formatCategory(Category $c): array
    {
        return [
            'id'         => $c->id,
            'name'       => $this->localize($c->name),
            'image'      => $this->imageUrl($c->image_path),
            'sort_order' => $c->sort_order,
        ];
    }

    private function formatProduct(Product $p): array
    {
        $price     = (float) $p->price;
        $sale      = (float) ($p->sale ?? 0);
        $salePrice = $sale > 0 ? round($price * (1 - $sale / 100), 2) : null;

        return [
            'id'             => $p->id,
            'restaurant_id'  => $p->restaurant_id,
            'category_id'    => $p->category_id,
            'name'           => $this->localize($p->name),
            'description'    => $this->localize($p->description),
            'price'          => $price,
            'original_price' => $p->original_price ? (float) $p->original_price : null,
            'sale'           => $sale,
            'sale_price'     => $salePrice,
            'unit'           => $p->unit,
            'is_available'   => (bool) $p->is_available,
            'images'         => $p->images->map(fn ($img) => $this->imageUrl($img->path))->values()->all(),
            'image'          => $this->imageUrl($p->images->first()?->path),
        ];
    }

    // --------------- Endpoints ---------------

    /** GET /api/catalog/shop-categories */
    public function shopCategories(): JsonResponse
    {
        $categories = ShopCategory::withoutTrashed()
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($c) => [
                'id'         => $c->id,
                'name'       => $this->localize($c->name),
                'image'      => $this->imageUrl($c->image),
                'badge_text' => $c->badge_text,
                'status' => $c->status,
                'sort_order' => $c->sort_order,
            ]);

        return response()->json(['data' => $categories]);
    }

    /** GET /api/catalog/advertisements */
    public function advertisements(): JsonResponse
    {
        $ads = Advertisement::active()
            ->orderBy('queue')
            ->get()
            ->map(fn ($a) => [
                'id'         => $a->id,
                'title'      => $a->title,
                'image'      => $this->imageUrl($a->image_path),
                'details'    => $a->details,
                'url'        => $a->url,
                'queue'      => $a->queue,
            ]);

        return response()->json(['data' => $ads]);
    }

    /** GET /api/catalog/restaurants */
    public function restaurants(Request $request): JsonResponse
    {
        $query = Restaurant::withoutTrashed()
            ->with(['branches' => fn ($q) => $q->where('status', 'active')->orderBy('name')])
            ->where('status', 'active')
            ->when($request->search, fn ($q) => $q->where('name', 'LIKE', "%{$request->search}%"))
            ->when($request->sort === 'name_asc',  fn ($q) => $q->orderBy('name'))
            ->when($request->sort === 'name_desc', fn ($q) => $q->orderByDesc('name'))
            ->unless($request->sort, fn ($q) => $q->orderBy('id'));

        $restaurants = $query->paginate((int) ($request->per_page ?? 20));

        return response()->json([
            'data' => collect($restaurants->items())->map(fn ($r) => $this->formatRestaurant($r)),
            'meta' => [
                'current_page' => $restaurants->currentPage(),
                'last_page'    => $restaurants->lastPage(),
                'per_page'     => $restaurants->perPage(),
                'total'        => $restaurants->total(),
            ],
        ]);
    }

    /** GET /api/catalog/restaurants/{id} */
    public function restaurant(int $id): JsonResponse
    {
        $restaurant = Restaurant::withoutTrashed()
            ->with(['branches' => fn ($q) => $q->where('status', 'active')->orderBy('name')])
            ->where('status', 'active')
            ->findOrFail($id);

        $categories = Category::withoutTrashed()
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        $products = Product::withoutTrashed()
            ->with('images')
            ->where('restaurant_id', $id)
            ->where('is_available', true)
            ->get();

        $grouped = $categories->map(function (Category $c) use ($products) {
            $items = $products->where('category_id', $c->id)->values();
            if ($items->isEmpty()) return null;
            return array_merge($this->formatCategory($c), [
                'products' => $items->map(fn ($p) => $this->formatProduct($p))->all(),
            ]);
        })->filter()->values();

        return response()->json([
            'data' => array_merge($this->formatRestaurant($restaurant), [
                'categories' => $grouped,
            ]),
        ]);
    }

    /** GET /api/catalog/categories */
    public function categories(Request $request): JsonResponse
    {
        $categories = Category::withoutTrashed()
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($c) => $this->formatCategory($c));

        return response()->json(['data' => $categories]);
    }

    /** GET /api/catalog/products */
    public function products(Request $request): JsonResponse
    {
        $search = trim($request->search ?? '');

        $query = Product::withoutTrashed()
            ->with('images')
            ->where('is_available', true)
            ->when($request->restaurant_id, fn ($q) => $q->where('restaurant_id', $request->restaurant_id))
            ->when($request->category_id,   fn ($q) => $q->where('category_id',   $request->category_id))
            ->when($search, fn ($q) => $q->where(function ($q) use ($search) {
                $q->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(name, "$.uz")) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('JSON_UNQUOTE(JSON_EXTRACT(name, "$.en")) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('JSON_UNQUOTE(JSON_EXTRACT(name, "$.tr")) LIKE ?', ["%{$search}%"]);
            }))
            ->when($request->sort === 'price_asc',  fn ($q) => $q->orderBy('price'))
            ->when($request->sort === 'price_desc', fn ($q) => $q->orderByDesc('price'))
            ->when($request->sort === 'name_asc',   fn ($q) => $q->orderByRaw('JSON_UNQUOTE(JSON_EXTRACT(name, "$.uz"))'))
            ->when($request->sort === 'name_desc',  fn ($q) => $q->orderByRaw('JSON_UNQUOTE(JSON_EXTRACT(name, "$.uz")) DESC'))
            ->unless($request->sort, fn ($q) => $q->orderBy('id'));

        $products = $query->paginate((int) ($request->per_page ?? 20));

        return response()->json([
            'data' => collect($products->items())->map(fn ($p) => $this->formatProduct($p)),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'per_page'     => $products->perPage(),
                'total'        => $products->total(),
            ],
        ]);
    }

    /** GET /api/catalog/products/{id} */
    public function product(int $id): JsonResponse
    {
        $product = Product::withoutTrashed()
            ->with('images')
            ->where('is_available', true)
            ->findOrFail($id);

        return response()->json(['data' => $this->formatProduct($product)]);
    }
}
