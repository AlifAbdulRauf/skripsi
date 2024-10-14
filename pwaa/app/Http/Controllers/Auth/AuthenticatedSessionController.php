<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Authentication failed.'], 401);
        }

        $role = $user->role;
        $redirectUrl = '/admin'; // Default redirect

        if ($role === 'owner' || $role === 'Owner') {
            return redirect('/owner');
        } elseif ($role === 'dokter' || $role === 'Dokter') {
            return redirect('/dokter');
        } elseif ($role === 'pasien' || $role === 'Pasien') {
            return redirect('/home');
        }

        return redirect("/admin");
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
