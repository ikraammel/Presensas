<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'cours_id',
        'titre',
        'contenu',
        'user_id',
        'date_publication'
    ];

    protected $dates = ['date_publication'];

    public function cours()
    {
        return $this->belongsTo(Cours::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
