<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Title using Laravel config helper -->
    <title>{{ config('app.name', 'Maintenance App') }} - Login</title>
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    
    <style>
        /* Apply the Inter font and a light background */
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Styling for the main login card */
        .login-card {
            max-width: 500px;
            width: 90%;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            padding: 2rem;
        }

        .header-icon {
            font-size: 3rem;
            color: #0d6efd; /* Bootstrap primary color */
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>

    <div class="card login-card">
        <div class="card-body text-center">
            <!-- Icon placeholder (using a common repair emoji/symbol) -->
            <div class="header-icon">🛠️</div>
            
            <h1 class="card-title fw-bolder mb-2" style="font-size: 2.25rem;">
                Request Maintenance
            </h1>
            <p class="text-muted mb-4">
                Sign in to quickly submit and track your repair and facility requests.
            </p>

            <!-- Form setup for Laravel authentication -->
            <form method="POST" action=>
                @csrf <!-- Laravel CSRF Protection -->

                <!-- Email Field -->
                <div class="mb-3">
                    <label for="emailInput" class="form-label visually-hidden">Email address</label>
                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                           id="emailInput" name="email" placeholder="Email address" required autocomplete="email" autofocus>
                    
                    @error('email')
                        <div class="invalid-feedback text-start">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Password Field -->
                <div class="mb-4">
                    <label for="passwordInput" class="form-label visually-hidden">Password</label>
                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                           id="passwordInput" name="password" placeholder="Password" required autocomplete="current-password">
                    
                    @error('password')
                        <div class="invalid-feedback text-start">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Remember Me Checkbox (Optional, often included) -->
                <div class="form-check text-start mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Remember Me
                    </label>
                </div>


                <!-- Login Button -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold">
                        Log In
                    </button>
                </div>

                <hr class="my-4">

                <!-- Forgot Password / Register Links -->
                <div class="d-flex justify-content-between">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-decoration-none text-secondary small">Forgot Password?</a>
                    @endif

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-decoration-none text-secondary small">Register New Account</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle CDN (required for components like dropdowns, modals, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>