<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'name', 'price', 'sort_order'];
    protected $casts = ['name' => 'array', 'price' => 'float', 'sort_order' => 'integer'];

    public function product() { return $this->belongsTo(Product::class); }
}
