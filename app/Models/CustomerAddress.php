<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAddress extends Model
{
    use SoftDeletes;
    protected $fillable = ['customer_id','label','address','lat','lng','is_default'];
    protected $casts = ['is_default' => 'boolean'];

    public function customer() { return $this->belongsTo(Customer::class); }
}
