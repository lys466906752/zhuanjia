<?php

	//管理员表模型层

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Admins_model extends CI_Model
	{

		public function __construct()
		{
			parent::__construct();
		}

		//定义表名
		private function tableName()
		{

			return $this->db->dbprefix.'admins';

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

		//查询单条数据集
		public function getOneRowForUsername($username)
		{
			$query=$this->db->query("select * from `".$this->tableName()."` where `username`='$username' limit 1");

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
			$res=$this->getOneRowForUsername($data['username']);
			if(!$res)
			{
				$_array=[
					'username'=>$data['username'],
					'passwd'=>sha1($data['passwd']),
					'login_time'=>0,
					'login_ip'=>'',
					'counts'=>0
				];

				if($this->db->insert($this->tableName(),$_array))
				{
					return true;
				}
				$msg='网络连接失败';
				return false;
			}

			$msg='用户名已存在';
			return false;
		}

		//修改方法
		public function updates($data,&$msg)
		{
			$msg='';
			$res=$this->getOneRow($data['id']);

			if($res)
			{
				if($res['passwd']!=sha1($data['passwd']))
				{
					$_array=[
						'passwd'=>sha1($data['passwd'])
					];

					if($this->db->update($this->tableName(),$_array,['id'=>$data['id']]))
					{
						return true;
					}

					$msg='网络连接失败';
					return false;

				}

				$msg='密码不能与当前密码一致';
				return false;
			}

			$msg='没有找到这条信息';
			return false;
		}

		//删除方法
		public function del($id,&$msg)
		{

			$msg='';

			if($this->db->query("delete from `".$this->tableName()."` where `id` in(".$id.") and `username`!='root' "))
			{
				return true;
			}

			$msg='网络连接失败，请稍后再试';
			return false;
		}


		//登录方法
		public function logins($username,$passwd)
		{

			$res=$this->getOneRowForUsername($username);
			if($res)
			{
				if($res['passwd']!=sha1($passwd))
				{
					return false;
				}

				$_array=[
					'login_time'=>time(),
					'login_ip'=>get_ip(),
					'counts'=>$res['counts']+1
				];

				$this->db->update($this->tableName(),$_array,['id'=>$res['id']]);

				//写入到对应的session文件中

				$res=array_merge($res,$_array);

				$this->session->set_userdata('PC_Auth_Identity',$this->encrypt->encode(json_encode($res)));

				return $res;
			}
			
			return false;
		}

	}