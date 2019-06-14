<?php
	
	//后台控制器的父级控制器

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Ci extends CI_Controller
	{

		public $adminInfos=false;
		
		public $ajaxLoginArrays=[];
		
		public $IframeLoginArrays=[];
		
		public $typeInfo;

		public function __construct()
		{
			parent::__construct();
		}


		//获取对应的admin信息
		public function authFindByIdentity($string,&$result)
		{
			
			$array=json_decode($string,true);

			$this->load->model('admins/Admins_model','admin');
			
			if($result=$this->admin->getOneRow($array['id']))
			{
				return $result;	
			}
			
			return false;
		}
		

		//检测admin数据是否合法
		protected function checkAdmin()
		{
			if(!$this->session->has_userdata('PC_Auth_Identity')){return false;}
			
			if(!$this->authFindByIdentity($this->encrypt->decode($this->session->userdata('PC_Auth_Identity')),$result))
			{
				$this->session->unset_userdata('PC_Auth_Identity');

				return false;
			}
			
			return $result;
		}
		

		//做入口检测
		protected function authLogin()
		{
			
			$this->ajaxLoginArrays=array_merge($this->ajaxLoginArrays,$this->ajaxLoginArray);
			$this->IframeLoginArrays=array_merge($this->IframeLoginArrays,$this->IframeLoginArray);

			$controller=strtolower($this->uri->segment(2));
			$function=strtolower($this->uri->segment(3));	
			
			$this->adminInfos=$this->checkAdmin();
			
			$checkRom=$controller.'@'.$function;

			foreach($this->ajaxLoginArrays as $k=>$v)
			{
				$this->ajaxLoginArrays[$k]=strtolower($controller."@".$v);	
			}

			if(in_array($checkRom,$this->ajaxLoginArrays))
			{
				if(!$this->adminInfos)
				{
					ajaxs(200,'login failed');
				}
				return true;
			}
			
			foreach($this->IframeLoginArrays as $k=>$v)
			{
				$this->IframeLoginArrays[$k]=strtolower($controller."@".$v);	
			}

			if(in_array($checkRom,$this->IframeLoginArrays))
			{
				if(!$this->adminInfos)
				{
					iframeshow(200,'login failed');
				}
				return true;
			}
			
			if(!$this->adminInfos)
			{
				header("location:".admin_url()."login/index.html");exit();
			}

			return true;
			
		}
		
		//对选择的项目做检测
		private function checkType()
		{
			if(isset($_COOKIE['webType']))
			{
				$webType=$_COOKIE['webType'];
				$query=$this->db->query("select * from `dc_type` where `id`='$webType'");	
				if($query->num_rows()>0)
				{
					$this->typeInfo=$query->row_array();
					return true;	
				}
				return false;
			}		
			else
			{
				return false;	
			}
		}
		
		//ajax的项目检测
		public function checkTypeAjax()
		{
			$this->checkType()?'':ajaxs(300,'请先选择分类');	
		}
		
		//ajax的项目检测
		public function checkTypeFilter()
		{
			$this->checkType()?'':header('location:'.admin_url().'home/choose');	
		}

	}