<?php
include ("config.php");
$db = FALSE;
$db = ($GLOBALS["___mysqli_ston"] = mysqli_connect("$mysql_host",  "$mysql_webui_user",  "$mysql_webui_passwd"));
if (!$db) {
	$info = "<p align=\"center\" class=\"table_error\">Could not connect:" . mysqli_error($GLOBALS["___mysqli_ston"]) . "</p>";
	die (mysqli_error($GLOBALS["___mysqli_ston"]));
}
$table = FALSE;
$table = mysqli_select_db( $db, $mysql_database);
if (!$table) {
	$info = "<p align=\"center\" class=\"table_error\">Could not connect:" . mysqli_error($GLOBALS["___mysqli_ston"]) . "</p>";
	die (mysqli_error($GLOBALS["___mysqli_ston"]));
}
?>
