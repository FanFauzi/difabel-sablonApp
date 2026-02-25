<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogsController extends Controller
{
    public function index(Request $request)
    {
        // Get all activities for detailed log
        $query = ActivityLog::with('user');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by model type
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        $activityLogs = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get login activities (user login records)
        $loginActivities = ActivityLog::with('user')
            ->where('action', 'login')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get order creation activities (user membuat pesanan)
        $orderActivities = ActivityLog::with('user')
            ->where('action', 'created')
            ->where('model_type', 'App\Models\Order')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get admin data change activities (admin mengubah data)
        $adminActivities = ActivityLog::with('user')
            ->whereIn('action', ['updated', 'deleted', 'created'])
            ->whereHas('user', function($query) {
                $query->where('role', 'admin');
            })
            ->where('model_type', '!=', 'App\Models\Order') // Exclude order creation by admin
            ->orderBy('created_at', 'desc')
            ->get();

        // Get unique users for filter dropdown
        $users = \App\Models\User::select('id', 'name')->get();

        // Get available actions for filter
        $actions = ActivityLog::select('action')->distinct()->pluck('action');

        // Get available model types for filter
        $modelTypes = ActivityLog::select('model_type')->distinct()->whereNotNull('model_type')->pluck('model_type');

        return view('admin.activity-logs.index', compact(
            'activityLogs',
            'loginActivities',
            'orderActivities',
            'adminActivities',
            'users',
            'actions',
            'modelTypes'
        ));
    }

    public function show($id)
    {
        $activityLog = ActivityLog::with('user')->findOrFail($id);

        return view('admin.activity-logs.show', compact('activityLog'));
    }

    public function destroy($id)
    {
        $activityLog = ActivityLog::findOrFail($id);
        $activityLog->delete();

        return redirect()->route('activity-logs.index')
                        ->with('success', 'Log aktivitas berhasil dihapus');
    }

    public function clear(Request $request)
    {
        $query = ActivityLog::query();

        // Apply same filters as index method
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        $count = $query->count();
        $query->delete();

        return redirect()->route('activity-logs.index')
                        ->with('success', "Berhasil menghapus {$count} log aktivitas");
    }
}
