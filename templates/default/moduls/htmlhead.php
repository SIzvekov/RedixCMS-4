<?php //v.1.0.?>
<title><?=$this->page_info['meta_title']?></title>
<meta name="keywords" content="<?=str_replace('"',"'",$this->page_info['meta_keywords'])?>" />
<meta name="description" content="<?=str_replace('"',"'",$this->page_info['meta_description'])?>" />
<meta http-equiv="Content-Type" content="text/html; charset=<?=$this->config['charset']?>" />
<LINK REV="MADE" HREF="http://rrwd.ru/" title="Rekora&Redix webDevelopment" />
<?if($fileurl = $this->tplfile_exists("css/stylesheet.css")){?>
<link href="<?=$fileurl?>" type="text/css" rel="stylesheet" />
<?}?><?if($fileurl = $this->tplfile_exists("css/stylesheet.ie6.css")){?>
<!--[if IE 6]><link rel="stylesheet" href="<?=$fileurl?>" type="text/css" media="screen" /><![endif]-->
<?}?><?if($fileurl = $this->tplfile_exists("css/stylesheet.ie7.css")){?>
<!--[if IE 7]><link rel="stylesheet" href="<?=$fileurl?>" type="text/css" media="screen" /><![endif]-->
<?}?><?
if($fileurl = $this->tplfile_exists("favicon.ico")){?>
<link rel="icon" href="<?=$fileurl?>" type="image/x-icon" />
<link rel="shortcut icon" href="<?=$fileurl?>" type="image/x-icon" />
<?}?><?
if($fileurl = $this->tplfile_exists("js/JsHttpRequest.js")){?>
<script type="text/javascript" src="<?=$fileurl?>"></script>
<?}?><?
if($fileurl = $this->tplfile_exists("js/js.js")){?>
<script type="text/javascript" src="<?=$fileurl?>"></script>
<?}?><?
////// HIGHSLIDE
if(0){
?><?
if($fileurl = $this->tplfile_exists("js/highslide/highslide-full.js")){?>
<script type="text/javascript" src="<?=$fileurl?>"></script>
<?}?><?
if($fileurl = $this->tplfile_exists("js/highslide/highslide.config.js")){?>
<script type="text/javascript" src="<?=$fileurl?>"></script>
<?}?><?if($fileurl = $this->tplfile_exists("css/highslide.css")){?>
<link href="<?=$fileurl?>" type="text/css" rel="stylesheet" />
<?}?><?if($fileurl = $this->tplfile_exists("css/highslide-ie6.css")){?>
<!--[if lt IE 7]>
<link href="<?=$fileurl?>" type="text/css" rel="stylesheet" />
<![endif]-->
<?}?><?
}
/////////////////////////HIGHSLIDE
?><?

///////// IS ADMIN
if(intval($_SESSION['user']['group']['isadmin'])){?>
<script language='JavaScript' src='/<?=$this->adm_path?>/moduls/overlib_mini/overlib_mini.js.php?admtpl=<?=$this->config['adm_tpl']?>'></script>
<script language="Javascript">function tooltip(name, html) {name = name.toLowerCase();return overlib(html, CAPTION, name)}</script>

<link href="/<?=$this->adm_path?>/moduls/highslide/highslide.css" rel="stylesheet" type="text/css" />
<script language='JavaScript' src='/<?=$this->adm_path?>/moduls/highslide/highslide-with-html.js'></script>
<script language='JavaScript' src='/<?=$this->adm_path?>/moduls/highslide/highslide.config.js'></script>
<script language='JavaScript' src='/<?=$this->adm_path?>/moduls/highslide/lang/<?=$this->admin_par?>.js'></script>

<script type="text/javascript">
hs.graphicsDir = '/<?=$this->adm_path?>/moduls/highslide/graphics/';
hs.outlineType = 'rounded-white';

hs.wrapperClassName = 'draggable-header';
</script><?
}
///////// IS ADMIN
?>