<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>{{ config('app.name', 'Maintenance App') }} - Register</title>
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            max-width: 500px;
            width: 90%;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            padding: 2rem;
        }
        .header-icon {
            font-size: 3rem;
            color: #0d6efd;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>

    <div class="card register-card">
        <div class="card-body text-center">
            <div class="header-icon">📝</div>
            
            <h1 class="card-title fw-bolder mb-2" style="font-size: 2.25rem;">
                Create Your Account
            </h1>
            <p class="text-muted mb-4">
                Enter your details to register and access the maintenance portal.
            </p>

            <!-- Form targets the store method in the RegisteredUserController -->
            <form method="POST" action="{{ route('register') }}">
                @csrf 

                <!-- Name Field -->
                <div class="mb-3">
                    <label for="nameInput" class="form-label visually-hidden">Full Name</label>
                    <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                           id="nameInput" name="name" value="{{ old('name') }}" placeholder="Full Name" required autofocus>
                    
                    @error('name')
                        <div class="invalid-feedback text-start">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Field -->
                <div class="mb-3">
                    <label for="emailInput" class="form-label visually-hidden">Email address</label>
                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                           id="emailInput" name="email" value="{{ old('email') }}" placeholder="Email address" required>
                    
                    @error('email')
                        <div class="invalid-feedback text-start">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Password Field -->
                <div class="mb-3">
                    <label for="passwordInput" class="form-label visually-hidden">Password</label>
                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                           id="passwordInput" name="password" placeholder="Password" required autocomplete="new-password">
                    
                    @error('password')
                        <div class="invalid-feedback text-start">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Confirm Password Field -->
                <div class="mb-4">
                    <label for="passwordConfirmInput" class="form-label visually-hidden">Confirm Password</label>
                    <input type="password" class="form-control form-control-lg" 
                           id="passwordConfirmInput" name="password_confirmation" placeholder="Confirm Password" required>
                </div>

                <!-- Register Button -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold">
                        Register
                    </button>
                </div>

                <hr class="my-4">

                <!-- Link to Login -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-decoration-none text-secondary small">
                        Already have an account? Log In
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>