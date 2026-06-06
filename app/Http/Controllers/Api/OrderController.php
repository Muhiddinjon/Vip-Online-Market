<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    private static array $cancelableStatuses = ['pending', 'confirmed', 'preparing'];

    private function customer(): Customer
    {
        $user = auth()->user();
        return Customer::firstOrCreate(
            ['user_id' => $user->id],
            ['name'    => $user->name ?? 'Mijoz']
        );
    }

    private function imageUrl(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }

    private function formatOrder(Order $order): array
    {
        $order->loadMissing(['items.product.images', 'restaurant']);

        return [
            'id'               => $order->id,
            'status'           => $order->status,
            'payment_method'   => $order->payment_method,
            'payment_status'   => $order->payment_status,
            'subtotal'         => (float) $order->subtotal,
            'delivery_fee'     => (float) $order->delivery_fee,
            'total'            => (float) $order->total,
            'delivery_address' => $order->delivery_address,
            'delivery_lat'     => $order->delivery_lat,
            'delivery_lng'     => $order->delivery_lng,
            'note'             => $order->note,
            'cancel_reason'    => $order->cancel_reason,
            'can_cancel'       => in_array($order->status, self::$cancelableStatuses),
            'created_at'       => $order->created_at?->toIso8601String(),
            'restaurant'       => $order->restaurant ? [
                'id'   => $order->restaurant->id,
                'name' => $order->restaurant->name,
                'logo' => $this->imageUrl($order->restaurant->logo),
            ] : null,
            'items' => $order->items->map(fn ($item) => [
                'id'           => $item->id,
                'product_id'   => $item->product_id,
                'variant_id'   => $item->variant_id,
                'variant_name' => $item->variant_name,
                'name'         => $item->name,
                'price'        => (float) $item->price,
                'quantity'     => $item->quantity,
                'unit'         => $item->unit,
                'subtotal'     => round($item->price * $item->quantity, 2),
                'image'        => $this->imageUrl($item->product?->images->first()?->path),
            ])->all(),
        ];
    }

    /** GET /api/orders — Buyurtmalar tarixi */
    public function index(Request $request): JsonResponse
    {
        $customer = $this->customer();

        $orders = Order::where('customer_id', $customer->id)
            ->withoutTrashed()
            ->latest()
            ->paginate((int) ($request->per_page ?? 10));

        return response()->json([
            'data' => collect($orders->items())->map(fn ($o) => $this->formatOrder($o)),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page'    => $orders->lastPage(),
                'per_page'     => $orders->perPage(),
                'total'        => $orders->total(),
            ],
        ]);
    }

    /** GET /api/orders/active — Faol buyurtmalar */
    public function active(): JsonResponse
    {
        $customer = $this->customer();

        $orders = Order::where('customer_id', $customer->id)
            ->whereNotIn('status', ['delivered', 'cancelled'])
            ->withoutTrashed()
            ->latest()
            ->get();

        return response()->json([
            'data' => $orders->map(fn ($o) => $this->formatOrder($o)),
        ]);
    }

    /** GET /api/orders/{id} — Buyurtma tafsilotlari */
    public function show(int $id): JsonResponse
    {
        $customer = $this->customer();

        $order = Order::where('customer_id', $customer->id)
            ->withoutTrashed()
            ->findOrFail($id);

        return response()->json(['data' => $this->formatOrder($order)]);
    }

    /** POST /api/orders — Buyurtma berish */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'restaurant_id'      => 'required|integer|exists:restaurants,id',
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.variant_id' => 'nullable|integer',
            'items.*.quantity'   => 'required|integer|min:1|max:99',
            'payment_method'     => 'required|in:cash,card',
            'delivery_address'   => 'nullable|string|max:500',
            'delivery_lat'       => 'nullable|numeric',
            'delivery_lng'       => 'nullable|numeric',
            'note'               => 'nullable|string|max:500',
        ]);

        $customer     = $this->customer();
        $restaurantId = (int) $request->restaurant_id;
        $productIds   = collect($request->items)->pluck('product_id')->unique()->all();

        // Mahsulotlarni tekshirish
        $products = Product::withoutTrashed()
            ->whereIn('id', $productIds)
            ->where('restaurant_id', $restaurantId)
            ->where('is_available', true)
            ->with(['images', 'variants'])
            ->get()
            ->keyBy('id');

        foreach ($request->items as $item) {
            if (!isset($products[$item['product_id']])) {
                return response()->json([
                    'message' => "Mahsulot #{$item['product_id']} topilmadi yoki mavjud emas.",
                ], 422);
            }
        }

        // Variant ID larni yuklash
        $variantIds = collect($request->items)->pluck('variant_id')->filter()->unique()->all();
        $variants   = $variantIds
            ? ProductVariant::whereIn('id', $variantIds)->get()->keyBy('id')
            : collect();

        // Narxlarni hisoblash
        $subtotal  = 0;
        $lineItems = [];
        foreach ($request->items as $item) {
            $product  = $products[$item['product_id']];
            $qty      = (int) $item['quantity'];
            $locale   = app()->getLocale();

            $variantId   = $item['variant_id'] ?? null;
            $variant     = $variantId ? ($variants[$variantId] ?? null) : null;

            // Variant ushbu mahsulotga tegishli ekanligini tekshirish
            if ($variant && $variant->product_id !== $product->id) {
                return response()->json([
                    'message' => "Variant #{$variantId} bu mahsulotga tegishli emas.",
                ], 422);
            }

            $price    = $variant ? (float) $variant->price : (float) $product->price;
            $subtotal += $price * $qty;

            $langName     = $product->name[$locale] ?? $product->name['uz'] ?? $product->name['en'] ?? '';
            $variantName  = $variant
                ? ($variant->name[$locale] ?? $variant->name['uz'] ?? $variant->name['en'] ?? null)
                : null;

            $lineItems[] = [
                'product_id'   => $product->id,
                'variant_id'   => $variant?->id,
                'variant_name' => $variantName,
                'name'         => $langName,
                'price'        => $price,
                'quantity'     => $qty,
                'unit'         => $product->unit ?? 'dona',
            ];
        }

        $deliveryFee = 0;
        $total       = $subtotal + $deliveryFee;

        $order = DB::transaction(function () use ($request, $customer, $restaurantId, $subtotal, $deliveryFee, $total, $lineItems) {
            $order = Order::create([
                'customer_id'      => $customer->id,
                'restaurant_id'    => $restaurantId,
                'status'           => 'pending',
                'payment_method'   => $request->payment_method,
                'payment_status'   => 'pending',
                'subtotal'         => $subtotal,
                'delivery_fee'     => $deliveryFee,
                'total'            => $total,
                'delivery_address' => $request->delivery_address,
                'delivery_lat'     => $request->delivery_lat,
                'delivery_lng'     => $request->delivery_lng,
                'note'             => $request->note,
            ]);

            foreach ($lineItems as $item) {
                $order->items()->create($item);
            }

            return $order;
        });

        return response()->json(['data' => $this->formatOrder($order)], 201);
    }

    /** POST /api/orders/{id}/cancel — Buyurtmani bekor qilish */
    public function cancel(int $id, Request $request): JsonResponse
    {
        $customer = $this->customer();

        $order = Order::where('customer_id', $customer->id)
            ->withoutTrashed()
            ->findOrFail($id);

        if (!in_array($order->status, self::$cancelableStatuses)) {
            return response()->json([
                'message' => 'Bu buyurtmani bekor qilib bo\'lmaydi. Restoran allaqachon tayyorlashni boshlagan.',
            ], 422);
        }

        $order->update([
            'status'        => 'cancelled',
            'cancel_reason' => $request->input('reason'),
        ]);

        return response()->json(['data' => $this->formatOrder($order)]);
    }
}
