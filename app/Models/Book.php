<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['title', 'autor', 'description', 'image', 'emission', 'units', 'status' ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'books_categories');
    }

    
    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

}
