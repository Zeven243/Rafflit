<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

class AuditSystemsController extends Controller
{
    public function index(Request $request)
    {
        $audits = Activity::with('causer')->latest()->paginate(20);

        return Inertia::render('AuditSystems/index', [
            'audits' => $audits,
        ]);
    }
}
