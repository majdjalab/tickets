<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use  HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'password',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Str::upper($value)
        );
    }

    // protected function password(): Attribute
    // {
    //     return Attribute::make(
    //         set: fn ($value) => bcrypt($value)
    //     );
    // }
    protected function isAdmin(): Attribute
    {
        $admins = ['majd.jalab@lead-alliance.net', 'majd@majd.com'];
        return Attribute::make(
            get: fn () => in_array($this->email, $admins)
        );
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function createdTicket( $dateStart, $dateEnd){
      return $this->tickets()->whereBetween('created_at', [$dateStart, $dateEnd]);

    }
    public function assignedTicket( $dateStart, $dateEnd){
        return $this->tickets()->whereBetween('assigned_at', [$dateStart, $dateEnd]);

    }
    public function updatedTicket( $dateStart, $dateEnd){
        return $this->tickets()->whereBetween('updated_at', [$dateStart, $dateEnd]);

    }

}
