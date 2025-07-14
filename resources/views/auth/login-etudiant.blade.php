<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Étudiant</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
</head>
<body class="login-page">

    <div class="login-box">
        <div class="login-logo">
            <b>Portail Étudiant</b>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Connexion à votre espace étudiant</p>

                {{-- Messages flash --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->has('login'))
                    <div class="alert alert-danger">
                        {{ $errors->first('login') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.etudiant.submit') }}">
                    @csrf

                    {{-- Matricule --}}
                    <div class="input-group mb-3">
                        <input id="matricule" type="text"
                               class="form-control @error('matricule') is-invalid @enderror"
                               name="matricule" value="{{ old('matricule') }}" placeholder="Matricule" required autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-id-card"></span>
                            </div>
                        </div>
                        @error('matricule')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    {{-- Mot de passe --}}
                    <div class="input-group mb-3">
                        <input id="password" type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password" placeholder="Mot de passe" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @error('password')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    {{-- Bouton --}}
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">
                                Se connecter
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Lien vers inscription --}}
                <p class="mt-3 mb-0 text-center">
                    <a href="{{ route('register.etudiant') }}">Créer un compte étudiant</a>
                </p>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
