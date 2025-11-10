<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Compte extends Model
{
    use HasFactory, HasUuids;


    public $incrementing = false; 
    protected $keyType = 'string';


     protected $fillable = [
         'numCompte',
         'titulaire',
         'date_creation',
         'statut',
         'date_debut_bloquage',
         'date_fin_bloquage',
     ];

    protected $casts = [
        'date_creation' => 'date',
        'date_debut_bloquage' => 'datetime',
        'date_fin_bloquage' => 'datetime',
    ];

    protected static function booted()
    {
       
    }


    public function client(){
        return $this->belongsTo(Client::class, 'titulaire');
    }


    // public function getSoldeAttribute(): float
    // {
    //     $depot = $this->transactions()->where('type', 'depot')->sum('montant');
    //     $retrait = $this->transactions()->where('type', 'retrait')->sum('montant');
    //     return $depot - $retrait;
    // }

}
