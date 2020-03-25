<?php

/**
This provides a function to remove a supplied value of sql_mode from the MySql SESSION variable

The function is called from within an SMF sessiom, Eg:

RemoveModeFromMysqlSession ( 'ONLY_GROUP_BY' );

*/

function RemoveModeFromMysqlSession ($remove) {
	
	global $smcFunc;

	$res = $smcFunc['db_query']('', 'select @@SESSION.sql_mode');

	$mode = $smcFunc['db_fetch_row']($res)[0];

	// The mode string is composed of modes listed as MODE1,MODE2,MODE3
	// So we have to allow for a preceding or trailing comma being left

	$remove_list = array (
		',' . $remove, $remove . ',' );

	$mode = str_replace($remove_list, '', $mode, $count);
	
	if($count > 0)
		$smcFunc['db_query']('', 'set SESSION sql_mode = "' . $mode . '"');
		
	$smcFunc['db_free_result']($res);
}

?>