<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\CoursController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\RegisterUserController;
use App\Http\Controllers\SeanceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        $type = Auth::User()->type;
        return view('home', ['type' => $type]);
    } else {
        return view('home');
    }
});

Route::view('/home', 'home')->middleware('auth')
    ->name('home');

/*===================== Admin ============================*/
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.home');
    Route::get('/admin/etudiants', [AdminController::class, 'etudiants'])->name('admin.etudiants');
    Route::get('/admin/enseignants', [AdminController::class, 'enseignants'])->name('admin.enseignants');
    Route::get('/admin/modules', [AdminController::class, 'modules'])->name('admin.modules');
    Route::get('/admin/users/index', [UserController::class, 'show'])->name('admin.users.index');
    Route::get('/admin/users/indexAll', [UserController::class, 'showAll'])->name('admin.users.indexAll');
    Route::get('/admin/users/indexEnseignant', [UserController::class, 'showEnseignant'])->name('admin.users.indexEnseignant');
    Route::get('/admin/users/indexEtudiant', [UserController::class, 'showEtudiant'])->name('admin.users.indexEtudiant');
    Route::get('/admin/users/search', [UserController::class, 'recherche'])->name('admin.users.search');
    Route::get('/admin/users/edit/{id}', [UserController::class, 'editForm'])->name('admin.users.edit');
    Route::post('/admin/users/edit/{id}', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::get('/admin/users/delete/{id}', [UserController::class, 'deleteForm'])->name('admin.users.delete');
    Route::post('/admin/users/delete/{id}', [UserController::class, 'delete'])->name('admin.users.delete');
    Route::get('/admin/cours/index', [CoursController::class, 'show'])->name('admin.cours.index');
    Route::get('/admin/cours/add', [CoursController::class, 'addForm'])->name('admin.cours.add');
    Route::post('/admin/cours/add', [CoursController::class, 'add'])->name('admin.cours.add');
    Route::get('/admin/cours/searchIntitule', [CoursController::class, 'search'])->name('admin.cours.search');
    Route::get('/admin/cours/edit/{id}', [CoursController::class, 'editForm'])->name('admin.cours.edit');
    Route::post('/admin/cours/edit/{id}', [CoursController::class, 'edit'])->name('admin.cours.edit');
    Route::get('/admin/cours/delete/{id}', [CoursController::class, 'deleteForm'])->name('admin.cours.delete');
    //Route::post('/admin/cours/delete/{id}',[CoursController::class, 'delete'])->name('admin.cours.delete');
    Route::get('/admin/user/addAdmin', [UserController::class, 'addUserForm'])->name('admin.user.createAdmin');
    Route::post('/admin/user/addAdmin', [UserController::class, 'addUserAdmin'])->name('admin.user.createAdmin');
    Route::get('/admin/user/addEnseignant', [UserController::class, 'addUserForm'])->name('admin.user.createEnseignant');
    Route::post('/admin/user/addEnseignant', [UserController::class, 'addUserEnseignant'])->name('admin.user.createEnseignant');
    Route::get('/admin/editMdp', [UserController::class, 'editForm_Mdp'])->name('admin.account.edit');
    Route::post('/admin/editMdp', [UserController::class, 'editMdp'])->name('admin.account.edit');
    Route::get('/admin/editNonEtPrenom/{id}', [UserController::class, 'editFormNomPrenom'])->name('admin.account.editNomPrenom');
    Route::post('/admin/editNonEtPrenom/{id}', [UserController::class, 'editNomPrenom'])->name('admin.account.editNomPrenom');
    Route::get('/admin/profil', [UserController::class, 'showProfil'])->name('admin.profil');

});


