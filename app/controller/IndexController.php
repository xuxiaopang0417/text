<?php
namespace controller;
use model\IndexModel;
use framework\Page;
class IndexController extends Controller
{
   public $index;
   public function __construct()
   {
       parent::__construct();
       $this->index = new IndexModel();
   }


    public function login()
	{
		echo '我是登陆的方法<br />';
	}
	public function index()
    {

        //进行分页查询，查询数据库中总数据的条数,在这里只需要查询数据标题的条数
        $paging = $this->index->table('article')->field('count(title)')->where('title!=""')->select();
        //得到总条数
        $number = $paging[0]['count(title)'];
       //得到页码的总页码数
        $page = new Page(5,$number);
        //获取url地址 ,获取首页，上一页，下一页，尾页
        $url = $page->allPage();
        //获取一下limit
        $limit = $page->limit();
        $this->assign('url',$url);
        //文章的全部详情
        $detail = $this->index->table('article')->field('*')->where('title!=""and isdel=0')->limit($limit)->select();

        $this->assign('detail',$detail);
        $this->display();
    }
	
	
	
	
}