@extends('layouts.form')

@section('form')
    @component('components.public_form',['action' => route('register'), 'title' => 'Cadastre-se', 'submitMessage' => 'Continuar', 'formClasses' => 'px-5 px-5 col-sm-6 col-11 top-spacing'])
        <div class="form-stepper mb-5">
            <div class="d-flex flex-row">
                <div class="step current-step me-1">
                    <i class="bi bi-circle"></i>
                    <span class="ms-2">Dados pessoais</span>
                </div>
                <div class="step">
                    <i class="bi bi-dash-lg m-0"></i>
                    <i class="bi bi-circle m-0"></i>
                    <span class="ms-2">Confirmação</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <label for="name" class="h5 fw-bold">Nome *</label>
                <div class="input-container">
                    <input id="name" placeholder="Nome completo" type="text" class="form-control @error('name') is-invalid @enderror" name="name" required autocomplete="name" autofocus>
                    <i class="bi bi-tag input-icon @error('name') d-none @enderror"></i>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="mb-4 col-6">
                <label for="cpf" class="h5 fw-bold">CPF *</label>
                <div class="input-container">
                    <input id="cpf" placeholder="000.000.000-00" type="text" class="form-control @error('cpf') is-invalid @enderror" name="cpf" value="{{ old('cpf') }}" required autocomplete="cpf">
                    <i class="bi bi-person input-icon @error('cpf') d-none @enderror"></i>
                    @error('cpf')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <label for="phone" class="h5 fw-bold">Telefone *</label>
                <div class="input-container">
                    <input id="phone" placeholder="(00) 00000-0000" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" required autocomplete="tel">
                    <i class="bi bi-telephone input-icon @error('phone') d-none @enderror"></i>
                    @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="mb-4 col-6">
                <label for="email" class="h5 fw-bold">E-mail *</label>
                <div class="input-container">
                    <input id="email" placeholder="exemplo@email.com" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                    <i class="bi bi-envelope input-icon @error('email') d-none @enderror"></i>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <label for="password" class="h5 fw-bold">Senha *</label>
                <div class="input-container">
                    <input id="password" placeholder="senha" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    <i class="bi bi-key input-icon @error('password') d-none @enderror"></i>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-6">
                <label for="password-confirm" class="h5 fw-bold">Confirmar senha *</label>
                <div class="input-container">
                    <input id="password-confirm" placeholder="senha" type="password" class="form-control @error('password-confirm') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password">
                    <i class="bi bi-key input-icon @error('password-confirm') d-none @enderror"></i>
                    @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
        </div>
    @endcomponent
@endsection
{{-- 
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name">

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
