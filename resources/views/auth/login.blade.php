@extends('layouts.guest')

@section('title', 'Login')

@section('body')
    <x-partials.authentication-card>
        <x-display-validation-errors />
        <form action="{{ route('login') }}" method="POST" class="px-3 md:p-5 w-full border-b-2">
            <x-input name="email" id="email" type="email" label="Email" />
            <x-input name="password" id="password" type="password" label="Password" />
            <label for="remember"></label>
            <div class="my-3">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Remember Me</label>
            </div>
            @csrf
            <div class="flex justify-between md:justify-end gap-1 items-center">
                <a href="{{route('password.request')}}" class="text-blue-800">Forgot your Password?</a>
                <x-button class="my-3 px-6 md:px-10">
                    Log in
                </x-button>
            </div>
        </form>
        <div class="py-6">
            <p>Dont Have An account? <a href="{{route('register')}}" class="text-blue-800"> Create Account</a></p>
        </div>
    </x-partials.authentication-card>
@endsection