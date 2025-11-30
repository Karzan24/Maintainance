<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Maintenance Dashboard</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8f9fa; }
        .app-header { padding: 1.5rem 0; background-color: #ffffff; border-bottom: 1px solid #e9ecef; }
        .stat-card { border-left: 5px solid; }
        
        /* Custom Status Colors */
        .status-pending { background-color: #ffc107; color: #343a40; } /* Yellow for New/Pending */
        .status-in_progress { background-color: #0dcaf0; color: #ffffff; } /* Cyan for Accepted */
        .status-completed { background-color: #198754; color: #ffffff; } /* Green for Done */
        .status-rejected { background-color: #dc3545; color: #ffffff; } /* Red for Rejected */
        
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
                <h1 class="h3 fw-bold mb-0 text-primary">Admin Maintenance Dashboard</h1>
                <p class="mb-0 text-muted small">Welcome, {{ Auth::user()->name }}.</p>
            </div>
            <div>
                <a href="{{ route('requests.create') }}" class="btn btn-success me-2">
                    <i class="bi bi-plus-circle"></i> New Request
                </a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="container-fluid">
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


        <!-- 1. STATISTICS CARDS -->
        <div class="row mb-5">
            <!-- Total Requests -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-sm h-100 stat-card" style="border-left-color: #6c757d;">
                    <div class="card-body">
                        <h6 class="text-uppercase text-secondary fw-bold">Total Requests</h6>
                        <h2 class="display-4 fw-bolder">{{ $stats['total_requests'] }}</h2>
                    </div>
                </div>
            </div>
            
            <!-- Requests Received Today -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-sm h-100 stat-card" style="border-left-color: #0d6efd;">
                    <div class="card-body">
                        <h6 class="text-uppercase text-primary fw-bold">Requests Today</h6>
                        <h2 class="display-4 fw-bolder">{{ $stats['today_requests'] }}</h2>
                    </div>
                </div>
            </div>

            <!-- Requests Pending / In Progress -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-sm h-100 stat-card" style="border-left-color: #ffc107;">
                    <div class="card-body">
                        <h6 class="text-uppercase text-warning fw-bold">Pending/In Progress</h6>
                        <h2 class="display-4 fw-bolder">{{ $stats['pending_requests'] }}</h2>
                    </div>
                </div>
            </div>

            <!-- Requests Done -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card shadow-sm h-100 stat-card" style="border-left-color: #198754;">
                    <div class="card-body">
                        <h6 class="text-uppercase text-success fw-bold">Completed Requests</h6>
                        <h2 class="display-4 fw-bolder">{{ $stats['done_requests'] }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. ALL REQUESTS TABLE -->
      <!-- 2. ALL REQUESTS TABLE -->
<h4 class="mb-3 fw-bold text-secondary">All Maintenance Requests</h4>

<div class="card shadow-sm border-0">
    <div class="card-body p-0 table-responsive">
        @if($requests->isEmpty())
            <div class="alert alert-info m-4">No maintenance requests have been submitted yet.</div>
        @else
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Location</th>
                        <th scope="col">Submitted By</th>
                        <th scope="col">Priority</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $maintenanceRequest)
                        <tr>
                            <td>{{ $maintenanceRequest->id }}</td>
                            <td>
                                <div class="fw-bold">{{ $maintenanceRequest->title }}</div>
                                <small class="text-muted text-truncate d-block" style="max-width: 250px;">
                                    {{ Str::limit($maintenanceRequest->description, 50) }}
                                </small>
                            </td>
                            <td>{{ $maintenanceRequest->location ?? 'N/A' }}</td>
                            <td>{{ $maintenanceRequest->user->name }} ({{ $maintenanceRequest->user->email }})</td>
                            <td>
                                <span class="badge bg-{{ 
                                    $maintenanceRequest->priority == 'urgent' ? 'danger' : (
                                    $maintenanceRequest->priority == 'high' ? 'warning' : 'info'
                                ) }} text-uppercase">
                                    {{ $maintenanceRequest->priority }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge text-uppercase status-{{ $maintenanceRequest->status }}">
                                    {{ $maintenanceRequest->status == 'pending' ? 'NEW' : str_replace('_', ' ', $maintenanceRequest->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <!-- ACCEPT / IN-PROGRESS BUTTON -->
                                    @if ($maintenanceRequest->status == 'pending')
                                        <form method="POST" action="{{ route('requests.update_status', $maintenanceRequest) }}" class="me-2">
                                            @csrf
                                            <input type="hidden" name="new_status" value="in_progress">
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-check-lg"></i> Accept
                                            </button>
                                        </form>
                                    
                                    <!-- COMPLETE BUTTON (Admin marks it done) -->
                                    @elseif ($maintenanceRequest->status == 'in_progress')
                                        <form method="POST" action="{{ route('requests.update_status', $maintenanceRequest) }}" class="me-2">
                                            @csrf
                                            <input type="hidden" name="new_status" value="completed">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-gear-fill"></i> Complete
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <!-- DELETE BUTTON -->
                                    <form method="POST" action="{{ route('requests.destroy.explicit', ['maintenanceRequest' => $maintenanceRequest->id]) }}" 
                                          class="d-inline" 
                                          onsubmit="return confirm('WARNING: Are you sure you want to delete this request permanently?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>