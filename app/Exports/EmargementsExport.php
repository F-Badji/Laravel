<?php

namespace App\Exports;

use App\Models\Emargement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;

class EmargementsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Emargement::with(['professeur', 'cours']);

        if ($this->request->has('date_debut')) {
            $query->whereDate('date', '>=', $this->request->date_debut);
        }

        if ($this->request->has('date_fin')) {
            $query->whereDate('date', '<=', $this->request->date_fin);
        }

        if ($this->request->has('professeur_id')) {
            $query->where('professeur_id', $this->request->professeur_id);
        }

        if ($this->request->has('cours_id')) {
            $query->where('cours_id', $this->request->cours_id);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Professeur',
            'Cours',
            'Statut',
            'Validation'
        ];
    }

    public function map($emargement): array
    {
        return [
            $emargement->date->format('d/m/Y H:i'),
            $emargement->professeur->nom . ' ' . $emargement->professeur->prenom,
            $emargement->cours->nom,
            ucfirst($emargement->statut),
            $emargement->valide ? 'Validé' : 'En attente'
        ];
    }
} 