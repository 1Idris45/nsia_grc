<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Inscription — réservée aux clients.
     * Les agents sont créés par un administrateur (via seeder ou back-office), pas via cette route.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nom_util' => 'required|string|max:255',
            'prenom_util' => 'required|string|max:255',
            'tel_util' => 'nullable|string|max:20',
            'adres_util' => 'nullable|string|max:255',
            'email_util' => 'required|email|unique:utilisateurs,email_util',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $utilisateur = Utilisateur::create([
            'nom_util' => $validated['nom_util'],
            'prenom_util' => $validated['prenom_util'],
            'tel_util' => $validated['tel_util'] ?? null,
            'adres_util' => $validated['adres_util'] ?? null,
            'email_util' => $validated['email_util'],
            'password' => Hash::make($validated['password']),
            'role' => 'client',
        ]);

        Client::create(['id_util' => $utilisateur->id]);

        Auth::login($utilisateur);

        return redirect('/dashboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email_util' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt([
            'email_util' => $credentials['email_util'],
            'password' => $credentials['password'],
        ])) {
            throw ValidationException::withMessages([
                'email_util' => ['Identifiants incorrects.'],
            ]);
        }

        $request->session()->regenerate();

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function me(Request $request)
    {
        return response()->json(
            $request->user()->load(['client', 'agent.service'])
        );
    }
}