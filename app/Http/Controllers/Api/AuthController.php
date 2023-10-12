<?php

namespace App\Http\Controllers\Api;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;
use App\Http\Helpers\HttpResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HttpResponse;
    
    function getAllTodos($id = null) {

        if($id){
            $todo = Todo::find($id);
       
            if($todo){
                return $this->successOrFail("get all todos.","success",$todo,200);
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
            'user_id' => auth()->id(),
            'title' => $request->title,
            'detail' => $request->detail,
            
        ]);
      

        $data = $request->all();
        return $this->successOrFail("Todo Created successfully.","success",$data,201);

    }

    //not done registter work yet
    function registerUser(Request $request) {

        $data = $request->all();

        // $token = $request->user()->createToken($request->token_name);

        return $this->successOrFail("user registerd","success",$data,201);
    }
    //not done registter work yet

    function login(Request $request){

        $request->validate([
            "email" => "email|required",
            "password" => "required"
        ]);

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = User::where('email',$request->email)->first();
            $token = $user->createToken("ApiToken".auth()->user()->name)->plainTextToken;

            return $this->successOrFail("You are Logged In.","success",[
                "user" => $user,
                "token"=> $token,
            ],201);
            
        }else{
            return "un-auth";
        }
    }

}
