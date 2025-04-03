<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_DIRECTOR = 'director';
    public const ROLE_ACCOUNTANT = 'accountant';
    public const ROLE_CHIEF_ACCOUNTANT = 'chief_accountant';
    public const ROLE_HR_MANAGER = 'hr_manager';
    public const ROLE_EMPLOYEE = 'employee';

    public const ROLES = [
        self::ROLE_DIRECTOR => 'Giám đốc',
        self::ROLE_ACCOUNTANT => 'Kế toán viên',
        self::ROLE_CHIEF_ACCOUNTANT => 'Kế toán trưởng',
        self::ROLE_HR_MANAGER => 'Trưởng phòng nhân sự',
        self::ROLE_EMPLOYEE => 'Nhân viên',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role',
        'email',
        'password',
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
        ];
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }
}
