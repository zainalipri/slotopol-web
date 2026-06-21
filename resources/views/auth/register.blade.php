@extends('layouts.app')

@section('content')
<div class="register-container">
    <div class="register-card">
        <h1>🎰 Slotopol Goldwin</h1>
        <h2>Create Your Account</h2>

        @if ($errors->any())
        <div class="error-box">
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error('name')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                @error('email')
                <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required>
                @error('phone')
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

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
            </div>

            <div class="terms">
                <input type="checkbox" name="terms" id="terms" required>
                <label for="terms">I agree to the Terms & Conditions and Privacy Policy</label>
            </div>

            <button type="submit" class="btn-register">Create Account</button>
        </form>

        <div class="register-footer">
            <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
        </div>
    </div>
</div>

<style>
    .register-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 200px);
        padding: 2rem;
    }

    .register-card {
        background: linear-gradient(135deg, #0f3460, #16213e);
        border: 2px solid #ffd700;
        border-radius: 15px;
        padding: 3rem;
        max-width: 500px;
        width: 100%;
        box-shadow: 0 10px 40px rgba(255, 215, 0, 0.2);
    }

    .register-card h1 {
        color: #ffd700;
        text-align: center;
        font-size: 2em;
        margin-bottom: 0.5rem;
        text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
    }

    .register-card h2 {
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

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="tel"],
    .form-group input[type="password"] {
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

    .terms {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        color: #b0b0b0;
    }

    .terms input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .terms label {
        margin: 0;
        cursor: pointer;
    }

    .btn-register {
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
    }

    .btn-register:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 30px rgba(255, 215, 0, 0.3);
    }

    .register-footer {
        text-align: center;
        margin-top: 1.5rem;
        color: #b0b0b0;
    }

    .register-footer a {
        color: #ffd700;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .register-footer a:hover {
        color: #ffed4e;
    }
</style>
@endsection
