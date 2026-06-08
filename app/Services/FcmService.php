<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PromoNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\ApnsConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmService
{
    private function messaging(): \Kreait\Firebase\Contract\Messaging
    {
        return app('firebase.messaging');
    }

    /**
     * Order statusi o'zgarganda foydalanuvchiga xabar yuboradi.
     */
    public function sendOrderStatusNotification(Order $order): void
    {
        $user = $order->customer?->user;

        if (! $user || ! $user->fcm_token) {
            return;
        }

        $status = $order->status;
        $titles = $this->orderStatusTitles($status);
        $bodies = $this->orderStatusBodies($status, $order->id);

        $message = CloudMessage::new()
            ->withToken($user->fcm_token)
            ->withNotification(
                Notification::create($titles['uz'], $bodies['uz'])
            )
            ->withData([
                'title_uz' => $titles['uz'],
                'title_en' => $titles['en'],
                'title_tr' => $titles['tr'],
                'body_uz'  => $bodies['uz'],
                'body_en'  => $bodies['en'],
                'body_tr'  => $bodies['tr'],
                'order_id' => (string) $order->id,
            ])
            ->withAndroidConfig(
                AndroidConfig::fromArray(['priority' => 'high'])
            )
            ->withApnsConfig(
                ApnsConfig::fromArray([
                    'headers' => ['apns-priority' => '10'],
                    'payload' => ['aps' => ['content-available' => 1]],
                ])
            );

        try {
            $this->messaging()->send($message);
        } catch (\Throwable $e) {
            if ($this->isInvalidToken($e->getMessage())) {
                $user->update(['fcm_token' => null]);
            } else {
                Log::error('FCM sendOrderStatusNotification error', [
                    'order_id' => $order->id,
                    'error'    => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Barcha fcm_token mavjud foydalanuvchilarga reklama xabari yuboradi.
     * Qaytaruvchi qiymat — muvaffaqiyatli yetkazilgan tokenlar soni.
     */
    public function sendPromoNotification(PromoNotification $promo): int
    {
        $successCount  = 0;
        $invalidTokens = [];

        $imageUrl = $promo->image ? asset('storage/' . $promo->image) : null;

        $data = array_filter([
            'title_uz' => $promo->title_uz,
            'title_en' => $promo->title_en,
            'title_tr' => $promo->title_tr,
            'body_uz'  => $promo->body_uz,
            'body_en'  => $promo->body_en,
            'body_tr'  => $promo->body_tr,
            'image'    => $imageUrl,
        ]);

        $baseMessage = CloudMessage::new()
            ->withNotification(
                Notification::create($promo->title_uz, $promo->body_uz)
            )
            ->withData($data)
            ->withAndroidConfig(
                AndroidConfig::fromArray(['priority' => 'high'])
            )
            ->withApnsConfig(
                ApnsConfig::fromArray([
                    'headers' => ['apns-priority' => '10'],
                    'payload' => ['aps' => ['content-available' => 1]],
                ])
            );

        User::whereNotNull('fcm_token')
            ->select('id', 'fcm_token')
            ->chunkById(500, function ($users) use ($baseMessage, &$successCount, &$invalidTokens) {
                $tokens = $users->pluck('fcm_token')->all();

                try {
                    $report = $this->messaging()->sendMulticast($baseMessage, $tokens);
                    $successCount += $report->successes()->count();

                    $stale = array_merge(
                        $report->invalidTokens(),
                        $report->unknownTokens()
                    );
                    array_push($invalidTokens, ...$stale);
                } catch (\Throwable $e) {
                    Log::error('FCM sendPromoNotification chunk error', ['error' => $e->getMessage()]);
                }
            });

        if (! empty($invalidTokens)) {
            User::whereIn('fcm_token', $invalidTokens)->update(['fcm_token' => null]);
        }

        return $successCount;
    }

    private function isInvalidToken(string $message): bool
    {
        return str_contains($message, 'not-registered')
            || str_contains($message, 'invalid-argument')
            || str_contains($message, 'INVALID_ARGUMENT')
            || str_contains($message, 'registration-token-not-registered');
    }

    private function orderStatusTitles(string $status): array
    {
        return match ($status) {
            'confirmed'  => ['uz' => 'Buyurtma tasdiqlandi',    'en' => 'Order confirmed',   'tr' => 'Sipariş onaylandı'],
            'rejected'   => ['uz' => 'Buyurtma rad etildi',     'en' => 'Order rejected',    'tr' => 'Sipariş reddedildi'],
            'preparing'  => ['uz' => 'Buyurtma tayyorlanmoqda', 'en' => 'Preparing order',   'tr' => 'Sipariş hazırlanıyor'],
            'ready'      => ['uz' => 'Buyurtma tayyor',         'en' => 'Order is ready',    'tr' => 'Sipariş hazır'],
            'delivering' => ['uz' => 'Buyurtma yetkazilmoqda',  'en' => 'Order on the way',  'tr' => 'Sipariş yolda'],
            'delivered'  => ['uz' => 'Buyurtma yetkazildi',     'en' => 'Order delivered',   'tr' => 'Sipariş teslim edildi'],
            'cancelled'  => ['uz' => 'Buyurtma bekor qilindi',  'en' => 'Order cancelled',   'tr' => 'Sipariş iptal edildi'],
            default      => ['uz' => 'Buyurtma yangilandi',     'en' => 'Order updated',     'tr' => 'Sipariş güncellendi'],
        };
    }

    private function orderStatusBodies(string $status, int|string $orderId): array
    {
        return match ($status) {
            'confirmed'  => [
                'uz' => "#{$orderId} buyurtmangiz qabul qilindi va tasdiqlandi.",
                'en' => "Your order #{$orderId} has been confirmed.",
                'tr' => "#{$orderId} numaralı siparişiniz onaylandı.",
            ],
            'rejected'   => [
                'uz' => "#{$orderId} buyurtmangiz rad etildi.",
                'en' => "Your order #{$orderId} has been rejected.",
                'tr' => "#{$orderId} numaralı siparişiniz reddedildi.",
            ],
            'preparing'  => [
                'uz' => "#{$orderId} buyurtmangiz tayyorlanmoqda.",
                'en' => "Your order #{$orderId} is being prepared.",
                'tr' => "#{$orderId} numaralı siparişiniz hazırlanıyor.",
            ],
            'ready'      => [
                'uz' => "#{$orderId} buyurtmangiz tayyor, kuryer kutmoqda.",
                'en' => "Your order #{$orderId} is ready, waiting for courier.",
                'tr' => "#{$orderId} numaralı siparişiniz hazır, kurye bekleniyor.",
            ],
            'delivering' => [
                'uz' => "#{$orderId} buyurtmangiz yo'lda, kuryer yetkazib kelmoqda.",
                'en' => "Your order #{$orderId} is on its way.",
                'tr' => "#{$orderId} numaralı siparişiniz yolda.",
            ],
            'delivered'  => [
                'uz' => "#{$orderId} buyurtmangiz muvaffaqiyatli yetkazildi. Xarid uchun rahmat!",
                'en' => "Your order #{$orderId} has been delivered. Thank you!",
                'tr' => "#{$orderId} numaralı siparişiniz teslim edildi. Teşekkürler!",
            ],
            'cancelled'  => [
                'uz' => "#{$orderId} buyurtmangiz bekor qilindi.",
                'en' => "Your order #{$orderId} has been cancelled.",
                'tr' => "#{$orderId} numaralı siparişiniz iptal edildi.",
            ],
            default      => [
                'uz' => "#{$orderId} buyurtmangiz yangilandi.",
                'en' => "Your order #{$orderId} has been updated.",
                'tr' => "#{$orderId} numaralı siparişiniz güncellendi.",
            ],
        };
    }
}
