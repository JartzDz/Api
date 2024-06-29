<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Supabase\SupabaseClient;

class UsersController extends Controller
{
    protected $supabase;

    public function __construct()
    {
        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_KEY');

        $this->supabase = new SupabaseClient($supabaseUrl, $supabaseKey);
    }

    public function index()
    {
        // Consulta todos los usuarios desde la tabla 'usuario'
        $response = $this->supabase
            ->table('usuario')
            ->select('*')
            ->execute();

        if ($response->error) {
            // Manejar el error si ocurre
            return response()->json(['error' => $response->error->message], 500);
        }

        // Obtén los datos de la respuesta
        $usuarios = $response->data;

        return response()->json($usuarios);
    }
}
