<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/8 0008
 * Time: 下午 7:20
 */

namespace controller;
use framework\Code;
use model\UserModel;
class UserController extends Controller
{

    //定义一个成员属性，保存userModel实例化后的对象
    public $user;
    public function __construct()
    {
        parent::__construct();
        $this->user = new UserModel();

    }

    //创建一个UserController类之后继承与controller
    public function register()
    {
        //把对应的register渲染到页面中去
        $this->display();
    }
    public function verify()
    {
        $code = new Code();
        $code->outImage();
        //要把用户输入的和显示的验证码匹配，在这里可以用session保存
        $_SESSION['code'] = $code->code;

    }
   //将用户信息注册到数据库中
   public function  doRegister()
    {
        //把用户传递过来的信息进行接收
        $emali = $_POST['email'];
        $name = $_POST['username'];
        $pwd = md5($_POST['password']);
        $repwd = md5($_POST['repassword']);
        $yzm = $_POST['yzm'];
        $ip = $_SERVER['REMOTE_ADDR'];
        //假如ip地址是::1则要进行转换
        if($ip == '::1'){
            $ip = '127.0.0.1';
        }

        //用户的信息进行接收了之后进行判断
      if (strcmp($pwd,$repwd)){
           $msg = '两次密码不一致';
           $this->notice($msg);
           exit;
      }

        if(strlen($name) < 6){
            $msg = '用户名长度不能小于6位';
            $this->notice($msg,null,3);
            exit;
        }

        if(strlen($pwd) < 6){
            $msg = '密码长度不能小于6位';
            $this->notice($msg,null,3);
            exit;
        }
        if(preg_match("/^\d*$/",$pwd)){
            $msg = '密码必须包含字母、不能为纯数字';
            $this->notice($msg,null,3);
            exit;
        }
      $result = $this->user->table('user')->where("username='$name'")->field('id')->select();
        if ($result){
            $msg = '用户名不能相同';
            $this->notice($msg);
            exit;
        }

      //不区分大小写

      if (strcasecmp($yzm,$_SESSION['code'])) {
          $msg = '你的验证码输入错误';
         $this->notice($msg);
          exit;
      }
      $data['username'] = $name;
      $data['password'] = $pwd;
      $data['email'] = $emali;
      $data['addip'] = $ip;
      $result =  $this->user->table('user')->insert($data);

        if ($result) {
            $this->notice('恭喜小主，注册成功','index.php');
        }else {
           $this->notice('注册失败');
        }

    }
    //登录的方法
//先把登录页面渲染出来
    public function login()
    {
        $this->display();
    }

    public function doLogin()
   {
       //接收一下输入时候传过来的信息
       $name = $_POST['username'];
       var_dump($name);
      $pwd = md5($_POST['password']);
      $verify = $_POST['verify'];
       //二进制安全比较字符串
      if (strcasecmp($verify,$verify))
      {
          $msg ='你输入验证错误';
          $this->notice($msg);
          die;
      }
      //验证用户是否存在
       $result = $this->user->table('user')->field('*')->where("username='$name'and isdel=0")->select();
      var_dump($result);
      if (empty($result)){
          $msg = '用户不存在';
          $this->notice($msg);
          die;
      }
      if (strcmp($result[0]['password'],$pwd)){
          $msg = '密码不正确';
         $this->notice($msg);
          exit;
      }
          $_SESSION['username'] = $name;
     $_SESSION['id'] = $result[0]['id'];
      $_SESSION['undertype'] = $result[0]['undertype'];

     $this->notice('登陆成功','index.php');

      }
      //清除用户，退出登录
      public function out()
      {
          if (isset($_SESSION['id'])){
              session_unset();
              session_destroy();
              $msg = '退出成功,正在跳转首页';
             $this->notice($msg,'index.php');
          }
      }

   }
























