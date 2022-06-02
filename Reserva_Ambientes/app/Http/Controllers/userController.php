<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
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
                "status" => true,
                "msg" => "se cambio correctamente",
                ]);
        }else{
            return response()->json([
                "status" => false,
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
                    "status" => true,
                    "msg" => "codigo verficado",
                    ]);
                

            }else{
                return response()->json([
                    "status" => false,
                    "msg" => "error de codigo",
                    ]);
            }
        }else{
            return response()->json([
                "status" => false,
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
            "status" => true,
            "msg" => "codigo enviado",
            ]);
        }else{
            return response()->json([
                "status" => false,
                "msg" => "correo no registrado",
            ],404);
        }


    }

    public function registro(Request $request){
        $request->validate([
            'name' => 'required',
            'apellido' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',

        ]);
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
}
