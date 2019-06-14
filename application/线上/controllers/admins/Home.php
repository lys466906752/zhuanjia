<?php
	
	//后台主页控制器

	defined('BASEPATH') OR exit('No direct script access allowed');

	require 'Ci.php';

	class Home extends Ci
	{

		public $ajaxLoginArray=['rubbishs','changes'];
		
		public $IframeLoginArray=[];

		public function __construct()
		{
			parent::__construct();

			$this->authLogin();
		}

		//后台主界面
		public function index()
		{
			//查询对应的分类信息
			$data['types']=$this->db->query("select * from `dc_type`");
			$this->load->view("admins/index.php",$data);
		}

		//后台欢迎页
		public function welcome()
		{
			$data['doctor']=$this->db->query("select `id` from `dc_doctors`");
			$data['dict']=$this->db->query("select `id` from `dc_dicts`");

			$this->load->view("admins/welcome.php",$data);
		}

		//清理缓存
		public function rubbishs()
		{
			set_time_limit(0);
			$this->load->helper('cache');
			clearFileCache(FCPATH.'caches/apis');
			ajaxs(100,'success');
		}
		
		//选择站点的设置
		public function choose()
		{
			$data['types']=$this->db->query("select * from `dc_type`");
			$this->load->view("admins/choose.php",$data);	
		}
		
		//切换对应的分类信息
		public function changes()
		{
			$id=intval($this->uri->segment(4));
			setcookie('webType',$id,time()+24*3600*365,'/');
			ajaxs(100,'success');	
		}

		//下载api文件
		public function api()
		{
			$file=FCPATH.'assets/tmp/api.doc';
			header("Content-type:application/octet-stream");
			$filename = basename($file);
			header("Content-Disposition:attachment;filename = ".$filename);
			header("Accept-ranges:bytes");
			header("Accept-length:".filesize($file));
			readfile($file);			
		}
	}