<?php
/**
* 
* BackupDB.php
*
* A simple backup suite
*
*/

date_default_timezone_set('Europe/London');

logMessage ('Starting the backup routine v2020.03.08.02');

$forumPath = '..';

$backupsDir = $forumPath . '/DUMPS/backups' ;

$settingsFile = $forumPath . '/Settings.php' ;

logMessage ('Extracting credentials from ' . $settingsFile);

if (!file_exists($settingsFile)) {
	  die('Settings file doesn\'t exist');
	} else {
	  logMessage ('Settings file found');
}

/**
* Load the Settings file, which contains username, password and DB name
*/
class dbSettings {
	
	public $dbname = '';
		
	public $dbuser;

	public $dbpasswd;
	
	function __construct ($settingsFile) {
		
		require $settingsFile;
		
		$this->dbname = $db_name;
		
		$this->dbuser = $db_user;
		
		$this->dbpasswd = $db_passwd;
	}
	
	function db_user() {
		return $this->dbuser;
	}
	
	function db_name() {
		return $this->dbname;
	}
	
	function db_passwd() {
		return $this->dbpasswd;
	}
}

$settings = new dbSettings($settingsFile);

logMessage ('Extracted ok');

logMessage ('Database Name: ' . $settings->dbname);

$outputFile = 'dump' . date('YmdHi') . '.gz' ;

$localFile = $backupsDir . '/' . $outputFile ;

logMessage ('Output to ' . $localFile);

include('Mysqldump.php');

    $dumpSettings = array(
        'include-tables' => array(),
        'exclude-tables' => array(),
        'compress' => 'Gzip',
        'no-data' => false,
        'add-drop-table' => true,
        'single-transaction' => true,
        'lock-tables' => false,
        'add-locks' => true,
        'extended-insert' => false,   // Shambles: false puts queries on one line, but uploading takes forever
        'complete-insert' => false,
        'disable-keys' => true,
        'where' => '',
        'no-create-info' => false,
        'skip-triggers' => false,
        'add-drop-trigger' => true,
        'routines' => false,
        'hex-blob' => true,
        'databases' => false,
        'add-drop-database' => false,
        'skip-tz-utc' => false,
        'no-autocommit' => true,
        'default-character-set' => 'utf8',
        'skip-comments' => false,
        'skip-dump-date' => false,
    );
	
//    $dump = new Mysqldump\Mysqldump('mysql:host=localhost;dbname=' . $db_name, $db_user, $db_passwd, $dumpSettings);
// Changed host=localhost to host=127.0.0.1 as per https://stackoverflow.com/questions/20723803/pdoexception-sqlstatehy000-2002-no-such-file-or-directory

    $dump = new Mysqldump\Mysqldump('mysql:host=127.0.0.1;dbname=' . $settings->dbname, $settings->dbuser, $settings->dbpasswd, $dumpSettings);

    $dump->start($localFile);

	logMessage('DUMP COMPLETED');

function logMessage($str) {
	$time = date('H:i:s') . ' ' . $str;
	echo $time . '<br>';
}
?>