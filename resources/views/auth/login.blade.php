@extends('layouts.app')

@section('content')
    <div class="container" id="login">
        <div class="row justify-content-center">
            @if(Session::has('sharingText'))
            <div class="alert alert-primary col-md-7" role="alert">
                <h4 class="alert-heading">NetApp</h4>
                <p> - Вам предоставляется доступ к одному из списков {{Session::get('sharingText')}}, для подтверждения войдите в систему.</p>
            </div>
            @endif
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Войти</div>

                    <div class="card-body">
                        @if(!\Illuminate\Support\Facades\Session::has('status'))
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">Телефон</label>

                                    <div class="col-md-6">
                                        <input id="email" type="tel"
                                               class="form-control @error('phone') is-invalid @enderror" name="phone"
                                               value="{{ old('phone') }}" required autofocus>

                                        @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

{{--                                <div class="form-group row">--}}
{{--                                    <label for="password" class="col-md-4 col-form-label text-md-right">Пароль</label>--}}

{{--                                    <div class="col-md-6">--}}
{{--                                        <input id="password" type="password"--}}
{{--                                               class="form-control @error('password') is-invalid @enderror"--}}
{{--                                               name="password" required autocomplete="current-password">--}}

{{--                                        @error('password')--}}
{{--                                        <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                        @enderror--}}
{{--                                    </div>--}}
{{--                                </div>--}}


                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Войти
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @else
                            <form method="POST" action="{{ route('web.checkCode') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">Введите код</label>

                                    <div class="col-md-6">
                                        <input id="email" type="number"
                                               class="form-control @error('code') is-invalid @enderror" name="code"
                                               value="{{ old('code') }}" required autofocus>

                                        @error('code')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>
{{--                                <input name="password" value="{{\Illuminate\Support\Facades\Session::get('password')}}" hidden>--}}
                                <input name="phone" value="{{\Illuminate\Support\Facades\Session::get('phone')}}" hidden>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            Подтвердить
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

