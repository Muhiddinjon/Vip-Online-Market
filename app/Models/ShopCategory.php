<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopCategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'image', 'badge_text', 'status', 'sort_order'];
    protected $casts = [
        'name'       => 'array',
        'sort_order' => 'integer',
        'status'     => 'boolean',
    ];
}
