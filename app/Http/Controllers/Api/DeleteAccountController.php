<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeleteAccountController extends Controller
{
    /** POST /api/account/delete/request — OTP yuborish */
    public function request(Request $request): JsonResponse
    {
        $request->validate(['phone' => 'required|string']);

        $user = User::where('phone', $request->phone)
            ->whereIn('status', ['active', 'blocked'])
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Bu raqam bilan hisob topilmadi.'], 404);
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'phone'      => $request->phone,
            'code'       => $code,
            'expires_at' => now()->addMinutes(5),
        ]);

        $this->sendSms($request->phone, $code);

        return response()->json(['message' => 'Tasdiqlash kodi yuborildi.']);
    }

    /** POST /api/account/delete/confirm — OTP tasdiqlash va hisobni o'chirish */
    public function confirm(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string',
            'code'  => 'required|string|size:6',
        ]);

        $otp = OtpCode::where('phone', $request->phone)
            ->where('code', $request->code)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (!$otp || $otp->isExpired()) {
            return response()->json(['message' => 'Kod noto\'g\'ri yoki muddati tugagan.'], 422);
        }

        $user = User::where('phone', $request->phone)
            ->whereIn('status', ['active', 'blocked'])
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Hisob topilmadi.'], 404);
        }

        $otp->update(['used_at' => now()]);

        $user->tokens()->delete();
        $user->update(['status' => 'deleted']);

        return response()->json(['message' => 'Hisobingiz muvaffaqiyatli o\'chirildi.']);
    }

    private function sendSms(string $phone, string $code): void
    {
        try {
            $normalized = ltrim(str_replace('+998', '', $phone), '0');

            $client = new Client();
            $client->postAsync('https://send.smsxabar.uz/broker-api/send', [
                'headers' => [
                    'Content-Type'  => 'application/json; charset=utf-8',
                    'Authorization' => 'Basic cW9xb25zaXR5OmdINzl+WEo2eSpzXw==',
                ],
                'json' => [
                    'messages' => [
                        'recipient'  => '998' . $normalized,
                        'message-id' => 'del' . $code,
                        'sms'        => [
                            'originator' => '3700',
                            'content'    => ['text' => 'Vip Online Market: Hisobni o\'chirish kodi - ' . $code],
                        ],
                    ],
                ],
                'timeout' => 3,
            ])->wait();
        } catch (\Exception $e) {
            Log::error('DeleteAccount SMS error: ' . $e->getMessage());
        }
    }
}
