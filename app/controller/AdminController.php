<?php
/**
 * Created by PhpStorm.
 * User: 徐涛
 * Date: 2018/2/26/026
 * Time: 17:22
 */
namespace controller;
namespace controller;
use model\AdminModel;
use framework\Page;
class AdminController extends Controller
{
    //定义一个成员属性，保存AdminController实例化后的对象
    public $admin;
    public function __construct()
    {
        parent::__construct();
        $this->admin = new adminModel();

    }

    public function site_index1()
    {

        $this->display();
    }
    public function site_index1_2()
    {
        $this->display();
    }
    public function site_index2()
    {
       $user = $this->admin->table('user')->field('*')->select();
       $this->assign('user',$user);


        $this->display();
    }
    public function del()
    {
        //接收post传过来的数据
       $title = $_POST['title'];
       var_dump($title);
       //多项删除，拼接穿过来的id
       $id = join(',',$title);
       var_dump($id);
       //准备sql语句

        $ruset = $this->admin->table('user')->where("id in ($id)")->delete();
       echo $this->admin->sql;
        if ($ruset){
            $msg = '删除成功';
            $this->notice($msg);
            die;
        }else{
            $msg = '删除失败';
            $this->notice($msg);
            die;
        }

    }

    public function site_index2_1()
    {
        //查询ip
        $user = $this->admin->table('user')->field('*')->select();
        $this->assign('user',$user);
        $this->display();
    }
    public function addip()
    {
        /*//接收多选框传过来的值
        $title = $_POST['title'];

        $id = join(',',$title);
        var_dump($id);
        //接收一下天数
        $day0 = $_POST['day'];
        var_dump($day0);
        $time = time();
       // var_dump($time);
        foreach ($day0 as $value){
            $day1 = $value;
            var_dump($day1);
            $time1 = $time+("$day1"*(60*60*24));
            var_dump($time1);
        }
$data['isdel'] = $time1;

            $this->admin->table('user')->where("id in($id)")->update($data);
        */











        }










    //板块管理模块
    public function site_index3()
    {
      //查询板块
        $plate = $this->admin->table('board')->field('*')->select();
        $this->assign('plate',$plate);
        $this->display();
    }
    public function report()
    {
        //接收一下post值
        $bkname = $_POST['bkname'];
        //接收排序
        $select = $_POST['bid'];
        if (!empty($_POST['up'])) {
            if ($_POST['up']) {

                foreach ($_POST['bid'] as $key => $value) {
                    $bid1 = $value;
                   $data['bkname'] = $bkname[$key];
                    $result = $this->admin->table('board')->where("bid = $bid1")->update($data);

                }
                if(!$result){
                    $msg = '修改成功';
                    $this->notice($msg);
                    die;

                }else {
                    $msg = '修改失败';
                    $this->notice($msg);
                    die;
                }
            }
        }

       if (!empty($_POST['del'])){
           $bid1 = $_POST['chekbox'];
          $bid = join(',',$bid1);

          $result = $this->admin->table('board')->where("bid = $bid")->delete();
           if ($result){
               $msg = '删除成功';
               $this->notice($msg);
               die;
           }else{
               $msg = '删除失败';
               $this->notice($msg);
               die;
           }
           }

       }

    //添加板块
    public function site_index3_1()
    {
        //显示添加板块页面
        $this->display();
    }
    public function addition()
    {
        //接收一下写入板块的
        $bkname = $_POST['bkname'];
        //判断板块内容是否为空
        if (empty($bkname)){
            $msg = '板块内容不能为空';
            $this->notice($msg);
            die;
        }
        //判断板块长度是否符合要求
        if (strlen($bkname)<6){
            $msg = '板块长度不符合要求';
            $this->notice($msg);
            die;
        }

        $data['bkname'] = $bkname;
        $result = $this->admin->table('board')->insert($data);
        if ($result){
            $this->notice('添加成功','index.php?c=admin&a=site_index3');
        }else{
            $this->notice('添加失败');
        }

    }

    public function site_index4()
    {

      //查询用户表
        $user = $this->admin->table('user')->field('*')->select();
        $this->assign('user',$user);

        //查询主题数量
        $sum = $this->admin->table('article,board')->field('count(title)')->where("bid = pid and isdel=0")->select();
        $this->assign('sum',$sum);

      //连表查询，得到相应的板块
        $linked = $this->admin->table('article,board')->field('*')->where("bid = pid and isdel=0")->select();
        $this->assign('linked',$linked);
        $this->display();
    }
    public function essay()
    {
        //接收一下input的值
        $title = $_POST['title'];
        //把post传过来的id拼接成字符串
        $id = implode(',',$title);
        //修改isdel的值,修改成1；不显示
        $isdela = ['isdel'=>1];
        $isdel = $isdela['isdel'];
        $data['isdel'] = $isdel;

        $result = $this->admin->table('article')->where("id in($id)")->update($data);
       echo $this->admin->sql;
       if ($result){
           $msg = '放入成功';
          $this->notice($msg);
           die;
       }else{
           $msg = '放入失败';
           $this->notice($msg);
           die;
       }

    }

