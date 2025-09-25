<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tarea;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TareaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tareas = Tarea::with('usuario:id,nombre')
            ->select('id', 'titulo', 'descripcion', 'estado', 'usuario_id', 'fecha_vencimiento')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $tareas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|max:150',
            'descripcion' => 'nullable',
            'estado' => 'required|in:pendiente,en_progreso,completada',
            'usuario_id' => 'required|exists:usuarios,id',
            'fecha_vencimiento' => 'required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $tarea = Tarea::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Tarea creada exitosamente',
            'data' => $tarea
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tarea = Tarea::with('usuario:id,nombre')->find($id);

        if (!$tarea) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tarea no encontrada'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $tarea
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tarea = Tarea::find($id);

        if (!$tarea) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tarea no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'titulo' => 'sometimes|required|max:150',
            'descripcion' => 'nullable',
            'estado' => 'sometimes|required|in:pendiente,en_progreso,completada',
            'usuario_id' => 'sometimes|required|exists:usuarios,id',
            'fecha_vencimiento' => 'sometimes|required|date'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $tarea->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Tarea actualizada exitosamente',
            'data' => $tarea
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tarea = Tarea::find($id);

        if (!$tarea) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tarea no encontrada'
            ], 404);
        }

        $tarea->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Tarea eliminada exitosamente'
        ]);
    }
}