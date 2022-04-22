@extends('layouts.auth')

@section('app-content')

    <h4 class="auth-header">
        {{ __('Reimposta la password di accesso') }}
    </h4>

    <form method="POST" action="{{ route('password.update') }}">
        {!! csrf_field() !!}
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-group">
            <label for="email">{{ __('E-Mail') }}</label>
            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                   type="email"
                   name="email"
                   id="email"
                   placeholder="{{ __('Email') }}"
                   value="{{ old('email') }}"
                   required
            >
            <div class="pre-icon os-icon os-icon-user-male-circle"></div>
        </div>

        @if ($errors->has('email'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('email') }}</strong></div>
        @endif


        <div class="form-group">
            <label for="password"> {{ __('Password') }}</label>
            <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                   type="password"
                   name="password"
                   placeholder="Password"
                   id="password" required>
            <div class="pre-icon os-icon os-icon-fingerprint"></div>
        </div>
        @if ($errors->has('password'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('password') }}</strong></div>
        @endif


        <div class="form-group">
            <label for="password"> {{ __('Conferma password') }}</label>
            <input class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                   type="password"
                   name="password_confirmation"
                   placeholder="Conferma password"
                   id="password_confirmation" required>
            <div class="pre-icon os-icon os-icon-fingerprint"></div>
        </div>
        @if ($errors->has('password_confirmation'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('password_confirmation') }}</strong></div>
        @endif

        <div class="buttons-w text-center">
            <button class="btn btn-primary" type="submit">
                {{ __('Aggiorna la mia password') }}
            </button>
        </div>

        <div class="buttons-w text-center">
            <a href="{{ route('login') }}"
               title="{{ __('Accesso al sistema') }}"
               class="btn btn-default">
                {{ __(' Ricordo la mia password!') }}
            </a>
        </div>

    </form>


@endsection
