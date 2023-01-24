<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Session;

class AuthController extends Controller
{
    public function signin(LoginRequest $request){
        $credentials = $request->getCredentials();

        if(!Auth::attempt($credentials)):
            // $request->session()->regenerate();
            $errors = ['errors'=>['errs' => ['Wrong credentials!, Please contact your Administrator']]];
            return response()->json($errors,401);
        endif;

        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        $a = Auth::user();
        return response()->json($a,200);
   }

   public function logout(){
    try {
        Session::flush();
        $success = true;
        $message = 'Successfully logged out';
    } catch (\Illuminate\Database\QueryException $ex) {
        $success = false;
        $message = $ex->getMessage();
    }

    // response
    $response = [
        'success' => $success,
        'message' => $message,
    ];
    return response()->json($response);
}
}
