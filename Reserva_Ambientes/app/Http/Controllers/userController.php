<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class userController extends Controller
{
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
