<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'company_id'
    ];

    public function companies()
    {
        return $this->belongsTo(Company::class);
    }

    public function responsiblities()
    {
        return $this->hasMany(Responsibility::class);
    }
    public function employees()
    {
        return $this->belongsTo(Employees::class);
    }
}
