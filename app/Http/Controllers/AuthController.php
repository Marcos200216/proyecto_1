<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
        ]);
    
        return response()->json(['message' => 'User registered successfully'], 201);
    }
    
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
    
            $token = bin2hex(random_bytes(30));
    
            User::where('id', $user->id)->update(['api_token' => $token]);
    
            return response()->json([
                'message' => 'Login exitoso',
                'token' => $token,
                'user' => $user
            ], 200);
        }
    
        return response()->json(['message' => 'Credenciales incorrectas'], 401);
    }
    
}
