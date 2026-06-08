<?php

namespace App\Observers;

use App\Models\Order;
use App\Services\FcmService;

class OrderObserver
{
    public function __construct(private readonly FcmService $fcm) {}

    public function updated(Order $order): void
    {
        if ($order->wasChanged('status')) {
            $this->fcm->sendOrderStatusNotification($order);
        }
    }
}
