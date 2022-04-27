<?php

namespace App\Controllers;

use App\Core\Application;
use App\Classes\LinkShortener;
use App\Core\Middlewares\AuthMiddleware;
use App\Core\Request;
use App\Models\Links;

class LinkController extends BaseController
{
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['index', 'store']));
    }

    public function index()
    {
        $user = Application::$app->auth;
        $linkModel = new Links();
        $links = $linkModel->getAll($user->id);

        $base_url = Application::url();
        $data = [];
        foreach ($links as $link){
            $data[] = [
                'id' => $link['id'],
                'short_url' => $base_url.$link['shortened_link'],
                'redirect_to' => $link['main_address'],
                'create_at' => $link['created_at'],
                'expire_at' => $link['expire_at']
            ];
        }

        return $this->response(200, "successful", ['links' => $data]);
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