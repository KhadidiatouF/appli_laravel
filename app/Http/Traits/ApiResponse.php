<?php

namespace App\Http\Traits;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{

    protected  function successResponse(string $message, $data = null, int $code = 200): JsonResponse
    {

        return response()->json(
            [
                'success' => true,
                'message' => $message,
                "data" => $data,

            ],
            $code
        );
    }
    protected function errorResponse(
        string $message = 'Erreur serveur',
        int $code = 500,
        $errors = null
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
    protected function  paginatedResponse($paginator, string $message = 'Liste paginée récupérée avec succès'): JsonResponse
    {

        $metadonne = [

            'date_creation' => now()->toIso8601String(),
            'version' => 1

        ];

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator['data'],
            "metadonnees" => $metadonne,
            'pagination' => $paginator['meta'],
            'links' => $paginator['links']
        ], 200);
    }
}

