<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reclamation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StatistiqueController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $query = Reclamation::query();

        if ($user->role === 'agent' && $user->agent->type_agent === 'specifique') {
            $query->where('reclamations.id_serv', $user->agent->id_serv);
        }

        $stats = [
            'total_reclamations' => (clone $query)->count(),
            'par_statut' => $this->parStatut(clone $query),
            'par_service' => $this->parService(clone $query),
            'par_agent' => $this->parAgent(clone $query),
            'delai_moyen_jours' => $this->delaiMoyenTraitement(clone $query),
            'taux_hors_delai' => $this->tauxHorsDelai(clone $query),
            'evolution_mensuelle' => $this->evolutionMensuelle(clone $query),
        ];

        return Inertia::render('Dashboard', ['stats' => $stats]);
    }

    private function parStatut(Builder $query)
    {
        return $query->join('statuts_reclamation', 'reclamations.id_stat', '=', 'statuts_reclamation.id')
            ->select('statuts_reclamation.lib_stat', DB::raw('count(*) as total'))
            ->groupBy('statuts_reclamation.lib_stat')
            ->get();
    }

    private function parService(Builder $query)
    {
        return $query->join('services', 'reclamations.id_serv', '=', 'services.id')
            ->select('services.lib_serv', DB::raw('count(*) as total'))
            ->groupBy('services.lib_serv')
            ->get();
    }

    private function parAgent(Builder $query)
    {
        return $query->whereNotNull('id_agt')
            ->join('agents', 'reclamations.id_agt', '=', 'agents.id')
            ->join('utilisateurs', 'agents.id_util', '=', 'utilisateurs.id')
            ->select(
                'utilisateurs.nom_util',
                'utilisateurs.prenom_util',
                DB::raw('count(*) as total')
            )
            ->groupBy('utilisateurs.id', 'utilisateurs.nom_util', 'utilisateurs.prenom_util')
            ->get();
    }

    private function delaiMoyenTraitement(Builder $query)
    {
        $moyenne = $query->whereNotNull('date_traitement')
            ->select(DB::raw('AVG(date_traitement - dat_reclam) as moyenne'))
            ->value('moyenne');

        return $moyenne ? round($moyenne, 1) : null;
    }

    private function tauxHorsDelai(Builder $query)
    {
        $totalConcerne = (clone $query)
            ->where(function ($q) {
                $q->whereNotNull('date_traitement')
                  ->orWhere('date_echeance', '<', now());
            })
            ->count();

        if ($totalConcerne === 0) {
            return 0;
        }

        $horsDelai = (clone $query)
            ->where(function ($q) {
                $q->whereColumn('date_traitement', '>', 'date_echeance')
                  ->orWhere(function ($q2) {
                      $q2->whereNull('date_traitement')
                         ->where('date_echeance', '<', now());
                  });
            })
            ->count();

        return round(($horsDelai / $totalConcerne) * 100, 1);
    }

    /**
     * Nombre de réclamations soumises par mois, sur les 6 derniers mois.
     */
    private function evolutionMensuelle(Builder $query)
    {
        $debut = now()->subMonths(5)->startOfMonth();

        $resultats = $query->where('dat_reclam', '>=', $debut)
            ->select(
                DB::raw("to_char(dat_reclam, 'YYYY-MM') as mois"),
                DB::raw('count(*) as total')
            )
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->keyBy('mois');

        $labels = [];
        $valeurs = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $cle = $date->format('Y-m');
            $labels[] = $date->translatedFormat('M Y');
            $valeurs[] = $resultats->get($cle)->total ?? 0;
        }

        return ['labels' => $labels, 'valeurs' => $valeurs];
    }
}