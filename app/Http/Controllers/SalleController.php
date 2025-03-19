<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    public function index()
    {
        $salles = Salle::withCount('cours')->paginate(10);
        return view('salles.index', compact('salles'));
    }

    public function create()
    {
        return view('salles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:255|unique:salles'
        ]);

        Salle::create($request->all());

        return redirect()->route('salles.index')
            ->with('success', 'La salle a été créée avec succès.');
    }

    public function show(Salle $salle)
    {
        $cours = $salle->cours()
            ->with('professeur')
            ->orderBy('heure_debut', 'desc')
            ->paginate(10);
        return view('salles.show', compact('salle', 'cours'));
    }

    public function edit(Salle $salle)
    {
        return view('salles.edit', compact('salle'));
    }

    public function update(Request $request, Salle $salle)
    {
        $request->validate([
            'libelle' => 'required|string|max:255|unique:salles,libelle,' . $salle->id
        ]);

        $salle->update($request->all());

        return redirect()->route('salles.index')
            ->with('success', 'La salle a été mise à jour avec succès.');
    }

    public function destroy(Salle $salle)
    {
        if ($salle->cours()->exists()) {
            return back()->with('error', 'Impossible de supprimer cette salle car elle est associée à des cours.');
        }

        $salle->delete();

        return redirect()->route('salles.index')
            ->with('success', 'La salle a été supprimée avec succès.');
    }
} 