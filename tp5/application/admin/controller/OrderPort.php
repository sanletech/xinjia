<?php
namespace app\admin\controller;
use app\admin\common\Base;
use think\Request;
use think\Db;

use think\config;
//引入七牛云的相关文件
use Qiniu\Auth as Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

class OrderPort extends Base
{   
    
    public function Upload()
    {
        $file = request()->file('file');
        // 要上传图片的本地路径
        $filePath = $file->getRealPath();
        $ext = pathinfo($file->getInfo('name'), PATHINFO_EXTENSION);  //后缀
        // 上传到七牛后保存的文件名
        $key =substr(md5($file->getRealPath()) , 0, 5). date('YmdHis') . rand(0, 9999) . '.' . $ext;
        require_once APP_PATH . '/../vendor/Qiniu/autoload.php';
        // 需要填写你的 Access Key 和 Secret Key
        $accessKey = Config::get('qiniu.accessKey');
        $secretKey = Config::get('qiniu.secretKey');
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
        // 要上传的空间
        $bucket = Config::get('qiniu.bucket');
        $domain = Config::get('qiniu.DOMAIN');
        $token = $auth->uploadToken($bucket);
        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            return ["err"=>1,"msg"=>$err,"data"=>""];
        } else {
            //返回图片的完整URL
            return ["err"=>0,"msg"=>"上传完成","data"=>($domain .'/'. $ret['key'])];
        }
    }

    
    //港到港订单页
    public function portList()
    {
        return $this->view->fetch('orderPort/port_list');
    }
    //所有订单
    public function all_ordePport()
    {
        return $this->view->fetch('orderPort/all_ordePport');
    }
    //在线支付
    public function port_payment()
    {
        return $this->view->fetch('orderPort/port_payment');
    }
    //月结
    public function port_month()
    {
        return $this->view->fetch('orderPort/port_month');
    }
    //详情
    public function port_details()
    {
        return $this->view->fetch('orderPort/port_details');
    }

}
