<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = ['title', 'image_path', 'details', 'url', 'status', 'queue'];
    protected $casts = [
        'status' => 'integer',
        'queue'  => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
