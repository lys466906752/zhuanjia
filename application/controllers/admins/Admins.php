<?php
	
	//后台管理员控制器

	defined('BASEPATH') OR exit('No direct script access allowed');

	require 'Ci.php';

	class Admins extends Ci
	{

		public $ajaxLoginArray=['inserts','updates','del'];
		
		public $IframeLoginArray=[];

		public function __construct()
		{
			parent::__construct();

			$this->authLogin();
		}

		//后台管理员主界面
		public function index()
		{
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]=1;

			$this->load->view("admins/admins/index.php",$data);
		}

		//ajax读取管理员列表
		public function indexAjax()
		{
			$pagesize=12;
			$pageindex=intval($this->uri->segment(4));
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$keywords=trim($_REQUEST["keywords"]):$keywords="";
			
			$where="";
			
			if($keywords!="")
			{
				$where.=" and (`username`='$keywords')";
			}
			
			$sql="select * from `".$this->db->dbprefix."admins` where `id`>0 ".$where." order by `login_time` desc";
			
			$this->load->library("pagesclass");
			$sql=$this->pagesclass->indexs($sql,$pagesize,$pagecount,$pageall,$pageindex,$this->db);
			$data["query"]=$this->db->query($sql);
			$data["pagesize"]=$pagesize;
			$data["pagecount"]=$pagecount;
			$data["pageindex"]=$pageindex;
			$data["pageall"]=$pageall;
			$data["keywords"]=$keywords;
			$data["arrs"]=$this->pagesclass->page_number($pagecount,$pageindex);

			$this->load->view("admins/admins/index.ajax.php",$data);
		}

		//添加一条管理员信息
		public function add()
		{
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]=1;

			$this->load->view("admins/admins/add.php",$data);
		}

		//添加一条管理员信息
		public function inserts()
		{
			isset($_POST['username']) && strlen($_POST['username'])<=20?$username=$_POST['username']:ajaxs(300,'参数错误');
			isset($_POST['passwd']) && strlen($_POST['passwd'])>=6  && strlen($_POST['passwd'])<=18?$passwd=$_POST['passwd']:ajaxs(300,'参数错误');

			$this->load->model('admins/Admins_model','admin');

			$res=$this->admin->inserts(['username'=>$username,'passwd'=>$passwd],$msg);

			if($res)
			{
				ajaxs(100,'添加成功');
			}

			ajaxs(300,$msg);

		}

		//修改管理员
		public function edit()
		{
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]=1;

			$id=intval($this->uri->segment(4));

			$this->load->model('admins/Admins_model','admin');

			$data['res']=$this->admin->getOneRow($id);

			if($data['res'])
			{

				$this->load->view('admins/admins/edit.php',$data);
			}
			else
			{
				error('抱歉：没有找到当前这条信息');
			}
		}

		//修改管理员密码
		public function updates()
		{
			$id=intval($this->uri->segment(4));

			isset($_POST['passwd']) && strlen($_POST['passwd'])>=6  && strlen($_POST['passwd'])<=18?$passwd=$_POST['passwd']:ajaxs(300,'参数错误');

			$this->load->model('admins/Admins_model','admin');

			$res=$this->admin->updates(['id'=>$id,'passwd'=>$passwd],$msg);

			if($res)
			{
				ajaxs(100,'修改成功');
			}

			ajaxs(300,$msg);

		}

		//删除管理员信息
		public function del()
		{
			$id=trim($this->input->post('id'),',');

			$this->load->model('admins/Admins_model','admin');

			$res=$this->admin->del($id,$msg);

			if($res)
			{
				ajaxs(100,'删除成功');
			}

			ajaxs(300,$msg);

		}

	}