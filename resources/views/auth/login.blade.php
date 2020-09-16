@extends('layouts.app')

@section('content')
<!-- <div class="form-group row">
    <label for="username" class="col-sm-4 col-form-label text-md-right">{{ __('Username') }}</label>
    <div class="col-md-6">
        <input id="username" type="username" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>
        @if ($errors->has('username'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('username') }}</strong>
            </span>
        @endif
    </div>
</div> -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group row">
                            <div class="offset-md-4"></div>
                            <div class="col-md-6">
                                @if ($errors->has('extensionAttribute10'))
                                    <span class="invalid-feedback" style="display:block !important;" role="alert">
                                        <strong>{{ $errors->first('extensionAttribute10') }}</strong>
                                    </span>
                                @endif
                                @if (session('msjError'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ session('msjError') }}</strong>
                                    </span>
                                @endif
                                @if (isset($msjError))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $msjError }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dominio" class="col-md-4 col-form-label text-md-right">{{ __('Dominio') }}</label>

                            <div class="col-md-6">
                            
                                <select id="dominio" class="form-control{{ $errors->has('dominio') ? ' is-invalid' : '' }}" name="dominio" required autofocus>
                                    @if(old('dominio'))
                                        @php
                                            $dominioOld = old('dominio');
                                        @endphp
                                    @else
                                        @php
                                            $dominioOld = '';
                                        @endphp
                                    @endif
                                    <option value="" {{ empty($dominioOld) ? "selected" : "" }}>Seleccione...</option>
                                    <option value="1" {{ $dominioOld == 1 ? "selected" : "" }}>TELEVISA</option>
                                    <option value="2" {{ $dominioOld == 2 ? "selected" : "" }}>SOI</option>
                                </select>

                                @if ($errors->has('dominio'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('dominio') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Usuario de Red') }}</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ old('username') }}" required autofocus>

                                @if ($errors->has('username'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <!-- Quitando No. de empleado del login -->
                        <!-- <div class="form-group row">
                            <label for="extensionAttribute15" class="col-md-4 col-form-label text-md-right">{{ __('No. Empleado') }}</label>

                            <div class="col-md-6">
                                <input id="extensionAttribute15" type="number" class="form-control{{ $errors->has('extensionAttribute15') ? ' is-invalid' : '' }}" name="extensionAttribute15" value="{{ old('extensionAttribute15') }}" required autofocus>

                                @if ($errors->has('extensionAttribute15'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('extensionAttribute15') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> -->

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4 text-right">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Acceder') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    @if(session('msjWarning'))
    <script>
        swal({
            title: 'Bloqueo',
            text: '{{ session("msjWarning") }}',
            type: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            allowOutsideClick: false,
            allowEscapeKey: false,
            confirmButtonText: 'De acuerdo'
        });
    </script>
    @endif
@endpush