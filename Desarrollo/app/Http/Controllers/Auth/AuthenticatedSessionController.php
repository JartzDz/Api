<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $request->validate([
            'email' => ['required', 'email'],
            'contrasenia' => ['required'],
        ]);

        // Buscar al usuario por su correo electrónico
        $user = User::where('email', $request->email)->first();

        // Verificar si el usuario existe y la contraseña es correcta
        if ($user) {
            if (Hash::check($request->contrasenia, $user->contrasenia)) {
                Auth::login($user);
                return response()->json(['message' => 'Ingreso de Usuario Exitoso'], 200);
            } else {
                return response()->json(['message' => 'Credenciales Incorrectas'], 401);
            }
        } else {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json(['message' => 'Salida de Sesión Exitoso'], 200);
    }
}
