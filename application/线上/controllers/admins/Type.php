<?php
	
	//分类控制器管理

	defined('BASEPATH') OR exit('No direct script access allowed');

	require 'Ci.php';

	class Type extends Ci
	{

		public $ajaxLoginArray=['inserts','updates','del','changes'];
		
		public $IframeLoginArray=[];


		public function __construct()
		{
			parent::__construct();

			$this->authLogin();
		}

		//分类首页
		public function index()
		{
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]=1;

			$this->load->view("admins/type/index.php",$data);

		}

		//ajax读取字典列表
		public function indexAjax()
		{
			$pagesize=15;
			$pageindex=intval($this->uri->segment(4));
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$keywords=trim($_REQUEST["keywords"]):$keywords="";
			
			$where="";
			
			if($keywords!="")
			{
				$where.=" and (`name`='$keywords')";
			}
			
			$sql="select * from `".$this->db->dbprefix."type` where `id`>0 ".$where." order by `id` desc";
			
			$this->load->library("pagesclass");
			$sql=$this->pagesclass->indexs($sql,$pagesize,$pagecount,$pageall,$pageindex,$this->db);
			$data["query"]=$this->db->query($sql);
			$data["pagesize"]=$pagesize;
			$data["pagecount"]=$pagecount;
			$data["pageindex"]=$pageindex;
			$data["pageall"]=$pageall;
			$data["keywords"]=$keywords;
			$data["arrs"]=$this->pagesclass->page_number($pagecount,$pageindex);

			$this->load->view("admins/type/index.ajax.php",$data);
		}
		
		//添加字典
		public function add()
		{
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]=1;

			$this->load->view("admins/type/add.php",$data);
		}

		//添加字段-程序处理
		public function inserts()
		{
			isset($_POST['desc']) && strlen($_POST['desc'])<=100?$desc=$_POST['desc']:ajaxs(300,'参数错误');

			$this->load->model('admins/Type_model','type');

			$res=$this->type->inserts(['desc'=>$desc],$msg);

			if($res)
			{
				ajaxs(100,'添加成功');
			}

			ajaxs(300,$msg);
		}
		
		//修改字典
		public function edit()
		{
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]=1;

			$id=intval($this->uri->segment(4));

			$this->load->model('admins/Type_model','type');

			$data['res']=$this->type->getOneRow($id);

			if($data['res'])
			{

				$this->load->view('admins/type/edit.php',$data);
				
			}
			else
			{
				error('抱歉：没有找到当前这条信息');
			}			
		}

		//更新字典信息
		public function updates()
		{
			isset($_POST['desc']) && strlen($_POST['desc'])<=100?$desc=$_POST['desc']:ajaxs(300,'参数错误');

			$id=intval($this->uri->segment(4));

			$this->load->model('admins/Type_model','type');

			$res=$this->type->updates(['desc'=>$desc,'id'=>$id],$msg);

			if($res)
			{
				ajaxs(100,'修改成功');
			}

			ajaxs(300,$msg);
		}
		
		//删除字典
		public function del()
		{
			
			$id=trim($this->input->post('id'),',');

			$query_a=$this->db->query("select `id` from `dc_doctors` where `type` in (".$id.")");
			$query_b=$this->db->query("select `id` from `dc_dicts` where `type` in (".$id.")");

			if($query_a->num_rows()<=0 && $query_b->num_rows()<=0)
			{
				$this->load->model('admins/Type_model','type');
	
				$res=$this->type->del($id,$msg);
	
				if($res)
				{
					ajaxs(100,'删除成功');
				}
	
				ajaxs(300,$msg);					
			}
			else
			{
				ajaxs(300,'当前分类下包含字典或医生信息，请先删除后再来操作');	
			}

		}
		
	}
	
	