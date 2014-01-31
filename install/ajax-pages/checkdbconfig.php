<?
@mysql_connect($_GET['host'], $_GET['user'], $_GET['password']) or die("<div class='error'>ERROR: DB login, password or host is wrong, I can't connect to the DataBase</div>");
echo '<div class="ok">SUCCESS: host, user and password are correct!</div>';

if($_GET['createdb'])
{
	$sql = "CREATE DATABASE `".addslashes($_GET['name'])."`";
	@mysql_query($sql);
	if(mysql_error()) echo "<div class='error'>".mysql_error()."</div>";
}
@mysql_select_db($_GET['name']) or die("<div class='error'>ERROR: DB name is wrong or it does not exist, I can't select the DataBase. <a href='' onclick=\"checkdbconfig(1);return false;\">Create database '".$_GET['name']."'</a></div>");
echo '<div class="ok">SUCCESS: the DataBase exists and is selectable!</div>';
?>