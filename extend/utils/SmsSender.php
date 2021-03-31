<?php


namespace utils;

use Curl\Curl;
use think\facade\Cache;

/**
 * 接口购买地址 https://www.253.com/
 * 创蓝253
 */
class SmsSender
{
    private $curl;
    private $errorMsg;
    private $templateIndex = 0;

    protected $config = [
        'engine' => 'file',
        'geteway' => 'http://smssh1.253.com/msg/', // 短信网关地址
        'account' => 'xxxxxxxx',                   // 账号
        'password' => 'xxxxxxxx',                  // 密码
        'lifetime' => 900,                          // 验证码有效期，秒
        'daily_limit' => 10,                       // 每个账号一天允许的最大发送数
        'templates' => [                           // 模板文件
            "您好，您的验证码为 {code}，{lifetime}分钟内有效，请勿向任何人泄露，感谢您的使用！"
        ]
    ];

    /**
     * SmsSender constructor.
     * @param array $config
     * @throws \ErrorException
     */
    public function __construct(array $config = [])
    {
        $config = $config ?: (is_null(config('system.sms')) ? [] : config('system.sms'));
        $this->config = array_merge($this->config, (array)$config);
        $this->curl = new Curl();
    }

    /**
     * 短信发送
     * @param $mobile
     * @return bool
     * @throws \ErrorException
     */
    public function send($mobile)
    {
        if ($this->getSendTimes($mobile) >= $this->config['daily_limit']) {
            $this->errorMsg = '您今天的短信数已超出限制';

            return false;
        }

        $code = rand(100000, 999999);
        $message = $this->getTemplateMsg($code);

        // 发送短信
        $apiURL = $this->config['geteway'] . 'send/json';
        $apiParams = [
            'account' => $this->config['account'],
            'password' => $this->config['password'],
            'msg' => urlencode($message),
            'phone' => $mobile,
            'report' => true,
        ];
        $this->curl->setHeader('Content-Type', 'application/json; charset=utf-8');
        $result = $this->curl->post($apiURL, $apiParams);
        if (0 == $result->code) {
            $this->afterSend($mobile, $code);

            return true;
        } else {
            $this->errorMsg = $result->errorMsg;

            return false;
        }
    }

    /**
     * 选择模板
     * @param int $index
     * @return $this
     */
    public function chooseTemplate($index)
    {
        $this->templateIndex = count($this->config['templates']) < $index ? 0 : $index;

        return $this;
    }

    /**
     * 获取短信模板内容
     * @param $code
     * @return string
     */
    protected function getTemplateMsg($code)
    {
        return strtr($this->config['templates'][$this->templateIndex], [
                '{code}' => $code,
                '{lifetime}' => intval($this->config['lifetime'] / 60)
            ]
        );
    }

    /**
     * 获取当日发送的次数
     * @param $mobile
     * @return int
     */
    public function getSendTimes($mobile)
    {
        $times = Cache::store($this->config['engine'])->get($this->getDayLimitKeyName($mobile));

        return $times ?: 0;
    }

    /**
     * @param $mobile
     * @param $code
     */
    private function afterSend($mobile, $code)
    {
        Cache::store($this->config['engine'])->inc($this->getDayLimitKeyName($mobile));
        Cache::store($this->config['engine'])->set($this->getCodeKeyName($mobile), $code, $this->config['lifetime']);
    }

    /**
     * 验证码是否正确
     * @param $mobile
     * @param $code
     * @return bool
     */
    public function isEffective($mobile, $code)
    {
        $key = $this->getCodeKeyName($mobile);
        $originCode = Cache::store($this->config['engine'])->get($key);
        if ($originCode != $code) {
            return false;
        }
        Cache::store($this->config['engine'])->rm($key);

        return true;
    }

    protected function getDayLimitKeyName($mobile)
    {
        return $this->getKeyName(date('Ymd') . 'times', $mobile);
    }

    protected function getCodeKeyName($mobile)
    {
        return $this->getKeyName('code', $mobile);
    }

    protected function getKeyName($name, $mobile)
    {
        return sprintf('sms:%s:%s', $name, $mobile);
    }

    /**
     * 获取错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->errorMsg;
    }

    /**
     * 销毁curl对象
     */
    public function __destruct()
    {
        $this->curl->close();
    }
}
