<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $fillable = ['title', 'keyword', 'value', 'type', 'platform'];
    protected $casts    = ['title' => 'array', 'platform' => 'integer'];

    public static function get(string $keyword, mixed $default = null): mixed
    {
        $config = static::where('keyword', $keyword)->first();
        if (!$config) return $default;
        if ($config->type === 'switch') return in_array($config->value, ['1', 'true']);
        return $config->value ?? $default;
    }
}
