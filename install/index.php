<?
error_reporting(0);
header("Content-Type:text/html; charset=utf-8");
require_once("functions.php");

$error = array();

if($_POST['save'])
{
	if(!$_POST['db_name'] || !$_POST['db_user'] || !$_POST['db_host']) $error[] = "no DB host, DB name or DB user specified";

	if (mysql_connect($_POST['db_host'], $_POST['db_user'], $_POST['db_password']))
	{
		if(mysql_select_db($_POST['db_name'])) 
		{
			mysql_query("SET NAMES 'utf8'");
			mysql_query("SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';");
		}else $error[] = "DB name is wrong, I can't select this DataBase";
	}else $error[] = "DB login, password or host is wrong, I can't connect to DataBase";

	if(!sizeof($error))
	{
		// get db prefixes
		$sys_prefix = $_POST['sys_prefix'];
		$h_prefix = $_POST['h_prefix'];

		// load db dump of sys's
		$sys_sqls = file_get_contents("MySQL/system.sql");
		$sys_sqls = split(";\r",$sys_sqls);
		foreach($sys_sqls as $sql)
		{
			$sql = str_replace("#s_",$sys_prefix,$sql);
			mysql_query($sql);
		}
		$sql = "INSERT INTO `#s_cmshosts` SET `host`='".addslashes($_POST['host'])."', `db_prefix`='".addslashes($_POST['h_prefix'])."', `licens`='".addslashes($_POST['licens'])."'";
		$sql = str_replace("#s_",$sys_prefix,$sql);
		mysql_query($sql);
		$hostid = mysql_insert_id();

		// load dump of host's
		$host_sqls = file_get_contents("MySQL/host.sql");
		$host_sqls = split(";\r",$host_sqls);
		foreach($host_sqls as $sql)
		{
			$sql = str_replace("#h_",$sys_prefix.$h_prefix,$sql);
			mysql_query($sql);
		}
		

		// load dump of params's
		$param_sqls = file_get_contents("MySQL/param.sql");
		$param_sqls = split(";\r",$param_sqls);

		// get params
		$myparam = array();
		$params = split("\n", $_POST['params']);
		foreach($params as $paramstr)
		{
			$paramstr = split("=",trim($paramstr));
			$myparam[] = $paramstr;

			foreach($param_sqls as $sql)
			{
				$sql = str_replace("#__",$sys_prefix.$h_prefix.$paramstr[2],$sql);
				mysql_query($sql);
			}

			$sql = "INSERT INTO `#s_params` SET `hostid`=".intval($hostid).", `par`='".addslashes($paramstr[0])."', `par_name`='".addslashes($paramstr[1])."', `db_prefix`='".addslashes($paramstr[2])."', `default`='".intval($paramstr[3])."'";
			$sql = str_replace("#s_",$sys_prefix,$sql);
			mysql_query($sql);

			$sql = "UPDATE `#__config` SET `cemail`='".addslashes($_POST['contact'])."'";
			$sql = str_replace("#__",$sys_prefix.$h_prefix.$paramstr[2],$sql);
			mysql_query($sql);
		}

		$sql = "INSERT INTO `#h_users` SET `login`='".addslashes($_POST['adm_login'])."', `pas`='".addslashes(md5($_POST['adm_password']))."', `group`=2, `activ`='1'";
		$sql = str_replace("#h_",$sys_prefix.$h_prefix,$sql);
		mysql_query($sql);
		$sql = "INSERT INTO `#h_users` SET `login`='".addslashes($_POST['dev_login'])."', `pas`='".addslashes(md5($_POST['dev_password']))."', `group`=1, `activ`='1'";
		$sql = str_replace("#h_",$sys_prefix.$h_prefix,$sql);
		mysql_query($sql);

		die('install complete. Please, delete "/install/" dir');
	}
}


