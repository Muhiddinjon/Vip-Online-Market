<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['name', 'email', 'phone', 'password', 'role', 'status'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime', 'password' => 'hashed'];

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return $this->role === 'admin' && $this->status === 'active';
        }
        if ($panel->getId() === 'restaurant') {
            return $this->role === 'restaurant' && $this->status === 'active';
        }
        return false;
    }

    public function restaurant() { return $this->hasOne(Restaurant::class); }
    public function courier()    { return $this->hasOne(Courier::class); }
    public function customer()   { return $this->hasOne(Customer::class); }
    public function isAdmin()    { return in_array($this->role, ['admin', 'moderator']); }
}
