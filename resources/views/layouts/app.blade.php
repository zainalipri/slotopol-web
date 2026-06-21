<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Slotopol Goldwin')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f0f1e 0%, #1a1a2e 50%, #16213e 100%);
            color: #fff;
            min-height: 100vh;
        }

        .navbar {
            background: rgba(15, 15, 30, 0.95);
            border-bottom: 2px solid #ffd700;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 1.8em;
            font-weight: bold;
            color: #ffd700;
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.5);
        }

        .nav-center {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-center a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }

        .nav-center a:hover {
            color: #ffd700;
            background: rgba(255, 215, 0, 0.1);
        }

        .nav-right {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .balance {
            font-size: 1.2em;
            color: #ffd700;
            font-weight: bold;
        }

        .logout-btn {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: #000;
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        footer {
            text-align: center;
            padding: 30px;
            color: #666;
            border-top: 1px solid #333;
            margin-top: 60px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .footer-links a {
            color: #ffd700;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
            }

            .nav-center {
                gap: 1rem;
            }

            .container {
                padding: 1rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar">
        <div class="logo">🎰 Slotopol Goldwin</div>
        
        <ul class="nav-center">
            <li><a href="/">Home</a></li>
            <li><a href="/games">Games</a></li>
            <li><a href="/lottery">Lottery</a></li>
            @auth
            <li><a href="/referral">Referral</a></li>
            @endauth
        </ul>

        <div class="nav-right">
            @auth
            <div class="user-menu">
                <div class="balance">
                    💰 ₹{{ number_format(Auth::user()->balance, 2) }}
                </div>
                <a href="/profile" style="color: #fff; text-decoration: none;">
                    {{ Auth::user()->name }}
                </a>
                <form action="/logout" method="POST" style="margin: 0;">
                    @csrf
                    <button class="logout-btn" type="submit">Logout</button>
                </form>
            </div>
            @else
            <div style="display: flex; gap: 1rem;">
                <a href="/login" style="color: #fff; text-decoration: none; padding: 0.5rem 1rem; border: 2px solid #ffd700; border-radius: 5px;">Login</a>
                <a href="/register" style="background: #ffd700; color: #000; text-decoration: none; padding: 0.5rem 1rem; border-radius: 5px; font-weight: bold;">Register</a>
            </div>
            @endauth
        </div>
    </nav>

    <div class="container">
        @if($errors->any())
        <div style="background: rgba(244, 67, 54, 0.1); border: 2px solid #f44336; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
            @foreach($errors->all() as $error)
            <p style="color: #f44336; margin: 0.5rem 0;">{{ $error }}</p>
            @endforeach
        </div>
        @endif

        @if(session('success'))
        <div style="background: rgba(76, 175, 80, 0.1); border: 2px solid #4caf50; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
            <p style="color: #4caf50; margin: 0;">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div style="background: rgba(244, 67, 54, 0.1); border: 2px solid #f44336; padding: 1rem; border-radius: 5px; margin-bottom: 1rem;">
            <p style="color: #f44336; margin: 0;">{{ session('error') }}</p>
        </div>
        @endif

        @yield('content')
    </div>

    <footer>
        <div class="footer-links">
            <a href="/about">About Us</a>
            <a href="/terms">Terms & Conditions</a>
            <a href="/privacy">Privacy Policy</a>
            <a href="/contact">Contact</a>
        </div>
        <p>&copy; 2026 Slotopol Goldwin. All rights reserved.</p>
        <p>Licensed & Secured | Powered by Laravel</p>
    </footer>
</body>
</html>
