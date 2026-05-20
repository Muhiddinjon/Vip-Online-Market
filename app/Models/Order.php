<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = ['customer_id','restaurant_id','branch_id','courier_id','status','payment_method','payment_status','subtotal','delivery_fee','total','delivery_address','delivery_lat','delivery_lng','note','reject_reason'];
    protected $casts = [
        'subtotal'     => 'float',
        'delivery_fee' => 'float',
        'total'        => 'float',
        'delivery_lat' => 'float',
        'delivery_lng' => 'float',
    ];

    public function customer()   { return $this->belongsTo(Customer::class); }
    public function restaurant() { return $this->belongsTo(Restaurant::class); }
    public function branch()     { return $this->belongsTo(Branch::class); }
    public function courier()    { return $this->belongsTo(Courier::class); }
    public function items()      { return $this->hasMany(OrderItem::class); }
}
