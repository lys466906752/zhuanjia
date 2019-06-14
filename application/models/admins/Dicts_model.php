<?php

	//字典表模型

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Dicts_model extends CI_Model
	{

		public function __construct()
		{
			parent::__construct();
		}

		//定义表名
		private function tableName()
		{

			return $this->db->dbprefix.'dicts';

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

		//查询单条数据集根据ID
		public function getOneRowForWhere($where)
		{
			$query=$this->db->query("select * from `".$this->tableName()."` where ".$where." limit 1");

			if($query->num_rows()>0)
			{
				return $query->row_array();
			}	

			return false;
		}

		//查询所有的字典信息
		public function selectAll()
		{
			return $this->db->query("select * from `".$this->tableName()."` order by `sort` asc");
		}

		//添加方法
		public function inserts($data,&$msg)
		{
			$msg='';
			$res=$this->getOneRowForWhere("`field`='".$data['field']."'");
			if(!$res)
			{
				$_array=[
					'desc'=>$data['desc'],
					'field'=>$data['field'],
					'model'=>$data['model'],
					'type'=>$data['type']
				];

				if($this->db->insert($this->tableName(),$_array))
				{
					$id=$this->db->insert_id();
					$this->db->query("update `".$this->tableName()."` set `sort`='$id' where `id`='$id'");
					return true;
				}

				$msg='网络连接失败';
				return false;

			}

			$msg='字段名称(英文)已存在，请更换';
			return false;

		}

		//修改方法
		public function updates($data,&$msg)
		{

			$msg='';
			$res=$this->getOneRow($data['id']);

			if($res)
			{

				$_array=[
					'desc'=>trim($data['desc']),
					'field'=>trim($data['field']),
					'model'=>trim($data['model']),
					'sort'=>trim($data['sort'])
				];

				if($this->db->update($this->tableName(),$_array,['id'=>$data['id']]))
				{

					if($res['field']!=$_array['field'])
					{

						//更新对应的医生表
						$query=$this->db->query("select * from `".$this->db->dbprefix."doctors`");
						
						foreach($query->result_array() as $array)
						{

							$_array=[
								'json'=>json_encode(changeJsonFile($array['json'],$res['field'],$_array['field'])),
							];

							$this->db->update('doctors',$_array,['id'=>$array['id']]);
						}
					}

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

			$query=$this->db->query("select * from `".$this->tableName()."` where `id` in (".$id.")");

			$arrs=[];

			foreach($query->result_array() as $array)
			{
				$this->db->query("delete from `".$this->tableName()."` where `id`='".$array['id']."'");
				$arrs[]=$array['field'];
			}

			//更新对应的医生表
			$query=$this->db->query("select * from `".$this->db->dbprefix."doctors`");

			foreach($query->result_array() as $array)
			{

				$_array=[
					'json'=>json_encode(unsetJsonFile($array['json'],$arrs)),
				];

				$this->db->update('doctors',$_array,['id'=>$array['id']]);
			}			

			$msg='删除成功';
			return true;
		}


	}