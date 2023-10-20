<?php

namespace App\Http\Controllers\enfermeria;

use App\Http\Controllers\Controller;
use App\Models\HistorialMedico;
use App\Models\Imagen;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\RHDependiente;
use Illuminate\Support\Facades\Storage;

class DependienteController extends Controller
{
    public function index(){
        $data = RHDependiente::all();
        return response()->json($data, 200);
    }

    public function store(Request $request){
        // Log::error($request);
        try{
            $dependiente = RHDependiente::create([
                'empleado_id' => $request['historialMedico_id'],
                'nombre' => $request['nombre'],
                'sexo' => $request['sexo'],
                'fechaNacimiento' => $request['fechaNacimiento'],
                'telefono' => $request['prefijoInternacional'].$request['telefono'],
                'correo' => $request['email'],
                'parentesco' => $request['parentesco'],
                'estatus' => 'Vivo',
            ]);

            $pacientable_type = RHDependiente::class;

            $imagenBase64 = explode(";base64,",$request['imagen']);
            $imagenExplode = explode("image/", $imagenBase64[0]);
            $imagenFormato = $imagenExplode[1];
            $imagen = base64_decode($imagenBase64[1]);
            $imagenNombre = Str::random(12);
            $ruta = storage_path('app/private/fotografías/'.$imagenNombre.'.'.$imagenFormato);

            file_put_contents($ruta, $imagen);
           
            // Guardar la imagen
            $imagen = Imagen::create([
                'url' => $imagenNombre.'.'.$imagenFormato,
                'categoria' => 'fotografías',
                'imageable_id' => $dependiente->id,
                'imageable_type' => $pacientable_type
            ]);

            //Crear historial medico
            $historialMedico = HistorialMedico::create([
                'pacientable_id' => $dependiente->id,
                'pacientable_type' => $pacientable_type,
            ]);

            return response()->json([
                'message' => 'Dependiente guardado exitosamente',
            ]);

        }catch(\Exception $e){
            Log::error($e);
            return response()->json([
                 'error' => $e->getMessage()
                //'error' => $request,
            ], 500);
        }

    }

    public function update($id, Request $request){
        // Log::error($request);
        try{
            $dependiente = RHDependiente::find($id);

            $dependiente->update([
                'nombre' => $request['nombre'],
                'sexo' => $request['sexo'],
                'fechaNacimiento' => $request['fechaNacimiento'],
                'telefono' => $request['prefijoInternacional'].$request['telefono'],
                'correo' => $request['email'],
                'parentesco' => $request['parentesco'],
                'estatus' => 'Vivo',
            ]);

            if($request['imagen']){
                if($dependiente->image){
                    Storage::delete($dependiente->image->url);
                    $dependiente->image->delete();
                }

                $imagenBase64 = explode(";base64,",$request['imagen']);
                $imagenExplode = explode("image/", $imagenBase64[0]);
                $imagenFormato = $imagenExplode[1];
                $imagen = base64_decode($imagenBase64[1]);
                $imagenNombre = Str::random(12);
                $ruta = storage_path('app/private/fotografías/'.$imagenNombre.'.'.$imagenFormato);

                file_put_contents($ruta, $imagen);
            
                // Guardar la imagen
                $imagen = Imagen::create([
                    'url' => $imagenNombre.'.'.$imagenFormato,
                    'categoria' => 'fotografías',
                    'imageable_id' => $dependiente->id,
                    'imageable_type' => RHDependiente::class
                ]);
            }

            return response()->json([
                'message' => 'Dependiente editado exitosamente',
            ]);

        }catch(\Exception $e){
            Log::error($e);
            return response()->json([
                 'error' => $e->getMessage()
                //'error' => $request,
            ], 500);
        }

    }
}