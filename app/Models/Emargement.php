<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emargement extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'statut',
        'professeur_id',
        'cours_id'
    ];

    protected $casts = [
        'date' => 'datetime'
    ];

    public function professeur()
    {
        return $this->belongsTo(User::class, 'professeur_id');
    }

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }
} 