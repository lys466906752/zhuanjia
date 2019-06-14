<?php
	
	//后台上传控制器

	defined('BASEPATH') OR exit('No direct script access allowed');

	require 'Ci.php';

	class Uploads extends Ci
	{

		public $ajaxLoginArray=[];
		
		public $IframeLoginArray=['index'];

		public function __construct()
		{
			parent::__construct();

			$this->authLogin();
		}


		//上传图片处理
		public function index()
		{

			$field=trim($_GET['field']);
			$fieldItem='file_'.$field;

			$this->load->library("imgsclass");
			$file=$this->imgsclass->upload($_FILES[$fieldItem],$msg,'',["cutState"=>true,'cutMaxWidth'=>1500,'cutMaxHeight'=>1500]);	
			if($file)
			{
				iframeshow(100,$file.'|'.$field);
			}
			else
			{
				iframeshow(300,$msg.'|'.$field);	
			}

		}
	}