<?php

namespace App\Business\Entities;

/**
 * Class Role
 *
 * @package App\Business\Entities
 */
class Role extends Model
{

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->hasMany(User::class);
    }
}