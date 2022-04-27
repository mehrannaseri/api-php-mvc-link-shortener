<?php

namespace App\Controllers;

use App\Core\Application;
use App\Classes\LinkShortener;
use App\Core\Middlewares\AuthMiddleware;
use App\Core\Request;

class LinkController extends BaseController
{
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['index', 'store']));
    }

    public function index()
    {
        $user = Application::$app->auth;
        dd($user);
    }

    public function store(Request $request)
    {
        try{
            $shortener = new LinkShortener($request->body->link);
            list($short_url, $expire_time) = $shortener->urlToShortCode();
        }
        catch (\Exception $e){
            return $this->response(400, $e->getMessage());
        }

        return $this->response(201, "successful", [
            'new_url' => Application::url().$short_url,
            'expire_at' => $expire_time
        ]);
    }
}