<?php 

$daysToKeep = 7;
$timeCheck = time() - ($daysToKeep * 24 * 60 * 60);
$keepCount = 20;

// User configuration
$dumpdir = 'c:\\inetpub\\wwwroot\\Forum\\DUMPS\\backups';

// Files to skip, just being safe...
$skip = array ('.', '..', 'index.php', '.htaccess');

// Loop through the dir, building array of file stats
$dirinfo = array();
$dirhnd = opendir($dumpdir);

if (!$dirhnd) die("CANNOT READ DUMP DIRECTORY: " . $dumpdir);

while ($entry = readdir($dirhnd))
{
	if (substr($entry,0,4) == 'dump' && !in_array($entry, $skip))
	{
		$fileinfo = stat($dumpdir . '\\' . $entry);
		$dirinfo[] = array('file' => $entry, 'modified' => $fileinfo[9]);
	}
}
// Sort the files by last accessed dates, most recent to oldest

usort($dirinfo, 
	function ($a, $b)
	{
		return $b['modified'] - $a['modified'];
	}
);

$count = 0;

// Process the directory table
foreach ($dirinfo AS $direntry)
{
	$count++;
	
	if($count > $keepCount && $direntry['modified'] < $timeCheck) {
		unlink($dumpdir . '\\' . $direntry['file']);
	
		echo 'Deleting  ' . $direntry['file'] . '<br>';
	}
	else {
		echo 'Retaining ' . $direntry['file'] . '<br>';
	}
}
?>