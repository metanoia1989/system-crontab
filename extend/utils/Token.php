<?php


namespace utils;

use \Firebase\JWT\JWT;

class Token
{
    private $key;//secret key

    private $alg;//签名算法 支持'HS256', 'HS384', 'HS512', 'RS256'

    private $config = [
        'iss' => '', //签发者
        'aud' => '', //jwt所面向的用户
        'iat' => '', //签发时间
        'nbf' => '', //在什么时间之后该jwt才可用
        'exp' => '', //过期时间
        'data' => '' //额外信息
    ];

    private $errorMsg;

    public function __construct($key = 'jwt', $alg = 'HS256')
    {
        $this->key = $key;
        $this->alg = $alg;
    }

    /**
     * 生成jwt
     * @param $data
     * @param null $exp 秒
     * @param null $nbf 在什么时间之后该jwt才可用
     * @param null $iat 签发时间
     * @param null $iss 签发者
     * @param null $aud jwt所面向的用户
     * @return string
     */
    public function encode($data = '', $exp = null, $nbf = null, $iat = null, $iss = null, $aud = null)
    {
        $now = time();
        if (is_null($exp)) {
            unset($this->config['exp']);
        } else {
            $this->config['exp'] = $now + $exp;
        }
        $this->config['nbf'] = $nbf ? $nbf : $now;
        $this->config['iat'] = $iat ? $iat : $now;
        $this->config['iss'] = $iss ? $iss : 'https://www.cshaptx4869.com';
        $this->config['aud'] = $aud ? $aud : 'https://www.cshaptx4869.com';
        $this->config['data'] = $data;

        return JWT::encode($this->config, $this->key, $this->alg);
    }


    /**
     * 解析jwt
     * @param $jwt
     * @param int $leeway
     * @return bool|object
     */
    public function decode($jwt, $leeway = 60)
    {
        try {
            JWT::$leeway = $leeway;
            $decoded = JWT::decode($jwt, $this->key, (array)$this->alg);
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            // 签名不正确
            $this->errorMsg = 'token签名不正确';

            return false;
        } catch (\Firebase\JWT\BeforeValidException $e) {
            // 签名在某个时间点之后才能用
            $this->errorMsg = 'token还未生效';

            return false;
        } catch (\Firebase\JWT\ExpiredException $e) {
            // token过期
            $this->errorMsg = 'token过期';

            return false;
        } catch (\Exception $e) {
            // 其他错误
            $this->errorMsg = 'token不正确';

            return false;
        }

        return $decoded;
    }

    /**
     * 获取错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->errorMsg;
    }
}
