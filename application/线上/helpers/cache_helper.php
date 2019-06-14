<?php

	//文件缓存帮助小助手
	
	//读取文件缓存
	if(! function_exists('readFileCaches'))
	{
		function readFileCaches($id,$time='')
		{
			$res=createFileCacheDir();
			if($res)
			{
				$filePath=FCPATH.'caches/apis/'.$id.'.php';

				if(is_file($filePath))
				{


					if($time!='' && time()-filemtime($filePath)>$time)
					{
						return false;
					}

					return getFileCachesContent($filePath);

				}

				return false;
			}

			return false;
		}
	}

	//获取对应的缓存文件
	if(! function_exists('getFileCachesContent'))
	{
		function getFileCachesContent($filePath)
		{

			if(is_file($filePath))
			{
				$fileSize = filesize($filePath);

				$handler=fopen($filePath,'rb');//二进制方式打开

				$content=fread($handler,$fileSize);

				return json_decode($content,true);

			}
			
			return false;
		}
	}

	//写入对应的文件到缓存中
	if(! function_exists('writeFileCacheContent'))
	{
		function writeFileCacheContent($contents,$id)
		{

			$res=createFileCacheDir();

			if($res)
			{

				$filePath=FCPATH.'caches/apis/'.$id.'.php';

				$file=fopen($filePath,'w');

				fwrite($file,json_encode($contents));

				fclose($file);

				return false;
			}

			return false;
		}
	}

	//创建缓存目录
	if(! function_exists('createFileCacheDir'))
	{
		function createFileCacheDir()
		{

			if(!is_dir(FCPATH.'caches'))
			{
				if(!mkdir(FCPATH.'caches'))
				{
					return false;
				}
			}


			if(!is_dir(FCPATH.'caches/apis'))
			{
				if(!mkdir(FCPATH.'caches/apis'))
				{
					return false;
				}

			}

			return true;
		}
	}


	//清理缓存
	if(! function_exists('clearFileCache'))
	{
		function clearFileCache($cachePath)
		{
			if(is_dir($cachePath))
			{
				return dirFileDels($cachePath);	
			}
			return true;
		}
	}

	//删除文件夹及以下的文件
	if(! function_exists('dirFileDels'))
	{
		function dirFileDels($dir)
		{
			$dh=opendir($dir);
			while ($file=readdir($dh)) 
			{
				if($file!="." && $file!="..") 
				{
					
					$fullpath=$dir."/".$file;
					
					if(!is_dir($fullpath))
					{
						unlink($fullpath);
					} 
					else
					{
						dirFileDels($fullpath);
					}
					
				}
			}
			closedir($dh);
			//删除当前文件夹：
			if(rmdir($dir))
			{
				return true;
			}
			else
			{
				return false;
			}				
		}	
	}