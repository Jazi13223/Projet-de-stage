<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RésuTrack - ENEAM</title>
    
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- AOS CSS CDN -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Google Fonts (Rubik) -->
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Rubik', sans-serif;
            background: linear-gradient(to bottom, #f0f4f8, #d1e0e8);
            color: #333;
            margin: 0;
            overflow-x: hidden;
        }
        .hero {
            background: linear-gradient(to right, rgba(40, 167, 69, 0.95), rgba(23, 162, 184, 0.95));
            color: #fff;
            padding: 6rem 1rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
            opacity: 0.5;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .hero p {
            font-size: 1.25rem;
            font-weight: 400;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        .btn-primary {
            background-color: #fff;
            color: #28a745;
            padding: 0.75rem 2.5rem;
            border-radius: 2rem;
            font-weight: 700;
            font-size: 1.1rem;
            text-transform: uppercase;
            border: 2px solid #28a745;
            transition: background-color 0.3s ease, color 0.3s ease, transform 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #28a745;
            color: #fff;
            transform: scale(1.1);
        }
        .features-section {
            padding: 3rem 1rem;
            background-color: #fff;
        }
        .features-section h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 2rem;
        }
        .feature-card {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-align: center;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.2);
        }
        .feature-card i {
            font-size: 2.5rem;
            color: #28a745;
            margin-bottom: 1rem;
        }
        .feature-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .feature-card p {
            font-size: 0.875rem;
            color: #555;
        }
        .steps-section {
            padding: 3rem 1rem;
            background-color: #f8f9fa;
        }
        .steps-section h2 {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            text-align: center;
            margin-bottom: 2rem;
        }
        .step-card {
            background: #fff;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #17a2b8;
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .step-card:hover {
            transform: translateX(5px);
        }
        .step-card i {
            font-size: 1.5rem;
            color: #17a2b8;
            margin-right: 1rem;
        }
        .step-card h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .step-card p {
            font-size: 0.875rem;
            color: #555;
        }
        .footer {
            background-color: #343a40;
            color: #fff;
            padding: 1.5rem 1rem;
            text-align: center;
        }
        .footer .social-icons i {
            font-size: 1.25rem;
            margin: 0 0.75rem;
            color: #28a745;
            transition: color 0.3s ease, transform 0.3s ease;
        }
        .footer .social-icons i:hover {
            color: #17a2b8;
            transform: rotate(360deg);
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <div class="hero" data-aos="zoom-in" data-aos-duration="1000">
        <div class="container mx-auto px-4 max-w-5xl">
            <h1 data-aos="fade-up" data-aos-delay="100">Découvrez RésuTrack</h1>
            <p data-aos="fade-up" data-aos-delay="200">La plateforme moderne pour gérer vos résultats académiques</p>
            <a href="{{ route('auth.register.etudiant') }}" class="btn-primary inline-flex items-center" data-aos="zoom-in" data-aos-delay="300">
                <i class="fas fa-sign-in-alt mr-2"></i> Commencer
            </a>
        </div>
    </div>

    <!-- Fonctionnalités -->
    <div class="features-section" data-aos="fade-up" data-aos-duration="800">
        <div class="container mx-auto px-4 max-w-3xl">
            <h2 data-aos="fade-up" data-aos-delay="100">Pourquoi choisir RésuTrack ?</h2>
            <div class="feature-card" data-aos="slide-up" data-aos-delay="200">
                <i class="fas fa-user-graduate"></i>
                <h3>Consultation des notes</h3>
                <p>Accédez à vos résultats académiques en temps réel, où que vous soyez.</p>
            </div>
            <div class="feature-card" data-aos="slide-up" data-aos-delay="300">
                <i class="fas fa-chart-line"></i>
                <h3>Statistiques visuelles</h3>
                <p>Visualisez vos performances par UE, ECUE et semestre avec des graphiques clairs.</p>
            </div>
            <div class="feature-card" data-aos="slide-up" data-aos-delay="400">
                <i class="fas fa-check-circle"></i>
                <h3>Validation académique</h3>
                <p>Vérifiez votre statut de validation annuel et semestriel en un clic.</p>
            </div>
        </div>
    </div>

    <!-- Comment ça marche -->
    <div class="steps-section" data-aos="fade-up" data-aos-duration="800">
        <div class="container mx-auto px-4 max-w-3xl">
            <h2 data-aos="fade-up" data-aos-delay="100">Comment ça marche ?</h2>
            <div class="step-card" data-aos="fade-right" data-aos-delay="200">
                <div class="flex items-center">
                    <i class="fas fa-sign-in-alt"></i>
                    <div>
                        <h3>1. Connexion</h3>
                        <p>Connectez-vous avec votre compte étudiant sécurisé.</p>
                    </div>
                </div>
            </div>
            <div class="step-card" data-aos="fade-right" data-aos-delay="300">
                <div class="flex items-center">
                    <i class="fas fa-eye"></i>
                    <h3>2. Visualisation</h3>
                    <p>Consultez vos notes et moyennes détaillées.</p>
                    </div>
                </div>
            <div class="step-card" data-aos="fade-right" data-aos-delay="400">
                <div class="flex items-center">
                    <i class="fas fa-check"></i>
                    <div>
                        <h3>3. Validation</h3>
                        <p>Vérifiez le statut de validation de votre année académique.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer" data-aos="fade-up" data-aos-duration="800">
        <div class="container mx-auto px-4 max-w-5xl">
            <p class="text-sm">© 2025 RésuTrack - ENEAM. Tous droits réservés.</p>
            <div class="social-icons mt-2">
                <i class="fab fa-facebook" data-aos="zoom-in" data-aos-delay="100"></i>
                <i class="fab fa-twitter" data-aos="zoom-in" data-aos-delay="200"></i>
                <i class="fab fa-linkedin" data-aos="zoom-in" data-aos-delay="300"></i>
            </div>
        </div>
    </footer>

    <!-- AOS JS CDN -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
        });
    </script>
</body>
</html>