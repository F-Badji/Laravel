<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cours;
use App\Models\Emargement;
use App\Models\Salle;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'totalProfesseurs' => User::where('role', 'professeur')->count(),
            'coursDuJour' => Cours::whereDate('heure_debut', Carbon::today())->count(),
            'presencesAujourdhui' => Emargement::whereDate('date', Carbon::today())->count(),
            'sallesDisponibles' => Salle::count(),
            'prochainsCours' => Cours::with('salle')
                ->where('heure_debut', '>', Carbon::now())
                ->orderBy('heure_debut')
                ->take(5)
                ->get(),
            'notifications' => auth()->user()->notifications()->take(5)->get(),
            'dernieresActivites' => Emargement::with('professeur', 'cours')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
        ];

        return view('dashboard', $data);
    }
} 