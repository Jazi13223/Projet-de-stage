<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>RésuTrack - ENEAM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- AdminLTE + Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            background-color: #f0f4f8;
            color: #333;
            font-family: 'Roboto', sans-serif;
            margin: 0;
        }
        .hero {
            padding: 100px 20px;
            text-align: center;
            background: linear-gradient(to right, #28a745, #17a2b8);
            color: #fff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .hero h1 {
            font-size: 3.2rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .hero p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }
        .btn-custom {
            background-color: #fff;
            color: #28a745;
            padding: 12px 30px;
            border-radius: 30px;
            font-size: 1.1rem;
            text-transform: uppercase;
            font-weight: 600;
           
            transition: all 0.3s ease;
        }
        
        .section {
            padding: 60px 20px;
            text-align: center;
            margin-bottom: 50px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }
        .section-dark {
            background-color: #f8f9fa;
        }
        .section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 30px;
            color: #333;
        }
        .feature-box {
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            margin: 15px;
            color: #333;
        }
        .feature-box:hover {
            transform: translateY(-5px);
        }
        .feature-box i {
            font-size: 3.5rem;
            color: #28a745;
            margin-bottom: 20px;
        }
        .feature-box h5 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .feature-box p {
            font-size: 1.1rem;
            color: #555;
        }
        .footer {
            background: #343a40;
            color: #fff;
            padding: 40px 20px;
            text-align: center;
            margin-top: 40px;
            border-top: 5px solid #28a745;
        }
        .footer p {
            font-size: 1rem;
            margin: 0;
        }
        .footer .social-icons i {
            font-size: 1.5rem;
            margin: 0 10px;
            color: #28a745;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .footer .social-icons i:hover {
            color: #fff;
        }
    </style>
</head>
<body>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Bienvenue sur RésuTrack</h1>
        <p>Le portail de gestion des résultats académiques de l’ENEAM</p>
        <a href="{{ route('auth.register.etudiant') }}" class="btn-custom"><i class="fas fa-sign-in-alt"></i> Inscription</a>
    </div>

    <!-- Fonctionnalités -->
    <div class="section">
        <div class="container">
            <h2>Fonctionnalités principales</h2>
            <div class="row justify-content-center">
                <div class="col-md-3 feature-box">
                    <i class="fas fa-user-graduate"></i>
                    <h5>Consultation des notes</h5>
                    <p>Accède à tes résultats en temps réel.</p>
                </div>
                <div class="col-md-3 feature-box">
                    <i class="fas fa-chart-line"></i>
                    <h5>Statistiques visuelles</h5>
                    <p>Suivi de tes performances par UE, ECUE, semestre.</p>
                </div>
                <div class="col-md-3 feature-box">
                    <i class="fas fa-check-circle"></i>
                    <h5>Validation académique</h5>
                    <p>Visualise ton statut de validation annuel et semestriel.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Comment ça marche -->
    <div class="section section-dark">
        <div class="container">
            <h2>Comment ça marche ?</h2>
            <div class="row justify-content-center">
                <div class="col-md-3">
                    <i class="fas fa-sign-in-alt fa-2x mb-2"></i>
                    <h6>1. Connexion</h6>
                    <p>Identifie-toi avec ton compte étudiant.</p>
                </div>
                <div class="col-md-3">
                    <i class="fas fa-eye fa-2x mb-2"></i>
                    <h6>2. Visualisation</h6>
                    <p>Consulte tes notes et moyennes détaillées.</p>
                </div>
                <div class="col-md-3">
                    <i class="fas fa-check fa-2x mb-2"></i>
                    <h6>3. Validation</h6>
                    <p>Vérifie le statut de validation de ton année.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 RésuTrack - ENEAM. Tous droits réservés.</p>
        <p>Développé avec ❤️ par l'équipe RésuTrack</p>
        <div class="social-icons">
            <i class="fab fa-facebook"></i>
            <i class="fab fa-twitter"></i>
            <i class="fab fa-linkedin"></i>
        </div>
    </footer>

</body>
</html>
