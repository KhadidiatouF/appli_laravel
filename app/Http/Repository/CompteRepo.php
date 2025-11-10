<?php

namespace App\Http\Repository;
use App\Interfaces\RepositoriesInterfaces\CompteRepositoryInterface;
use App\Models\Compte;
use Illuminate\Pagination\LengthAwarePaginator;

class CompteRepo implements CompteRepositoryInterface{

    protected $model;

    public function __construct(Compte $model){
         $this->model=$model;
    }

    public function findAll()
    {
        return $this->model->all();
    }

    public function getAll(array $filters = [], $sort = 'created_at', $order = 'desc', $limit = 10): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($filters['titulaire'])) {
            $query->where('titulaire', $filters['titulaire']);
        }


        if (!empty($filters['statut'])) {
            $query->withoutGlobalScopes();
            if ($filters['statut'] === 'archive') {
                $query->where('statut', 'supprimé');
            } else {
                $query->where('statut', $filters['statut']);
            }
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('numCompte', 'like', "%$search%")
                  ->orWhereHas('client.user', function ($q2) use ($search) {
                      $q2->where('prenom', 'like', "%$search%")
                         ->orWhere('nom', 'like', "%$search%");
                  });
            });
        }

        return $query->with('client.user')->orderBy($sort, $order)->paginate($limit);
    }

    public function create(array $data): Compte
    {
        return $this->model->create($data);
    }

    
    


}

