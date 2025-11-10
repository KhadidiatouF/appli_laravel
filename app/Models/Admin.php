<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Admin extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false; // UUID
    protected $keyType = 'string';
    protected $fillable = ['id', 'user_id'];


      protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

  

}
