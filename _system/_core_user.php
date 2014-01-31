<?php
/* RedixCMS 4.0
здесь в классе можно определять любые функции, которых ещё нет в основном классе или в классе работы с БД, эти функции попадут в основной класс

функции prestart() и adm_prestart() - не удалять, иначе не будет работать сайт.
*/

class user_main 
{
	function prestart() // функция запускается сразу после создания класса и проверки авторизации пользователя
	{
		if($_GET['emptycart']=='all')
		{
			foreach($_SESSION['shopcart'] as $index=>$v) $this->add2cart('', '', '', '', array(), '', 'delete', $index);
			$this->reload("/cart/");
		}
		elseif(isset($_GET['emptycart']))
		{
			$this->add2cart('', '', '', '', array(), '', 'delete', $_GET['emptycart']);
			$this->reload("/cart/");
		}
	}
	
	function adm_prestart() // функция запускается сразу после создания класса и проверки авторизации пользователя в админке
	{
	}

	function ShowGeoIp(){
		$url = 'http://194.85.91.253:8090/geo/geo.html'; // url на XML сервер <<IpGeobase>>
		if (!$_SESSION['location']['city']) // проверяем наличие сессии
		{ // если сессии нет, то начинаем выполнять поиск
			$ip = $this->get_user_ip();
			$cl = curl_init(); // Устанавливаем cURL
			$query = '<ipquery><fields><all/></fields><ip-list><ip>'.$ip.'</ip></ip-list></ipquery>'; //  формируем запрос
			curl_setopt($cl, CURLOPT_URL, $url); //  выполняем запрос на сервер по адресу $url
			curl_setopt($cl, CURLOPT_RETURNTRANSFER,1); // указываем что ответ сервера нужно записать в переменную
			curl_setopt($cl, CURLOPT_TIMEOUT, 2); // таймаут соединения - 2 секунды
			curl_setopt($cl, CURLOPT_POST, 1); // указываем метод выполнения скрипта - POST
			curl_setopt($cl, CURLOPT_POSTFIELDS, $query); //  выпосляем запрос
			$result = curl_exec($cl); // записываем в переменную
			curl_close($cl); //  закрываем соединение
			preg_match_all("|<region>(.*?)</region>|", $result, $region); //  узнаём регион
			preg_match_all("|<city>(.*?)</city>|", $result, $city); //  узнаём город
			preg_match_all("|<district>(.*?)</district>|", $result, $district); //  узнаём округ
			preg_match_all("|<lat>(.*?)</lat>|", $result, $lat); //  узнаём широта
			preg_match_all("|<lng>(.*?)</lng>|", $result, $lng); //  узнаём долгота
			$_SESSION['location'] = array(); //  определяем сессию 'location' массивом
			$_SESSION['location']['region'] = $region[1][0]; //  записываем в массив сессии регион
			$_SESSION['location']['city'] = $city[1][0];  //  записываем в массив сессии город
			$_SESSION['location']['district'] = $district[1][0]; //  записываем в массив сессии округ
			$_SESSION['location']['lat'] = $lat[1][0]; //  записываем в массив сессии широту
			$_SESSION['location']['lng'] = $lng[1][0]; //  записываем в массив сессии долготу
		}
		return $_SESSION['location'];  //  возвращаем результат
	}

	function upk($price=0) // user price koefficient
	{
		$koef = ($_SESSION['user']['koef']?$_SESSION['user']['koef']:1);
		return $price*$koef;
	}


