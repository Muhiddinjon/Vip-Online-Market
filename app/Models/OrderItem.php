<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = ['order_id','product_id','variant_id','variant_name','name','price','quantity','unit'];
    protected $casts = [
        'product_id' => 'integer',
        'variant_id' => 'integer',
        'price'      => 'float',
        'quantity'   => 'integer',
    ];

    public function order()   { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function variant() { return $this->belongsTo(ProductVariant::class); }
}
