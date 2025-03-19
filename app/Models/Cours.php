<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cours extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'heure_debut',
        'heure_fin',
        'salle_id',
        'professeur_id'
    ];

    protected $casts = [
        'heure_debut' => 'datetime',
        'heure_fin' => 'datetime'
    ];

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function professeur()
    {
        return $this->belongsTo(User::class, 'professeur_id');
    }

    public function emargements()
    {
        return $this->hasMany(Emargement::class);
    }

    public function hasConflitHoraire()
    {
        return Cours::where('professeur_id', $this->professeur_id)
            ->where('id', '!=', $this->id)
            ->where(function ($query) {
                $query->whereBetween('heure_debut', [$this->heure_debut, $this->heure_fin])
                    ->orWhereBetween('heure_fin', [$this->heure_debut, $this->heure_fin])
                    ->orWhere(function ($q) {
                        $q->where('heure_debut', '<=', $this->heure_debut)
                            ->where('heure_fin', '>=', $this->heure_fin);
                    });
            })
            ->exists();
    }

    public function hasConflitSalle()
    {
        return Cours::where('salle_id', $this->salle_id)
            ->where('id', '!=', $this->id)
            ->where(function ($query) {
                $query->whereBetween('heure_debut', [$this->heure_debut, $this->heure_fin])
                    ->orWhereBetween('heure_fin', [$this->heure_debut, $this->heure_fin])
                    ->orWhere(function ($q) {
                        $q->where('heure_debut', '<=', $this->heure_debut)
                            ->where('heure_fin', '>=', $this->heure_fin);
                    });
            })
            ->exists();
    }
} 