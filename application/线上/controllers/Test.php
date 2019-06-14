<?php
	error_reporting(0);
	class Test extends CI_Controller
	{

		public function goGo($str)
		{
			header('Content-type:text/html;charset=utf8');
			$contents=$str;
			$qian=false;
			$explode=explode('INSERT INTO `dede_addonarticle` VALUES',$contents);
			foreach($explode as $v)
			{
				if(substr_count($v,"'219")>0)
				{
					if(substr_count($v,'</p>')>0)
					{
						$strs='';
						$ar=explode('</p>',$v);
						foreach($ar as $kk=>$vv)
						{
							if(substr_count($vv,'<br />')>0)
							{
								$rv='';
								$arrs=explode('<br />',$vv);
								//print_r($arrs);die();
								foreach($arrs as $kkk=>$vvv)
								{
									if(substr_count($vvv,'<a')>0)
									{
										$es=explode('<a',$vvv);
										$rv.=$es[0].'</p><a'.$es[1];
										$qian=true;
									}
									else
									{
										if(substr_count($vvv,'<strong>')>0 && substr_count($vvv,'<a')<=0)
										{
											$rv.=$vvv;	
										}
										else
										{
											if($qian)
											{
												$rv.='<p>'.$vvv.'</p><p>';
												$qian=false;
											}
											else
											{
												$rv.=$vvv.'</p><p>';
											}
										}
										
										
									}
								}
								echo $rv;die();
							}
							else
							{
								$strs=$vv.'</p>';
							}
						}

						$strs=trim($str,'</p>');
						echo $strs;
					}
				}
				$qian=false;
			}	
		}

		public function index()
		{
			header('Content-type:text/html;charset=utf8');
			$contents=file_get_contents(FCPATH.'100.txt');
			
			//$preg='$(.*)VALUES("(.*)","(.*)","(.*)","(.*)","(.*)","(.*)");$iUs';

			//$preg=str_replace('"',"'",$preg);

			//echo $preg;die();

			//preg_match_all($preg, $contents, $res);

			//print_r($res);
			//exit();

			$explode=explode('INSERT INTO `dede_addonarticle` VALUES',$contents);

			//print_r($explode);die();

			$str='';

			foreach($explode as $v)
			{

				if($v!='')
				{
					$str.=$this->returnLoad($v);
				}
				
			}
			$str=str_replace('<p>　　', '<p>', $str);
			$this->goGo($str);
			//$files=fopen(FCPATH.'res.txt','w');
			//fwrite($files,$str);
			//fclose($files);
			//echo $str;die();
			//echo strip_tags($contents);
		}

		public function returnLoad($contents)
		{
			$str='';

			$next=true;
			$next1=true;

			$array=explode('\r\n',$contents);

			if(substr_count($contents,"'245")>0)
			{
			

				//print_r($array);die();
			}			

			//print_r($contents);die();

			$max=count($array);

			$i=0;
			foreach($array as $k=>$v)
			{
				$i++;
				$v=str_replace('</P>','</p>',$v);
				$v=str_replace('<P>','<p>',$v);
				$v=trim($v,'　');
	
				if($k==0)
				{

					if(substr_count($v,'<p')>0)
					{
						

							
						
							//直接返回对应的值
							return 'INSERT INTO `dede_addonarticle` VALUES'.$contents;
						
					}
					else
					{
						$arr=explode("','",$v);

						//print_r($arr);die();

						$content=$arr[2];
					}

				}
				else
				{
					$content=$v;
				}

				if(substr_count($content,'<div')>0 && substr_count($content,'</div>')<=0)
				{
					//说明当前的div标记中还有数据，下面的循环数据不能录入
					if(substr_count($contents,"'245")>0)
					{
						//echo $content;
						//echo 1000;die();
					}

					$next=false;
				}

				if(substr_count($content,'<p')>0 && substr_count($content,'</p>')<=0)
				{
					//说明当前的div标记中还有数据，下面的循环数据不能录入
					if(substr_count($contents,"'245")>0)
					{
						//echo $content;
						//echo 1000;die();
					}

					$next1=false;
				}
					


				if(substr_count($content,'<div')<=0 && substr_count($content,'<p')<=0 && substr_count($content,'</strong><br />')<=0  && $next && $next1)
				{


					if(substr_count($content,'<br />')>0)
					{
						
						$rs='';
						$arrarr=explode('<br />',$content);
						foreach($arrarr as $vvv)
						{
							if($vvv!='')
							{
								if(substr_count($vvv,'<strong>')<=0)
								{
									$rs.='<p>'.$vvv.'</p>';
								}
								else
								{
									$rs.=$vvv;
								}
							}
						}
						$str.=$rs;
					}
					else
					{
						if($max<=$i)
						{
							if(substr_count($content,"',''")>0)
							{
								$as=explode("',''",$content);
						
								$content=$as[0];
								if(substr_count($content,'<div')<=0 && substr_count($content,'<p')<=0 && substr_count($content,'</strong><br />')<=0  && $next && $next1)
								{
									$content=str_replace('<br />','',$content);
									if(substr_count($content,'<a')>0)
									{
										$arrItem=explode('<a',$content);
										$content='<p>		'.trim($arrItem[0],'	').'</p><a'.$arrItem[1]."',''".$as[1];
									}
									else
									{
										$content='<p>		'.trim($content,'	').'</p>'."',''".$as[1];
									}

									if($k==0)
									{
										$str=$arr[0]."','".$arr[1]."','".$content;
									}
									else
									{
										$str.=$content;
									}
								}
								else
								{
									$str.=$content."',''".$as[1];
								}
							}
						}
						else
						{
							//在重组的条件内
							$content=str_replace('<br />','',$content);
							if(substr_count($content,'<a')>0)
							{
								$arrItem=explode('<a',$content);
								$content='<p>		'.trim($arrItem[0],'	').'</p><a'.$arrItem[1];
							}
							else
							{
								$content='<p>		'.trim($content,'	').'</p>';
							}

							
							if($k==0)
							{
								$str=$arr[0]."','".$arr[1]."','".$content;
							}
							else
							{
								$str.=$content;
							}
						}

					}
				}
				else
				{


					if($k==0)
					{
						$str=$arr[0]."','".$arr[1]."','".$content;
					}
					else
					{
						$str.=$content;
					}
				}

				if(substr_count($content,'</div>')>0)
				{
					if(substr_count($contents,"'245")>0)
					{
						//echo $content;
						//echo 100;die();
					}

					$next=true;
				}

				if(substr_count($content,'</p>')>0)
				{
					if(substr_count($contents,"'245")>0)
					{
						//echo $content;
						//echo 100;die();
					}

					$next1=true;
				}

			}



			$strs=str_replace('<br />','',$str);	

			$strs=str_replace('<p>','<p>		', $str);

			$strs=str_replace('<p>				','<p>',$str);

			$strs=str_replace('<p>		','<p>',$str);



			return 'INSERT INTO `dede_addonarticle` VALUES'.$strs;

			if(substr_count($contents,"'219")>0)
			{
				//echo $strs;die();
			}	
		}

	}