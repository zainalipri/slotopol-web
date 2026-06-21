@extends('layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-wrapper">
        <!-- Login Form -->
        <div class="auth-form" id="loginForm">
            <div class="form-header">
                <h1>🎰 Slotopol Goldwin</h1>
                <p>Login to Your Account</p>
            </div>

            @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                <p>❌ {{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="login_email">📧 Email Address</label>
                    <input type="email" name="email" id="login_email" value="{{ old('email') }}" placeholder="your@email.com" required>
                </div>

                <div class="form-group">
                    <label for="login_password">🔐 Password</label>
                    <input type="password" name="password" id="login_password" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn-submit">🚀 Login Now</button>
            </form>

            <div class="form-divider">
                <span>OR</span>
            </div>

            <button type="button" class="btn-supabase" onclick="loginWithSupabase()">
                🔐 Login with Supabase
            </button>

            <div class="form-footer">
                <p>Don't have an account? <a href="#" onclick="toggleForms(event)">Register here</a></p>
                <p><a href="#">🔑 Forgot password?</a></p>
            </div>
        </div>

        <!-- Register Form -->
        <div class="auth-form hidden" id="registerForm">
            <div class="form-header">
                <h1>🎰 Slotopol Goldwin</h1>
                <p>Join the Gaming Revolution</p>
            </div>

            @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                <p>❌ {{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="reg_name">👤 Full Name</label>
                        <input type="text" name="name" id="reg_name" value="{{ old('name') }}" placeholder="John Doe" required>
                    </div>

                    <div class="form-group">
                        <label for="reg_phone">📱 Phone Number</label>
                        <input type="tel" name="phone" id="reg_phone" value="{{ old('phone') }}" placeholder="+92 300 1234567" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="reg_email">📧 Email Address</label>
                    <input type="email" name="email" id="reg_email" value="{{ old('email') }}" placeholder="your@email.com" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="reg_password">🔐 Password</label>
                        <input type="password" name="password" id="reg_password" placeholder="Min 6 characters" required>
                    </div>

                    <div class="form-group">
                        <label for="reg_password_confirm">✓ Confirm Password</label>
                        <input type="password" name="password_confirmation" id="reg_password_confirm" placeholder="Confirm password" required>
                    </div>
                </div>

                <div class="form-group referral">
                    <label for="referral_code">🎁 Referral Code (Optional)</label>
                    <input type="text" name="referral_code" id="referral_code" placeholder="Get bonus by using a code" value="{{ request('ref') ?? '' }}">
                </div>

                <div class="terms">
                    <input type="checkbox" name="terms" id="terms" required>
                    <label for="terms">I agree to <a href="#">Terms & Conditions</a> and <a href="#">Privacy Policy</a></label>
                </div>

                <button type="submit" class="btn-submit">✨ Create Account</button>
            </form>

            <div class="form-divider">
                <span>OR</span>
            </div>

            <button type="button" class="btn-supabase" onclick="registerWithSupabase()">
                🆕 Sign up with Supabase
            </button>

            <div class="form-footer">
                <p>Already have an account? <a href="#" onclick="toggleForms(event)">Login here</a></p>
            </div>
        </div>
    </div>

    <!-- Features Panel -->
    <div class="features-panel">
        <div class="feature-item">
            <div class="feature-icon">🎮</div>
            <h3>170+ Games</h3>
            <p>Slots, Fishing, Casino & More</p>
        </div>

        <div class="feature-item">
            <div class="feature-icon">💰</div>
            <h3>6 Payment Methods</h3>
            <p>JazzCash, EasyPaisa, UPI & More</p>
        </div>

        <div class="feature-item">
            <div class="feature-icon">🎁</div>
            <h3>Daily Bonuses</h3>
            <p>Free spins & Referral Rewards</p>
        </div>

        <div class="feature-item">
            <div class="feature-icon">🏆</div>
            <h3>VIP Program</h3>
            <p>Exclusive Perks & Benefits</p>
        </div>
    </div>
</div>

<style>
    .auth-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: calc(100vh - 100px);
        gap: 2rem;
        padding: 2rem;
    }

    .auth-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .auth-form {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
        border: 2px solid #ffd700;
        border-radius: 20px;
        padding: 3rem;
        max-width: 500px;
        width: 100%;
        box-shadow: 0 20px 60px rgba(255, 215, 0, 0.2);
        animation: slideIn 0.5s ease;
    }

    .auth-form.hidden {
        display: none;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .form-header h1 {
        color: #ffd700;
        font-size: 2.2em;
        margin-bottom: 0.5rem;
        text-shadow: 0 0 20px rgba(255, 215, 0, 0.5);
    }

    .form-header p {
        color: #b0b0b0;
        font-size: 1.1em;
    }

    .alert {
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        animation: shake 0.3s ease;
    }

    .alert-error {
        background: rgba(244, 67, 54, 0.1);
        border: 2px solid #f44336;
        color: #f44336;
    }

    .alert p {
        margin: 0.5rem 0;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        color: #ffd700;
        font-weight: 600;
        margin-bottom: 0.7rem;
        font-size: 0.95em;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="tel"],
    .form-group input[type="password"] {
        width: 100%;
        padding: 0.9rem 1rem;
        background: rgba(255, 215, 0, 0.05);
        border: 2px solid #ffd700;
        border-radius: 10px;
        color: #fff;
        font-size: 0.95em;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-group input::placeholder {
        color: #666;
    }

    .form-group input:focus {
        outline: none;
        background: rgba(255, 215, 0, 0.1);
        box-shadow: 0 0 20px rgba(255, 215, 0, 0.4);
        border-color: #ffed4e;
    }

    .form-group.referral input {
        border-color: #4caf50;
    }

    .form-group.referral input:focus {
        box-shadow: 0 0 20px rgba(76, 175, 80, 0.4);
    }

    .terms {
        display: flex;
        align-items: flex-start;
        gap: 0.8rem;
        margin-bottom: 1.5rem;
        color: #b0b0b0;
    }

    .terms input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-top: 2px;
        cursor: pointer;
        accent-color: #ffd700;
    }

    .terms label {
        margin: 0;
        cursor: pointer;
        line-height: 1.5;
    }

    .terms a {
        color: #ffd700;
        text-decoration: none;
    }

    .terms a:hover {
        text-decoration: underline;
    }

    .btn-submit {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
        color: #000;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1.05em;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(255, 215, 0, 0.4);
    }

    .btn-submit:active {
        transform: translateY(-1px);
    }

    .form-divider {
        text-align: center;
        color: #666;
        margin: 1.5rem 0;
        position: relative;
        font-size: 0.9em;
    }

    .form-divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #333;
    }

    .form-divider span {
        background: linear-gradient(135deg, #0f3460, #16213e);
        padding: 0 1rem;
        position: relative;
    }

    .btn-supabase {
        width: 100%;
        padding: 0.9rem;
        background: rgba(255, 215, 0, 0.08);
        color: #ffd700;
        border: 2px solid #ffd700;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.95em;
    }

    .btn-supabase:hover {
        background: rgba(255, 215, 0, 0.15);
        box-shadow: 0 5px 15px rgba(255, 215, 0, 0.2);
    }

    .form-footer {
        text-align: center;
        margin-top: 1.5rem;
        color: #b0b0b0;
        font-size: 0.9em;
    }

    .form-footer p {
        margin: 0.5rem 0;
    }

    .form-footer a {
        color: #ffd700;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .form-footer a:hover {
        color: #ffed4e;
        text-decoration: underline;
    }

    .features-panel {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        align-content: center;
    }

    .feature-item {
        background: linear-gradient(135deg, rgba(255, 215, 0, 0.05) 0%, rgba(255, 215, 0, 0.02) 100%);
        border: 2px solid #ffd700;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .feature-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(255, 215, 0, 0.2);
    }

    .feature-icon {
        font-size: 2.5em;
        margin-bottom: 1rem;
    }

    .feature-item h3 {
        color: #ffd700;
        margin: 0.5rem 0;
        font-size: 1.2em;
    }

    .feature-item p {
        color: #b0b0b0;
        margin: 0;
        font-size: 0.9em;
    }

    @media (max-width: 1024px) {
        .auth-container {
            grid-template-columns: 1fr;
        }

        .features-panel {
            grid-template-columns: repeat(2, 1fr);
            margin-top: 2rem;
        }
    }

    @media (max-width: 768px) {
        .auth-container {
            padding: 1rem;
            gap: 1.5rem;
        }

        .auth-form {
            padding: 2rem;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .features-panel {
            grid-template-columns: 1fr;
        }

        .feature-item {
            padding: 1rem;
        }

        .form-header h1 {
            font-size: 1.8em;
        }
    }
</style>

<script>
    function toggleForms(e) {
        e.preventDefault();
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        
        loginForm.classList.toggle('hidden');
        registerForm.classList.toggle('hidden');
    }

    function loginWithSupabase() {
        // Redirect to Supabase OAuth
        window.location.href = '/auth/supabase/login';
    }

    function registerWithSupabase() {
        // Redirect to Supabase OAuth
        window.location.href = '/auth/supabase/register';
    }

    // Add enter key support
    document.addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            const form = event.target.closest('form');
            if (form) {
                form.submit();
            }
        }
    });
</script>
@endsection
