<?php

	//接口Api信息

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Api extends CI_Controller
	{

		public function __construct()
		{
			parent::__construct();
		}

		public function index()
		{

			$this->load->helper('cache');
			
			isset($_GET['doctorId']) && trim($_GET['doctorId'])!=''?$doctorId=trim($_GET['doctorId']):$doctorId='';
			isset($_GET['selectItem']) && trim($_GET['selectItem'])!=''?$selectItem=trim($_GET['selectItem'],','):$selectItem=[];

			$selectItem=$this->getDicts($selectItem);//获取一下字段信息
			//print_r($selectItem);
			if($doctorId!='')
			{
				$arr=explode(',',$doctorId);
				$res=[];
				$r=0;
				foreach($arr as $id)
				{
					if($id!='' && is_numeric($id))
					{
						//获取一下缓存数据，没有的话，直接通过查询get到
						$resCacheArr=readFileCaches($id,1296000);
						if($resCacheArr)
						{
							$selectArray=$resCacheArr;
							$res[$r]=[];
							$res[$r]=array_merge($res[$r],$selectArray);
							$res[$r]['id']=$selectArray['id'];
							$res[$r]['realname']=$selectArray['realname'];
							$res[$r]['state']=2;
							$r++;
						}
						else
						{
							
							$query=$this->db->query('select * from `dc_doctors` where `id`="'.$id.'" and `show`=1 ');
							if($query->num_rows()>0)
							{
								$selectArray=$query->row_array();
								$res[$r]=$selectArray;
								$res[$r]=array_merge($res[$r],createJsonItem($selectArray['json'],$selectItem));
								$res[$r]['id']=$selectArray['id'];
								$res[$r]['realname']=$selectArray['realname'];
								$res[$r]['state']=2;
								if(isset($res[$r]['json']))
								{
									unset($res[$r]['json']);
								}
								writeFileCacheContent($res[$r],$id);	
								$r++;
							}	
							else
							{
								$res[$r]['id']=$id;
								$res[$r]['realname']='';
								$res[$r]['state']=1;
								$r++;		
							}
						}
					}
				}
				
				echo jsonp_show(['code'=>100,'result'=>$res]);
			}
			else
			{
				$selectSql='select * from `dc_doctors` where `show`=1 ';
				$resArray=[];
				$query=$this->db->query($selectSql);
				$i=0;
				foreach($query->result_array() as $selectArray)
				{
					$resArray[$i]=getSelectItem($selectArray['json'],$selectItem);
					$resArray[$i]['id']=$selectArray['id'];
					$resArray[$i]['realname']=$selectArray['realname'];
					$res[$r]['state']=2;
					$i++;
				}
				
				echo jsonp_show(['code'=>100,'result'=>$res]);				
			}
			
		}
		
		
		public function home()
		{
			$this->load->helper('cache');
			
			isset($_GET['doctorId']) && trim($_GET['doctorId'])!=''?$doctorId=trim($_GET['doctorId']):$doctorId='';
			isset($_GET['selectItem']) && trim($_GET['selectItem'])!=''?$selectItem=trim($_GET['selectItem'],','):$selectItem=[];

			$selectItem=$this->getDicts($selectItem);//获取一下字段信息
			//print_r($selectItem);
			if($doctorId!='')
			{
				$arr=explode(',',$doctorId);
				$res=[];
				$r=0;
				foreach($arr as $id)
				{
					if($id!='' && is_numeric($id))
					{
						//获取一下缓存数据，没有的话，直接通过查询get到
						$resCacheArr=readFileCaches($id,7200);
						if($resCacheArr)
						{
							$selectArray=$resCacheArr;
							$res[$r]=[];
							$res[$r]=array_merge($res[$r],$selectArray);
							$res[$r]['id']=$selectArray['id'];
							$res[$r]['realname']=$selectArray['realname'];
							$r++;
						}
						else
						{
							
							$query=$this->db->query('select * from `dc_doctors` where `id`="'.$id.'" and `show`=1 ');
							if($query->num_rows()>0)
							{
								$selectArray=$query->row_array();
								$res[$r]=createJsonItem($selectArray['json'],$selectItem);
								$res[$r]['id']=$selectArray['id'];
								$res[$r]['realname']=$selectArray['realname'];
								writeFileCacheContent($res[$r],$id);	
								$r++;
							}	
							else
							{
								$res[$r]['id']='';
								$res[$r]['realname']='';
								$r++;		
							}
						}
					}
				}
				
				echo jsonp_show(['code'=>100,'result'=>$res]);
			}
			else
			{
				$selectSql='select * from `dc_doctors` where `show`=1 ';
				$resArray=[];
				$query=$this->db->query($selectSql);
				$i=0;
				foreach($query->result_array() as $selectArray)
				{
					$resArray[$i]=getSelectItem($selectArray['json'],$selectItem);
					$resArray[$i]['id']=$selectArray['id'];
					$resArray[$i]['realname']=$selectArray['realname'];
					$i++;
				}
				
				echo jsonp_show(['code'=>100,'result'=>$res]);				
			}


		}
		
		//获取对应的字典信息
		private function getDicts($selectItem)
		{
			$selectRes=$selectItem;
			$dictsQuery=$this->db->query("select * from `dc_dicts`");
			$selectJsonItem=[];//设置一个查询的字段集合
			$arr=[];
			foreach($dictsQuery->result_array() as $dictsArray)
			{
				$selectJsonItem[]=$dictsArray['field'];
				$k=$dictsArray['field'];
				$arr[$k]=0;
			}
			
			$selectItem=$arr;
			
			if(!is_array($selectRes) && !empty($selectRes))
			{
				
				$selectItem=explode(',',$selectRes);
				$arr=[];
				$selectJsonItem=[];
				foreach($selectItem as $v)
				{
					if(substr_count($v,'|')>0)
					{
						$exp=explode('|',$v);
						$key=$exp[0];
						$arr[$key]=$exp[1];
						$selectJsonItem[]=$exp[0];
					}
					else
					{
						$arr[$v]=0;
						$selectJsonItem[]=$v;
					}
				}
			
				$selectItem=$arr;
			}	
				
			return $selectJsonItem;	
		}

		public function demo()
		{
			$this->load->view('demo.php');
		}

	}

	//重新自定义方法
	function createJsonItem($res,$model)
	{
		$arr=[];
		foreach($model as $k=>$v)
		{
			$arr[$v]='';
		}
		$arrayRes=json_decode($res,true);
		foreach($arrayRes as $k=>$v)
		{
			//if(isset($arr[$k]))
			//{
				$arr[$k]=$v["value"];	
			//}
			//print_r($arrayRes[$k]);	
		}
		return $arr;
	}