    public function site_index4_1()
    {
        //查询用户表
        $user = $this->admin->table('user')->field('*')->select();
        $this->assign('user',$user);

        //查询主题数量
        $sum = $this->admin->table('article,board')->field('count(title)')->where("bid = pid and isdel=1")->select();
        $this->assign('sum',$sum);

        //连表查询，得到相应的板块
        $linked = $this->admin->table('article,board')->field('*')->where("bid = pid and isdel=1")->select();
        $this->assign('linked',$linked);
        $this->display();
    }
    public function undeletion()
    {

        if (!empty($_POST['recover'])){
            if ($_POST['recover'] == '恢复主题'){

                //接收一下input的值
                $title = $_POST['title'];
                //把post传过来的id拼接成字符串
                $id = implode(',',$title);
                //修改isdel的值,修改成1；不显示
                $isdela = ['isdel'=>0];
                $isdel = $isdela['isdel'];
                $data['isdel'] = $isdel;

                $result = $this->admin->table('article')->where("id in($id)")->update($data);
                echo $this->admin->sql;
                if ($result){
                    $msg = '放入成功';
                      $this->notice($msg);
                    die;
                }else{
                    $msg = '放入失败';
                      $this->notice($msg);
                    die;
                }

            }
        }

       if (!empty($_POST['del'])){
           if ($_POST['del'] == '删除主题'){
               //接收一下input的值
               $title = $_POST['title'];
               //把post传过来的id拼接成字符串
               $id = implode(',',$title);
               //修改isdel的值,修改成1；不显示
               $isdela = ['isdel'=>2];
               $isdel = $isdela['isdel'];
               $data['isdel'] = $isdel;

               $result = $this->admin->table('article')->where("id in($id)")->update($data);
               echo $this->admin->sql;
               if ($result){
                   $msg = '放入成功';
                     $this->notice($msg);
                   die;
               }else{
                   $msg = '放入失败';
                    $this->notice($msg);
                   die;
               }


           }
       }

    }

    public function site_index4_2()
    {
           //查询回帖的数据
           $article = $this->admin->table('article')->field('*')->where("title=''and isdel=0")->select();
           $this->assign('article',$article);
           //查询用户
           $user = $this->admin->table('user')->field('*')->select();
           $this->assign('user',$user);
           //查新板块
           $article1 = $this->admin->table('article,board')->field('*')->where("bid = pid")->select();
           $this->assign('article1',$article1);
           //查询数量
           $article2 = $this->admin->table('article')->field('count(content)')->where("title=''and isdel=0")->select();
           $this->assign('article2',$article2);
           $this->display();
    }
   public function reply()
   {
//接收一下input的值
       $title = $_POST['title'];
       //把post传过来的id拼接成字符串
       $id = implode(',',$title);
       //修改isdel的值,修改成1；不显示
       $isdela = ['isdel'=>1];
       $isdel = $isdela['isdel'];
       $data['isdel'] = $isdel;

       $result = $this->admin->table('article')->where("id in($id)")->update($data);
       echo $this->admin->sql;
       if ($result){
           $msg = '放入成功';
           $this->notice($msg);
           die;
       }else{
           $msg = '放入失败';
           $this->notice($msg);
           die;
       }

   }
    public function site_index4_3()
    {
        //查询回帖的数据
        $article = $this->admin->table('article')->field('*')->where("title=''and isdel=1")->select();
        $this->assign('article',$article);
        //查询用户
        $user = $this->admin->table('user')->field('*')->select();
        $this->assign('user',$user);
        //查新板块
        $article1 = $this->admin->table('article,board')->field('*')->where("bid = pid")->select();
        $this->assign('article1',$article1);
        //查询数量
        $article2 = $this->admin->table('article')->field('count(content)')->where("title=''and isdel=1")->select();
        $this->assign('article2',$article2);
        $this->display();
    }
     public function message()
     {
         if (!empty($_POST['recover'])){
             if ($_POST['recover'] == '恢复主题'){
                 //接收一下input的值
                 $title = $_POST['title'];
                 //把post传过来的id拼接成字符串
                 $id = implode(',',$title);
                 //修改isdel的值,修改成1；不显示
                 $isdela = ['isdel'=>0];
                 $isdel = $isdela['isdel'];
                 $data['isdel'] = $isdel;

                 $result = $this->admin->table('article')->where("id in($id)")->update($data);
                 echo $this->admin->sql;
                 if ($result){
                     $msg = '放入成功';
                     $this->notice($msg);
                     die;
                 }else{
                     $msg = '放入失败';
                     $this->notice($msg);
                     die;
                 }

             }
         }

         if (!empty($_POST['del'])){
             if ($_POST['del'] == '删除主题'){
                 //接收一下input的值
                 $title = $_POST['title'];
                 //把post传过来的id拼接成字符串
                 $id = implode(',',$title);
                 //修改isdel的值,修改成1；不显示
                 $isdela = ['isdel'=>2];
                 $isdel = $isdela['isdel'];
                 $data['isdel'] = $isdel;

                 $result = $this->admin->table('article')->where("id in($id)")->update($data);
                 echo $this->admin->sql;
                 if ($result){
                     $msg = '放入成功';
                     $this->notice($msg);
                     die;
                 }else{
                     $msg = '放入失败';
                     $this->notice($msg);
                     die;
                 }


             }
         }

     }
}
