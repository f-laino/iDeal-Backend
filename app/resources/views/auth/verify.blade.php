@extends('layouts.auth')

@section('app-content')

    <h4 class="auth-header">
        {{ __('Ho dimenticato la password') }}
    </h4>

    <form method="POST" action="{{ route('password.email') }}">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="email">{{ __('E-Mail') }}</label>
            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                   type="email"
                   name="email"
                   id="email"
                   value="{{ old('email') }}"
                   required
            >
            <div class="pre-icon os-icon os-icon-user-male-circle"></div>
        </div>


        @if ($errors->has('email'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('email') }}</strong></div>

        @elseif ($errors->has('status'))
            <div class="help-block form-text with-errors form-control-feedback">
                <strong>{{ $errors->first('status') }}</strong></div>
        @endif

        <div class="buttons-w">
            <button class="btn btn-primary" type="submit">
                {{ __('Invia email di recupero') }}
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
