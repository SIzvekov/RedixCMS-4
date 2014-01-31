<?
//set_time_limit(120);
$install_file = $_FILES['installfile']['tmp_name'];

if(!is_file($install_file))
{?>
<form method="post" enctype="multipart/form-data">
	file: <input type="file" name="installfile"/><br/>
	<input type="submit">
</form>
<?}else{

$file_structure = array();
$dir_structure = array();
$SQL_QUERIES = '';
$SQL_FOR_EXEX = array();
$ftp_host = 'redixhost.ru';
$ftp_login = 'redix-dev_nevpk';
$ftp_password = 'redix-dev_nevpk';

$zip = zip_open($install_file);
while ($zip_entry = zip_read($zip)) {

	// filename
	$filename = zip_entry_name($zip_entry);

	// filetype
	if(preg_match("/.*\/$/", $filename)) $filetype = 'd';
	else $filetype = 'f';

	// filecontent
	if ($filetype=='f' && zip_entry_open($zip, $zip_entry, "r")) {
		$filecontent = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
		zip_entry_close($zip_entry);
	} else $filecontent = '';
        

	if($filename == 'sql.sql') $SQL_QUERIES = $filecontent;
	else if(end(array_reverse(split("/", $filename)))=='www')
	{
		$filename = preg_replace("/^www\/(.*)/", "\\1", $filename);
		if($filename) 
		{
			if($filetype == 'd')
			{
				if(!is_dir(DOCUMENT_ROOT."/".$filename)) $dir_structure[] = array("name"=>$filename);
			}else
			{
				if(!is_file(DOCUMENT_ROOT."/".$filename)) $file_structure[] = array("name"=>$filename, "content"=>$filecontent);
			}
		}
	}
}
zip_close($zip);

/*
print_r($dir_structure);
echo '<hr>';
print_r($file_structure);
*/
/////////////////// DO SQL

// CREATE COMPONENTS RECORDS
$sqls = split("--sql\n", $SQL_QUERIES);
$num_of_components = sizeof($sqls);

// GET auto increment from components table
$sql = "SHOW TABLE STATUS LIKE '#h_components'";
$row = $this->fetch_assoc($this->query($sql));
$auto_increment = $row['Auto_increment'];

$com_ids = array();
$ids = array();
for($i=1;$i<=$num_of_components;$i++)
{
	$ids[] = "{comid:".$i."}";
	$com_ids[] = $auto_increment+$i-1;
}

foreach($sqls as $sql)
{
	$SQL_FOR_EXEX[] = trim(str_replace($ids, $com_ids, $sql));
}

// *************************************************************


foreach ($SQL_FOR_EXEX as $sql) {
	$prepid = intval($this->insert_id());
	$sql = trim(str_replace("{prepid}", $prepid, $sql));
	$this->query($sql);
//	echo $this->prefixed($sql)."<hr>";
}

///////////////// END OF DOING SQL


// LOGIN VIA FTP

$conn_id = ftp_connect($ftp_host) or die('connection faild');
$login_result = ftp_login($conn_id, $ftp_login, $ftp_password) or die('login faild');

foreach ($dir_structure as $dirname) {
	ftp_mkdir($conn_id, trim($dirname['name']));
	echo trim($dirname['name'])." was created <br>";
}

foreach ($file_structure as $filename) {
	
	$filepath = DOCUMENT_ROOT."/".$filename['name'];
	$dirname = dirname($filename['name']);

	// change dir mode
	ftp_chmod($conn_id, 0777, $dirname);
	
	// write file
	$file = fopen($filepath, 'w');
	fwrite($file, $filename['content']);
	fclose($file);

	// change dir mode
	$dirname_array = split("/", $dirname);
	if($dirname_array[0]!='images')
	{
		ftp_chmod($conn_id, 0755, $dirname);
	}else
	{
		chmod($filepath, 0777);
	}
	echo "file: ".$filename['name']." has been created<br/>";

}
// CLOSE FTP CONNECTION
ftp_close($conn_id);

}
?>