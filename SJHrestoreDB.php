<?php

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">';
header( 'Content-type: text/html; charset=utf-8' );
echo '<head><title>SJH restore DB utility</title></head>';
echo '<body>';
echo '<p><strong>SJHrestoreDB starting</p>';

if (ob_get_level() == 0) ob_start();
	
$backupsDir = getcwd() . '/backups' ;

require_once ($_SERVER['DOCUMENT_ROOT'] . '/forum/Settings.php');

if (!is_dir($backupsDir)) 
		die('<p>Tried to find the backups directory but failed, miserably</p>');

echo '<p>Using '. $backupsDir . '</p>';

$files = scandir($backupsDir);

$dumps = array();

for ($x=0; $x < count($files); $x++) {
	if ((strlen($files[$x]) == 19) && substr($files[$x],0,4) == 'dump') 
		$dumps[] = $files[$x];}

$dumps = array_reverse($dumps);

echo '<style>
.button {
  background-color: #4CAF50;
  border: none;
  color: white;
  padding: 15px 25px;
  text-align: center;
  font-size: 16px;
  cursor: pointer;
}

.button:hover {
  background-color: black;
}
</style>';

echo '<br />
	<form action="SJHrestoreDBcode.php" method="POST" id="form1">
	
	<label for="dump-select">Choose a DUMP file:</label>
	<select name="dumpsel" id="dumpsel">';
		foreach($dumps as $dump)
			echo '<option value="',$dump,'">',$dump,'</option>';
		echo '
	</select>
	
	<p>Perform FixDB?<input type="checkbox" name="dofix"></p>
	<input type="hidden" name="backupsDir" value="' . $backupsDir . '">
	<input type="hidden" name="dbname" value="' . $db_name . '">
	<input type="hidden" name="username" value="' . $db_user . '">
	<input type="hidden" name="password" value="' . $db_passwd . '">
	</form><br />
	
	<button class="button" type="submit" form="form1" value="Submit">Upload Backup</button>';
	
echo '</body>';

?>