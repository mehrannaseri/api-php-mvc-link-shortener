<?php

namespace App\Core;

use App\Core\Exceptions\UnAuthorizeException;
use DateTimeImmutable;
use Firebase\JWT\JWT;

class JwtToken
{
    public string $secret_key;
    public string $issuer;
    public string $expire;

    public function __construct(array $config)
    {
        $this->secret_key = $config['secret'];
        $this->issuer = $config['issuer'];
        $this->expire = $config['expire'];
    }
    public function generate($user)
    {
        $issuer_claim = $this->issuer;
        $issued_at_claim = time(); // issued at
        $not_before_claim = $issued_at_claim + 10;
        $expire_claim = $issued_at_claim + $this->expire;
        $token = array(
            "issuer" => $issuer_claim,
            "issued_at" => $issued_at_claim,
            "not_before_claim" => $not_before_claim,
            "expire_at" => $expire_claim,
            "data" => array(
                "id" => $user->id,
                "firstname" => $user->name,
                "email" => $user->email
            ));

        return [JWT::encode($token, $this->secret_key, 'HS256'), $expire_claim];
    }

    public function authorize()
    {
        $request = Application::$app->request;
        $token = $request->getHeader('HTTP_AUTHORIZATION');

        $token = explode(' ', $token);
        $plain_token = json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token[1])[1]))));

        $now = new DateTimeImmutable();

        if ($plain_token->issuer !== $this->issuer ||
            $plain_token->not_before_claim > $now->getTimestamp() ||
            $plain_token->expire_at < $now->getTimestamp())
        {
            return false;
        }
        Application::$app->auth = $plain_token->data;
        return true;
    }
}