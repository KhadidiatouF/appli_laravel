<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompteRequest;
use App\Http\Resources\CompteResource;
use App\Http\Services\CompteService;
use Exception;

/**
 * @OA\Info(
 *     title="OmPay - API de Gestion des Comptes Bancaires",
 *     version="1.0.0",
 *     description="API OmPay pour la gestion des comptes bancaires avec création automatique d'utilisateurs et clients"
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000",
 *     description="Serveur de développement"
 * )
 * 
 * @OA\Server(
 *     url="https://appli-laravel-groupe.onrender.com/",
 *     description="Serveur de production"
 * )
 *
 * @OA\Schema(
 *     schema="Pagination",
 *     type="object",
 *     title="Pagination",
 *     description="Informations de pagination",
 *     @OA\Property(property="current_page", type="integer", example=1),
 *     @OA\Property(property="per_page", type="integer", example=10),
 *     @OA\Property(property="total", type="integer", example=50),
 *     @OA\Property(property="last_page", type="integer", example=5),
 *     @OA\Property(property="from", type="integer", example=1),
 *     @OA\Property(property="to", type="integer", example=10)
 * )
 *
 * @OA\Schema(
 *     schema="Links",
 *     type="object",
 *     title="Links",
 *     description="Liens de navigation",
 *     @OA\Property(property="first", type="string", example="http://api.banque.example.com/api/v1/comptes?page=1"),
 *     @OA\Property(property="last", type="string", example="http://api.banque.example.com/api/v1/comptes?page=5"),
 *     @OA\Property(property="prev", type="string", nullable=true, example=null),
 *     @OA\Property(property="next", type="string", example="http://api.banque.example.com/api/v1/comptes?page=2")
 * )
 *
 * @OA\Schema(
 *     schema="Compte",
 *     type="object",
 *     title="Compte",
 *     description="Objet représentant un compte bancaire",
 *     @OA\Property(property="id", type="string", format="uuid", example="550e8400-e29b-41d4-a716-446655440000"),
 *     @OA\Property(property="numeroCompte", type="string", example="C-20251025-ABCD"),
 *     @OA\Property(property="titulaire", type="string", example="Mamadou Diallo"),
 *     @OA\Property(property="solde", type="number", format="float", example=500000),
 *     @OA\Property(property="devise", type="string", enum={"FCFA", "XOF", "EUR", "USD"}, example="FCFA"),
 *     @OA\Property(property="dateCreation", type="string", format="date-time", example="2025-10-25T17:33:20Z"),
 *     @OA\Property(property="statut", type="string", enum={"actif", "inactif", "bloqué"}, example="actif"),
 *     @OA\Property(property="motifBlocage", type="string", nullable=true, example=null),
 *     @OA\Property(property="informationsBlocage", type="object", nullable=true,
 *         description="Informations de blocage pour les comptes bloqués",
 *         @OA\Property(property="dateDebutBlocage", type="string", format="date-time", example="2025-10-30T12:00:00Z"),
 *         @OA\Property(property="dateFinBlocage", type="string", format="date-time", example="2025-11-02T12:00:00Z"),
 *         @OA\Property(property="dureeBlocageJours", type="integer", example=3, description="Durée du blocage en jours")
 *     ),
 *     @OA\Property(property="metadata", type="object",
 *         @OA\Property(property="derniereModification", type="string", format="date-time", example="2025-10-25T17:33:20Z"),
 *         @OA\Property(property="version", type="integer", example=1)
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ClientData",
 *     type="object",
 *     required={"prenom", "nom", "email", "telephone"},
 *     @OA\Property(property="prenom", type="string", maxLength=100, description="Prénom du client"),
 *     @OA\Property(property="nom", type="string", maxLength=100, description="Nom du client"),
 *     @OA\Property(property="email", type="string", format="email", description="Adresse email"),
 *     @OA\Property(property="telephone", type="string", description="Numéro de téléphone"),
 *     @OA\Property(property="adresse", type="string", maxLength=255, description="Adresse"),
 *     @OA\Property(property="nci", type="string", description="Numéro CNI")
 * )
 *
 * @OA\Schema(
 *     schema="CreateCompteRequest",
 *     type="object",
 *     required={"solde", "client"},
 *     @OA\Property(property="solde", type="number", minimum=10000, description="Solde initial (minimum 10 000 FCFA)"),
 *     @OA\Property(property="client", ref="#/components/schemas/ClientData", description="Informations du client")
 * )
 *
 * @OA\Schema(
 *     schema="ErrorResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", description="Message d'erreur"),
 *     @OA\Property(property="errors", type="object", description="Détails des erreurs de validation")
 * )
 *
 * @OA\Schema(
 *     schema="SuccessResponse",
 *     type="object",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", description="Message de succès"),
 *     @OA\Property(property="data", ref="#/components/schemas/Compte", description="Données du compte créé")
 * )
 *
 * @OA\Components(
 *     @OA\SecurityScheme(
 *         securityScheme="bearerAuth",
 *         type="http",
 *         scheme="bearer",
 *         bearerFormat="JWT",
 *         description="Entrez votre token JWT ici"
 *     )
 * )
 *
 * @OA\Tag(
 *     name="Comptes",
 *     description="Gestion des comptes bancaires"
 * )
 */
