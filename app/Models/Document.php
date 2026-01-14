<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'cours_id',
        'titre',
        'description',
        'fichier',
        'type_fichier',
        'user_id'
    ];

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
