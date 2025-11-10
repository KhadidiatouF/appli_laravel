<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'numCompte' => $this->numCompte,
            'titulaire' => $this->client ? $this->client->user->prenom . ' ' . $this->client->user->nom : null,
            'solde' => $this->solde,
            'devise' => 'FCFA',
            'date_creation' => $this->created_at->toIso8601String(),
            'statut' => $this->statut,
            'metadata' => [
                'derniereModification' => $this->updated_at->toIso8601String(),
                'version' => 1,
            ]
        ];

        if ($this->statut === 'bloque') {
            $data['motifBlocage'] = $this->motif_blocage;
            $data['date_debut_blocage'] = $this->date_debut_blocage;
            $data['date_fin_blocage'] = $this->date_fin_blocage;
        }

        return $data;
    }
}