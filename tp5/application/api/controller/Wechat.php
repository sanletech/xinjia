<?php
//客户的账单
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Session;
use app\api\controller\Common ;
use app\index\model\Order as OrderM;
use app\api\model\Wechat as WechatM;
use think\cache\driver\Redis ;

class Wechat extends Common
{    
    protected  $order_status;
    protected  $page=5;
    protected  $mtime ;
    
    protected $redis_config =[
        'DATA_CACHE_PREFIX' => 'Redis_',//缓存前缀
        'DATA_CACHE_TYPE'=>'Redis',//默认动态缓存为Redis
        'DATA_CACHE_TIMEOUT' => false,
        'REDIS_RW_SEPARATE' => true, //Redis读写分离 true 开启
        'REDIS_HOST'=>'127.0.0.1', //redis服务器ip，多台用逗号隔开；读写分离开启时，第一台负责写，其它[随机]负责读；
        'REDIS_PORT'=>'6379',//端口号
        'REDIS_TIMEOUT'=>'300',//超时时间
        'REDIS_PERSISTENT'=>false,//是否长连接 false=短连接
        'REDIS_AUTH'=>'',//AUTH认证密码
    ];
    
    protected $wechat_config = [
    //微信的接口配置
    'appid' =>'wx158584120ea9ec49'
    ,'AppSecret'=>'9ff909df785e6b3977d80c12e375c4ab'
    ];


    
    protected function _initialize()
    {   
        $this->member_code =Session::get('member_code');
//         var_dump( $this->member_code);
        $this->order_status = config('config.order_status');
        $this->mtime =  date('Y-m-d H:i:s');
    }
    
    //阿里云发送短信
    public function ali_sms($phone){
        $data = action('index/Login/ali_sms',['phone'=>$phone],'controller');
        return $data;
    }
    
    
    
    // 小程序门到门下单页面 price_sum($member_code,$start_add,$end_add,$load_time,$page,$limit,$sea_id='')
    public function orderList($limit=10,$page=1,$start_add='',$end_add='',$ship_id='',$start_time='',$end_time=''){
        $member_code = $this->member_code;
        // var_dump($member_code);exit;
        //计算出从那条开始查询
        $sea_pirce =new OrderM;
        $data = $sea_pirce ->price_sum($member_code,$start_add,$end_add,$ship_id,$start_time,$end_time,$page,$limit);
        $list =  $data['list'] ;
        return json(array('page'=>$page,'limit'=>$limit,'list'=>$list)) ;
    }
    
    //小程序港到港
    public function orderPortList($page=1,$limit=10,$start_add='',$end_add='',$ship_id=''){
    
        $sea_pirce =new \app\index\model\OrderPort();
        $data = $sea_pirce ->price_port($page,$limit,$start_add,$end_add,$ship_id);
        $list =  $data['list'] ;
        return json(array('page'=>$page,'limit'=>$limit,'list'=>$list)) ;
        
    }


    //小程序门到门下单页面 //海运费id  ,柜型
    public function orderBook($sea_id,$container_size){
        if(!($container_size=='40HQ' || $container_size=='20GP')){
            return false;
        }
        $member_code = $this->member_code;
        $sea_pirce =new OrderM;
        $list = $sea_pirce ->orderBook($sea_id ,$container_size,$member_code);
        
         //创建订单令牌
        action('index/OrderToken/createToken','', 'controller');
        $TOKEN = Session::get('TOKEN');
        return json(array('TOKEN'=>$TOKEN,'list'=>$list));
    } 
    
    //小程序 获取联系/发货人的信息  action
      public function selectlinkman()
    {
        $data = action('index/Order/selectlinkman','controller');
        return json($data);
    }
    
    //小程序 门到门 订单处理
      public function orderData()
    {
        $data =$this->request->param();
        // $this->_p($data);exit;
        // $response = action('index/Order/order_data',['data'=>$data,'type'=>'wechat'],'controller');
        $response = action('index/Order/order_data',['data'=>$data],'controller');
        return $response;    
    }
    
