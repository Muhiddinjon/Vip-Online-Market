<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\JsonResponse;

class ConfigController extends Controller
{
    /** GET /api/config — platform=1 bo'lgan sozlamalar */
    public function index(): JsonResponse
    {
        $configs = Config::where('platform', 1)
            ->orderBy('id')
            ->get()
            ->map(fn (Config $c) => [
                'keyword' => $c->keyword,
                'value'   => $c->type === 'switch'
                    ? in_array($c->value, ['1', 'true'])
                    : $c->value,
                'type'    => $c->type,
            ]);

        return response()->json(['data' => $configs]);
    }
}
