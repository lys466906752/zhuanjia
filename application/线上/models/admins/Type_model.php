<?php

	//分类表模型

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Type_model extends CI_Model
	{

		public function __construct()
		{
			parent::__construct();
		}

		//定义表名
		private function tableName()
		{

			return $this->db->dbprefix.'type';

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
			
			$query=$this->db->query("select * from `".$this->tableName()."` where `name`='".$data['desc']."' limit 1");
			if($query->num_rows()>0)
			{
				$msg='当前分类名称已存在';
				return false;	
			}
			else
			{
				$msg='';
				$_array=[
					'name'=>$data['desc'],
				];
	
				if($this->db->insert($this->tableName(),$_array))
				{
					return true;
				}
	
				$msg='网络连接失败';
				return false;
			}

		}

		
		//修分类信息
		public function updates($data,&$msg)
		{
			$query=$this->db->query("select * from `".$this->tableName()."` where `name`='".$data['desc']."' and `id`!='".$data['id']."' limit 1");
			if($query->num_rows()>0)
			{
				$msg='当前分类名称已存在';
				return false;	
			}
			else
			{
				$msg='';
				$res=$this->getOneRow($data['id']);
	
				if($res)
				{
	
					$_array=[
						'name'=>trim($data['desc']),
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