/*====================== Enseignant =======================*/
Route::middleware(['auth', 'is_enseignant'])->group(function () {
    Route::get('/enseignant', [EnseignantController::class, 'index'])->name('enseignant.home');
    Route::get('/enseignant/profil', [EnseignantController::class, 'showProfil'])->name('enseignant.profil');
    Route::get('/enseignant/etudiants', [EnseignantController::class, 'indexEtudiants'])->name('enseignant.etudiants');
    Route::get('/enseignant/editMdp', [EnseignantController::class, 'editFormMdp'])->name('enseignant.account.edit');
    Route::post('/enseignant/editMdp', [EnseignantController::class, 'editMdp'])->name('enseignant.account.edit');
    Route::get('/enseignant/editNonPrenom/{id}', [EnseignantController::class, 'editForm_NomPrenom'])->name('enseignant.account.editNomPrenom');
    Route::post('/enseignant/editNonPrenom/{id}', [EnseignantController::class, 'editName'])->name('enseignant.account.editNomPrenom');
    Route::get('/enseignant/seance/AfficheListeSeance', [EnseignantController::class, 'showSeance'])->name('enseignant.showSeance');
    Route::get('/enseignant/seance/pointerUnEtudiant/{id}', [EnseignantController::class, 'pointageEtudiantForm'])->name('enseignant.pointer.etudiant');
    Route::post('/enseignant/seance/pointerUnEtudiant/{id}', [EnseignantController::class, 'pointageEtudiant'])->name('enseignant.pointer.etudiant');
    Route::get('/enseignant/seance/pointerUnEtudiantPlusieurs', [EnseignantController::class, 'pointageEtudiantPlusieursForm'])->name('enseignant.pointer.etudiantPlusieurs');
    Route::post('/enseignant/seance/pointerUnEtudiantPlusieurs', [EnseignantController::class, 'pointageEtudiantPlusieurs'])->name('enseignant.pointer.etudiantPlusieurs');
    Route::get('/enseignant/seance/showListeEnseignant/{id}', [EnseignantController::class, 'showEnseignantList'])->name('enseignant.cours.show');

    // Gestion des classes
    Route::get('/enseignant/classes', [EnseignantController::class, 'indexClasses'])->name('enseignant.classes.index');
    Route::get('/enseignant/classes/create', [EnseignantController::class, 'createClasseForm'])->name('enseignant.classes.create');
    Route::post('/enseignant/classes', [EnseignantController::class, 'storeClasse'])->name('enseignant.classes.store');
    Route::get('/enseignant/classes/{id}/edit', [EnseignantController::class, 'editClasseForm'])->name('enseignant.classes.edit');
    Route::post('/enseignant/classes/{id}', [EnseignantController::class, 'updateClasse'])->name('enseignant.classes.update');
    Route::get('/enseignant/classes/{id}/delete', [EnseignantController::class, 'deleteClasse'])->name('enseignant.classes.delete');
    Route::get('/enseignant/classes/{id}/etudiants', [EnseignantController::class, 'showClasseEtudiants'])->name('enseignant.classes.etudiants');
    Route::get('/enseignant/classes/{id}/etudiants/add', [EnseignantController::class, 'addEtudiantToClasseForm'])->name('enseignant.classes.etudiants.add');
    Route::post('/enseignant/classes/{id}/etudiants', [EnseignantController::class, 'storeEtudiantToClasse'])->name('enseignant.classes.etudiants.store');
    Route::get('/enseignant/classes/{id}/import-csv', [EnseignantController::class, 'importEtudiantsCSVForm'])->name('enseignant.classes.import-csv');
    Route::post('/enseignant/classes/{id}/import-csv', [EnseignantController::class, 'importEtudiantsCSV'])->name('enseignant.classes.import-csv');

    // Gestion des modules
    Route::get('/enseignant/modules', [EnseignantController::class, 'indexModules'])->name('enseignant.modules.index');
    Route::get('/enseignant/modules/create', [EnseignantController::class, 'createModuleForm'])->name('enseignant.modules.create');
    Route::post('/enseignant/modules', [EnseignantController::class, 'storeModule'])->name('enseignant.modules.store');
    Route::get('/enseignant/modules/{id}/edit', [EnseignantController::class, 'editModuleForm'])->name('enseignant.modules.edit');
    Route::post('/enseignant/modules/{id}', [EnseignantController::class, 'updateModule'])->name('enseignant.modules.update');
    Route::get('/enseignant/modules/{id}/delete', [EnseignantController::class, 'deleteModule'])->name('enseignant.modules.delete');
    Route::get('/enseignant/modules/{id}/link-classe', [EnseignantController::class, 'linkModuleToClasseForm'])->name('enseignant.modules.link-classe');
    Route::post('/enseignant/modules/{id}/link-classe', [EnseignantController::class, 'storeModuleClasseLink'])->name('enseignant.modules.link-classe');

    // Gestion des séances
    Route::get('/enseignant/seances', [EnseignantController::class, 'indexSeances'])->name('enseignant.seances.index');
    Route::get('/enseignant/seances/create', [EnseignantController::class, 'createSeanceForm'])->name('enseignant.seances.create');
    Route::post('/enseignant/seances', [EnseignantController::class, 'storeSeance'])->name('enseignant.seances.store');
    Route::get('/enseignant/seances/{id}/edit', [EnseignantController::class, 'editSeanceForm'])->name('enseignant.seances.edit');
    Route::post('/enseignant/seances/{id}', [EnseignantController::class, 'updateSeance'])->name('enseignant.seances.update');
    Route::get('/enseignant/seances/{id}/delete', [EnseignantController::class, 'deleteSeance'])->name('enseignant.seances.delete');
    Route::get('/enseignant/seances/calendrier', [EnseignantController::class, 'calendarSeances'])->name('enseignant.seances.calendrier');
    Route::get('/enseignant/seances/{id}/qr-code', [EnseignantController::class, 'generateQRCode'])->name('enseignant.seances.qr-code');

    // Enregistrement présence
    Route::get('/enseignant/presences/{seanceId}/manuel', [EnseignantController::class, 'presenceManuelleForm'])->name('enseignant.presences.manuel');
    Route::post('/enseignant/presences/{seanceId}/manuel', [EnseignantController::class, 'storePresenceManuelle'])->name('enseignant.presences.manuel');
    Route::get('/enseignant/presences/{seanceId}/qr-code', [EnseignantController::class, 'presenceQRCodeForm'])->name('enseignant.presences.qr-code');
    Route::post('/enseignant/presences/{seanceId}/qr-code/scan', [EnseignantController::class, 'scanQRCode'])->name('enseignant.presences.qr-code.scan');
    Route::get('/enseignant/presences/{seanceId}/nfc', [EnseignantController::class, 'presenceNFCForm'])->name('enseignant.presences.nfc');
    Route::post('/enseignant/presences/{seanceId}/nfc/scan', [EnseignantController::class, 'scanNFC'])->name('enseignant.presences.nfc.scan');

    // Documents
    Route::get('/enseignant/documents', [EnseignantController::class, 'indexDocuments'])->name('enseignant.documents.index');
    Route::get('/enseignant/documents/create', [EnseignantController::class, 'createDocumentForm'])->name('enseignant.documents.create');
    Route::post('/enseignant/documents', [EnseignantController::class, 'storeDocument'])->name('enseignant.documents.store');
    Route::get('/enseignant/documents/{id}/delete', [EnseignantController::class, 'deleteDocument'])->name('enseignant.documents.delete');
    Route::get('/enseignant/documents/{id}/download', [EnseignantController::class, 'downloadDocument'])->name('enseignant.documents.download');

    // Annonces
    Route::get('/enseignant/annonces', [EnseignantController::class, 'indexAnnonces'])->name('enseignant.annonces.index');
    Route::get('/enseignant/annonces/create', [EnseignantController::class, 'createAnnonceForm'])->name('enseignant.annonces.create');
    Route::post('/enseignant/annonces', [EnseignantController::class, 'storeAnnonce'])->name('enseignant.annonces.store');
    Route::get('/enseignant/annonces/{id}/edit', [EnseignantController::class, 'editAnnonceForm'])->name('enseignant.annonces.edit');
    Route::post('/enseignant/annonces/{id}', [EnseignantController::class, 'updateAnnonce'])->name('enseignant.annonces.update');
    Route::get('/enseignant/annonces/{id}/delete', [EnseignantController::class, 'deleteAnnonce'])->name('enseignant.annonces.delete');

    // Statistiques
    Route::get('/enseignant/statistiques', [EnseignantController::class, 'indexStatistiques'])->name('enseignant.statistiques.index');
    Route::get('/enseignant/statistiques/classe/{classeId}', [EnseignantController::class, 'statistiquesParClasse'])->name('enseignant.statistiques.classe');
    Route::get('/enseignant/statistiques/etudiant/{etudiantId}', [EnseignantController::class, 'statistiquesParEtudiant'])->name('enseignant.statistiques.etudiant');
    Route::get('/enseignant/statistiques/export/excel', [EnseignantController::class, 'exportStatistiquesExcel'])->name('enseignant.statistiques.export.excel');
    Route::get('/enseignant/statistiques/export/pdf', [EnseignantController::class, 'exportStatistiquesPDF'])->name('enseignant.statistiques.export.pdf');
});

