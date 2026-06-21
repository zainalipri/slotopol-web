@extends('layouts.app')

@section('content')
<div class="login-container">
    <div class="login-card">
        <h1>🎰 Slotopol Goldwin</h1>
        <h2>Login to Your Account</h2>

        @if ($errors->any())
        <div class="error-box">
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                @error('email')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                @error('password')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="login-footer">
            <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
            <p><a href="#">Forgot password?</a></p>
        </div>

        <div class="divider">OR</div>

        <div class="social-login">
            <p style="color: #b0b0b0; margin-bottom: 1rem;">Login with Supabase</p>
            <button type="button" class="btn-supabase" onclick="loginWithSupabase()">
                🔐 Supabase Authentication
            </button>
        </div>
    </div>
</div>

<style>
    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 200px);
        padding: 2rem;
    }

    .login-card {
        background: linear-gradient(135deg, #0f3460, #16213e);
        border: 2px solid #ffd700;
        border-radius: 15px;
        padding: 3rem;
        max-width: 450px;
        width: 100%;
        box-shadow: 0 10px 40px rgba(255, 215, 0, 0.2);
    }

    .login-card h1 {
        color: #ffd700;
        text-align: center;
        font-size: 2em;
        margin-bottom: 0.5rem;
        text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
    }

    .login-card h2 {
        color: #b0b0b0;
        text-align: center;
        font-size: 1.3em;
        margin-bottom: 2rem;
    }

    .error-box {
        background: rgba(244, 67, 54, 0.1);
        border: 2px solid #f44336;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        color: #f44336;
    }

    .error-box p {
        margin: 0.5rem 0;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        color: #ffd700;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    .form-group input {
        width: 100%;
        padding: 1rem;
        background: rgba(255, 215, 0, 0.05);
        border: 2px solid #ffd700;
        border-radius: 8px;
        color: #fff;
        font-size: 1em;
        transition: all 0.3s ease;
    }

    .form-group input:focus {
        outline: none;
        background: rgba(255, 215, 0, 0.1);
        box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
    }

    .error-text {
        color: #f44336;
        font-size: 0.9em;
        display: block;
        margin-top: 0.3rem;
    }

    .btn-login {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #ffd700, #ffed4e);
        color: #000;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        font-size: 1.1em;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 1rem;
    }

    .btn-login:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 30px rgba(255, 215, 0, 0.3);
    }

    .login-footer {
        text-align: center;
        margin-top: 1.5rem;
        color: #b0b0b0;
    }

    .login-footer p {
        margin: 0.5rem 0;
    }

    .login-footer a {
        color: #ffd700;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .login-footer a:hover {
        color: #ffed4e;
    }

    .divider {
        text-align: center;
        color: #666;
        margin: 2rem 0;
        position: relative;
    }

    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #333;
    }

    .divider {
        background: linear-gradient(135deg, #0f3460, #16213e);
        position: relative;
    }

    .social-login {
        text-align: center;
    }

    .btn-supabase {
        width: 100%;
        padding: 1rem;
        background: rgba(255, 215, 0, 0.1);
        color: #ffd700;
        border: 2px solid #ffd700;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-supabase:hover {
        background: rgba(255, 215, 0, 0.2);
        box-shadow: 0 5px 15px rgba(255, 215, 0, 0.2);
    }
</style>

<script>
    function loginWithSupabase() {
        // Supabase OAuth login can be implemented here
        alert('Supabase authentication coming soon!');
    }
</script>
@endsection
