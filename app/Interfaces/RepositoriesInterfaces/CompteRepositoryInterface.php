<?php

namespace App\Interfaces\RepositoriesInterfaces;

use App\Models\Admin;
use App\Models\Client;
use App\Models\Compte;
use Illuminate\Pagination\LengthAwarePaginator;

interface CompteRepositoryInterface
{
    public function findAll();

    public function getAll(array $filters = [], string $sort = 'created_at', string $order = 'desc', int $limit = 10): LengthAwarePaginator;

    // public function findById(string $id): ?Compte;

    public function create(array $data): Compte | Client | Admin
    ;


    // public function update(Compte $compte, array $data): Compte;

    // public function delete(Compte $compte): Compte;
}

