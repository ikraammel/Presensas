<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Feuille de présence</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            margin: 5px 0;
            font-size: 18px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 5px;
            font-weight: bold;
        }

        .presence-table {
            width: 100%;
            border-collapse: collapse;
        }

        .presence-table th,
        .presence-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .presence-table th {
            background-color: #f2f2f2;
        }

        .status-present {
            color: green;
            font-weight: bold;
        }

        .status-absent {
            color: red;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>FEUILLE DE PRÉSENCE</h1>
        <p>Année Universitaire: {{ date('Y') }}-{{ date('Y') + 1 }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td>Cours: {{ $seance->cours->intitule }}</td>
            <td>Date: {{ \Carbon\Carbon::parse($seance->date_debut)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td>Heure: {{ \Carbon\Carbon::parse($seance->date_debut)->format('H:i') }} -
                {{ \Carbon\Carbon::parse($seance->date_fin)->format('H:i') }}</td>
            <td>Enseignant: {{ Auth::user()->nom }} {{ Auth::user()->prenom }}</td>
        </tr>
    </table>

    <table class="presence-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Étudiant</th>
                <th>Numéro Étudiant</th>
                <th>Statut</th>
                <th>Heure Pointage</th>
                <th>Signature</th>
            </tr>
        </thead>
        <tbody>
            @forelse($presences as $index => $presence)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $presence->etudiants->nom }} {{ $presence->etudiants->prenom }}</td>
                    <td>{{ $presence->etudiants->noet }}</td>
                    <td>
                        @if($presence->statut == 'present')
                            <span class="status-present">PRÉSENT</span>
                        @else
                            <span class="status-absent">{{ strtoupper($presence->statut) }}</span>
                        @endif
                    </td>
                    <td>{{ $presence->date_enregistrement ? \Carbon\Carbon::parse($presence->date_enregistrement)->format('H:i') : '-' }}
                    </td>
                    <td></td> {{-- Espace pour signature manuelle si imprimé --}}
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Aucune présence enregistrée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Signature de l'enseignant:</p>
        <br><br><br>
        <p>_______________________</p>
    </div>
</body>

</html>