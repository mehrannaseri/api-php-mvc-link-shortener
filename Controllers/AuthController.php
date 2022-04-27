<?php

namespace App\Controllers;

use App\Core\Application;
use App\Core\Request;
use App\Models\User;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $user = new User();
        $user->save($request->body->name, $request->body->email, password_hash($request->body->password, PASSWORD_DEFAULT));
        return $this->response(201, "Successful registration");
    }

    public function login(Request $request)
    {
        $model = new User();
        $user = $model->getByEmail($request->body->email);

        if(! $user){
            return $this->response(422, "Invalid Email or Password");
        }
        elseif(! password_verify($request->body->password, $user->password)){
            return $this->response(422, "Invalid Email or Password");
        }
        else{
            list($token, $expire_time) = Application::$app->jwt->generate($user);

            return $this->response(200, "Logged in", [
                'name' => $user->name,
                'email' => $user->email,
                'token' => $token,
                "expireAt" => $expire_time
            ]);
        }
    }
}