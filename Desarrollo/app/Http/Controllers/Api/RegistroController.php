<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Negocio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; 
class RegistroController extends Controller
{
    public function registrarCliente(Request $request)
    {
        // Validación de datos para el registro de cliente
        $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email|unique:usuario,email',
            'telefono' => 'required',
            'contrasenia' => 'required|min:6',
            // otros campos necesarios para el cliente
        ]);

        try {
            // Iniciar una transacción
            DB::beginTransaction();

            // Crear registro en la tabla de usuarios asociando el cliente
            $usuario = User::create([
                'email' => $request->email,
                'contrasenia' => Hash::make($request->contrasenia),
                'telefono' => $request->telefono,
                'tipo_usuario' => 'Cliente', // opcional: define el tipo de usuario
            ]);

            // Verificar que el usuario fue creado correctamente
            if (!$usuario) {
                throw new \Exception('Error al crear el usuario');
            }

            $idUsuario = $usuario->id_usuario; 

            // Crear registro en la tabla de clientes
            $cliente = Cliente::create([
                'id_cliente' => $idUsuario, // asocia el id_cliente
                'nombre' => $request->nombre,
                'foto_perfil' => $request->has('foto_perfil') ? $request->foto_perfil : null,
                // otros campos para el cliente
            ]);

            // Verificar que el cliente fue creado correctamente
            if (!$cliente) {
                throw new \Exception('Error al crear el cliente');
            }

            // Confirmar la transacción
            DB::commit();

            return response()->json(['message' => 'Registro de cliente exitoso'], 201);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            return response()->json(['message' => 'Error al registrar cliente', 'error' => $e->getMessage()], 500);
        }
    }

    public function registrarNegocio(Request $request)
    {
        $request->validate([
            'nombre_negocio' => 'required|string',
            'email' => 'required|email|unique:usuario,email',
            'telefono' => 'required',
            'contrasenia' => 'required|min:6',
            'posicion_x' => 'required|numeric',
            'posicion_y' => 'required|numeric',
        ]);

        try {
            // Iniciar una transacción
            DB::beginTransaction();

            // Crear registro en la tabla de usuarios asociando el cliente
            $usuario = User::create([
                'email' => $request->email,
                'contrasenia' => Hash::make($request->contrasenia),
                'telefono' => $request->telefono,
                'tipo_usuario' => 'Negocio', // opcional: define el tipo de usuario
            ]);

            // Verificar que el usuario fue creado correctamente
            if (!$usuario) {
                throw new \Exception('Error al crear el usuario');
            }

            // Obtener el ID del usuario recién creado
            $idUsuario = $usuario->id_usuario;

            // Crear registro en la tabla de negocios
            $negocio = Negocio::create([
                'id_negocio' => $idUsuario,
                'id_categoria' => $request->has('id_categoria') ? $request->id_categoria : null,
                'nombre_negocio' => $request->nombre_negocio,
                'descripcion' => $request->has('descripcion') ? $request->descripcion : null,
                'horario_apertura' => $request->has('horario_apertura') ? $request->horario_apertura : null,
                'horario_cierre' => $request->has('horario_cierre') ? $request->horario_cierre : null,
                'horario_oferta' => $request->has('horario_oferta') ? $request->horario_oferta : null,
                'logotipo' => $request->has('logotipo') ? $request->logotipo : null,
                'imagen_referencial' => $request->has('imagen_referencial') ? $request->imagen_referencial : null,
                'posicion_x' => $request->posicion_x,
                'posicion_y' => $request->posicion_y,
            ]);

            // Verificar que el negocio fue creado correctamente
            if (!$negocio) {
                throw new \Exception('Error al crear el negocio');
            }

            // Confirmar la transacción
            DB::commit();

            return response()->json(['message' => 'Negocio registrado correctamente', 'data' => $negocio], 201);
        } catch (\Throwable $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            return response()->json(['message' => 'Error al registrar negocio', 'error' => $e->getMessage()], 500);
        }
    }
}