	function icq_online($uin=0, $cachttl=300)
	{
		$uin = str_replace("-","",$uin);
		$uin = str_replace(" ","",$uin);
		$uin = str_replace(",","",$uin);
		$uin = str_replace(".","",$uin);
		if(!$uin) return 0;

		if(!file_exists(DOCUMENT_ROOT."/_cache/icq_stat") || !is_dir(DOCUMENT_ROOT."/_cache/icq_stat")) mkdir(DOCUMENT_ROOT."/_cache/icq_stat", 0777);
		
		$cach_file = DOCUMENT_ROOT."/_cache/icq_stat/".$uin.".txt";
		$cach_file_time = filemtime($cach_file);
		if((time()-$cach_file_time)<=$cachttl)
		{
			$stat = file($cach_file);
			return trim($stat[0]);
		}
		
		$file = @file_get_contents('http://online.mirabilis.com/scripts/online.dll?icq='.$uin.'&img=5');
		$md5 = md5($file);
		
		if($md5=='501aa29a5565a264b1257b66bcbf82ea') $stat = 1;
		else $stat = 0;
		
		if(file_exists($cach_file)) unlink($cach_file);

		$f = fopen($cach_file,"w");
		fwrite($f,$stat);
		fclose($f);

		return $stat;
	}
	function skype_online($uin='',$cachttl=300)
	{
		if(!$uin) return 0;

		if(!file_exists(DOCUMENT_ROOT."/_cache/skype_stat") || !is_dir(DOCUMENT_ROOT."/_cache/skype_stat")) mkdir(DOCUMENT_ROOT."/_cache/skype_stat", 0777);
		$cach_file = DOCUMENT_ROOT."/_cache/skype_stat/".$uin.".txt";
		$cach_file_time = filemtime($cach_file);
		if((time()-$cach_file_time)<=$cachttl)
		{
			$stat = file($cach_file);
			return trim($stat[0]);
		}

		$statusuri = "http://mystatus.skype.com/%s.xml";
		$str_status_xml =  @file_get_contents(sprintf($statusuri,$uin));
		
		$lang = 'en';
		$match = array();
		$pattern = "~xml:lang=\"".strtolower($lang)."\">(.*)</~";
		preg_match($pattern,$str_status_xml, $match);
		$stat = $match[1];

		if(file_exists($cach_file)) unlink($cach_file);

		$f = fopen($cach_file,"w");
		fwrite($f,$stat);
		fclose($f);

		return $stat;
	}

	function get_param_rutext($pid=0)
	{
		$pid = intval($pid);
		if(!$pid) return '';
		static $got_names = array();
		if(in_array($pid, array_keys($got_names)))
		{
			return $got_names[$pid];
		}else
		{
			$sql = "SELECT `name` FROM `#__cat_params` WHERE `id`=".$pid;
			$row = $this->fetch_assoc($this->query($sql));
			return $row['name'];
		}
	}

	function add2cart($tovid=0, $record_id=0, $url='', $tov_title='', $params = array(), $qu=1, $act = 'add', $index='')
	{
		$tovid = intval($tovid);
		$qu = intval($qu);
		$record_id = intval($record_id);

		if(!is_array($_SESSION['shopcart'])) $_SESSION['shopcart'] = array();

		$ind = $index?$index:$tovid."-".md5(serialize($params));
//		echo ' = '.$ind;
		if(!$qu && $act == 'update') $act = 'delete';
		switch($act)
		{
			case 'add':
				$tov = $this->fetch_assoc($this->query("SELECT `artikul`, `1c-id` FROM `#__catalog_prod` WHERE `id`=".intval($record_id)));
				if(!isset($_SESSION['shopcart'][$ind]))
				{
					$_SESSION['shopcart'][$ind] = array(
					'tovid'=>$tovid,
					'artikul'=>$tov['artikul'],
					'1c-id'=>$tov['1c-id'],
					'record_id'=>$record_id,
					'tov_title'=>$tov_title,
					'params'=>$params,
					'url'=>$url,
					'quantity'=>0	
					);
				}
				if(!$qu) $qu = 1;
				$_SESSION['shopcart'][$ind]['quantity']+=$qu;
			break;

			case 'update':
				$_SESSION['shopcart'][$ind]['quantity']=$qu;
			break;
			
			case 'delete':
				unset($_SESSION['shopcart'][$ind]);
			break;
		}
		return $ind;
	}

	function get_user_price($income='')
	{
		$price = str_replace(",",".",trim($income));
		list($mon, $cent) = split("\.",$price);
		$mon = intval($mon);
		$cent = substr(intval($cent),0,2);
		if(!strlen($cent)) $cent = '00';
		elseif(strlen($cent)==1) $cent = $cent.'0';
		$price = $mon.".".$cent;
		return $price;
	}

	function get_db_array($sql = '', $f='')
	{
		if(!$sql) return array();
		$res = $this->query($sql);
		$row = array();
		while($r = $this->fetch_assoc($res)) if($f)  $row[] = $r[$f]; else $row[] = $r;
		return $row;
	}

	function printr($array=array())
	{
		echo '<pre>';print_r($array);echo '</pre>';
	}

	function get_prices($item = array())
	{
		if(!isset($item['price'])) $info['price'] = 0;
		if(!isset($item['disc_am'])) $info['disc_am'] = 0;
		$price = 0;
		$disc_price = 0;

		$price = str_replace(",",".",trim($item['price']));
		list($mon, $cent) = split("\.",$price);
		$mon = intval($mon);
		$cent = substr(intval($cent),0,2);
		if(!strlen($cent)) $cent = '00';
		elseif(strlen($cent)==1) $cent = $cent.'0';
		$price = $mon.".".$cent;

		if(intval($item['disc_am']))
		{
			$disc_price = str_replace(",",".",trim($item['disc_am']));
			list($mon, $cent) = split("\.",$disc_price);
			$mon = intval($mon);
			$cent = substr(intval($cent),0,2);
			if(!strlen($cent)) $cent = '00';
			elseif(strlen($cent)==1) $cent = $cent.'0';
			$disc_price = $mon.".".$cent;
		}
		return array('price'=>$price, 'old_price'=>$disc_price);
	}

