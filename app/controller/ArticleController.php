<?php
/**
 * Created by PhpStorm.
 * User: 徐涛
 * Date: 2018/2/19/019
 * Time: 16:45
 */

namespace controller;
use model\ArticleModel;
use framework\Page;
use framework\Upload;

class ArticleController extends Controller
{
    //定义一个成员属性，保存ArticleModel实例化后的对象
    public $article;
    public function __construct()
    {
        parent::__construct();
        $this->article = new ArticleModel();

    }

   public function blog1()
{
    //接收一下通过get穿过来的id
  @$id = $_GET['id'];

    $this->assign('id',$id);
    $article = $this->article->table('article')->field('*')->where("id=$id")->select();
    $this->assign('article',$article);

    $article1 = $this->article->table('article')->field('content,time')->where("pid=$id")->select();
    $this->assign('article1',$article1);

    //查询大板块
    $bkname = $this->article->table('board')->field('*')->select();
    $bkname1 = $this->article->table('board')->field('*')->where("bid=$id")->select();
    // var_dump($bkname1);
    $this->assign('bkname',$bkname);
    $this->assign('bkname1',$bkname1);

     //查询一下用户头像
    @$head = $this->article->table('user')->field('*')->where("id=$_SESSION[id]")->select();
    $this->assign('head',$head);

    //把页面渲染出来
    $this->display();


}
public function say()
{
    //判断用户是否登录,给与相对的权限
    if(empty($_SESSION['id'])){

        $msg = '抱歉你还未登录, 请登录后再发表';
        $this->notice($msg);
        die;
    }
    //接收版块的id用于写在文章的pid字段中
    $pid = $_GET['id'];

    //标题
    $title = $_POST['title'];
    //内容
    $content = $_POST['content'];
    //对标题和内容进行判断
    if(empty($title)){

        $msg = '标题不能为空,请重新输入';
        $this->notice($msg);
        die;
    }
    if(empty($content)){

        $msg = '发表的内容不能为空, 请重新发表';
        $this->notice($msg);
        die;
    }
    $data['pid'] = $pid;
    var_dump($data['pid']);
    $data['title'] = $title;
    $data['content'] = $content;
    $data['uid'] = $_SESSION['id'];

    $result = $this->article->table('article')->insert($data);

    if($result){

        $msg = '发表成功, 正在跳转详情页';
        $this->notice($msg,"index.php");
        die;

    }

}
public function discuss()
{
    //判断是否登录
    if (empty($_SESSION['id'])){
        $msg = '抱歉你还未登录, 请登录后再评论';
        $this->notice($msg);
        die;
    }
//接收传过来的数据
    //id = pid判断哪个帖子下的回复
  $id = $_GET['id'];
    $content = $_POST['content'];
    //判断内容不能为空
if (empty($content))
{
    $msg = '内容不能为空';
    $this->notice($msg);
    die;
}
$data['pid'] = $id;
$data['content'] = $content;
$data['uid'] = $_SESSION['id'];
$data['title'] = '';
$result = $this->article->table('article')->insert($data);
if ($result){
    $msg = '评论成功';
    $this->notice($msg);
    die;
}

}
  public function picture()
  {
      //先new一个对象
      $pic = new Upload();
      $a = $pic->uploadFile('file');
      $b = $pic->newName;
      $turePath = "$a$b";

      $data['picture'] =$turePath;
      if (empty($_SESSION['id'])){
          $msg = '请先登录';
          $this->notice($msg);
          die;
      }
       $result = $this->article->table('user')->where("id=$_SESSION[id]")->update($data);
        if($result){
            $msg = '上传头像成功';
            $this->notice($msg);
            die;

        }else{
            $msg = '上传头像失败';
            $this->notice($msg);
            die;
        }




  }













}