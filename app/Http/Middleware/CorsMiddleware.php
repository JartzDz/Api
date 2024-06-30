<?php

namespace App\Http\Middleware;

use Closure;

class CORSMiddleware
{
    public function handle($request, Closure $next)
    {
        // Headers para permitir CORS
        return $next($request)
        ->header('Access-Control-Allow-Origin', 'http://localhost:3000') // Ajusta según tu configuración de frontend
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS') // Métodos permitidos
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization'); // Headers permitidosrs permitidos

        // Si es una solicitud OPTIONS, retornar la respuesta con los headers
        if ($request->isMethod('OPTIONS')) {
            return $response;
        }

        return $response;
    }
}