	function print_prices($prices = array())
	{
		if(!isset($prices['old_price'])) $prices['old_price'] = 0;
		$echo = '';
		$echo .= $prices['old_price']?'<div class="oldprice">'.$prices['old_price'].' руб.</div>':'';
		$echo .= $prices['price'].' руб.';
		return $echo;
	}

	function adm_showfield(&$row=array(), $fname='', $type='inp')
	{
		if(!$_GET['edit'])
		{
			if($type=='inp') return $row[$fname];
			elseif($type=='txt') return nl2br(trim($row[$fname]));
		}
		
		$echo = '';
		
		if($type=='inp') $echo .= $this->adm_show_input($fname, $row[$fname], $row[$fname], "width:100px", "");
		elseif($type=='txt') $echo .= $this->adm_show_editor($fname,$row[$fname],"","50", "300");
		
		$this->accf[] = $fname;
		return $echo;
	}

	function adm_showtovfield(&$row=array(), $toid=0, $fid='')
	{
		if(!$_GET['edit']) return $row[$fid];
		$echo = '';
		$echo .= $this->adm_show_input('tovar['.$toid.']['.$fid.']', $row[$fid], $row[$fid], "width:50px", "");
		return $echo;
	}

	function cron_1c_upai($table='')
	{
		if(!$table) return;
		$table = addslashes($table);
		$sql = "SELECT `id` FROM `".$table."` WHERE 1 ORDER BY `id` DESC LIMIT 0,1";
		$last_id = $this->fetch_assoc($this->query($sql));
		$last_id = $last_id['id']+1;
		$this->query("ALTER TABLE `".$table."` AUTO_INCREMENT =".intval($last_id));

		return;
	}
	function cron_1c_uniqueprodurl($url='', $loop = 0)
	{
		if(!$url) return '';
		$sql = "SELECT `id` FROM `#__sitemap` WHERE `url`='".addslashes($url)."'";
		$is = $this->num_rows($this->query($sql));
		if($is)
		{
			if($loop)
			{
				$url = split('_',$url);
				array_pop($url);
				$url = join("_",$url);
			}
			$loop++;
			$newurl = $url.'_'.$loop;
			return $this->cron_1c_uniqueprodurl($newurl, $loop);
		}else return $url;
	}

	function cron_1c_addparamtoprod($content='', $pname='', &$params_available=array())
	{
		if(!$content || !$pname) return '';
		if(trim($content))
		{
			$ps = split(";",trim($content));
			foreach($ps as $k=>$v) $ps[$k] = trim($v);
			$ps = join(";",$ps);
			return $params_available[$pname]['id'].'='.$ps."\n";
		}
	}	

	function cron_1c_formfulllistofparams($content='')
	{
		if(!$content) return array();
		if(trim($content))
		{
			$ps = split(";",trim($content));
			foreach($ps as $k=>$v) $ps[$k] = trim($v);
		}
		return $ps;
	}

	function retrieve_images($ones_code = '')
	{
		if(!$ones_code) return array();
		$dir = DOCUMENT_ROOT.'/images/prod_photos';
		$infofile = $dir.'/imgsinfo/'.$ones_code.'.txt';

		$images = array();

		if(is_file($infofile))
		{
			$images = file($infofile);
			foreach($images as $k=>$v) $images[$k] = trim($v);
			return $images;
		}
		
		$extensions = array('jpg','jpeg','bmp','gif','png');
		
		$i = 0;
		$is_file = 1;


		while($is_file)
		{
			$is_file = 0;
			foreach($extensions as $ext)
			{
				$cur_file = $ones_code.'_'.$i.'.'.$ext;
				if(is_file($dir.'/'.$cur_file))
				{
					$images[] = $cur_file;
					$is_file = 1;
				}
				$cur_file = $ones_code.'_'.$i.'.'.strtoupper($ext);
				if(is_file($dir.'/'.$cur_file))
				{
					$images[] = $cur_file;
					$is_file = 1;
				}
			}
			$i++;
		}

		
		
		$f = fopen($infofile,'w');
		fwrite($f,join("\n",$images));
		fclose($f);

		return $images;
	}
}
?>