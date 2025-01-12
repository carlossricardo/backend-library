<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Privilege extends Model
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

    public function profiles(){
        return $this->belongsToMany(Profile::class, 'profiles_privileges');
    }


}


