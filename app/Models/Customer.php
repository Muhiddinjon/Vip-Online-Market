<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    protected $fillable = ['user_id','name','avatar','google_id'];

    public function user()      { return $this->belongsTo(User::class); }
    public function addresses() { return $this->hasMany(CustomerAddress::class); }
    public function orders()    { return $this->hasMany(Order::class); }
}