class CompteController extends Controller
{
    use ApiResponse;
    protected $compteService;

    public function __construct(CompteService $compteService) {
        $this->compteService = $compteService;
    }

    /**
     * Lister tous les comptes (Admin) ou les comptes du client connecté
     *
     * @OA\Get(
     *     path="/api/v1/comptes",
     *     summary="Lister les comptes",
     *     description="Récupère la liste paginée des comptes avec possibilité de filtrage et tri",
     *     operationId="getComptes",
     *     tags={"Comptes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Numéro de page",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Nombre d'éléments par page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10, maximum=100)
     *     ),
     *     @OA\Parameter(
     *         name="statut",
     *         in="query",
     *         description="Filtrer par statut",
     *         required=false,
     *         @OA\Schema(type="string", enum={"actif", "inactif", "bloqué"})
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Recherche par titulaire ou numéro",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Tri",
     *         required=false,
     *         @OA\Schema(type="string", enum={"dateCreation", "solde", "titulaire"})
     *     ),
     *     @OA\Parameter(
     *         name="order",
     *         in="query",
     *         description="Ordre",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des comptes",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Compte")),
     *             @OA\Property(property="pagination", ref="#/components/schemas/Pagination"),
     *             @OA\Property(property="links", ref="#/components/schemas/Links")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non autorisé"),
     *     @OA\Response(response=403, description="Accès refusé")
     * )
     */
    public function index(Request $request)
    {
        try {
            // $user = auth()->user();

            $filters = $request->only(['type', 'statut', 'search']);
            $sort = $request->get('sort', 'created_at');
            $order = $request->get('order', 'desc');
            $limit = min($request->get('limit', 10), 100);

            $comptes = $this->compteService->listComptes($filters, $sort, $order, $limit);
            $compteCollection = CompteResource::collection($comptes)->response()->getData(true);

            return $this->paginatedResponse($compteCollection, 'Liste des comptes');
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    /**
     * Créer un nouveau compte
     *
     * @OA\Post(
     *     path="/api/v1/comptes",
     *     summary="Créer un nouveau compte",
     *     description="Crée un nouveau compte bancaire. Si le client n'existe pas, il est créé automatiquement avec génération de mot de passe et code. Un email et un SMS sont envoyés automatiquement.",
     *     operationId="createCompte",
     *     tags={"Comptes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateCompteRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Compte créé avec succès",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Données invalides",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(response=401, description="Non autorisé"),
     *     @OA\Response(response=403, description="Accès refusé")
     * )
     */
    public function store(StoreCompteRequest $request){
        try{

           $data= $request->all();

            $compte = $this->compteService->createCompte($data);
            $compteResource = new CompteResource($compte);
        //    return $this->paginatedResponse($compteResource, 'Compte créé avec succes ! ');
            return $this->successResponse('Compte créé avec succes !', $compteResource);



        }catch(\Throwable $e){
            throw $e;
        }
    }
}