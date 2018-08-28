<?php

header('content-type:text/html;charset=utf-8');

/**
 * 聚合数据发送手机验证码工具类
 * Class SendCode
 */

class SendCode
{

    private $key;
    private $tpl_id;

    public function __construct()
    {
        $this->key = 'AppKey';  // 聚合数据后台的AppKey
        $this->tpl_id = 'tpl_id';    // 申请的短信模板ID，根据实际情况修改短信模板
    }


    public function send($mobile){


        if (empty($mobile)) {
            $this->showJson(-4,'手机号不能为空');
        }

        $code = mt_rand(100000,999999);
        $sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL
        $smsConf = array(
            'key'   => $this->key, //您申请的APPKEY
            'mobile'    => $mobile, //接受短信的用户手机号码
            'tpl_id'    => $this->tpl_id, //您申请的短信模板ID，根据实际情况修改
            'tpl_value' =>'#code#='.$code.'&#company#=聚合数据' //您设置的模板变量，根据实际情况修改
        );

        $content = $this->juhecurl($sendUrl,$smsConf, 1); //请求发送短信
        if($content){
            $result = json_decode($content,true);
            $error_code = $result['error_code'];
            if($error_code == 0){
                //状态为0，说明短信发送成功
                $data['code'] = $code;
                $this->showJson(1, $data);
            }else{
                //状态非0，说明失败
                $msg = $result['reason'];
                $this->showJson(-3, "短信发送失败(".$error_code.")：".$msg);
            }
        }else{
            //返回内容异常，以下可根据业务逻辑自行修改
            $this->showJson(-3, '请求发送短信失败');
        }

    }


    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    public function juhecurl($url,$params=false,$ispost=0){

        $httpInfo = array();
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;

    }


    public function showJson($status = 1, $return = NULL) {

        $ret = array('status' => $status);

        if (!is_array($return)) {
            if ($return) {
                $ret['result']['message'] = $return;
            }

            exit(json_encode($ret));
        } else {
            $ret['result'] = $return;
        }

        exit(json_encode($ret));
    }

}