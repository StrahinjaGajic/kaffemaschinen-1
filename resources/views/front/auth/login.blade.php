@extends('front.layouts.app')

@section('meta_title', 'Login: Schoengebraucht')
@section('meta_description', 'My Account Management System for Schoengebraucht E Commerce')

@section('content')
    <!-- Main Content - start -->
    <main>
        <section class="container stylization maincont">


            <ul class="b-crumbs">
                <li>
                    <a href="{{ route('home') }}">
                        Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('login') }}">
                        Login
                    </a>
                </li>
            </ul>
            <h1 class="main-ttl"><span>Login</span></h1>
                <div class="login-form">
                    <form class="login" role="form" method="POST" action="{{ route('login.post') }}">
                        {{ csrf_field() }}
                        @if (session('status'))
                            <div class="alert alert-danger">
                                {{ session('status') }}
                            </div>
                        @endif
                        
                            <input placeholder="E-mail*" id="email" type="email" name="email" class="form-control login_input" value="{{ old('email') }}" required autofocus>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                            @endif
                        
                            <input placeholder="Passwort*" id="password" class="form-control login_input" type="password" name="password" required>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                            @endif
                        
                       <div class="login_button">
                          
                          
                           <div class="remember_me">
                           <input type="checkbox" name="remember_me" id="rememberme" value="forever" style="display:none;">
                            <label class="labelcina" for="rememberme">{{ __('front.account-remember-me') }}</label>
                             
                            </div>
                            <input type="submit" value="Login" style="display:unset;">
                             
                        </div>
                            

                        
                        <p class="auth-lost_password">
                            <a href="/forgot-password">{{ __('front.account-lost-your-password') }}</a>
                        </p>
                    </form>
</div>



        </section>
    </main>
    <!-- Main Content - end -->

@endsection

