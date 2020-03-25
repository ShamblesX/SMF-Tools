<?php

if(!isset($_POST['dumpsel']))
	Die('Not Authorised');

require_once('lognow.php');

$reqdDump = $_POST['dumpsel'];

log_now('Selected ' . $reqdDump);

$backupsDir = $_POST['backupsDir'];
$dbname = $_POST['dbname'];
$username = $_POST['username'];
$password = $_POST['password'];
$dofix = empty($_POST['dofix']) ? 'off' : 'on';

$status = 0;

$ext = strpos($reqdDump,'.gz');

if (!$ext) {	
	$prefix = $reqdDump;
	$actualFile = $reqdDump . '.gz';
} else {
	$actualFile = $reqdDump;
	$prefix = substr($reqdDump,0,strlen($reqdDump)-3);
}

if (!chdir($backupsDir)) {
	die('<p><strong>Unable to switch to backup folder ' . $backupsDir . '</strong></p>');
} else {
	log_now ('Switching to folder ' . $backupsDir);
}

$operSys = PHP_OS == 'WINNT' ? 'WINDOWS' : 'LINUX';

$cmd = ($operSys == 'WINDOWS' ? '7z.exe e -y ' : 'gzip e -y ') . $actualFile ;

log_now ('.. decompressing ' . $actualFile . ' under ' . $operSys);

log_now ('.. executing ' . $cmd);

exec($cmd,$ignore,$status);

if (0 == $status)
	log_now ('.. decompression seems to have worked');
else
	die(".. decompression seems to have failed, status ".$status);

$cmd = 'mysql -u' . $username . ' -p' . $password . ' ' . $dbname . ' < ' . $prefix;

log_now ('.. reloading DB from ' . $prefix);

log_now ('.. executing ' . $cmd);

exec($cmd,$ignore,$status);

if (0 === $status)
{
	log_now ('.. DB reload seems to have worked');
	
	@unlink ($prefix);
	
	if($dofix !== 'on')
		log_now ('<strong>don\'t forget to run FIXDB</strong>');
	else {
		@require_once('fixdb.php');
		log_now('fixdb has been executed');
		}
		
}
else
	die("<p>.. DB reload seems to have failed, status ".$status.'</p>');

?>