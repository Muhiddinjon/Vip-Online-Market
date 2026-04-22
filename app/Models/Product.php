<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = ['restaurant_id', 'category_id', 'name', 'description', 'price', 'unit', 'is_available'];
    protected $casts = ['name' => 'array', 'description' => 'array', 'is_available' => 'boolean'];

    // Prevent Filament FileUpload from accidentally setting the images relationship
    public function setImagesAttribute(mixed $value): void {}

    public function restaurant() { return $this->belongsTo(Restaurant::class); }
    public function category()   { return $this->belongsTo(Category::class); }
    public function images()     { return $this->hasMany(ProductImage::class)->orderBy('sort_order'); }
}
