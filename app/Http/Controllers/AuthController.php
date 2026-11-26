<?php  
 namespace App\Http\Controllers; 
use App\Models\Role; 
use App\Models\User; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Hash;   
class AuthController extends Controller {     
public function register(Request $request)     { 
$request->validate([
    'name' => 'required|string|max:255',             
    'email' => 'required|email|unique:users,email',             
    'password' => 'required|string|min:6|confirmed',             
    'user_image' => 'nullable|image|max:2048',         
    
    ]);   
           
     $imagePath = null;         
     if ($request->hasFile('user_image')) {             
 $imagePath = $request->file('user_image')->store('users', 'public');   
        


        }           
$user = User::create([ 
'name' => $request->name,             
'email' => $request->email,             
'password' => Hash::make($request->password),             
'user_image' => $imagePath,             
'is_active' => true,             
'role_id' => Role::where('name', 'customer')->value('id'),
  ]);           
 $token = $user->createToken('auth_token')->plainTextToken;
 return response()->json([             
'message' => 'Registration successful', 
'user' => $user->load('role'),             
 'token' => $token,         
 ], 201);    
}      
public function login(Request $request)     {    
  $request->validate([             
 'email' => 'required|email',            
'password' => 'required|string',        
 ]);   
  $user = User::where('email', $request->email)->first();           
 if (! $user || ! Hash::check($request->password, $user->password)) {             
    return response()->json([                 
   'message' => 'Invalid email or password',             
   ], 401);        
  }           
 if (! $user->is_active) {            
 return response()->json([                 
 'message' => 'Your account is inactive',             
     ], 403);         
      }           
     $token = $user->createToken('auth_token')->plainTextToken;           
    return response()->json([             
                                                        'message' => 'Login successful',             
     'user' => $user->load('role'),             
                                                        
        'token' => $token,       
         ]);     
         }      
                                                          public function profile(Request $request)     { 
         return response()->json($request->user()->load('role')); 
        }      
        public function logout(Request $request)     
        {  
        $request->user()->currentAccessToken()->delete();           
         return response()->json(['message' => 'Logged out successfully']);    
         } } 