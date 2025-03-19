<?php

namespace App\Http\Controllers;

use App\Models\Emargement;
use App\Models\User;
use App\Models\Cours;
use App\Exports\EmargementsExport;
use App\Exports\PresencesExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class RapportController extends Controller
{
    public function index()
    {
        $professeurs = User::where('role', 'professeur')->get();
        $cours = Cours::all();
        return view('rapports.index', compact('professeurs', 'cours'));
    }

    public function presences(Request $request)
    {
        $query = Emargement::with(['professeur', 'cours']);

        if ($request->has('date_debut')) {
            $query->whereDate('date', '>=', $request->date_debut);
        }

        if ($request->has('date_fin')) {
            $query->whereDate('date', '<=', $request->date_fin);
        }

        if ($request->has('professeur_id')) {
            $query->where('professeur_id', $request->professeur_id);
        }

        if ($request->has('cours_id')) {
            $query->where('cours_id', $request->cours_id);
        }

        $emargements = $query->get();

        // Calculer les statistiques globales
        $stats = [
            'total' => $emargements->count(),
            'presents' => $emargements->where('statut', 'present')->count(),
            'absents' => $emargements->where('statut', 'absent')->count(),
            'retards' => $emargements->where('statut', 'retard')->count()
        ];

        // Calculer les statistiques par professeur
        $statsParProfesseur = $emargements->groupBy('professeur_id')
            ->map(function ($group) {
                return [
                    'total' => $group->count(),
                    'presents' => $group->where('statut', 'present')->count(),
                    'absents' => $group->where('statut', 'absent')->count(),
                    'retards' => $group->where('statut', 'retard')->count()
                ];
            });

        // Calculer les statistiques par cours
        $statsParCours = $emargements->groupBy('cours_id')
            ->map(function ($group) {
                return [
                    'total' => $group->count(),
                    'presents' => $group->where('statut', 'present')->count(),
                    'absents' => $group->where('statut', 'absent')->count(),
                    'retards' => $group->where('statut', 'retard')->count()
                ];
            });

        $professeurs = User::where('role', 'professeur')->get();
        $cours = Cours::all();

        return view('rapports.index', compact(
            'emargements',
            'stats',
            'statsParProfesseur',
            'statsParCours',
            'professeurs',
            'cours'
        ));
    }

    public function exportPDF(Request $request)
    {
        $query = Emargement::with(['professeur', 'cours']);

        if ($request->has('date_debut')) {
            $query->whereDate('date', '>=', $request->date_debut);
        }

        if ($request->has('date_fin')) {
            $query->whereDate('date', '<=', $request->date_fin);
        }

        if ($request->has('professeur_id')) {
            $query->where('professeur_id', $request->professeur_id);
        }

        if ($request->has('cours_id')) {
            $query->where('cours_id', $request->cours_id);
        }

        $emargements = $query->get();

        $pdf = Pdf::loadView('rapports.pdf', compact('emargements'));
        return $pdf->download('rapport-emargements.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new EmargementsExport($request), 'emargements.xlsx');
    }

    public function exportPresences(Request $request)
    {
        return Excel::download(new PresencesExport($request), 'statistiques-presences.xlsx');
    }
} 