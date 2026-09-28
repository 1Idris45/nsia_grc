<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ProfilController extends Controller
{
    public function show(Request $request)
    {
        return Inertia::render('Profil/Show', [
            'utilisateur' => $request->user()->load(['client', 'agent.service']),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'nom_util' => 'required|string|max:255',
            'prenom_util' => 'required|string|max:255',
            'tel_util' => 'nullable|string|max:20',
            'adres_util' => 'nullable|string|max:255',
            'email_util' => 'required|email|unique:utilisateurs,email_util,' . $user->id,
        ]);

        $user->update($validated);

        return redirect('/profil')->with('success', 'Profil mis à jour.');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return redirect('/profil')->with('success', 'Mot de passe mis à jour.');
    }
}