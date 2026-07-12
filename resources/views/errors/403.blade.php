<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès non autorisé - GestApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        .error-card {
            max-width: 520px;
            width: 100%;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            text-align: center;
            padding: 2.5rem;
            background: #fff;
        }
        .error-icon {
            font-size: 4.5rem;
            color: #dc3545;
            margin-bottom: 1rem;
        }
        .error-title {
            font-weight: 700;
            color: #2c3e50;
        }
        .error-message {
            color: #6c757d;
            font-size: 1.05rem;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">
            <i class="bi bi-emoji-frown"></i>
        </div>
        <h1 class="h3 error-title mb-3">Désolé, accès non autorisé</h1>
        <p class="error-message mb-4">
            Vous n'êtes pas autorisé à accéder à cette section.
            Veuillez contacter votre chef ou les administrateurs.
        </p>
        <div class="d-flex gap-2 justify-content-center">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
            <a href="{{ route('Home') }}" class="btn btn-primary">
                <i class="bi bi-house-door me-1"></i> Tableau de bord
            </a>
        </div>
    </div>
</body>
</html>
