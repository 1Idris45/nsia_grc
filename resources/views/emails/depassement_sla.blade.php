<!DOCTYPE html>
<html>
<body>
    <p>Bonjour {{ $reclamation->client->utilisateur->prenom_util }},</p>
    <p>Votre réclamation « {{ $reclamation->obj_reclam }} » soumise le {{ $reclamation->dat_reclam->format('d/m/Y') }} est toujours en cours de traitement.</p>
    <p>Nous vous prions de nous excuser pour ce délai supplémentaire. Notre équipe reste mobilisée sur votre dossier.</p>
    <p>NSIA Vie Assurances</p>
</body>
</html>