<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{
    protected $fillable = ['phone','code','expires_at','used_at'];
    protected $casts = ['expires_at' => 'datetime', 'used_at' => 'datetime'];

    public function isExpired(): bool { return now()->gt($this->expires_at); }
    public function isUsed(): bool    { return !is_null($this->used_at); }
    public function isValid(): bool   { return !$this->isExpired() && !$this->isUsed(); }
}
