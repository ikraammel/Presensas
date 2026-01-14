<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    // Enregistrer un nouveau document
    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'cours_id' => 'required|exists:cours,id',
            'titre' => 'required|string|max:255',
            'fichier' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,zip,jpg,png,jpeg|max:10240', // 10MB max
            'description' => 'nullable|string',
        ]);

        // Vérifier que l'enseignant a accès à ce cours
        $user = Auth::user();
        $coursIds = $user->cours->pluck('id')->toArray();
        
        if (!in_array($validated['cours_id'], $coursIds)) {
            return redirect()->back()
                ->withErrors(['erreur' => 'Vous n\'avez pas accès à ce module.'])
                ->withInput();
        }

        try {
            $file = $request->file('fichier');
            
            // Nettoyer le nom du fichier
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            
            // Créer le dossier s'il n'existe pas
            if (!Storage::disk('public')->exists('documents')) {
                Storage::disk('public')->makeDirectory('documents');
            }
            
            // Sauvegarder le fichier
            $filePath = $file->storeAs('documents', $fileName, 'public');

            // Créer l'enregistrement dans la base de données
            Document::create([
                'cours_id' => $validated['cours_id'],
                'titre' => $validated['titre'],
                'description' => $validated['description'] ?? null,
                'fichier' => $filePath,
                'type_fichier' => $file->getClientOriginalExtension(),
                'user_id' => $user->id,
            ]);

            return redirect()->back()
                ->with('etat', 'Document ajouté et partagé avec succès !');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['erreur' => 'Erreur lors de l\'upload du document : ' . $e->getMessage()])
                ->withInput();
        }
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