/*===================== Étudiant =======================*/
Route::middleware(['auth', 'is_etudiant'])->group(function () {
    Route::get('/etudiant', [EtudiantController::class, 'index'])->name('etudiant.home');
    Route::get('/etudiant/profil', [EtudiantController::class, 'showProfil'])->name('etudiant.profil');
    Route::get('/etudiant/editMdp', [EtudiantController::class, 'editFormMdp'])->name('etudiant.account.edit');
    Route::post('/etudiant/editMdp', [EtudiantController::class, 'editMdp'])->name('etudiant.account.edit');
});

/*===================== Gestionnaire =======================*/
Route::middleware(['auth', 'is_gestionnaire'])->group(function () {
    Route::get('/gestionnaire', [\App\Http\Controllers\GestionnaireController::class, 'index'])->name('gestionnaire.home');
    Route::get('/gestionnaire/profil', [\App\Http\Controllers\GestionnaireController::class, 'showProfil'])->name('gestionnaire.profil');
    Route::get('/gestionnaire/editMdp', [\App\Http\Controllers\GestionnaireController::class, 'editFormMdp'])->name('gestionnaire.account.edit');
    Route::post('/gestionnaire/editMdp', [\App\Http\Controllers\GestionnaireController::class, 'editMdp'])->name('gestionnaire.account.edit');
});



/*===================== Login & Logout ===========================*/
Route::get('/login', [AuthenticatedSessionController::class, 'showForm'])
    ->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'login']);
Route::get('/logout', [AuthenticatedSessionController::class, 'logout'])
    ->name('logout')->middleware('auth');


/*============================= Register ========================*/
Route::get('/register', [RegisterUserController::class, 'showForm'])
    ->name('register');
Route::post('/register', [RegisterUserController::class, 'store']);
