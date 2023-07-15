<?php


$dbConn = mysqli_connect('localhost','root','root','postulacionbd') or die('MySQL connect failed. ' . mysqli_connect_error());
//$dbConn = mysqli_connect('MYSQL5039.site4now.net','a47d48_jorgepe','V3QmbvAPEMUXwrQ','db_a47d48_jorgepe') or die('MySQL connect failed. ' . mysqli_connect_error());
function dbQuery($sql) {
	global $dbConn;
	$result = mysqli_query($dbConn, $sql) or die(mysqli_error($dbConn));
	return $result;
}

function dbFetchAssoc($result) {
	return mysqli_fetch_assoc($result);
}

function dbNumRows($result) {
    return mysqli_num_rows($result);
}

function closeConn() {
	global $dbConn;
	mysqli_close($dbConn);
}
	
//End of file