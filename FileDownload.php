<?php
/**
* FileDownload.php
*
* Just a test file to perform actions upon clicking a download link (WIP)
*
*/
$file_name = 'i30_Door_Cable_ChangeORIG.pdf';


$ip = $_SERVER['REMOTE_ADDR'];

date_default_timezone_set('Europe/London');

$dets = sprintf("%s %s\n", date('Y/m/d H:i'), $_SERVER['REMOTE_ADDR']);

$myfile = @fopen("DLlog.txt", "a");
@fwrite($myfile, $dets);
@fclose($myfile);

$ext = substr($file_name, strpos($file_name,'.')+1);

header('Content-disposition: attachment; filename='.$file_name);
header("Content-Length: " . filesize($file_name));

if(strtolower($ext) == "txt")
{
    header('Content-type: text/plain'); // works for txt only
}
else
{
    header('Content-type: application/'.$ext); // works for all extensions except txt
}
readfile($file_name);
?>