<?php

namespace App\Http\Controllers\Api;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use App\Http\Helpers\HttpResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponse;
    
    function getAllTodos($id = null) {

        if($id){
            $todo = Todo::find($id);
       
            if($todo){
                return $this->successOrFail("Here your specific Todo.","success",$todo,200);
            }else{
                return $this->successOrFail("Sorry No todo Found.","fail",[],404);
            }

        }else{

            $todos = Todo::all();
            return $this->successOrFail("get all todos.","success",$todos,200);  
        }

    }

    function createTodo(Request $request){

        $user = auth()->user();
        
        Todo::create([
            'user_id' => auth()->user()->id,
            'title' => $request->title,
            'detail' => $request->detail,           
        ]);
      
        $data = $request->all();
        return $this->successOrFail("Todo Created successfully.","success",$data,201);

    }


    function registerUser(Request $request) {

        $request->validate([
            "name" => "required",
            'email' =>'required|email|unique:users,email',
            "password" => "required"
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        $token = $user->createToken("ApiToken".$user->name)->plainTextToken;
        
        return $this->successOrFail("You are Registered.","success",[
            "user" => $user,
            "token"=> $token,
        ],201);
       
    }


    function login(Request $request){

        $request->validate([
            "email" => "email|required",
            "password" => "required"
        ]);

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = User::where('email',$request->email)->first();
            $token = $user->createToken("ApiToken".$user->name)->plainTextToken;

            return $this->successOrFail("You are Logged In.","success",[
                "user" => $user,
                "token"=> $token,
            ],201);
            
        }else{
            return "un-auth";
        }
    }

}
