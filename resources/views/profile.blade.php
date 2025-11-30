<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Account</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .app-header { padding: 1.5rem 0; background-color: #ffffff; border-bottom: 1px solid #e9ecef; }
        .status-in_progress { background-color: #0dcaf0; color: #ffffff; }
        .status-completed { background-color: #198754; color: #ffffff; } 
        .status-badge {
            display: inline-block;
            padding: .35em .65em;
            font-size: .75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
        }
    </style>
</head>
<body>
    <div class="app-header mb-4 shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 fw-bold mb-0 text-primary">My Account</h1>
                <p class="mb-0 text-muted small">Manage your profile and track your maintenance tickets.</p>
            </div>
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <!-- Link to the New Request Form -->
                <a href="{{ route('requests.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Submit New Request
                </a>
            </div>
        </div>
    </div>
    
    <div class="container">
        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- LEFT COLUMN: Profile Management -->
            <div class="col-lg-5 mb-4">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white fw-bold">Update Account Information</div>
                    <div class="card-body">
                        
                        <!-- NAME AND EMAIL FORM -->
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PATCH')
                            
                            <h5 class="fw-bold mb-3 mt-1">Personal Details</h5>

                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <button type="submit" class="btn btn-primary fw-bold">Save Details</button>
                        </form>
                    </div>
                </div>

                <!-- PASSWORD MANAGEMENT -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-secondary text-white fw-bold">Update Password</div>
                    <div class="card-body">
                        
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PATCH')
                            
                            <p class="text-muted small">Only fill these fields if you wish to change your password.</p>

                            <!-- Current Password -->
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                                @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <!-- New Password -->
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password">
                                @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            
                            <!-- Confirm New Password -->
                            <div class="mb-4">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                            </div>

                            <button type="submit" class="btn btn-secondary fw-bold">Change Password</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: My Requests Table -->
            <div class="col-lg-7 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-info text-white fw-bold">My Maintenance Tickets ({{ $userRequests->count() }} Total)</div>
                    <div class="card-body p-0 table-responsive">
                        @if($userRequests->isEmpty())
                            <div class="alert alert-warning m-4">You have not submitted any maintenance requests yet.</div>
                        @else
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Priority</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($userRequests as $request)
                                        <tr>
                                            <td>{{ $request->id }}</td>
                                            <td><div class="fw-bold">{{ $request->title }}</div></td>
                                            <td>
                                                <span class="badge bg-{{ $request->priority == 'urgent' ? 'danger' : 'info' }} text-uppercase">{{ $request->priority }}</span>
                                            </td>
                                            <td>
                                                <span class="status-badge text-uppercase status-{{ $request->status }}">
                                                    {{ $request->status == 'pending' ? 'NEW' : str_replace('_', ' ', $request->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <!-- Client Action Button (Mark as Done) -->
                                                @if ($request->status == 'in_progress')
                                                    <form method="POST" action="{{ route('requests.client_complete', $request) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" 
                                                            onclick="return confirm('Mark as complete?')">
                                                            Mark as Done
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted small">Awaiting service</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>