<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageVisitResource;
use App\Models\PageVisit;
use Illuminate\Http\Request;

class PageVisitController extends Controller
{
    public function index(Request $request)
    {
        $query = PageVisit::latest();

        if ($request->filled('path')) {
            $query->where('path', 'like', '%' . $request->path . '%');
        }

        if ($request->filled('device_type')) {
            $query->where('device_type', $request->device_type);
        }

        return PageVisitResource::collection($query->paginate(50));
    }

    public function stats()
    {
        $total = PageVisit::count();
        $today = PageVisit::whereDate('created_at', today())->count();
        $byDevice = PageVisit::selectRaw('device_type, COUNT(*) as count')
            ->groupBy('device_type')
            ->pluck('count', 'device_type');
        $topPages = PageVisit::selectRaw('path, COUNT(*) as count')
            ->groupBy('path')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return response()->json(compact('total', 'today', 'byDevice', 'topPages'));
    }
}
