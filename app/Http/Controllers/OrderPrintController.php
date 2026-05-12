<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

class OrderPrintController extends Controller
{
    public function show(Order $order): View
    {
        $user = auth()->user();

        if ($user->role === 'restaurant') {
            abort_if($order->restaurant_id !== $user->restaurant?->id, 403);
        } elseif (! in_array($user->role, ['admin', 'moderator'])) {
            abort(403);
        }

        $order->load(['items', 'customer.user', 'restaurant']);

        return view('orders.print', compact('order'));
    }
}
