<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // Enregistrer un nouveau document
    public function store(Request $request)
    {
        $request->validate([
            'cours_id' => 'required|exists:cours,id',
            'titre' => 'required|string|max:255',
            'fichier' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,jpg,png,jpeg|max:10240', // 10MB max
            'description' => 'nullable|string',
        ]);

        $file = $request->file('fichier');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('documents', $fileName, 'public');

        Document::create([
            'cours_id' => $request->cours_id,
            'titre' => $request->titre,
            'description' => $request->description,
            'fichier' => $filePath,
            'type_fichier' => $file->getClientOriginalExtension(),
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('etat', 'Document ajouté avec succès.');
    }

    // Supprimer un document
    public function destroy($id)
    {
        $document = Document::findOrFail($id);

        // Vérifier si l'utilisateur est le propriétaire ou admin
        if (Auth::id() !== $document->user_id && Auth::user()->type !== 'admin') {
            return redirect()->back()->withErrors(['erreur' => 'Vous n\'avez pas la permission de supprimer ce document.']);
        }

        // Supprimer le fichier du stockage
        if (Storage::disk('public')->exists($document->fichier)) {
            Storage::disk('public')->delete($document->fichier);
        }

        $document->delete();

        return redirect()->back()->with('etat', 'Document supprimé.');
    }

    // Télécharger un document
    public function download($id)
    {
        $document = Document::findOrFail($id);

        // Vérification d'accès basique (si nécessaire, étoffer selon logique cours/étudiant)
        // Ici on suppose que si l'étudiant a le lien (via son cours), il peut télécharger.

        $filePath = storage_path('app/public/' . $document->fichier);

        if (file_exists($filePath)) {
            return response()->download($filePath, $document->titre . '.' . $document->type_fichier);
        } else {
            return redirect()->back()->withErrors(['erreur' => 'Fichier introuvable.']);
        }
    }
}
