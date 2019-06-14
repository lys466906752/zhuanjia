<?php
	
	//医生管理控制器

	defined('BASEPATH') OR exit('No direct script access allowed');

	require 'Ci.php';

	class Doctors extends Ci
	{

		public $ajaxLoginArray=['inserts','updates','del','changes'];
		
		public $IframeLoginArray=[];


		public function __construct()
		{
			parent::__construct();

			$this->authLogin();
		}


		//医生列表
		public function index()
		{
			$this->checkTypeFilter();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]=1;

			$this->load->view("admins/doctors/index.php",$data);
		}

		//医生列表ajax
		public function indexAjax()
		{
			$this->checkTypeAjax();
			$pagesize=12;
			$pageindex=intval($this->uri->segment(4));
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$keywords=trim($_REQUEST["keywords"]):$keywords="";
			
			$where="";
			
			if($keywords!="")
			{
				$where.=" and (`realname`='$keywords')";
			}
			
			$sql="select * from `".$this->db->dbprefix."doctors` where `type`='".$_COOKIE['webType']."' ".$where." order by `id` desc";
			
			//echo $sql;
			
			$this->load->library("pagesclass");
			$sql=$this->pagesclass->indexs($sql,$pagesize,$pagecount,$pageall,$pageindex,$this->db);
			$data["query"]=$this->db->query($sql);
			$data["pagesize"]=$pagesize;
			$data["pagecount"]=$pagecount;
			$data["pageindex"]=$pageindex;
			$data["pageall"]=$pageall;
			$data["keywords"]=$keywords;
			$data["arrs"]=$this->pagesclass->page_number($pagecount,$pageindex);

			$this->load->view("admins/doctors/index.ajax.php",$data);
		}

		//添加医生
		public function add()
		{
			$this->checkTypeFilter();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]=1;
			isset($_REQUEST["rooms"]) && trim($_REQUEST["rooms"])!=""?$data["rooms"]=trim($_REQUEST["rooms"]):$data["rooms"]='';
			$this->load->view("admins/doctors/add.php",$data);
		}

		//添加医生，程序处理
		public function inserts()
		{
			$this->checkTypeAjax();
			isset($_POST['realname']) && strlen($_POST['realname'])<=40?$realname=$_POST['realname']:ajaxs(300,'参数错误');
			isset($_POST['show']) && strlen($_POST['show'])<=20?$show=$_POST['show']:$show=1;
			isset($_POST['rooms']) && strlen($_POST['rooms'])<=40?$rooms=$_POST['rooms']:ajaxs(300,'参数错误');

			$this->load->model('admins/Doctors_model','doctors');

			$res=$this->doctors->inserts(['realname'=>$realname,'show'=>$show,'rooms'=>$rooms],$msg);

			if($res)
			{
				ajaxs(100,'添加成功');
			}

			ajaxs(300,$msg);
		}

		//修改医生的显示状态
		public function changes()
		{
			isset($_POST['act']) && in_array($_POST['act'],[1,2])?$act=$_POST['act']:ajaxs(300,'参数错误');
			isset($_POST['id']) && is_numeric($_POST['id'])?$id=intval($_POST['id']):ajaxs(300,'参数错误');

			$this->load->model('admins/Doctors_model','doctors');

			$res=$this->doctors->changes(['id'=>$id,'act'=>$act],$msg);

			if($res)
			{
				ajaxs(100,'设置成功');
			}

			ajaxs(300,$msg);
		}

		//修改医生的详细信息
		public function edit()
		{

			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]=1;
			isset($_REQUEST["rooms"]) && trim($_REQUEST["rooms"])!=""?$data["rooms"]=trim($_REQUEST["rooms"]):$data["rooms"]='';

			$id=intval($this->uri->segment(4));

			$this->load->model('admins/Doctors_model','doctors');

			$data['res']=$this->doctors->getOneRow($id);

			if($data['res'])
			{

				$this->load->model('admins/Dicts_model','dicts');
				$data['dicts']=$this->db->query("select * from `dc_dicts` where `type`='".$_COOKIE['webType']."' order by `sort` asc");

				$this->load->view('admins/doctors/edit.php',$data);
				
			}
			else
			{
				error('抱歉：没有找到当前这条信息');
			}

		}

		//修改医生信息
		public function updates()
		{
			isset($_POST['realname']) && strlen($_POST['realname'])<=40?$realname=$_POST['realname']:ajaxs(300,'参数错误');
			isset($_POST['show']) && strlen($_POST['show'])<=20?$show=$_POST['show']:$show=1;
			isset($_POST['jsonStr']) && trim($_POST['jsonStr'])!=''?$jsonStr=$_POST['jsonStr']:$jsonStr='';
			isset($_POST['rooms']) && strlen($_POST['rooms'])<=40?$rooms=$_POST['rooms']:ajaxs(300,'参数错误');

			$id=intval($this->uri->segment(4));

			$this->load->model('admins/Doctors_model','doctors');

			//print_r(createDoctorJson($jsonStr));die();

			$res=$this->doctors->updates(['realname'=>$realname,'show'=>$show,'json'=>createDoctorJson($jsonStr),'id'=>$id,'rooms'=>$rooms],$msg);

			if($res)
			{
				ajaxs(100,'修改成功');
			}

			ajaxs(300,$msg);

		}

		//删除医生信息
		public function del()
		{

			$id=trim($this->input->post('id'),',');

			$this->load->model('admins/Doctors_model','doctors');

			$res=$this->doctors->del($id,$msg);

			if($res)
			{
				ajaxs(100,'删除成功');
			}

			ajaxs(300,$msg);

		}


	}

