<?php

namespace App\Http\Services;

use App\Models\Client;
use App\Interfaces\RepositoriesInterfaces\IFirstOrCreateRepository;

class ClientService
{
    protected $clientRepository;

    public function __construct(IFirstOrCreateRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function findOrCreate($data): Client
    {
        return $this->clientRepository->findOrCreate($data);
 
    }
}