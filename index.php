<?php
if(file_exists("install/index.php")){header("Location: install/index.php");exit;}
/*
RedixCMS 4.0
������� ����, ����������� � ����� ������
*/

/* ������ ����: ���������� */
session_cache_limiter('nocache');
session_start(); // �������� ������

/* ��������� ��� ������������� */
if (file_exists('install.php')) {
	require_once('install.php');
	exit();
}

/* ������ ����: ������� */
//���������� ���� �������
require_once("_config.php");

//change language
if($_SERVER['HTTP_ACCEPT_LANGUAGE'] && !$_SESSION['choosed_lang'] && REQUEST_URI=='/' && sizeof($_LANGS)>1){$lang=strtolower(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2));if(!in_array($lang,$_LANGS)){$lang=$_LANGS[0];}header("Location: http://".HTTP_HOST.'/'.$lang.'/');$_SESSION['choosed_lang']=$lang;exit;}

//clearing mirrors.
/*if(HTTP_HOST=='...') {header("HTTP/1.1 301 Moved Permanently");header("Location: http://...".REQUEST_URI);exit;}*/

//���������� ���� ���������� �������
require_once("_system/_global_functions.php");
//���������� ���� ���������������� �������. ��� ������� �������� � �������� �����
require_once("_system/_core_user.php");
//���������� ���� ������ � ��
require_once("_system/_db_".DB_TYPE.".php");
//���������� ���� �������� ������
require_once("_system/_core_".CMS_VERSION.".php");
// ���������� ���� �������� ������
require_once(ADMINDIRNAME."/_system/_adm_core_".ADM_VERSION.".php");

/* ������ ����: ����������� ���������� ���������� */
//���������� �������� ����� ����
$core = new adm_core(ADMINDIRNAME);
//��������� ����������� ������������
$core->login();
$core->prestart();

/* ���¨���� ����: ������������ �������� */
$core->core_go_proceedform();
$cache_data = $core->core_get_cachepage();
if($cache_data["cache"]) {
	//���� ������� ���������� �������� �� ����, �� ���������� � TEXT HTML �������� � �� ��������� ���� ����
	$_TEXT = $cache_data["data"];
} else {
	//������ ������ � �����
	ob_start();
	//������ ����������� ����������
	$core->core_go_component();
	//����� ������� ������� �����
	$core->core_site_template();
	//�������� ���������� ������
	$_TEXT = ob_get_contents();
	//�������� �����
	ob_end_clean ();
	//�������� � ��� ��������
	$core->core_put_cachepage($_TEXT);
}

/* ����� ����: ���������� ������ */
//��������� ������
$core->core_go_header();
//������� �����
$core->core_show_page($_TEXT);
//����� ����������
$core->core_write_statistic();
//��������� ������� � ��
$core->db_close();
echo "\n<!--redixCMS time2gen : ".$core->core_show_exec_time()."-->";
$core->core_debug(0);
?>