<?php


	//会员消息方法
	if(! function_exists('getMsgCount'))
	{
		function getMsgCount($sid,$uid)
		{
			$fileDir=FCPATH.'msg/'.$sid.'/'.$uid.'.count.php';
			if(is_file($fileDir))
			{
				require $fileDir;
				return $noRead;
			}
			else
			{
				//写入一个初始化的数字到统计记录中
				writeMsgCount($sid,$uid,0);
				return 0;	
			}
		}	
	}
	
	//对相应的消息文件中写相应的数量信息
	if(! function_exists('writeMsgCount'))
	{
		function writeMsgCount($sid,$uid,$int)
		{
			createDir($sid);
			$fileDir=FCPATH.'msg/'.$sid.'/'.$uid.'.count.php';
			$fileOpen=fopen($fileDir,'w');
			$fileRes="<?php";
			$fileRes.="\n";
			$fileRes.="	//auth:recson";
			$fileRes.="\n";
			$fileRes.="	//updateTime:".date('Y-m-d H:i:s');
			$fileRes.="\n";
			$fileRes.='	$noRead="';
			$fileRes.=$int.'";';
			$fileRes.="\n";
			$fileRes.="?>";
			
			if(fwrite($fileOpen,$fileRes))
			{
				fclose($fileOpen);
				
				clearMembersMsg($sid,$uid);//自动执行一下清理
				
				return true;
			}
			fclose($fileOpen);
			return false;	
		}	
	}
	
	//按照最大配置清理数据信息
	if(! function_exists('clearMembersMsg'))
	{
		function clearMembersMsg($sid,$uid)
		{
			$count=getMsgCount($sid,$uid);	
			$conf=select_config('site');
			$countMax=$conf['maxMsgCountSave'];
			if($countMax<$count)
			{
				//执行清理操作
				$fileDir=FCPATH.'msg/'.$sid.'/'.$uid.'.item.php';
				if(is_file($fileDir))
				{
					require $fileDir;
					if($lists)
					{
						$res=array();
						$r=0;
						$array=json_decode($lists,true);
						for($i=0;$i<count($array);$i++)
						{
							$res[$r][0]=$array[$i][0];
							if($r>=$countMax)
							{
								writeMsgFile($sid,$uid,$res,1);	
							}
							$r++;
							
						}
					}
				}
			}
		}	
	}
	
	//创建对应的站点文件夹信息
	if(! function_exists('createDir'))
	{
		function createDir($sid)
		{
			if(!is_dir(FCPATH.'msg/'.$sid))
			{
				if(mkdir(FCPATH.'msg/'.$sid))
				{
					return true;	
				}
				return false;	
			}
			return true;				
		}	
	}
	
	
	//会员写入消息
	if(! function_exists('writeMsg'))
	{
		function writeMsg($sid,$uid,$array)
		{
			$fileDir=FCPATH.'msg/'.$sid.'/'.$uid.'.item.php';
			if(is_file($fileDir))
			{
				require $fileDir;	
				
				if(isset($lists))
				{
					//往数组的最左边入赘一个数组
					$arrays=json_decode(($lists),true);
					
					//在数组最左边入赘
					array_unshift($arrays,$array);
					
					return writeMsgFile($sid,$uid,$arrays);
				}
				else
				{
					$arr=array(
						"0"=>$array
					);
					return writeMsgFile($sid,$uid,$arr);	
				}
			}
			else
			{
				$arr=array(
					"0"=>$array
				);
				return writeMsgFile($sid,$uid,$arr);
			}
		}	
	}
	
	//往会员消息文件中写数据
	if(! function_exists('writeMsgFile'))
	{
		function writeMsgFile($sid,$uid,$arrays,$add='')
		{
			createDir($sid);
			$fileDir=FCPATH.'msg/'.$sid.'/'.$uid.'.item.php';
			$fileOpen=fopen($fileDir,'w');
			$fileRes="<?php";
			$fileRes.="\n";
			$fileRes.="	//auth:recson";
			$fileRes.="\n";
			$fileRes.="	//updateTime:".date('Y-m-d H:i:s');
			$fileRes.="\n";
			$fileRes.='	$lists="';
			$fileRes.=addslashes(json_encode($arrays)).'";';
			$fileRes.="\n";
			$fileRes.="?>";
			
			if(fwrite($fileOpen,$fileRes))
			{
				fclose($fileOpen);
				
				if($add=='')
				{
					//记录一个新的消息提醒到会员存储中
					$count=getMsgCount($sid,$uid);
					$count=$count+1;
					writeMsgCount($sid,$uid,$count);
					//结束记录新消息的提醒
				}
				
				return true;
			}
			fclose($fileOpen);
			return false;				
		}	
	}
	
	//删除制定编号消息
	if(! function_exists('deleteMsg'))
	{
		function deleteMsg($sid,$id,$uid)
		{
			$arr=explode(',',$id);
			createDir($sid);
			$fileDir=FCPATH.'msg/'.$sid.'/'.$uid.'.item.php';
			if(is_file($fileDir))
			{
				require $fileDir;
				if(isset($lists))
				{
					$res=array();
					$r=0;
					$array=json_decode($lists,true);
					for($i=0;$i<count($array);$i++)
					{
						if(!in_array($array[$i][0]['id'],$arr))
						{
							$res[$r][0]=$array[$i][0];
							$r++;
						}	
					}
					writeMsgFile($sid,$uid,$res,1);
				}
			}
		}	
	}
	
	//查询对应的消息翻页信息
	if(! function_exists('selectMsgPagination'))
	{
		function selectMsgPagination($face,$sid,$uid,$pageindex,$pagesize,&$pageall,&$pagecount)
		{
			//参数，站点ID：用户ID：当前页码：每页条码数：引用变量计算出多少页
			createDir($sid);
			$pagecount=0;
			$pageall=0;
			
			$fileDir=FCPATH.'msg/'.$sid.'/'.$uid.'.item.php';
			if(is_file($fileDir))
			{
				require $fileDir;
				if(isset($lists))
				{
					$array=json_decode($lists,true);
					$pageall = count($array);
					$pagecount=ceil($pageall/$pagesize);
					
					$listStart=($pageindex-1)*$pagesize;
					$listEnd=$pageindex*$pagesize;
					
					
					if($pageindex>$listEnd || $pageindex<=0)
					{
						return array();	
					}
					else
					{
						$arr=array();
						$a=0;
						for($i=$listStart;$i<$listEnd;$i++)
						{
							
							if(isset($array[$i]))
							{
								$arr[$a]=$array[$i][0];
								$arr[$a]['time']=date('Y-m-d H:i',$arr[$a]['time']);
								$arr[$a]['showText']=createUbb($arr[$a]['showText'],$face);
								$arr[$a]['yousText']=createUbb($arr[$a]['yousText'],$face);
								$a++;	
							}	
						}	
						return $arr;
					}
					
				}	
				else
				{
					return array();	
				}
			}
			else
			{
				
				return array();	
			}
		}
	}
	
	if(! function_exists('createUbb'))
	{
		function createUbb($contents,$faces)
		{
			foreach($faces->result_array() as $face)
			{
				$contents=str_replace('['.$face['name'].']','<img src="'.base_url().$face['file'].'" style="width:30px;">',$contents);	
			}
			return $contents;	
		}
	}