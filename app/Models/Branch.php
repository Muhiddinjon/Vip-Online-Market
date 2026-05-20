<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $fillable = ['restaurant_id', 'name', 'address', 'lat', 'lng', 'phone', 'working_hours', 'status'];
    protected $casts = [
        'working_hours' => 'array',
        'lat'           => 'float',
        'lng'           => 'float',
    ];

    public function restaurant() { return $this->belongsTo(Restaurant::class); }
    public function orders()     { return $this->hasMany(Order::class); }
    public function products()   { return $this->belongsToMany(Product::class, 'branch_product')->withPivot('is_available'); }
}
