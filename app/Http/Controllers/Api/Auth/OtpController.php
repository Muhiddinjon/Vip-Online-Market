<?php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Exception;

class OtpController extends Controller
{
    public function send(Request $request)
    {
        $request->validate(['phone' => 'required|string']);

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'phone'      => $request->phone,
            'code'       => $code,
            'expires_at' => now()->addMinutes(5),
        ]);

        try
        {
            $url = "https://send.smsxabar.uz/broker-api/send";

            $headers = [
                "Content-Type" => "application/json; charset=utf-8",
                "Cache-Control" => "no-cache",
                "Authorization" => "Basic cW9xb25zaXR5OmdINzl+WEo2eSpzXw=="
            ];

            // Remove leading '+998' if present
            if (strpos($phone, '+998') !== false) {
                $phone = substr($phone, 4);
            }

            $data = [
                "messages" => [                   
                    "recipient" => "998" . $phone,
                    "message-id" => "ip" . $code,
                    "sms" => [
                        "originator" => "3700",
                        "content" => [
                            "text" => "Vip Online Market: Tasdiqlash kodi - " . $code
                        ]
                    ]
                ]
            ];

            $client = new Client();

            $promise = $client->postAsync($url, [
                'headers' => $headers,
                'json' => $data,
                'timeout' => 3
            ]);

            // Attach success and error callbacks
            $promise->then(
                function ($response) {

                },
                function ($exception) {
                    // Error callback
                    // Handle the exception as needed
                    \Log::error("An error occurred: " . $exception->getMessage());
                }
            );

            $promise->wait();

        }
        catch (Exception $e) 
        {
            // Log the error
            Log::error("An error occurred: " . $e->getMessage());
        }

        // TODO: real SMS integration
        // SmsService::send($request->phone, "Tasdiqlash kodi: $code");

        return response()->json(['message' => 'SMS yuborildi.']);
    }

    public function verify(Request $request)
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

        $otp->update(['used_at' => now()]);

        $user = User::firstOrCreate(
            ['phone' => $request->phone],
            ['name' => 'Mijoz', 'role' => 'customer', 'password' => bcrypt(Str::random(16))]
        );

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => ['id' => $user->id, 'name' => $user->name, 'phone' => $user->phone, 'role' => $user->role],
        ]);
    }
}
