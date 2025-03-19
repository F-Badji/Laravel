<?php

namespace App\Http\Controllers;

use App\Models\Cours;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CoursController extends Controller
{
    public function index()
    {
        $cours = Cours::with(['salle', 'professeur'])
            ->orderBy('heure_debut', 'desc')
            ->paginate(10);
        return view('cours.index', compact('cours'));
    }

    public function create()
    {
        $salles = Salle::all();
        $professeurs = User::where('role', 'professeur')->get();
        return view('cours.create', compact('salles', 'professeurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'heure_debut' => 'required|date',
            'heure_fin' => 'required|date|after:heure_debut',
            'salle_id' => 'required|exists:salles,id',
            'professeur_id' => 'required|exists:users,id'
        ]);

        // Vérifier les conflits d'horaire
        $cours = new Cours($request->all());
        if ($cours->hasConflitHoraire()) {
            return back()->with('error', 'Il y a un conflit d\'horaire avec un autre cours pour ce professeur.');
        }

        if ($cours->hasConflitSalle()) {
            return back()->with('error', 'Cette salle est déjà occupée pendant ce créneau horaire.');
        }

        $cours->save();

        return redirect()->route('cours.index')
            ->with('success', 'Le cours a été créé avec succès.');
    }

    public function show(Cours $cours)
    {
        return view('cours.show', compact('cours'));
    }

    public function edit(Cours $cours)
    {
        $salles = Salle::all();
        $professeurs = User::where('role', 'professeur')->get();
        return view('cours.edit', compact('cours', 'salles', 'professeurs'));
    }

    public function update(Request $request, Cours $cours)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'heure_debut' => 'required|date',
            'heure_fin' => 'required|date|after:heure_debut',
            'salle_id' => 'required|exists:salles,id',
            'professeur_id' => 'required|exists:users,id'
        ]);

        // Vérifier les conflits d'horaire
        $cours->fill($request->all());
        if ($cours->hasConflitHoraire()) {
            return back()->with('error', 'Il y a un conflit d\'horaire avec un autre cours pour ce professeur.');
        }

        if ($cours->hasConflitSalle()) {
            return back()->with('error', 'Cette salle est déjà occupée pendant ce créneau horaire.');
        }

        $cours->save();

        return redirect()->route('cours.index')
            ->with('success', 'Le cours a été mis à jour avec succès.');
    }

    public function destroy(Cours $cours)
    {
        $cours->delete();
        return redirect()->route('cours.index')
            ->with('success', 'Le cours a été supprimé avec succès.');
    }

    public function checkConflit(Request $request)
    {
        $cours = new Cours($request->all());
        $conflitHoraire = $cours->hasConflitHoraire();
        $conflitSalle = $cours->hasConflitSalle();

        return response()->json([
            'conflitHoraire' => $conflitHoraire,
            'conflitSalle' => $conflitSalle,
            'message' => $conflitHoraire ? 'Conflit d\'horaire avec un autre cours pour ce professeur.' : 
                       ($conflitSalle ? 'Cette salle est déjà occupée pendant ce créneau horaire.' : '')
        ]);
    }
} 