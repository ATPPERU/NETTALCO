<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    $validator = Validator::make($credentials, [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Credenciales incorrectas.',
            'errors' => $validator->errors()
        ]);
    }

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();

        // Delegamos la lógica de redirección a authenticated()
        return $this->authenticated($request, $user);
    }

    return response()->json([
        'success' => false,
        'message' => 'Las credenciales son incorrectas.',
    ], 401);
}


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

   protected function authenticated(Request $request, $user)
{
    if ($user->two_factor_secret) {
        Auth::logout();
        session(['2fa:user:id' => $user->id]);

        return response()->json([
            'success' => true,
            'two_factor_required' => true,
            'redirect_url' => route('2fa.verify.form'),
        ]);
    }

    return response()->json([
        'success' => true,
        'welcome_message' => '¡Bienvenido ' . $user->usuario . '!',
        'redirect_url' => route('dashboard'),
    ]);
}


}
