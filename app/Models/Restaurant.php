<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id','name','description','address','lat','lng','logo','cover_image','phone','working_hours','status','delivery_time','delivery_fee','open_time','close_time','rating'];
    protected $casts = [
        'working_hours' => 'array',
        'description'   => 'array',
        'lat'           => 'float',
        'lng'           => 'float',
        'delivery_time' => 'integer',
        'delivery_fee'  => 'float',
        'rating'        => 'float',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function branches() { return $this->hasMany(Branch::class); }
    public function products() { return $this->hasMany(Product::class); }
    public function orders()   { return $this->hasMany(Order::class); }
}
