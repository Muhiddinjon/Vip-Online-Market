<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = ['customer_id','restaurant_id','courier_id','status','payment_method','payment_status','subtotal','delivery_fee','total','delivery_address','delivery_lat','delivery_lng','note'];

    public function customer()   { return $this->belongsTo(Customer::class); }
    public function restaurant() { return $this->belongsTo(Restaurant::class); }
    public function courier()    { return $this->belongsTo(Courier::class); }
    public function items()      { return $this->hasMany(OrderItem::class); }
}
