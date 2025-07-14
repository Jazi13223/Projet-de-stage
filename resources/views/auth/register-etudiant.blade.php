@extends('adminlte::auth.auth-page', ['auth_type' => 'register'])

@section('title', 'Inscription Étudiant')

@section('auth_header', 'Créer un compte Étudiant')

@section('auth_body')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

    <form action="{{ route('register.etudiant') }}" method="POST">
        @csrf

        {{-- Nom --}}
        <div class="mb-3">
            <label for="name">Nom</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        {{-- Prénom --}}
        <div class="mb-3">
            <label for="first_name">Prénom</label>
            <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
            @error('first_name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        {{-- Matricule --}}
        <div class="mb-3">
            <label for="matricule">Matricule</label>
            <input type="text" name="matricule" class="form-control" value="{{ old('matricule') }}" required>
            @error('matricule') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        {{-- Filière --}}
        <div class="mb-3">
            <label for="filiere_id">Filière</label>
            <select name="filiere_id" class="form-control" required>
                <option value="">-- Sélectionner une filière --</option>
                @foreach ($filieres as $filiere)
                    <option value="{{ $filiere->id }}" {{ old('filiere_id') == $filiere->id ? 'selected' : '' }}>
                        {{ $filiere->name }}
                    </option>
                @endforeach
            </select>
            @error('filiere_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        {{-- Année académique --}}
        <div class="mb-3">
            <label for="year_id">Année académique</label>
            <select name="year_id" class="form-control" required>
                <option value="">-- Sélectionner une année --</option>
                @foreach ($years as $year)
                    <option value="{{ $year->id }}" {{ old('year_id') == $year->id ? 'selected' : '' }}>
                        {{ $year->academic_year }}
                    </option>
                @endforeach
            </select>
            @error('year_id') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        {{-- Semestre --}}
        <div class="mb-3">
            <label for="semester_id">Semestre</label>
            <select name="semester_id" class="form-control" required>
                <option value="">-- Sélectionner un semestre --</option>
                @foreach ($semesters as $semester)
                    <option value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
                        {{ $semester->name }}
                    </option>
                @endforeach
            </select>
            @error('semester_id') <span class="text-danger">{{ $message }}</span> @enderror
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
        Déjà inscrit ? <a href="{{ route('login.etudiant') }}">Se connecter</a>
    </p>
@endsection
