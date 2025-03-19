<?php

namespace App\Http\Controllers;

use App\Models\Emargement;
use App\Models\Cours;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmargementController extends Controller
{
    public function index()
    {
        $emargements = Emargement::with(['professeur', 'cours'])
            ->orderBy('date', 'desc')
            ->paginate(10);
        return view('emargements.index', compact('emargements'));
    }

    public function create()
    {
        $cours = Cours::where('heure_debut', '<=', Carbon::now())
            ->where('heure_fin', '>=', Carbon::now())
            ->get();
        $professeurs = User::where('role', 'professeur')->get();
        return view('emargements.create', compact('cours', 'professeurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'statut' => 'required|in:present,absent,retard',
            'professeur_id' => 'required|exists:users,id',
            'cours_id' => 'required|exists:cours,id'
        ]);

        // Vérifier si un émargement existe déjà pour ce cours et ce professeur à cette date
        $existingEmargement = Emargement::where('date', $request->date)
            ->where('professeur_id', $request->professeur_id)
            ->where('cours_id', $request->cours_id)
            ->first();

        if ($existingEmargement) {
            return back()->with('error', 'Un émargement existe déjà pour ce cours et ce professeur à cette date.');
        }

        $emargement = Emargement::create($request->all());

        return redirect()->route('emargements.index')
            ->with('success', 'L\'émargement a été enregistré avec succès.');
    }

    public function show(Emargement $emargement)
    {
        return view('emargements.show', compact('emargement'));
    }

    public function edit(Emargement $emargement)
    {
        $cours = Cours::all();
        $professeurs = User::where('role', 'professeur')->get();
        return view('emargements.edit', compact('emargement', 'cours', 'professeurs'));
    }

    public function update(Request $request, Emargement $emargement)
    {
        $request->validate([
            'date' => 'required|date',
            'statut' => 'required|in:present,absent,retard',
            'professeur_id' => 'required|exists:users,id',
            'cours_id' => 'required|exists:cours,id'
        ]);

        // Vérifier si un autre émargement existe déjà pour ce cours et ce professeur à cette date
        $existingEmargement = Emargement::where('date', $request->date)
            ->where('professeur_id', $request->professeur_id)
            ->where('cours_id', $request->cours_id)
            ->where('id', '!=', $emargement->id)
            ->first();

        if ($existingEmargement) {
            return back()->with('error', 'Un émargement existe déjà pour ce cours et ce professeur à cette date.');
        }

        $emargement->update($request->all());

        return redirect()->route('emargements.index')
            ->with('success', 'L\'émargement a été mis à jour avec succès.');
    }

    public function destroy(Emargement $emargement)
    {
        $emargement->delete();
        return redirect()->route('emargements.index')
            ->with('success', 'L\'émargement a été supprimé avec succès.');
    }

    public function valider(Emargement $emargement)
    {
        $emargement->update(['valide' => true]);
        return back()->with('success', 'L\'émargement a été validé avec succès.');
    }

    public function rapport(Request $request)
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

        $emargements = $query->orderBy('date', 'desc')->get();

        $professeurs = User::where('role', 'professeur')->get();
        $cours = Cours::all();

        return view('emargements.rapport', compact('emargements', 'professeurs', 'cours'));
    }
} 