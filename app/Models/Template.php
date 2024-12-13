<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Template extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'type',
        'content',
    ];


    public function campaign() : HasMany
    {
        return $this->hasMany(Campaign::class);
    }

    public function blocks() : HasMany
    {
        return $this->hasMany(Block::class);
    }

}
