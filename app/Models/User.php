<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'avatar',
        'role_id',
        'password',
        'is_active',
        'is_on_line',
        'school_id',
        'work_on_year',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_on_line' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the school that owns the User
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    /**
     * Get all of the payments for the User
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the role that owns the User
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * The singleAppLinks that belong to the User
     */
    public function singleAppLinks(): BelongsToMany
    {
        return $this->belongsToMany(SingleAppLink::class)->withPivot('id');
    }

    /**
     * The subLinks that belong to the User
     */
    public function subLinks(): BelongsToMany
    {
        return $this->belongsToMany(SubLink::class)->withPivot('id');
    }

    /**
     * The multiAppLinks that belong to the User
     */
    public function multiAppLinks(): BelongsToMany
    {
        return $this->belongsToMany(MultiAppLink::class)->withPivot(('id'));
    }

    /**
     * Scope for reusable query on user model
     */
    public function scopeFilter(Builder $query, string $q = ''): Builder
    {
        return $query->join('roles', 'roles.id', 'users.role_id')
            ->when($q, function ($query, $keyToSearch) {
                return $query->where('name', 'like', '%' . $keyToSearch . '%')
                    ->orWhere('phone', 'like', '%' . $keyToSearch . '%')
                    ->orWhere('email', 'like', '%' . $keyToSearch . '%');
            })->select('users.*')->with(['role']);
    }
}
