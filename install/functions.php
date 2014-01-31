<?
function readlangfile($lang="")
{
	global $mui;
	$mui = array();
	if(file_exists("lang/".$lang.".txt"))
	{
		$langfile = file("lang/".$lang.".txt");
		foreach($langfile as $str)
		{
			$str = trim($str);
			$str = split("===",$str);
			if($str[0] && $str[1]) $mui[$str[0]] = $str[1];
		}
	}else echo "<p>READ LANG FILE ERROR</p>";
}

function getmui($key)
{
	global $mui;
	if(isset($mui[$key])) return $mui[$key];
	else return 'error: no mui key "'.$key.'"';
}
?>