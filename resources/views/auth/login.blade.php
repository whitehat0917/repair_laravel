@extends('front.layout')

@section('main')
<style>
    header{
        display: none;
    }
</style>
   <section class="h-100 white-background">
        <div class="col-six dark-background h-100 vertical-center full-width-on-mobile pw-60">
            <div class="login-header-div text-center">
                <img src="{{ asset('images/login_logo.png') }}" alt="">
            </div>
        </div>
        <div class="col-six h-100 full-width-on-mobile">
            <div class="login-content-div text-center">
                @if (session('confirmation-success'))
                    @component('front.components.alert')
                        @slot('type')
                            success
                        @endslot
                        {!! session('confirmation-success') !!}
                    @endcomponent
                @endif
                @if (session('confirmation-danger'))
                    @component('front.components.alert')
                        @slot('type')
                            error
                        @endslot
                        {!! session('confirmation-danger') !!}
                    @endcomponent
                @endif
                <div class="text-center pt-60">
                    <img src="{{ asset('images/login_logo1.png') }}" alt="" width="170">
                </div>
                <p class="fs-30 font-bold text-black mt-100 mb-10">Log in account</p>
                <form role="form" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    @if ($errors->has('log'))
                        @component('front.components.error')
                            {{ $errors->first('log') }}
                        @endcomponent
                    @endif   
                    <div class="login-form-div">
                        <label class="margin-0 dark-color">User</label>
                        <input id="log" type="text" class="full-width" name="log" required autofocus>
                    </div>
                    <div class="login-form-div full-width-on-mobile">
                        <label class="margin-0 dark-color">Password</label>
                        <input id="password" type="password" class="full-width" name="password" required>
                    </div>
                    <div class="login-button-div full-width-on-mobile mt-50">
                        <input class="button-primary login-button" type="submit" value="Log in">
                    </div>
                </form>
                <div class="login-footer-div text-light-yellow mt-20">&copy;Foned 2020</div>
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-twelve">
                <div class="primary-content">
                    @if (session('confirmation-success'))
                        @component('front.components.alert')
                            @slot('type')
                                success
                            @endslot
                            {!! session('confirmation-success') !!}
                        @endcomponent
                    @endif
                    @if (session('confirmation-danger'))
                        @component('front.components.alert')
                            @slot('type')
                                error
                            @endslot
                            {!! session('confirmation-danger') !!}
                        @endcomponent
                    @endif
                    <h3>@lang('Login')</h3>
                    <form role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        @if ($errors->has('log'))
                            @component('front.components.error')
                                {{ $errors->first('log') }}
                            @endcomponent
                        @endif   
                        <input id="log" type="text" placeholder="@lang('Login')" class="full-width" name="log" value="{{ old('log') }}" required autofocus>
                        <input id="password" type="password" placeholder="@lang('Password')" class="full-width" name="password" required>
                        <label class="add-bottom">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> 
                            <span class="label-text">@lang('Remember me')</span>
                        </label>
                        <input class="button-primary full-width-on-mobile" type="submit" value="@lang('Login')">
                        <label class="add-bottom">
                            <a href="{{ route('password.request') }}">
                                @lang('Forgot Your Password?')
                            </a><br>
                            <a href="{{ route('register') }}">
                                @lang('Not registered?')
                            </a>
                        </label>
                    </form>
                </div>
            </div>
        </div> -->
    </section>
@endsection
