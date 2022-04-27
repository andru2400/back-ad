<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){

        $request->validate([
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password' => 'required|confirmed',            // supone que hay un campo passwords_confirmation
        ]);

        $user = new User();
        $user->name     = $request->name;
        $user->email    = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status'    => 1,
            'message'   => "Registro de usuario exitoso!"
        ]);

    }

    public function login(Request $request){
        $request->validate([
            'email'     => 'required|email',
            'password' => 'required',
        ]);

        $user =  User::where("email","=", $request->email)->first();
        if(isset($user)){
            if(Hash::check($request->password, $user->password)){
                // Crea Token de Sanctum !!
                $token = $user->createToken("auth_token")->plainTextToken;
                return response()->json([
                    'status'        => 1,
                    'message'       => "Usuario Logueado exitosamente!",
                    'access_token'  => $token
                ], );

            }else{
                return response()->json([
                    'status'    => 0,
                    'message'   => "Password Incorrecto!"
                ], 404);
            }
        }else{
            return response()->json([
                'status'    => 0,
                'message'   => "Usuario no registrado!"
            ], 404);
        }

    }

    public function userProfile(){
        return response()->json([
            'status'    => 1,
            'message'   => "Perfil del usuario!",
            'data'      => auth()->user()
        ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        // Eliminar Token
        return response()->json([
            'status'    => 1,
            'message'   => "Logout exitoso, se eliminaron tokens del usuario!",
            'data'      => auth()->user()
        ]);
    }
}
