<?php
namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
