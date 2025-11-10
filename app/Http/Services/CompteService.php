<?php
namespace App\Http\Services;

use App\Interfaces\RepositoriesInterfaces\CompteRepositoryInterface;
use App\Models\Compte;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CompteService{
    protected $compteRepo;
     protected $userService;
    protected $clientService;

    public function __construct(CompteRepositoryInterface $compteRepo, UserService $userService, ClientService $clientService){
         $this->compteRepo=$compteRepo;
         $this->userService=$userService;
         $this->clientService=$clientService;

    }

    public function getComptes()
    {
        return $this->compteRepo->findAll();
    }


    public function createCompte(array $data): Compte
    {

        // return $this->compteRepo->create($data);
        $user = $this->userService->findOrCreate($data['client']);
        // $client = $this->clientService->findOrCreate($user);
        $client = $this->clientService->findOrCreate(['user_id' => $user->id]);
        return $this->compteRepo->create([
        'titulaire' => $client->id,
        'date_creation' => now()->toDateString()
        ]);
    }

    public function listComptes(array $filters, $sort, $order, $limit): LengthAwarePaginator
    {
        return $this->compteRepo->getAll($filters, $sort, $order, $limit);
 
    }
}