<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Client extends Model
{
    use HasFactory  , HasUuids;

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

    public function compte(){
        return $this->hasMany(Compte::class);
    }

}
