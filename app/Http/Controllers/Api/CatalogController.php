<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Restaurant;
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

    private function formatRestaurant(Restaurant $r): array
    {
        return [
            'id'          => $r->id,
            'name'        => $r->name,
            'description' => $this->localize($r->description),
            'address'     => $r->address,
            'lat'         => $r->lat,
            'lng'         => $r->lng,
            'logo'        => $this->imageUrl($r->logo),
            'cover_image' => $this->imageUrl($r->cover_image),
            'phone'       => $r->phone,
            'status'      => $r->status,
        ];
    }

    private function formatCategory(Category $c): array
    {
        return [
            'id'         => $c->id,
            'name'       => $this->localize($c->name),
            'sort_order' => $c->sort_order,
        ];
    }

    private function formatProduct(Product $p): array
    {
        return [
            'id'             => $p->id,
            'restaurant_id'  => $p->restaurant_id,
            'category_id'    => $p->category_id,
            'name'           => $this->localize($p->name),
            'description'    => $this->localize($p->description),
            'price'          => (float) $p->price,
            'original_price' => $p->original_price ? (float) $p->original_price : null,
            'unit'           => $p->unit,
            'is_available'   => (bool) $p->is_available,
            'images'         => $p->images->map(fn ($img) => $this->imageUrl($img->path))->values()->all(),
            'image'          => $this->imageUrl($p->images->first()?->path),
        ];
    }

    // --------------- Endpoints ---------------

    /** GET /api/restaurants */
    public function restaurants(Request $request): JsonResponse
    {
        $query = Restaurant::withoutTrashed()
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

    /** GET /api/restaurants/{id} */
    public function restaurant(int $id): JsonResponse
    {
        $restaurant = Restaurant::withoutTrashed()
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

    /** GET /api/categories */
    public function categories(Request $request): JsonResponse
    {
        $categories = Category::withoutTrashed()
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($c) => $this->formatCategory($c));

        return response()->json(['data' => $categories]);
    }

    /** GET /api/products */
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

    /** GET /api/products/{id} */
    public function product(int $id): JsonResponse
    {
        $product = Product::withoutTrashed()
            ->with('images')
            ->where('is_available', true)
            ->findOrFail($id);

        return response()->json(['data' => $this->formatProduct($product)]);
    }
}
