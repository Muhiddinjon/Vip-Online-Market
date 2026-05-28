<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','image_path','shown_in_main','sort_order','status'];
    protected $casts = ['name' => 'array', 'sort_order' => 'integer', 'shown_in_main' => 'boolean'];

    public function products() { return $this->hasMany(Product::class); }
}
