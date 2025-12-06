<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceRequestController extends Controller
{
    // =======================
    // WEB DASHBOARD METHODS
    // =======================

    /**
     * Admin/Technician Dashboard - view all requests
     */
    public function index()
    {
        $user = Auth::user();

        $allRequests = MaintenanceRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_requests' => $allRequests->count(),
            'today_requests' => MaintenanceRequest::whereDate('created_at', today())->count(),
            'pending_requests' => $allRequests->whereIn('status', ['pending', 'in_progress'])->count(),
            'done_requests' => $allRequests->where('status', 'completed')->count(),
        ];

        return view('dashboard', [
            'requests' => $allRequests,
            'stats' => $stats,
            'user' => $user,
        ]);
    }

    /**
     * Client Dashboard - view only their requests
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
     * Show the form to create a request (web)
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a new request (web)
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

    /**
     * Update status (web)
     */
    public function updateStatus(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $validated = $request->validate([
            'new_status' => 'required|in:pending,in_progress,completed,rejected',
        ]);

        $maintenanceRequest->update(['status' => $validated['new_status']]);

        return redirect()->route('dashboard')
            ->with('success', "Request #{$maintenanceRequest->id} status updated successfully.");
    }

    /**
     * Client marks as complete (web)
     */
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

    /**
     * Delete request (web)
     */
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

    // =======================
    // API METHODS FOR FLUTTER
    // =======================

    /**
     * Fetch requests for the authenticated user (Flutter)
     */
    public function apiIndex(Request $request)
    {
        $user = $request->user();

        $requests = $user->maintenanceRequests()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['requests' => $requests]);
    }

    /**
     * Submit new request via API (Flutter)
     */
    public function apiStore(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'priority' => 'required|string|in:low,medium,high,urgent',
        ]);

        $requestModel = $user->maintenanceRequests()->create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'location' => $validated['location'],
            'priority' => $validated['priority'],
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'request' => $requestModel
        ], 201);
    }

    /**
     * Mark a request as complete via API (Flutter)
     */
    public function completeRequest($id, Request $request)
    {
        $user = $request->user();

        $requestModel = $user->maintenanceRequests()->where('id', $id)->firstOrFail();

        $requestModel->status = 'completed';
        $requestModel->save();

        return response()->json([
            'success' => true,
            'request' => $requestModel
        ]);
    }
}
