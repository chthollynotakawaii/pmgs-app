<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
public function canAccessPanel(Panel $panel): bool
{
    return $this->role === $panel->getId(); // Match role with panel ID (e.g., 'admin', 'user')
}
public function isOnline(): bool
{
    return $this->last_seen && $this->last_seen->gt(now()->subMinutes(5));
}



    public $timestamps = true;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Determine if the user can access the Filament admin panel.
     *
     * @param \Filament\Panel $panel
     * @return bool
     */
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'department',
        'role',
        'password',
        'last_seen', // ✅ Add this
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_seen' => 'datetime', // ✅ Add this
        ];
    }
    public function department() { return $this->belongsTo(Department::class); }
    public function setUserAttribute($value)
    {
        $this->attributes['name'] = strtoupper($value);
    }
}
