<?
header("Content-type: text/plain;");
?>User-Agent: *
Allow: /
Host: <?=$_SERVER['HTTP_HOST'] /* need to be changed into real host name of the primer host in order to prevent wrong indexing by search engines*/?> 
Sitemap: http://<?=$_SERVER['HTTP_HOST']?>/sitemap.xml