<?php
	
	//后台主页登录控制器

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Login extends CI_Controller
	{

		public function __construct()
		{
			parent::__construct();
		}


		//登录主页面
		public function index()
		{

			if(isset($_POST['username']) && trim($_POST['username'])!='')
			{
				//是否post请求提交
				$username=P('username');
				$passwd=P('passwd');
				$csrfLogin=P('csrfLogin');
				//载入model层
				if(!check_csrfToken($this->encrypt,$csrfLogin))
				{
					ajaxs(300,'网页数据读取失败，请刷新重试');
				}

				$this->load->model('admins/Admins_model','admins');
				$res=$this->admins->logins($username,$passwd);

				if($res)
				{
					ajaxs(100,'登录成功');
				}

				ajaxs(300,'用户名或密码错误！');
			}
			else
			{

				//映射模板
				$this->load->view('admins/login.php');
			}

		}


		//登出方法
		public function outs()
		{
			unset($_SESSION['PC_Auth_Identity']);
			header('location:'.admin_url().'login/index.html');
			exit();	
		}

		public function downloadtmp()
		{
			isset($_GET['id']) && trim($_GET['id'])!='' && intval($_GET['id'])!=0?$id=intval($_GET['id']):exit();
			$this->load->model('admins/Dicts_model','dicts');
			$data['query']=$this->db->query("select * from `dc_dicts` where `type`='$id' order by `sort` asc");

			$this->load->view('admins/dicts/download.template.php',$data);
		}

	}