    //门到门 订单查询
    ////状态 已完成completion，待支付payment，已取消cancel，审核中audit_in，审核通过audit_pass，已订舱book，派车中send_car，
    //状态 已完成，待支付，已取消，信息处理中，承运中，已订舱，派车中，
    public function orderQuery($order_num='',$limit=10,$page=1,$status='all',$s_port='',$e_port=''){

        $dataM = new WechatM();
        $member_code = $this->member_code;
        // var_dump($member_code);exit;
        $data = $dataM->orderQuery($member_code,$limit,$page,$status,$order_num,$s_port,$e_port);
        return json($data);
        
    }
    //订单详情
    public function orderDetail($order_num){
        
        $dataM =  new WechatM();
        $member_code =  $this->member_code;
        $data = $dataM->orderDetail($member_code,$order_num);
        return json($data);
    }
            
         
        //小程序 //添加收/发货人的信息
    public function linkmanAdd($data)
    {
        $result = $this->validate(
            $data,
            [
            'link_name' => 'require|max:25',
            'phone' =>'require|number|length:11',
            'company'=>'require|length:50',
            ]);
        if(true !== $result){
        // 验证失败 输出错误信息
        return json($result);
        }

       $response = action('index/Order/linkmanAdd',['data'=>$data],'controller');
       return json($response);    
        
    }
    
     //收/发货人的信息的删除
    public function linkmanDel($id=0) {
        $response = action('index/Order/linkmanDel',['id'=>$id],'controller');
        return json($response);
    }
    
    //收发货人的信息修改
    public function  linkmanUpdate($data){
        $response = action('index/Order/linkmanUpdate',['id'=>$data],'controller');
        return json($response);
    }
  
    
    //船公司 信息列表查询
    public function ship($id='all') {
        if (is_int($id)){
            $map = ['id'=>$id];
        }else{
            $map = ['id'=>['>',0]];
        }
        $data = Db::name('shipcompany')
                ->where('status',1)->fetchSql(false)
                ->field('id,ship_short_name ship_name')
                ->where($map)->select();
            // $this->_p($data);exit;
        return json($data);
    }
    
    //港口信息类列表
    public function portName() {
        $data = Db::name('port')->alias('p')
                ->join('hl_city C','C.city_id = P.city_id')
                ->where('P.status',1)
                ->field('P.port_code value,P.port_name name')
                // ->field('P.port_code,P.port_name,C.city_id,city')
                ->order('C.city_id')
                ->group('P.port_code')
                ->select();
        //分组
        // $temp = array();
        // foreach ($data as $key=>$value){
        //     $temp['city_id']['port_list'][] =array($value['port_code'],$value['port_name']);
        //     $temp['city_id']['city'] =$value['city'];
        // }
        // $temp = array_values($temp);
        return json($data);
    }

    
    public function wechatOpenID($code='033tggLd05OcTs1TsSId0rJ5Ld0tggLj',$phone='')
    {
        $appid = $this->wechat_config['appid'];  $AppSecret = $this->wechat_config['AppSecret'];

        $url ='https://api.weixin.qq.com/sns/jscode2session?appid='
                ."$appid"         
                . '&secret='
                ."$AppSecret"             
                . '&js_code='.$code.'&grant_type=authorization_code';
        
        $info = file_get_contents($url);//发送HTTPs请求并获取返回的数据，推荐使用curl
        $json = json_decode($info);//对json数据解码
        $arr = get_object_vars($json);
        if(array_key_exists('errcode', $arr)){
            return array('error'=>  implode(',', $arr));
        }
        $openID = $arr['openid'];
        $session_key = $arr['session_key'];
           
        return  array('openID'=>$openID) ; 
    }
    
    
    public function  redis(){
        $redis=new \Redis();
        $redis->connect($this->redis_config['REDIS_HOST'],$this->redis_config['REDIS_PORT']);
         $redis->set("tutorial-name", "Redis 1211351");

        echo $redis->get("tutorial-name");
    }
}
