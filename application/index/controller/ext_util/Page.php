<?php

class pageclass{
    private $total;   //总记录
    private $pagesize;   //每页显示多少条
    private $limit;    //limit
    private $page;    //当前页
    private $pagenum;  //总页码
    private $url;    //地址
    public function __construct($_total,$_pagesize,$page){
        $this->total = $_total ? $_total :1 ;  //总条数
        $this->pagesize = $_pagesize;      //每页显示多少条
        $this->page = $page;          //当前页码
        $this->pagenum = ceil($this->total / $this->pagesize);  //总页码
        $this->limit = "limit ".($this->page-1)*$this->pagesize.",".$this->pagesize;  //分页语句
        $this->url = $this->setUrl();  //地址
    }
    //分页语句
    public function limit(){
        return $this->limit;
    }
    //当前的url地址
    public function setUrl(){
        $server = $_SERVER['REQUEST_URI'];
        $im = explode('/',$server);
        if(empty($_SERVER['PATH_INFO'])){
            $array = array_splice($im,2,0,array('2'=>'index.php'));
        }
        $controller = $im[3];
        $action = $im[4];
        if(empty($_SERVER['PATH_INFO'])){
            $servers = "http://".$_SERVER['HTTP_HOST']."/".$im[1]."/".$controller."/".$action."/";
        }else{
            $servers = "http://".$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']."/".$controller."/".$action."/";
        }
        return $servers;
        //return $im;
    }
    //首页
    public function first(){
        //return $this->page;
        if($this->page > 1){
            return "<a href='".$this->url."page/1'>首页</a>";
        }
    }
    //上一页
    public function prev(){
        if($this->page==1){
            $page = 1;
        }else{
            $page = $this->page-1;
        }
        if($this->page > 1){
            return "<a href='".$this->url."page/".$page."'>上一页</a>";
        }else{
            return "<span class='disabled'>上一页</span>";
        }
    }


    //下一页
    public function next(){
        $page = $this->page+1;
        if($this->page < $this->pagenum){
            if(empty($this->page)){
                $pages = $this->page+2;
                return "<a href='".$this->url."page/".$pages."'>下一页</a>";
            }else{
                return "<a href='".$this->url."page/".$page."'>下一页</a>";
            }
        }else{
            return "<span class='disabled'>下一页</span>";
        }
    }
    //尾页
    public function last(){
        if($this->page < $this->pagenum){
            return "<a href='".$this->url."page/".$this->pagenum."'>尾页</a>";
        }
    }
    //分页
    public function showpage(){


        $page_ = "";
        $page_ .= $this->first();
        $page_ .= " ".$this->prev();
        $page_ .= " ".$this->next();
        $page_ .= " ".$this->last();
        return $page_;
    }
}