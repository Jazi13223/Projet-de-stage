@extends('adminlte::auth.auth-page', ['auth_type' => 'register'])

@section('title', 'Inscription Secrétaire')

@section('auth_header', 'Créer un compte Secrétaire')

@section('auth_body')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('register.secretaire') }}" method="POST">
    @csrf

    {{-- Nom --}}
    <div class="mb-3">
        <label for="name">Nom complet</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    {{-- Email --}}
    <div class="mb-3">
        <label for="email">Adresse Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    {{-- Mot de passe --}}
    <div class="mb-3">
        <label for="password">Mot de passe</label>
        <input type="password" name="password" class="form-control" required>
        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

    {{-- Confirmation mot de passe --}}
    <div class="mb-3">
        <label for="password_confirmation">Confirmer le mot de passe</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    {{-- Bouton submit --}}
    <button type="submit" class="btn btn-primary btn-block">
        S'inscrire
    </button>
</form>

@endsection

@section('auth_footer')
    <p class="text-center">
        Déjà inscrit ? <a href="{{ route('login.secretaire') }}">Se connecter</a>
    </p>
@endsection

@push('adminlte_css')
<style>
    /* Masquer la sidebar */
    .main-sidebar {
        display: none;
    }

    /* Ajuster le contenu pour occuper tout l'espace */
    .content-wrapper {
        margin-left: 0 !important;
    }

    /* Masquer le footer */
    .footer {
        display: none;
    }

    /* Enlever les marges inutiles */
    .content-header {
        margin-bottom: 0;
    }

    .content {
        padding: 15px 0;
    }
</style>
@endpush