readlangfile('en');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=getmui('wintitle')?></title>
<meta http-equiv=Content-Type content="text/html; charset=utf-8" /> 
<style>
body{padding:5px;font-family:Verdana;font-size:12px;color:#000;}
div.block{padding:10px;margin:20px 0;border:1px solid #ccc;background:#fff;}
div.block:hover{background:#fcfcfc;}
div.block div.blocktitle {padding:5px;background:#ececec;margin-top:-22px;float:left;font-weight:bold;border:1px solid #ccc;}
div.block div.blockcontent{clear:both;margin-top:10px;}
div.block div.line {margin:5px 0;}
div.block div.line div.label{float:left;width:150px;margin-right:10px;text-align:left;}
div.block div.line div.inp{}
div.block div.line div.inp input{border:1px solid #000;padding:1px 3px;width:300px;font-family:Verdana;font-size:12px;color:#000;}
div.block div.line div.inp textarea{border:1px solid #000;padding:1px 3px;width:300px;font-family:Verdana;font-size:12px;color:#000;height:50px;}
div.error{padding:4px;border:1px dotted #f00;background:#ffdada;color:#800;font-weight:bold;margin:3px;}
div.ok{padding:4px;border:1px dotted #0f0;background:#daffda;color:#080;font-weight:bold;margin:3px;}
div.block div.line div.inp input.genpas{width:auto;cursor:pointer;}
</style>
</head>
<body>
<div style="color:#f00"><?=join("<br/>",$error)?></div>
<form method="post">
<div class="block">
 <div class="blocktitle">DataBase Connection</div>
 <div class="blockcontent">
  <div class="line"><div class="label">DataBase host:</div><div class="inp"><input type="text" name="db_host" value="<?=$_POST['db_host']?$_POST['db_host']:"localhost"?>" id="dbconf-host"/></div></div>
  <div class="line"><div class="label">DataBase user:</div><div class="inp"><input type="text" name="db_user" value="<?=$_POST['db_user']?$_POST['db_user']:""?>" id="dbconf-user" /></div></div>
  <div class="line"><div class="label">DataBase password:</div><div class="inp"><input type="password" name="db_password" value="<?=$_POST['db_password']?$_POST['db_password']:""?>" id="dbconf-password" /></div></div>
  <div class="line"><div class="label">DataBase name:</div><div class="inp"><input type="text" name="db_name" value="<?=$_POST['db_name']?$_POST['db_name']:""?>" id="dbconf-name" /></div></div>
  <input type="button" value="check DB config" onclick="checkdbconfig();" />
  <div id="dbconfcheckres"></div>
 </div>
</div>

<div class="block">
 <div class="blocktitle">DataBase prefixes and params</div>
 <div class="blockcontent">
  <div class="line"><div class="label">host:</div><div class="inp"><input type="text" name="host" value="<?=$_POST['host']?$_POST['host']:$_SERVER['HTTP_HOST']?>" /></div></div>
  <div class="line"><div class="label">system db prefix:</div><div class="inp"><input type="text" name="sys_prefix" value="<?=$_POST['sys_prefix']?$_POST['sys_prefix']:"sys_"?>" /></div></div>
  <div class="line"><div class="label">host db prefix:</div><div class="inp"><input type="text" name="h_prefix" value="<?=$_POST['h_prefix']?$_POST['h_prefix']:"rx_"?>" /></div></div>
  <div class="line"><div class="label">params:</div><div class="inp"><textarea name="params"><?=$_POST['params']?$_POST['params']:"ru=русский=ru_=1"?></textarea></div></div>
 </div>
</div>

<div class="block">
 <div class="blocktitle">Configuration</div>
 <div class="blockcontent">
  <div class="line"><div class="label">License key:</div><div class="inp"><input type="text" name="licens" value="<?=$_POST['licens']?$_POST['licens']:""?>" /></div></div>
  <div class="line"><div class="label">Admin contact e-mail:</div><div class="inp"><input type="text" name="contact" value="<?=$_POST['contact']?$_POST['contact']:""?>" /></div></div>
 </div>
</div>

<div class="block">
 <div class="blocktitle">Users</div>
 <div class="blockcontent">
  <div class="line"><div class="label">admin login:</div><div class="inp"><input type="text" name="adm_login" value="<?=$_POST['adm_login']?$_POST['adm_login']:"admin"?>" /></div></div>
  <div class="line"><div class="label">admin password:</div><div class="inp"><input type="password" name="adm_password" id="adm_password" value="" />&nbsp;<input type="button" class="genpas" value="gen" onclick="generatepassword('adm_password');"/></div></div>
  <div class="line"><div class="label">developer login:</div><div class="inp"><input type="text" name="dev_login" value="<?=$_POST['dev_login']?$_POST['dev_login']:"god"?>" /></div></div>
  <div class="line"><div class="label">developer password:</div><div class="inp"><input type="password" name="dev_password" id="dev_password" value="" />&nbsp;<input type="button" class="genpas" value="gen" onclick="generatepassword('dev_password');"/></div></div>
 </div>
</div>

<input type="submit" name="save" value="save">
</form>

<script>
function checkdbconfig(create)
{
	var dbconf_host = document.getElementById('dbconf-host').value;
	var dbconf_user = document.getElementById('dbconf-user').value;
	var dbconf_password = document.getElementById('dbconf-password').value;
	var dbconf_name = document.getElementById('dbconf-name').value;
	if(!dbconf_host || !dbconf_user || !dbconf_name) {alert('Fill up all fields.');return false;}
	
	var create=(create==1?'&createdb=1':'');
	ajax('/install/ajax-pages/checkdbconfig.php?host='+dbconf_host+'&user='+dbconf_user+'&password='+dbconf_password+'&name='+dbconf_name+create,'dbconfcheckres');
}

function ajax(url, divid, async)
{
	if(!url) return;
	if(async=='no') async = false;
	else async = true;

	if (window.XMLHttpRequest) request = new XMLHttpRequest();
    else if (window.ActiveXObject) request = new ActiveXObject("Microsoft.XMLHTTP");
	if (request) {
		request.onreadystatechange = ajcallback;
		if(document.getElementById(divid)) request.div = document.getElementById(divid);
		request.open("GET", url, async);
		request.send(null);
	}
}
function ajcallback() {
	if(!request.div) return;
	request.div.innerHTML='loading...';
    if (request.readyState != 4 || request.status != 200) return;
	request.div.innerHTML = request.responseText;
}
function generatepassword(input)
{
	inp = document.getElementById(input);
	if(!inp) return;
	
	var charsarray = new Array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');

	var newpassword = '';
	limiter = 0;
	while(newpassword.length<6 && limiter<1000)
	{
		ind = parseInt(Math.random()*100);
		if(charsarray[ind]) newpassword = newpassword+''+charsarray[ind];
		limiter++;
	}

	inp.type = 'text';
	inp.value = newpassword;
}
</script>
</body>
</html>