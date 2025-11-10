<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification</title>
</head>
<body>
    <h1>Bonjour {{ $user->prenom }} {{ $user->nom }},</h1>
    <p>Votre compte a été créé avec succès.</p>
    <p><strong>Mot de passe :</strong> {{ $password }}</p>
    <p>Veuillez utiliser ce mot de passe pour vous connecter.</p>
    <p>Cordialement,<br>L'équipe</p>
</body>
</html>