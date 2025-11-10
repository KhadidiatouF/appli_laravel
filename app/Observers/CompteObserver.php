<?php

namespace App\Observers;

use App\Models\Compte;
Use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\AuthentificationMail;
use App\Http\Services\SmsService;

class CompteObserver
{
    /**
     * Handle the Compte "created" event.
     */
    public function created(Compte $compte): void
    {
        Log::info("Un nouvel utilisateur a été créé : " . $compte->client->user->nom);

        // Envoyer l'email d'authentification
        $user = $compte->client->user;
        $password = $user->plain_password ?? 'password123'; // Utiliser le mot de passe en clair stocké temporairement

        Mail::to($user->email)->send(new AuthentificationMail($user, $password));

        // Envoyer le SMS avec le code
        $smsService = app(SmsService::class);
        $message = "Votre code d'authentification est : " . $user->code;
        $smsService->sendSms($user->telephone, $message);
    }

    public function creating(Compte $compte): void
    {
        if (empty($compte->id)) {
            $compte->id = (string) Str::uuid();
        }

        if (empty($compte->numCompte)) {
            do {
                $numero = self::generateAccountNumber();
            } while (Compte::where('numCompte', $numero)->exists());

            $compte->numCompte = $numero;
        }
    }

    /**
     * Handle the Compte "updated" event.
     */
    public function updated(Compte $compte): void
    {
        //
    }

    /**
     * Handle the Compte "deleted" event.
     */
    public function deleted(Compte $compte): void
    {
        //
    }

    /**
     * Handle the Compte "restored" event.
     */
    public function restored(Compte $compte): void
    {
        //
    }

    /**
     * Handle the Compte "force deleted" event.
     */
    public function forceDeleted(Compte $compte): void
    {
        //
    }

    private static function generateAccountNumber(): string
    {
        $prefix = 'C-';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(4));

        return $prefix . $date . '-' . $random;
    }
}
