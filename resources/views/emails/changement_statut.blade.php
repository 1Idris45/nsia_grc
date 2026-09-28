<!DOCTYPE html>
<html>
<body>
    <p>Bonjour {{ $reclamation->client->utilisateur->prenom_util }},</p>
    <p>{{ $reclamation->obj_reclam }}</p>
    <p>Nouveau statut : <strong>{{ $reclamation->statut->lib_stat }}</strong></p>
    <p>NSIA Vie Assurances</p>
</body>
</html>