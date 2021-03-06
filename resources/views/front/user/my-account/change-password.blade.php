<?php $nav_password = 'active'; ?>

@extends('front.layouts.app')

@section('meta_title','My Account E commerce')
@section('meta_description','My Account E commerce')

@section('content')
    <main class="padd">
        <div class="container">
            <div class="bat">
                <div class="row">

                    @include('front.user.my-account.sidebar')

                    <div class="col-sm-9 profile-info">
                        <h3 class="fat"><span>{{ __('front.account-change-password') }}</span></h3>
                        <div class="row space">
                            <div class="auth-wrap">

                                <div class="auth-col" style="width: 63%;">
                                    <form method="post" action="{{ route('my-account.change-password.post') }}" >
                                        {{ csrf_field() }}

                                        <p>
                                            <input placeholder="{{ __('front.account-current-password') }}*" type="password" name="current_password" id="reg_password">
                                        </p>
                                        <p>
                                            <input placeholder="{{ __('front.account-new-password') }}*" type="password" name="password" id="reg_password">
                                        </p>
                                        <p>
                                            <input placeholder="{{ __('front.account-confirm-password') }}*" type="password" name="password_confirmation" id="reg_password">
                                        </p>

                                        <p class="auth-submit">
                                            <input type="submit" value="{{ __('front.account-change-password') }}">
                                        </p>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
@endsection