<?php
	
	//字典管理控制器

	defined('BASEPATH') OR exit('No direct script access allowed');

	require 'Ci.php';

	class Dicts extends Ci
	{

		public $ajaxLoginArray=['inserts','updates','del','changes'];
		
		public $IframeLoginArray=[];


		public function __construct()
		{
			parent::__construct();

			$this->authLogin();
		}

		//字典首页
		public function index()
		{
			$this->checkTypeFilter();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]=1;

			$this->load->view("admins/dicts/index.php",$data);

		}

		//ajax读取字典列表
		public function indexAjax()
		{
			$this->checkTypeAjax();
			$pagesize=25;
			$pageindex=intval($this->uri->segment(4));
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$keywords=trim($_REQUEST["keywords"]):$keywords="";
			
			$where="";
			
			if($keywords!="")
			{
				$where.=" and (`desc`='$keywords' or `field`='$keywords')";
			}
			
			$sql="select * from `".$this->db->dbprefix."dicts` where `type`='".$_COOKIE['webType']."' ".$where." order by `sort` asc";
			
			$this->load->library("pagesclass");
			$sql=$this->pagesclass->indexs($sql,$pagesize,$pagecount,$pageall,$pageindex,$this->db);
			$data["query"]=$this->db->query($sql);
			$data["pagesize"]=$pagesize;
			$data["pagecount"]=$pagecount;
			$data["pageindex"]=$pageindex;
			$data["pageall"]=$pageall;
			$data["keywords"]=$keywords;
			$data["arrs"]=$this->pagesclass->page_number($pagecount,$pageindex);

			$this->load->view("admins/dicts/index.ajax.php",$data);
		}

		//添加字典
		public function add()
		{
			$this->checkTypeFilter();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]=1;

			$this->load->view("admins/dicts/add.php",$data);
		}

		//添加字段-程序处理
		public function inserts()
		{
			$this->checkTypeAjax();
			isset($_POST['desc']) && strlen($_POST['desc'])<=100?$desc=$_POST['desc']:ajaxs(300,'参数错误');
			isset($_POST['field']) && strlen($_POST['field'])<=100?$field=$_POST['field']:ajaxs(300,'参数错误');
			isset($_POST['model']) && in_array($_POST['model'],[1,2,3])?$model=$_POST['model']:ajaxs(300,'参数错误');
			isset($_POST['type']) && strlen($_POST['type'])<=100?$type=$_POST['type']:ajaxs(300,'参数错误');

			$this->load->model('admins/Dicts_model','dicts');

			$res=$this->dicts->inserts(['desc'=>$desc,'field'=>$field,'model'=>$model,'type'=>$type],$msg);

			if($res)
			{
				ajaxs(100,'添加成功');
			}

			ajaxs(300,$msg);
		}

		//修改字典
		public function edit()
		{
			$this->checkTypeFilter();
			isset($_REQUEST["keywords"]) && trim($_REQUEST["keywords"])!=""?$data["keywords"]=trim($_REQUEST["keywords"]):$data["keywords"]="";
			isset($_REQUEST["pageindex"]) && trim($_REQUEST["pageindex"])!=""?$data["pageindex"]=trim($_REQUEST["pageindex"]):$data["pageindex"]=1;

			$id=intval($this->uri->segment(4));

			$this->load->model('admins/Dicts_model','dicts');

			$data['res']=$this->dicts->getOneRow($id);

			if($data['res'])
			{

				$this->load->view('admins/dicts/edit.php',$data);
				
			}
			else
			{
				error('抱歉：没有找到当前这条信息');
			}			
		}

		//更新字典信息
		public function updates()
		{
			$this->checkTypeAjax();
			isset($_POST['desc']) && strlen($_POST['desc'])<=40?$desc=$_POST['desc']:ajaxs(300,'参数错误');
			isset($_POST['field']) && strlen($_POST['field'])<=20?$field=$_POST['field']:ajaxs(300,'参数错误');
			isset($_POST['model']) && trim($_POST['model'])!=''?$model=$_POST['model']:ajaxs(300,'参数错误');
			isset($_POST['sorts']) && trim($_POST['sorts'])!=''?$sort=$_POST['sorts']:ajaxs(300,'参数错误');

			$id=intval($this->uri->segment(4));

			$this->load->model('admins/Dicts_model','dicts');

			//print_r(createDoctorJson($jsonStr));die();

			$res=$this->dicts->updates(['desc'=>$desc,'field'=>$field,'model'=>$model,'id'=>$id,'sort'=>$sort],$msg);

			if($res)
			{
				ajaxs(100,'修改成功');
			}

			ajaxs(300,$msg);
		}

		//删除字典
		public function del()
		{
			$this->checkTypeAjax();
			
			$id=trim($this->input->post('id'),',');

			$this->load->model('admins/Dicts_model','dicts');

			$res=$this->dicts->del($id,$msg);

			if($res)
			{
				ajaxs(100,'删除成功');
			}

			ajaxs(300,$msg);
		}

		//下载字典
		public function downloads()
		{
			$contents=file_get_contents(admin_url().'login/downloadtmp?id='.$_COOKIE['webType']);
			$wordFile=fopen(FCPATH.'assets/tmp/dicts.'.$_COOKIE['webType'].'.doc','w');
			fwrite($wordFile,$contents);
			fclose($wordFile);
			$this->downLoadOpen(FCPATH.'assets/tmp/dicts.'.$_COOKIE['webType'].'.doc');
		}


		//上移动
		public function ups()
		{
			$this->checkTypeAjax();
			$id=intval($this->input->post("id"));
			$sql="select * from `".$this->db->dbprefix."dicts` where `type`='".$_COOKIE['webType']."' order by `sort` asc";
			$query=$this->db->query($sql);
			if($query->num_rows()>0){
				$result=$query->row_array();
				if($result["id"]==$id){
					//第一个id就是所选排序第一id，无需上移
					ajaxs(100,'success');
				}else{
					$i=0;
					$nid="";
					foreach($query->result_array() as $array){
						if($array["id"]!=$id){
							$nid=$array["id"];
							$this->db->query("update `".$this->db->dbprefix."dicts` set `sort`='$i' where `id`='".$array["id"]."'");	
						}else{
							//找到了对应的id信息了，前后的sort进行处理
							$i1=$i-1;
							$this->db->query("update `".$this->db->dbprefix."dicts` set `sort`='$i1' where `id`='".$array["id"]."'");
							$this->db->query("update `".$this->db->dbprefix."dicts` set `sort`='$i' where `id`='".$nid."'");	
							$nid=$array["id"];
						}
						$i++;	
					}
					ajaxs(100,'success');	
				}
			}
		}
				
		//下移动
		public function downs($id)
		{
			$this->checkTypeAjax();
			$id=intval($this->input->post("id"));
			$sql="select * from `".$this->db->dbprefix."dicts` where `type`='".$_COOKIE['webType']."' order by `sort` desc";
			$query=$this->db->query($sql);
			if($query->num_rows()>0){
				$result=$query->row_array();
				if($result["id"]==$id){
					//第一个id就是所选排序第一id，无需上移
					ajaxs(100,'success');
				}else{
					$i=$query->num_rows();
					$nid="";
					foreach($query->result_array() as $array){
						if($array["id"]!=$id){
							$nid=$array["id"];
							$this->db->query("update `".$this->db->dbprefix."dicts` set `sort`='$i' where `id`='".$array["id"]."'");	
						}else{
							//找到了对应的id信息了，前后的sort进行处理
							$i1=$i+1;
							$this->db->query("update `".$this->db->dbprefix."dicts` set `sort`='$i1' where `id`='".$array["id"]."'");
							$this->db->query("update `".$this->db->dbprefix."dicts` set `sort`='$i' where `id`='".$nid."'");
							$nid=$array["id"];
						}
						$i--;
					}
					ajaxs(100,'success');	
				}
			}		
		}		
		

		private function downLoadOpen($file)
		{

			header("Content-type:application/octet-stream");
			$filename = basename($file);
			header("Content-Disposition:attachment;filename = ".$filename);
			header("Accept-ranges:bytes");
			header("Accept-length:".filesize($file));
			readfile($file);

		}
	}