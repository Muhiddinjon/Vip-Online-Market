<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Unit extends Model
{
    protected $fillable = ['slug', 'name', 'status'];
    protected $casts = ['name' => 'array'];

    protected static function booted(): void
    {
        static::creating(function (Unit $unit) {
            if (empty($unit->slug)) {
                $unit->slug = Str::slug($unit->name['uz'] ?? $unit->name['en'] ?? 'unit');
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
