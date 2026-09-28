<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ReclamationController;
use App\Http\Controllers\Api\StatistiqueController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Accessible aux deux rôles (Client + Agent) — le filtrage des données
    // se fait à l'intérieur du contrôleur selon le rôle connecté
    Route::get('/reclamations', [ReclamationController::class, 'index']);
    Route::get('/reclamations/{reclamation}', [ReclamationController::class, 'show']);
    Route::get('/statistiques/dashboard', [StatistiqueController::class, 'dashboard']);

    // Routes réservées aux clients
    Route::middleware('role:client')->group(function () {
        Route::post('/reclamations', [ReclamationController::class, 'store']);
    });

    // Routes réservées aux agents (général + spécifique)
    Route::middleware('role:agent')->group(function () {
        Route::patch('/reclamations/{reclamation}/statut', [ReclamationController::class, 'changerStatut']);
    });
});