<?php
/**
 * Created by PhpStorm.
 * User: kindness
 * Date: 2019/3/2
 * Time: 20:19
 */
//初次接入
$wx = new Wx();
class Wx{

    //token
    private const TOKEN = 'kindness';
    //接收的数据对象
    private $obj;
    private $con;

    public function __construct()
    {
        //判断有没有echostr
        if (isset($_GET['echostr'])){
            echo $this->check();
        }else{
            //引入响应需要的文本
            $this->con = include 'config.php';
            //调用消息处理
            $this->accessMsg();
        }
    }

    /*
     * 接收消息处理
     * */
    private function accessMsg(){
        //接收传递的数据
        $xml = file_get_contents('php://input');
        //写入日志
        $this->writeLog($xml);
        //将接收到的数据转化为对象
        $this->obj = simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA);

        //接收消息类型
        $type = $this->obj->MsgType;

        //判断内容是否有图文
        if(stristr($this->obj->Content,'图文')){
            $type = 'imageText';
        }

        //动态方法
        $fun = $type.'Fun';

        echo $this->$fun();
    }

    /*
     * 响应文本
     * */
    private function textFun(){
        $content = (string)$this->obj->Content;
        //接收文本
        $xml = $this->con['text'];
        return sprintf($xml,$this->obj->FromUserName,$this->obj->ToUserName,time(),'wx:'.$content);
    }

    /*
     * 响应图文
     * */
    private function imageTextFun(){
        //接收图文
        $xml = $this->con['image_text'];
        return sprintf($xml,$this->obj->FromUserName,$this->obj->ToUserName,time(),'两袖青蛇','李淳罡的两袖青蛇','https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1551585175718&di=1f39776da4f12c69d9b133112852c8f5&imgtype=0&src=http%3A%2F%2Fb-ssl.duitang.com%2Fuploads%2Fitem%2F201605%2F15%2F20160515065257_seVXJ.thumb.700_0.jpeg','http://8tbuhz.natappfree.cc/info.php');
    }

    /*
     * 生成文本消息
     * */
//    private function createText($content){
//
//    }

    /*
    写日志
     * */
    private function writeLog($xml,$flag = 0){
        $title = $flag == 0 ? '接收' : '发送';
        $date = date('y-m-d h:i:s');
        $log = $title.'['.$date."]\n";
        $log .= "---------------------------------\n";
        $log .= $xml."\n";
        $log .= "---------------------------------\n";
        //写日志
        file_put_contents('wx.xml',$log,FILE_APPEND);
    }

    //验证
    private function check(){
        //接收公众平台传递过来的数据
        $signature = $_GET['signature'];
        $echostr = $_GET['echostr'];
        $tmpArr['nonce'] = $_GET['nonce'];
        $tmpArr['timestamp'] = $_GET['timestamp'];
        $tmpArr['token'] = self::TOKEN;

        //进行字典排序
        sort($tmpArr,SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ( $tmpStr == $signature){
            return $echostr;
        }
        return '';
    }

}