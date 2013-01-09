<?
#################
# DB Connection #
#################

$db = mssql_connect($dbHost, $dbUser, $dbPass); 
mssql_select_db($dbName, $db);

?>
