<?php

namespace App\Classes;

use App\Core\Application;
use App\Core\Exceptions\LinkException;
use App\Models\Link;

class LinkShortener
{
    protected static $chars = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";
    protected static $codeLength = 7;
    protected $timestamp;
    public $model;

    public function __construct(){
        $this->timestamp = date("Y-m-d H:i:s");
        $this->model = new Link();
    }

    public function urlToShortCode($url){
        if(empty($url)){
            throw new LinkException("You should send url");
        }

        if($this->validateUrlFormat($url) === false){
            throw new LinkException("URL does not have a valid format.");
        }

        if($this->urlExistsInDB($url)){
            throw new LinkException("This link was shortener before");
        }
        $short_url = $this->createShortCode();
        $expire_time = $this->expireTime($this->timestamp);

        return [$short_url, $expire_time];
    }

    public function expireTime($time)
    {
        $time = strtotime($time);
        return date("Y-m-d H:i:s", strtotime("+1 month", $time));
    }

    protected function validateUrlFormat($url){
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    public function urlExistsInDB($url){
        return $this->model->checkLink($url);
    }

    public function createShortCode(){
        $sets = explode('|', self::$chars);
        $all = '';
        $randString = '';
        foreach($sets as $set){
            $randString .= $set[array_rand(str_split($set))];
            $all .= $set;
        }

        $all = str_split($all);

        for($i = 0; $i < self::$codeLength - count($sets); $i++){
            $randString .= $all[array_rand($all)];
        }
        $randString = str_shuffle($randString);
        if($this->checkCodeInDataBase($randString) > 0)
            return $this->createShortCode();
        return $randString;
    }

    public function shortCodeToUrl($code, $increment = true){
        if(empty($code)) {
            throw new Exception("No short code was supplied.");
        }

        if($this->validateShortCode($code) == false){
            throw new Exception("Short code does not have a valid format.");
        }

        return $urlRow["long_url"];
    }

    protected function validateShortCode($code){
        $rawChars = str_replace('|', '', self::$chars);
        return preg_match("|[".$rawChars."]+|", $code);
    }

    protected function checkCodeInDataBase($short_code)
    {
        return $this->model->checkShortCode($short_code);
    }
}