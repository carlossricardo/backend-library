<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Loan extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['user_id', 'date_returned', 'note', 'reviewed_by', 'total_units', 'status'];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });

    }



    
    const STATUS_ACTIVE = 'active';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_RETURNED = 'returned';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_REJECTED = 'rejected';

    
    protected $attributes = [
        'status' => self::STATUS_ACTIVE, 
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {        
        return $this->belongsTo(User::class, 'reviewed_by');
    }


    public function details()
    {
        return $this->hasMany(LoanDetail::class);
    }




}
