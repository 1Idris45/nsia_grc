<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Service;
use App\Models\TypeReclamation;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdminController extends Controller
{
    // --- Agents ---

    public function indexAgents()
    {
        return Inertia::render('Admin/Agents/Index', [
            'agents' => Agent::with(['utilisateur', 'service'])->get(),
        ]);
    }

    public function creerAgentForm()
    {
        return Inertia::render('Admin/Agents/Create', [
            'services' => Service::select('id', 'lib_serv')->get(),
        ]);
    }

    public function storeAgent(Request $request)
    {
        $validated = $request->validate([
            'nom_util' => 'required|string|max:255',
            'prenom_util' => 'required|string|max:255',
            'tel_util' => 'nullable|string|max:20',
            'email_util' => 'required|email|unique:utilisateurs,email_util',
            'password' => 'required|string|min:8',
            'mat_agt' => 'required|string|unique:agents,mat_agt',
            'type_agent' => 'required|in:general,specifique',
            'id_serv' => 'required_if:type_agent,specifique|nullable|exists:services,id',
        ]);

        $utilisateur = Utilisateur::create([
            'nom_util' => $validated['nom_util'],
            'prenom_util' => $validated['prenom_util'],
            'tel_util' => $validated['tel_util'] ?? null,
            'email_util' => $validated['email_util'],
            'password' => Hash::make($validated['password']),
            'role' => 'agent',
        ]);

        Agent::create([
            'id_util' => $utilisateur->id,
            'mat_agt' => $validated['mat_agt'],
            'type_agent' => $validated['type_agent'],
            'id_serv' => $validated['type_agent'] === 'specifique' ? $validated['id_serv'] : null,
        ]);

        return redirect('/admin/agents')->with('success', 'Agent créé avec succès.');
    }

    public function editAgentForm(Agent $agent)
    {
        return Inertia::render('Admin/Agents/Edit', [
            'agent' => $agent->load('utilisateur'),
            'services' => Service::select('id', 'lib_serv')->get(),
        ]);
    }

    public function updateAgent(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'nom_util' => 'required|string|max:255',
            'prenom_util' => 'required|string|max:255',
            'tel_util' => 'nullable|string|max:20',
            'email_util' => 'required|email|unique:utilisateurs,email_util,' . $agent->id_util,
            'mat_agt' => 'required|string|unique:agents,mat_agt,' . $agent->id,
            'type_agent' => 'required|in:general,specifique',
            'id_serv' => 'required_if:type_agent,specifique|nullable|exists:services,id',
        ]);

        $agent->utilisateur->update([
            'nom_util' => $validated['nom_util'],
            'prenom_util' => $validated['prenom_util'],
            'tel_util' => $validated['tel_util'] ?? null,
            'email_util' => $validated['email_util'],
        ]);

        $agent->update([
            'mat_agt' => $validated['mat_agt'],
            'type_agent' => $validated['type_agent'],
            'id_serv' => $validated['type_agent'] === 'specifique' ? $validated['id_serv'] : null,
        ]);

        return redirect('/admin/agents')->with('success', 'Agent mis à jour.');
    }

        // --- Clients ---

    public function indexClients(Request $request)
    {
        $query = \App\Models\Client::with('utilisateur')
            ->withCount('reclamations');

        if ($request->filled('recherche')) {
            $query->whereHas('utilisateur', function ($q) use ($request) {
                $q->where('nom_util', 'ilike', '%' . $request->recherche . '%')
                  ->orWhere('prenom_util', 'ilike', '%' . $request->recherche . '%')
                  ->orWhere('email_util', 'ilike', '%' . $request->recherche . '%');
            });
        }

        return Inertia::render('Admin/Clients/Index', [
            'clients' => $query->latest()->get(),
            'filtres' => $request->only(['recherche']),
        ]);
    }

    public function showClient(\App\Models\Client $client)
    {
        return Inertia::render('Admin/Clients/Show', [
            'client' => $client->load('utilisateur'),
            'reclamations' => \App\Models\Reclamation::with(['statut', 'typeReclamation'])
                ->where('id_clit', $client->id)
                ->latest('dat_reclam')
                ->get(),
        ]);
    }

    // --- Services ---

    public function indexServices()
    {
        return Inertia::render('Admin/Services/Index', [
            'services' => Service::withCount('typesReclamation')->get(),
        ]);
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'lib_serv' => 'required|string|max:255',
            'des_serv' => 'nullable|string',
        ]);

        Service::create($validated);

        return redirect('/admin/services')->with('success', 'Service créé.');
    }

    public function updateService(Request $request, Service $service)
    {
        $validated = $request->validate([
            'lib_serv' => 'required|string|max:255',
            'des_serv' => 'nullable|string',
        ]);

        $service->update($validated);

        return redirect('/admin/services')->with('success', 'Service mis à jour.');
    }

    public function destroyService(Service $service)
    {
        if ($service->typesReclamation()->exists() || $service->agents()->exists()) {
            return back()->withErrors(['service' => "Impossible de supprimer : ce service est encore utilisé par des types de réclamation ou des agents."]);
        }

        $service->delete();

        return redirect('/admin/services')->with('success', 'Service supprimé.');
    }

    // --- Types de réclamation ---

    public function indexTypes()
    {
        return Inertia::render('Admin/Types/Index', [
            'types' => TypeReclamation::with('service')->get(),
            'services' => Service::select('id', 'lib_serv')->get(),
        ]);
    }

    public function storeType(Request $request)
    {
        $validated = $request->validate([
            'lib_typ_rec' => 'required|string|max:255',
            'des_typ_rec' => 'nullable|string',
            'id_serv' => 'nullable|exists:services,id',
        ]);

        TypeReclamation::create($validated);

        return redirect('/admin/types')->with('success', 'Type de réclamation créé.');
    }

    public function updateType(Request $request, TypeReclamation $type)
    {
        $validated = $request->validate([
            'lib_typ_rec' => 'required|string|max:255',
            'des_typ_rec' => 'nullable|string',
            'id_serv' => 'nullable|exists:services,id',
        ]);

        $type->update($validated);

        return redirect('/admin/types')->with('success', 'Type de réclamation mis à jour.');
    }

    public function destroyType(TypeReclamation $type)
    {
        if ($type->reclamations()->exists()) {
            return back()->withErrors(['type' => "Impossible de supprimer : ce type est déjà utilisé par des réclamations."]);
        }

        $type->delete();

        return redirect('/admin/types')->with('success', 'Type supprimé.');
    }
}