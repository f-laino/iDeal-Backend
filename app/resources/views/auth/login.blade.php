@extends('layouts.auth')

@section('app-content')

    <h4 class="auth-header">
        {{ __(' Area Riservata') }}
    </h4>

    <form method="POST" action="{{ route('login') }}">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="email">
                {{ __('Email') }}
            </label>
            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                   id="email"
                   type="email"
                   name="email"
                   placeholder=" {{ __('Email') }}"
                   value="{{ old('email') }}" required>
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
        <div class="buttons-w">
            <button class="btn btn-primary" type="submit">
                {{ __('Accedi') }}
            </button>
            <div class="form-check-inline">
                <label class="form-check-label">
                    <input class="form-check-input" type="checkbox" name="remember">
                    {{ __('Ricorda il mio accesso.') }}
                </label>
            </div>
        </div>

        <div class="buttons-w text-center">
            <a href="{{ route('password.request') }}" title="{{ __('Recupero Password') }}" class="btn btn-default">
                {{ __('Hai dimenticato la password?') }}
            </a>
        </div>
    </form>


@endsection
