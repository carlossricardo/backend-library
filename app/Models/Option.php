<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Option extends Model
{
    
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';
    

    protected $fillable = ['name', 'url', 'icon', 'parent_id', 'status', 'order'];

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
        return $this->belongsToMany(Profile::class, 'options_profiles');
    }

    
    public function children()
    {
        return $this->hasMany(Option::class, 'parent_id');
    }

    
    public function parent()
    {
        return $this->belongsTo(Option::class, 'parent_id');
    }

}

