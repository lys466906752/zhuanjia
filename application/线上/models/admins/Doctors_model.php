<?php

	//医生表模型

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Doctors_model extends CI_Model
	{

		public function __construct()
		{
			parent::__construct();
		}

		//定义表名
		private function tableName()
		{

			return $this->db->dbprefix.'doctors';

		}

		//查询单条数据集根据ID
		public function getOneRow($id)
		{

			$query=$this->db->query("select * from `".$this->tableName()."` where `id`='$id' limit 1");

			if($query->num_rows()>0)
			{
				return $query->row_array();
			}	

			return false;
		}

		//添加方法
		public function inserts($data,&$msg)
		{
			$msg='';
			
			$_array=[
				'realname'=>$data['realname'],
				'type'=>$data['rooms'],
				'show'=>$data['show']
			];

			if($this->db->insert($this->tableName(),$_array))
			{
				return true;
			}

			$msg='网络连接失败';
			return false;

		}

		//更新显示与隐藏状态
		public function changes($data,&$msg)
		{
			$msg='';
			
			$_array=[
				'show'=>$data['act']
			];

			if($this->db->update($this->tableName(),$_array,['id'=>$data['id']]))
			{
				return true;
			}

			$msg='网络连接失败';
			return false;
		}

		//修改医生信息
		public function updates($data,&$msg)
		{
			$msg='';
			$res=$this->getOneRow($data['id']);

			if($res)
			{

				$_array=[
					'realname'=>trim($data['realname']),
					'show'=>trim($data['show']),
					'json'=>json_encode($data['json']),
					'time'=>time()
				];

				if($this->db->update($this->tableName(),$_array,['id'=>$data['id']]))
				{
					return true;
				}

				$msg='网络连接失败';
				return false;

			}

			$msg='没有找到这条信息';
			return false;			
		}

		//删除方法
		public function del($id,&$msg)
		{

			$msg='';

			if($this->db->query("delete from `".$this->tableName()."` where `id` in(".$id.") "))
			{
				return true;
			}

			$msg='网络连接失败，请稍后再试';
			return false;
		}


	}