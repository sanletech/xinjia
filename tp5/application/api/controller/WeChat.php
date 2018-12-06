<?php
//客户的账单
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Session;
use app\api\controller\common AS CommonM ;
use app\index\model\Order as OrderM;
class WeChat extends Controller
{    
    public  $order_status;
    public  $page=5;
    public  $mtime ;
    public  $member_code;
    public function _initialize()
    {  
        $this->order_status = config('config.order_status');
        $this->mtime =  date('Y-m-d H:i:s');
        $this->member_code =Session::get('member_code','think');
    }
    
    public function __call($function_name, $arguments) {
        return $function_name.implode(', ', $arguments).'不存在';
    }
    
     //用户注册 或者手机号码绑定 与 weixin_code 绑定
    public function wechat_register ($wechat_code,$wechat_name,$phone,$code) {
         //20分钟内有效
        $valid_time  = array(date('Y-m-d H:i:s',strtotime('-20min')),date('Y-m-d H:i:s'));
        $res_code = Db::name('ali_sms')->where('phone',$phone)
                ->where('ctime','between time',$valid_time)
                ->order('ctime desc')->column('code');
        if(!in_array($code,$res_code)){
            return json(array('status'=>0,'message'=>'验证码不正确'));
        }
        //先查询 是否存在 相同的手机号 存在就绑定,不存在就添加
        $isset_phone = Db::name('member')->where('phone',$phone)->field('id','name')->find();
        if($isset_phone){
            $res_phone = Db::name('member')->where('id',$isset_phone['id'])
                    ->update(['wechat_code'=>$wechat_code,
                            'wechat_name'=>$wechat_name]);
            $message = '绑定';
        }  else {
                $IDCode = new \app\index\controller\IDCode();
                //查询用户表最大的id 生成零时客户member_code
                $id =Db::name('member')->max('id')+1;
                $member_code = $IDCode->create($id, 'zh');
                $map['member_code'] = $member_code; //唯一帐号
                $map['wechat_code'] = $wechat_code; 
                $map['create_time'] = $this->mtime; 
                $map['type'] = 'wechat'; 
                $map['wechat_name'] = $wechat_name;
            $res_phone = Db::name('member')->insert($map);
            $message = '注册';
        }
        //操作成功后，写入session 信息将用户
        if($res_phone){
            Session::set('member_code',$member['member_code']);
           
            //设置默认利润
            if($message=='注册'){
                Session::set('name',$wechat_name);
                $member_profit =  new \app\index\model\Login();
                $member_profit->member_profit($member_code);
            }  else {
                Session::set('name',$isset_phone['name']);
            }
        }
        return $res_phone ? array('status'=>1,'message'=>$message.'success'): array('status'=>0,'message'=>$message.'fail');
    }
    
    // 小程序门到门下单页面
    public function orderList($limit=10,$page=1,$start_add='',$end_add='',$load_time=''){
        $member_code = $this->member_code;
        
        //计算出从那条开始查询
        $sea_pirce =new OrderM;
        $data = $sea_pirce ->price_sum($member_code,$start_add,$end_add,$load_time,$page,$limit);
        //获取总页数
        $count = $data['count']; 
        $list =  $data['list'] ;
        return json(array('count'=>$count, 'page'=>$page,'limit'=>$limit,'list'=>$list)) ;
    }
    
    //小程序门到门下单页面 //海运费id
    public function orderBook($sea_id,$container_size){
        if(!($container_size=='40HQ' || $container_size=='20GP')){
            return false;
        }
        $member_code = $this->member_code;
        $sea_pirce =new OrderM;
        $list = $sea_pirce ->orderBook($sea_id ,$container_size,$member_code);
         //创建订单令牌
        action('OrderToken/createToken','', 'controller');
        $TOKEN = Session::get('TOKEN');
        return json(array('TOKEN'=>$TOKEN,'list'=>$list));
    } 
    //小程序 //添加收/发货人的信息
    public function linkmanAdd($data)
    {
        $link_name = $data['link_name'];
        $phone = $data['phone'];
        $company = $data['company'];
        $add= $data['add'];
        $member_code =Session::get('member_code');
        $time = date("Y-m-d H:i:s"); 
        $sql= "insert into hl_linkman(name ,phone ,company ,mtime,member_code,address) "
         . " values('$link_name','$phone','$company','$time','$member_code','$add')";
        $res =  Db::execute($sql);
        $res ?  $response['success'][]='添加linkman表': $response['fail'][]='添加linkman表';
        return  $response;    
        
    }

}