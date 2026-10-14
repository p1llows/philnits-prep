<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInitialAssessmentCompleted
{
    /**
     * Handle an incoming request.
     * Enforces the Pre-Test Gate: Requires learners to complete their pre-test / assessment
     * before accessing custom topic study, practice sessions, or study planning tools.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If not authenticated or is admin, bypass gate
        if (!$user || $user->isAdmin()) {
            return $next($request);
        }

        // If user hasn't completed their pre-test, redirect them to pre-test onboarding
        if (!$user->hasCompletedInitialAssessment()) {
            // Allow assessment routes, logout, profile, and dashboard itself
            if ($request->routeIs('assessment.*', 'assessments.*', 'dashboard', 'logout', 'profile.*')) {
                return $next($request);
            }

            return redirect()->route('dashboard')
                ->with('pretest_required', true)
                ->with('warning', 'Please take your initial pre-test assessment first to establish your readiness baseline and unlock study topics.');
        }

        return $next($request);
    }
}
