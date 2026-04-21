<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courier extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id','vehicle_type','plate_number','avatar','status','current_lat','current_lng'];

    public function user()   { return $this->belongsTo(User::class); }
    public function orders() { return $this->hasMany(Order::class); }
}
