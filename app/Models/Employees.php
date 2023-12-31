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
        'age',
        'photo',
        'phone',
        'team_id',
        'role_id',
        'is_verified',
        'verified_at'

    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
