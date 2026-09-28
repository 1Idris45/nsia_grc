<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2563eb; padding-bottom: 15px; }
        .header h1 { color: #2563eb; margin: 0; font-size: 20px; }
        .label { color: #6b7280; font-size: 10px; text-transform: uppercase; }
        .value { font-weight: bold; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        td { padding: 8px 0; vertical-align: top; width: 50%; }
        .description { background: #f9fafb; padding: 15px; border-radius: 4px; margin-top: 15px; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <h1>NSIA Vie Assurances</h1>
        <p>Récapitulatif de réclamation N°{{ $reclamation->cod_reclam }}</p>
    </div>

    <table>
        <tr>
            <td>
                <p class="label">Client</p>
                <p class="value">{{ $reclamation->client->utilisateur->prenom_util }} {{ $reclamation->client->utilisateur->nom_util }}</p>
            </td>
            <td>
                <p class="label">Statut</p>
                <p class="value">{{ $reclamation->statut->lib_stat }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p class="label">Type de réclamation</p>
                <p class="value">{{ $reclamation->typeReclamation->lib_typ_rec }}</p>
            </td>
            <td>
                <p class="label">Service</p>
                <p class="value">{{ $reclamation->service->lib_serv ?? 'Non affecté' }}</p>
            </td>
        </tr>
        <tr>
            <td>
                <p class="label">Date de soumission</p>
                <p class="value">{{ \Carbon\Carbon::parse($reclamation->dat_reclam)->format('d/m/Y') }}</p>
            </td>
            <td>
                <p class="label">Échéance (10 j. ouvrés)</p>
                <p class="value">{{ \Carbon\Carbon::parse($reclamation->date_echeance)->format('d/m/Y') }}</p>
            </td>
        </tr>
    </table>

    <p class="label">Objet</p>
    <p class="value">{{ $reclamation->obj_reclam }}</p>

    <p class="label">Description</p>
    <div class="description">{{ $reclamation->des_reclam }}</div>

    <p class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }} — NSIA Vie Assurances Côte d'Ivoire
    </p>
</body>
</html>