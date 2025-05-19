<?php

namespace App\Http\Controllers;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use Carbon\Carbon; 
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function enable2FA(Request $request)
    {
        $user = auth()->user();

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        $user->two_factor_secret = $secret;
        $user->save();

        $companyName = config('app.name');
        $qrUrl = $google2fa->getQRCodeUrl(
            $companyName,
            $user->email,
            $secret
        );

        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);
        $qrCodeSvg = $writer->writeString($qrUrl);

        return response()->json([
            'secret' => $secret,
            'qr_code_svg' => $qrCodeSvg,
            'message' => 'Clave 2FA generada correctamente.',
        ]);
    }

    public function showConfirmForm()
{
    return view('2fa.confirm');
}

public function confirm2FA(Request $request)
{
    $request->validate([
        'code' => 'required',
    ]);

    $user = auth()->user();
    $google2fa = new Google2FA();

    $valid = $google2fa->verifyKey($user->two_factor_secret, $request->input('code'));

    if ($valid) {
        $user->two_factor_confirmed_at = Carbon::now();
        $user->save();

        return redirect('/dashboard')->with('success', '2FA activado correctamente.');
    }

    return back()->withErrors(['code' => 'Código 2FA incorrecto. Intenta de nuevo.']);
}

    public function show2faForm()
    {
        return view('2fa.validacion');
    }

   public function verify2fa(Request $request)
{
    $request->validate([
        'one_time_password' => 'required',
    ]);

    $userId = session('2fa:user:id');

    if (!$userId) {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Por favor inicia sesión primero.'], 401);
        }
        return redirect()->route('login')->withErrors('Por favor inicia sesión primero.');
    }

    $user = Usuario::find($userId);
    if (!$user) {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Usuario no encontrado.'], 404);
        }
        return redirect()->route('login')->withErrors('Usuario no encontrado.');
    }

    $google2fa = new Google2FA();
    $valid = $google2fa->verifyKey($user->two_factor_secret, $request->one_time_password);

    if ($valid) {
        Auth::login($user);
        session()->forget('2fa:user:id');

        if ($request->ajax()) {
            return response()->json(['success' => true, 'redirect_url' => route('dashboard')]);
        }
        return redirect()->intended('/dashboard');
    } else {
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'Código 2FA incorrecto.'], 422);
        }
        return back()->withErrors(['one_time_password' => 'Código 2FA incorrecto.']);
    }
}

public function showEnableForm()
{
    return view('2fa.enable');
}


}
