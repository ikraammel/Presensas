<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seances extends Model
{
    use HasFactory;

    protected $table = 'seances';

    public $timestamps = false;

    protected $fillable = ['id', 'cours_id', 'groupe_id', 'date_debut', 'date_fin', 'type', 'type_seance', 'qr_token', 'qr_expires_at'];

    public function etudiants(){
        return $this->belongsToMany(Etudiants::class, 'presences', 'etudiant_id', 'seance_id');
    }

    public function cours(){
        return $this->belongsTo(Cours::class, 'cours_id', 'id');
    }

    public function presences(){
        return $this->hasMany(Presences::class, 'seance_id');
    }

    public function groupe(){
        return $this->belongsTo(Groupe::class, 'groupe_id');
    }
}
