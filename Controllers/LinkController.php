<?php

namespace App\Controllers;

use App\Core\Application;
use App\Classes\LinkShortener;
use App\Core\Middlewares\AuthMiddleware;
use App\Core\Request;
use App\Models\Link;

class LinkController extends BaseController
{
    public LinkShortener $shortener;
    public Link $model;
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['index', 'store', 'edit']));
        $this->shortener = new LinkShortener();
        $this->model = new Link();
    }

    public function index()
    {
        $user = Application::$app->auth;
        $links = $this->model->getAll($user->id);

        $base_url = Application::url();
        $data = [];
        foreach ($links as $link){
            $data[] = [
                'id' => $link['id'],
                'short_url' => $base_url.$link['shortened_link'],
                'redirect_to' => $link['main_address'],
                'create_at' => $link['created_at'],
                'expire_at' => $link['expire_at'],
                'deleted' => is_null($link['deleted_at'])  ? false : true
            ];
        }

        return $this->response(200, "successful", ['links' => $data]);
    }

    public function store(Request $request)
    {
        try{
            list($short_url, $expire_time) = $this->shortener->urlToShortCode($request->body->link);
            $this->model->saveLink(Application::$app->auth->id, $request->body->link, $short_url, $expire_time);
        }
        catch (\Exception $e){
            return $this->response(400, $e->getMessage());
        }

        return $this->response(201, "successful", [
            'new_url' => Application::url().$short_url,
            'expire_at' => $expire_time
        ]);
    }

    public function edit(Request $request)
    {
        try{
            $dataStored = $this->model->find($request->body->id);
            if(! $dataStored){
                throw new \Exception("Invalid data requested");
            }
            if(Application::$app->auth->id != $dataStored['user_id']){
                throw new \Exception("This link is not belongs to you");
            }
            list($short_url, $expire_time) = $this->shortener->urlToShortCode($request->body->link);

            $this->model->update($request->body->id, $request->body->link, $short_url);
        }
        catch (\Exception $e){
            return $this->response(400, $e->getMessage());
        }

        return $this->response(200, "Update successfully", [
            'new_url' => Application::url().$short_url,
            'expire_at' => $dataStored['expire_at']
        ]);


    }
}