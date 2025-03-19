<?php

namespace App\Exports;

use App\Models\Emargement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class PresencesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;
    protected $stats;
    protected $statsParProfesseur;
    protected $statsParCours;

    public function __construct(Request $request)
    {
        $this->request = $request;
        
        // Récupérer les émargements filtrés
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
        $this->stats = [
            'total' => $emargements->count(),
            'presents' => $emargements->where('statut', 'present')->count(),
            'absents' => $emargements->where('statut', 'absent')->count(),
            'retards' => $emargements->where('statut', 'retard')->count()
        ];

        // Calculer les statistiques par professeur
        $this->statsParProfesseur = $emargements->groupBy('professeur_id')
            ->map(function ($group) {
                return [
                    'total' => $group->count(),
                    'presents' => $group->where('statut', 'present')->count(),
                    'absents' => $group->where('statut', 'absent')->count(),
                    'retards' => $group->where('statut', 'retard')->count()
                ];
            });

        // Calculer les statistiques par cours
        $this->statsParCours = $emargements->groupBy('cours_id')
            ->map(function ($group) {
                return [
                    'total' => $group->count(),
                    'presents' => $group->where('statut', 'present')->count(),
                    'absents' => $group->where('statut', 'absent')->count(),
                    'retards' => $group->where('statut', 'retard')->count()
                ];
            });
    }

    public function collection()
    {
        // Créer une collection avec les statistiques
        $data = collect();

        // Ajouter les statistiques globales
        $data->push([
            'type' => 'global',
            'label' => 'Statistiques Globales',
            'total' => $this->stats['total'],
            'presents' => $this->stats['presents'],
            'absents' => $this->stats['absents'],
            'retards' => $this->stats['retards']
        ]);

        // Ajouter les statistiques par professeur
        foreach ($this->statsParProfesseur as $professeurId => $stats) {
            $professeur = \App\Models\User::find($professeurId);
            $data->push([
                'type' => 'professeur',
                'label' => $professeur ? $professeur->nom . ' ' . $professeur->prenom : 'Professeur inconnu',
                'total' => $stats['total'],
                'presents' => $stats['presents'],
                'absents' => $stats['absents'],
                'retards' => $stats['retards']
            ]);
        }

        // Ajouter les statistiques par cours
        foreach ($this->statsParCours as $coursId => $stats) {
            $cours = \App\Models\Cours::find($coursId);
            $data->push([
                'type' => 'cours',
                'label' => $cours ? $cours->nom : 'Cours inconnu',
                'total' => $stats['total'],
                'presents' => $stats['presents'],
                'absents' => $stats['absents'],
                'retards' => $stats['retards']
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Catégorie',
            'Total',
            'Présents',
            'Absents',
            'Retards'
        ];
    }

    public function map($row): array
    {
        return [
            $row['label'],
            $row['total'],
            $row['presents'],
            $row['absents'],
            $row['retards']
        ];
    }
} 