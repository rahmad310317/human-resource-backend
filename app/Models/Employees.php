<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employees extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'gender',
        'photo',
        'phone',
        'team_id',
        'role_id',
        'is_verified',
        'verified_at'

    ];

    public function teams()
    {
        return $this->belongsTo(Team::class);
    }
    public function roles()
    {
        return $this->hasMany(Role::class);
    }
}
