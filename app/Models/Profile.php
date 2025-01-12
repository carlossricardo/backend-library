<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;


class Profile extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['name', 'description', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function users(){
        return $this->belongsToMany(User::class, 'users_profiles');
    }

    public function options(){
        return $this->belongsToMany(Option::class, 'options_profiles');
    }

    public function privileges(){
        return $this->belongsToMany(Privilege::class, 'profiles_privileges');
    }
}
