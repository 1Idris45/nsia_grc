<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReclamationRequest;
use App\Models\Historique;
use App\Models\PieceJointe;
use App\Models\Reclamation;
use App\Models\Service;
use App\Models\StatutReclamation;
use App\Models\TypeReclamation;
use App\Services\AffectationService;
use App\Services\SlaService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReclamationController extends Controller
{
    public function __construct(
        private AffectationService $affectationService,
        private SlaService $slaService,
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();

        $query = Reclamation::with(['statut', 'typeReclamation', 'service', 'client.utilisateur']);

        if ($user->role === 'client') {
            $query->where('id_clit', $user->client->id);
        } elseif ($user->role === 'agent') {
            $agent = $user->agent;
            if ($agent->type_agent === 'specifique') {
                $query->where('id_serv', $agent->id_serv);
            }
        }

        return response()->json($query->latest('dat_reclam')->paginate(15));
    }

    public function show(Request $request, Reclamation $reclamation)
    {
        $this->autoriserAcces($request, $reclamation);

        return response()->json(
            $reclamation->load(['statut', 'typeReclamation', 'service', 'agent.utilisateur', 'piecesJointes', 'historiques', 'client.utilisateur'])
        );
    }

    public function creerForm(Request $request)
    {
        return Inertia::render('Reclamations/Create', [
            'types' => TypeReclamation::select('id', 'lib_typ_rec')->get(),
        ]);
    }

    public function store(StoreReclamationRequest $request)
    {
        $client = $request->user()->client;
        $type = TypeReclamation::findOrFail($request->id_typ_rec);
        $statutInitial = StatutReclamation::where('lib_stat', 'Nouvelle')->firstOrFail();

        $maintenant = now();

        $reclamation = Reclamation::create([
            'obj_reclam' => $request->obj_reclam,
            'des_reclam' => $request->des_reclam,
            'dat_reclam' => $maintenant->toDateString(),
            'date_echeance' => $this->slaService->calculerDateEcheance($maintenant)->toDateString(),
            'id_clit' => $client->id,
            'id_stat' => $statutInitial->id,
            'id_typ_rec' => $type->id,
            'id_serv' => $this->affectationService->determinerService($type),
        ]);

        if ($request->hasFile('pieces_jointes')) {
            foreach ($request->file('pieces_jointes') as $fichier) {
                $chemin = $fichier->store('pieces_jointes', 'public');

                PieceJointe::create([
                    'cod_reclam' => $reclamation->cod_reclam,
                    'nom_fich_piec' => $fichier->getClientOriginalName(),
                    'typ_fich_piec' => strtolower($fichier->getClientOriginalExtension()),
                    'chem_fichpiec' => $chemin,
                    'taille_piec' => intdiv($fichier->getSize(), 1024),
                    'dat_ajout_piec' => now(),
                ]);
            }
        }

        Historique::create([
            'cod_reclam' => $reclamation->cod_reclam,
            'id_util' => $request->user()->id,
            'act_ehistor' => 'Création',
            'dat_act_histor' => "Réclamation soumise par le client.",
            'heure_act_histor' => now(),
        ]);

        return redirect('/mes-reclamations')->with('success', 'Réclamation soumise avec succès.');
    }

    public function mesReclamations(Request $request)
    {
        $query = Reclamation::with(['statut', 'typeReclamation', 'service'])
            ->where('id_clit', $request->user()->client->id);

        if ($request->filled('recherche')) {
            $query->where('obj_reclam', 'ilike', '%' . $request->recherche . '%');
        }

        if ($request->filled('statut')) {
            $query->where('id_stat', $request->statut);
        }

        return Inertia::render('Reclamations/Index', [
            'reclamations' => $query->latest('dat_reclam')->get(),
            'statuts' => StatutReclamation::select('id', 'lib_stat')->get(),
            'filtres' => $request->only(['recherche', 'statut']),
        ]);
    }

    public function showClient(Request $request, Reclamation $reclamation)
    {
        $this->autoriserAcces($request, $reclamation);

        return Inertia::render('Reclamations/Show', [
            'reclamation' => $reclamation->load(['statut', 'typeReclamation', 'service', 'agent.utilisateur', 'piecesJointes', 'historiques.utilisateur']),
        ]);
    }

        public function exporterPdf(Request $request, Reclamation $reclamation)
    {
        $this->autoriserAcces($request, $reclamation);

        $reclamation->load(['statut', 'typeReclamation', 'service', 'client.utilisateur']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.reclamation', compact('reclamation'));

        return $pdf->download("reclamation-{$reclamation->cod_reclam}.pdf");
    }

    public function mesNotifications(Request $request)
    {
        $notifications = \App\Models\NotificationReclamation::whereHas('reclamation', function ($q) use ($request) {
                $q->where('id_clit', $request->user()->client->id);
            })
            ->with('reclamation:cod_reclam,obj_reclam')
            ->latest('dat_notif')
            ->get();

        return Inertia::render('Notifications/Index', [
            'notifications' => $notifications,
        ]);
    }

    public function pourAgent(Request $request)
    {
        $user = $request->user();
        $agent = $user->agent;

        $query = Reclamation::with(['statut', 'typeReclamation', 'service', 'client.utilisateur']);

        if ($agent->type_agent === 'specifique') {
            $query->where('reclamations.id_serv', $agent->id_serv);
        }

        if ($request->filled('recherche')) {
            $query->where('obj_reclam', 'ilike', '%' . $request->recherche . '%');
        }

        if ($request->filled('statut')) {
            $query->where('id_stat', $request->statut);
        }

        if ($agent->type_agent === 'general' && $request->filled('service')) {
            $query->where('id_serv', $request->service);
        }

        return Inertia::render('Agent/Index', [
            'reclamations' => $query->latest('dat_reclam')->get(),
            'statuts' => StatutReclamation::select('id', 'lib_stat')->get(),
            'services' => $agent->type_agent === 'general' ? Service::select('id', 'lib_serv')->get() : [],
            'filtres' => $request->only(['recherche', 'statut', 'service']),
        ]);
    }

    public function changerStatut(Request $request, Reclamation $reclamation, \App\Services\NotificationService $notificationService)
    {
        $user = $request->user();

        if ($user->role !== 'agent') {
            abort(403, 'Seul un agent peut modifier le statut.');
        }

        $agent = $user->agent;
        if ($agent->type_agent === 'specifique' && $reclamation->id_serv !== $agent->id_serv) {
            abort(403, "Cette réclamation ne concerne pas votre service.");
        }

        $validated = $request->validate([
            'id_stat' => 'required|exists:statuts_reclamation,id',
        ]);

        $ancienStatut = $reclamation->statut->lib_stat;

        $reclamation->update([
            'id_stat' => $validated['id_stat'],
            'id_agt' => $reclamation->id_agt ?? $agent->id,
        ]);

        $nouveauStatut = StatutReclamation::find($validated['id_stat'])->lib_stat;

        if (in_array($nouveauStatut, ['Résolue', 'Rejetée', 'Clôturée'])) {
            $reclamation->update(['date_traitement' => now()]);
        }

        Historique::create([
            'cod_reclam' => $reclamation->cod_reclam,
            'id_util' => $user->id,
            'act_ehistor' => 'Changement de statut',
            'dat_act_histor' => "Statut passé de \"{$ancienStatut}\" à \"{$nouveauStatut}\".",
            'heure_act_histor' => now(),
        ]);

        $notificationService->notifierChangementStatut($reclamation->fresh());

        return redirect()->back()->with('success', 'Statut mis à jour.');
    }

    private function autoriserAcces(Request $request, Reclamation $reclamation): void
    {
        $user = $request->user();

        if ($user->role === 'client' && $reclamation->id_clit !== $user->client->id) {
            abort(403, "Vous n'avez pas accès à cette réclamation.");
        }

        if ($user->role === 'agent') {
            $agent = $user->agent;
            if ($agent->type_agent === 'specifique' && $reclamation->id_serv !== $agent->id_serv) {
                abort(403, "Cette réclamation ne concerne pas votre service.");
            }
        }
    }
}