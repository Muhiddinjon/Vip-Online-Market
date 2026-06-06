<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'name', 'price', 'sort_order', 'image_visible'];
    protected $casts = ['name' => 'array', 'price' => 'float', 'sort_order' => 'integer', 'image_visible' => 'boolean'];

    public function product() { return $this->belongsTo(Product::class); }
}
