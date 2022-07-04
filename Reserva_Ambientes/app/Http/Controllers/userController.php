<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reserva;
use App\Models\Grupo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;
use App\Mail\recuperaraContra;
class userController extends Controller
{
    public function newPassword(Request $request){
        if($request->password1 == $request->password2){
            $usuario = User::where("email","=",$request->email)->first();
            $usuario->password = Hash::make($request->password1) ;
            $usuario->save();
            return response()->json([
                "status" => 1,
                "msg" => "se cambio correctamente",
                ]);
        }else{
            return response()->json([
                "status" => 0,
                "msg" => "verifique el password",
                ]);
        }
    }
    
    public function comparetoKey(Request $request){
        $usuario = User::where("email","=",$request->email)->first();
        if(isset($usuario->id)){
            $key_code = $usuario->verficador; 
            if($key_code == $request->codigo){
                $usuario->verficador = "d345dxf"; 
                $usuario->save();
                return response()->json([
                    "status" => 1,
                    "msg" => "codigo verficado",
                    ]);
                

            }else{
                return response()->json([
                    "status" => 0,
                    "msg" => "error de codigo",
                    ]);
            }
        }else{
            return response()->json([
                "status" => 0,
                "msg" => "correo no registrado",
            ],404);
        }
    }
    public function recoverPassword(Request $request){

        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $texto = substr(str_shuffle($permitted_chars), 0, 10);
        
        $usuario = User::where("email","=",$request->email)->first();
        //$usuario122 = User::findOrFail(2); 
        
        if(isset($usuario->id)){
            $usuario->verficador = $texto; 
            $t= $usuario->verficador;
            $usuario->save();
            Mail::to($usuario->email)->send(new recuperaraContra($usuario));
            return response()->json([
            "status" => 1,
            "msg" => "codigo enviado",
            ]);
        }else{
            return response()->json([
                "status" => 0,
                "msg" => "correo no registrado",
            ],404);
        }

    }
    public function index(){ //retorna todos los Usuarios
        $users = User::all();

        return $users;                     //JSON con los usuarios
    }

    public function getById($id)           //retorna una usuario por el ID
    {
        $user = User::findOrFail($id);     //si no encuentra un usuario devuelve falso
        return $user;                      //JSON con el usuario
    }

    public function registro(Request $request){
        /* $request->validate([
            'name' => 'required',
            'apellido' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',

        ]); */
        $usuario = User::where("email","=",$request->email)->first();
        if(isset($usuario->id)){
            return response()->json([
                "status" => 0,
                "msg" => "usuario ya existe",
            ]);
        }
        $user = new User();
        $user->name = $request->name;
        $user->apellido = $request->apellido;
        $user->email = $request->email;
        $user->esAdmin = $request->esAdmin;
        $user->password =Hash::make($request->password) ;
        $user->save();
        return response()->json([
            "status" => 1,
            "msg" => "registro exitoso",
        ]);

    }
    public function login(Request $request){
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);
        $user = User::where("email","=",$request->email)->first();
        if(isset($user->id)){
           if(Hash::check($request->password, $user->password)){
                //crear token
                $token = $user->createToken("auth_token")->plainTextToken;
                return response()->json([
                    "status" => 1,
                    "msg" => "usuario logueado exitosamente",
                    "access_token" => $token,
                    "email" => $user->email,
                    "name" => $user->name,
                    "apellido" => $user->apellido,
                    "esAdmin" => $user->esAdmin,
                    "id" => $user->id,
                ]);
        
           }else{
                return response()->json([
                    "status" => 0,
                    "msg" => "contraseña incorrecta",
                ],404);
     
           }
        }else{
            return response()->json([
                "status" => 0,
                "msg" => "usuario no registrado",
            ],404);
    
        }
    }
    public function perfil(){
        return response()->json([
            "status" => 1,
            "msg" => "acerca del usuario",
            "data" => auth()->user()
        ]);
    }
    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            "status" => 1,
            "msg" => "cierre sesion"
        ]);
    }

    public function destroy($id)               //elimina un ambiente
    {
        $user = User::findOrFail($id);        //si no encuentra ambiente devuelve falso
        $reservas = Reserva::where("id_users","=",$id)->get();
        $grupos = Grupo::where("id_users","=",$id)->get();
        foreach ($grupos as $grupo){       //convierte numero de periodo en texto
            $grupo->id_users = NULL;
            $grupo->save();
        }
        foreach ($reservas as $reserva){       //convierte numero de periodo en texto
            Reserva::destroy($reserva->id);
        }
        /* $url_image = str_replace('storage', 'public', $aula->imagen);
        Storage::delete($url_image);           //Elimina una imagen */
        User::destroy($id);
        return response()->json([              //JSON con los ambientes
            'Respuesta' => 'Eliminado correctamente'
        ], 201);                               
    }

    public function update(Request $request, $id)        //actualiza los datos de un aula
    {
        $request->validate([
            'name' => 'required',
            'apellido' => 'required',
            'password' => 'required',
            'esAdmin' => 'required',

        ]);

        $usuario = User::where("email","=",$request->email)->first();
        if(isset($usuario->id) && ($usuario->id != $id)){
            return response()->json([
                "status" => 0,
                "msg" => "email ya existe",
            ]);
        }

        $user = User::findOrFail($id);                   //si no encuentra aula devuelve falso   
        
        //$aula->update($request->except('imagen'));       //guarda cambios
        $user->name = $request->name;
        $user->apellido = $request->apellido;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->esAdmin = $request->esAdmin;
        $user->save();

        return response()->json([                        
            'Respuesta' => 'Actualizado correctamente'
            ], 202);
    }
        
}