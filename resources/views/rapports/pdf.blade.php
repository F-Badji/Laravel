<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rapport d'Émargements</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 14px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Institut Supérieur Informatique</h1>
        <p>Rapport d'Émargements</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Professeur</th>
                <th>Cours</th>
                <th>Statut</th>
                <th>Validation</th>
            </tr>
        </thead>
        <tbody>
            @foreach($emargements as $emargement)
                <tr>
                    <td>{{ $emargement->date->format('d/m/Y H:i') }}</td>
                    <td>{{ $emargement->professeur->nom }} {{ $emargement->professeur->prenom }}</td>
                    <td>{{ $emargement->cours->nom }}</td>
                    <td>{{ ucfirst($emargement->statut) }}</td>
                    <td>{{ $emargement->valide ? 'Validé' : 'En attente' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Généré le {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html> 