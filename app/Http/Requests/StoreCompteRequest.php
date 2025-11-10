<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCIN;

class StoreCompteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'solde' => 'required|numeric|min:10000',
            'titulaire'=>'required|string|max:100',
            'client.prenom' => 'required|string|max:100',
            'client.nom' => 'required|string|max:100',
            'client.email' => 'required|email',
            'client.telephone' => ['required'],
            'client.adresse' => 'nullable|string|max:255',
            'client.nci' => ['nullable', 'string', new ValidCIN()], // Déplacer la règle ValidCIN ici
         ];
    }

    public function messages(): array
    {
         return [
            'solde.required' => 'Le solde initial est obligatoire.',
            'solde.numeric' => 'Le solde doit être un nombre.',
            'solde.min' => 'Le solde initial doit être d\'au moins 10 000 FCFA.',
            'client.prenom.required' => 'Le prénom du client est obligatoire.',
            'client.nom.required' => 'Le nom du client est obligatoire.',
            'client.email.required' => 'L\'email du client est obligatoire.',
            'client.email.email' => 'L\'email du client n\'est pas valide.',
            'client.telephone.required' => 'Le téléphone du client est obligatoire.',
            'client.nci.unique' => 'Ce numéro CNI est déjà utilisé.',
        ];
    }
}
