<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User; 

class MaintenanceRequestController extends Controller
{
    /**
     * Display a listing of ALL requests for the Admin/Technician Dashboard.
     */
    public function index()
    {
        // Fetch the current user object for the view
        $user = Auth::user(); 
        
        // 1. Fetch all requests, eager-loading the associated user data
        $allRequests = MaintenanceRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // 2. Calculate Dashboard Statistics
        $stats = [
            'total_requests' => $allRequests->count(),
            'today_requests' => MaintenanceRequest::whereDate('created_at', today())->count(),
            'pending_requests' => $allRequests->whereIn('status', ['pending', 'in_progress'])->count(), 
            'done_requests' => $allRequests->where('status', 'completed')->count(),
        ];
        
        // --- REMOVED: $userRequests definition unnecessary for Admin Dashboard ---

        // 3. Pass data to the dashboard view
        return view('dashboard', [
            'requests' => $allRequests,
            'stats' => $stats,
            'user' => $user, 
            // --- REMOVED: Passing $userRequests is unnecessary ---
        ]);
    }

    /**
     * Display a listing of requests submitted by the currently authenticated user (Client View).
     */
    public function clientIndex()
    {
        $userRequests = Auth::user()->maintenanceRequests()
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user_requests', [
            'requests' => $userRequests,
        ]);
    }

    /**
     * Show the form for creating a new maintenance request.
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        Auth::user()->maintenanceRequests()->create($validatedData);

        return redirect()->route('dashboard')
            ->with('success', 'Maintenance request submitted successfully!');
    }

    public function updateStatus(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $validated = $request->validate([
            'new_status' => 'required|in:pending,in_progress,completed,rejected',
        ]);

        $maintenanceRequest->update([
            'status' => $validated['new_status']
        ]);

        return redirect()->route('dashboard')
            ->with('success', "Request #{$maintenanceRequest->id} status updated successfully.");
    }

    public function clientComplete(MaintenanceRequest $maintenanceRequest)
    {
        if ($maintenanceRequest->user_id !== Auth::id()) {
            return redirect()->route('my_requests')->with('error', 'Unauthorized action.');
        }

        if ($maintenanceRequest->status !== 'in_progress') {
            return redirect()->route('my_requests')->with('error', 'Request must be In Progress to be marked complete.');
        }

        $maintenanceRequest->update(['status' => 'completed']);

        return redirect()->route('my_requests')->with('success', "Request #{$maintenanceRequest->id} marked as Completed!");
    }

    public function destroy($id)
    {
        $maintenanceRequest = MaintenanceRequest::find($id);

        if (!$maintenanceRequest) {
            return redirect()->route('dashboard')
                ->with('error', 'Maintenance request not found.');
        }

        $maintenanceRequest->delete();

        return redirect()->route('dashboard')
            ->with('success', "Request #{$maintenanceRequest->id} has been deleted.");
    }

     public function apiIndex(Request $request)
    {
        // Get the client identified by the Sanctum token
        $user = $request->user();

        // Fetch requests associated with this user
        $requests = $user->maintenanceRequests()
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Return the data as JSON
        return response()->json([
            'requests' => $requests
        ]);
    }
}