<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'user_id'
    ];

    public function etudiants()
    {
        return $this->hasMany(Etudiants::class);
    }

    public function cours()
    {
        return $this->belongsToMany(Cours::class, 'cours_groupes', 'groupe_id', 'cours_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
