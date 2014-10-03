<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
/**
 * Created by PhpStorm.
 * User: router
 * Date: 10/3/14
 * Time: 1:20 PM
 */
#Database Connection
class database extends SQLite3
{
	function __construct()
	{
		$this->open('minidebconf.db');
	}
}
$db = new database();
if (!$db)
{
	echo $db->lastErrorMsg();
}
else
{
	$sql =<<<EOF
    CREATE TABLE IF NOT EXISTS registration
    (NAME          TEXT    NOT NULL,
    EMAIL         TEXT    NOT NULL,
    ORG           TEXT,
    CITY          TEXT,
    LAP           INT,
    ACCOM         INT,
    TSHIRT        TEXT,
    ARRIVAL       TEXT,
    DEPARTURE     TEXT,
    REGTIME       TEXT);
EOF;
	$ret = $db->exec($sql);
}

$name=$email=$org=$city=$prearrival=$predeparture=$arrival=$departure="";
$lap=$accom=0;
$nameerror = $emailerror = $arrivalerror = $departureerror = $orgerror = $cityerror = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$myDateTime = new DateTime(Date(''), new DateTimeZone('GMT'));
	$myDateTime->setTimezone(new DateTimeZone('Asia/Kolkata'));
	$date=$myDateTime->format('Y-m-d H:i:s');
	$name= $_POST['del-name'];
	if (empty($_POST['del-email']))
	{
		$emailerror = "Required Field";
	}
	else
	{
		$email = $_POST['del-email'];
		if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email))
		{
			$emailerror = "Invalid Format";
		}
	}
	$org = $_POST['del-org'];
	$city = $_POST['del-city'];
	if(!preg_match('/$^|^[a-zA-Z]+[0-9]*[\. ,]*[a-zA-Z0-9]*$/',$city))
	{
		$cityerror = "City name must start with a letter and can contain only alphanumerics, spaces, periods and commas";
	}

	if( empty( $_POST['del-arrival'] ) ) {

		$arrivalerror= "No arriving date given";
	} else {

		$arrival = $_POST['del-arrival'];
	}
	if( empty( $_POST['del-depart'] ) ) {
		$departureerror= "No departure date given";
	} else {
		$departure = $_POST['del-depart'];
	}
	$lap=$accom=$tshirt=1;
	if ($nameerror=="" && $emailerror=="" && $arrivalerror=="" && $departureerror=="" && $orgerror =="" && $cityerror=="")
	{

		$sql="INSERT INTO `registration` VALUES ('$name','$email','$org','$city','$lap','$accom','$tshirt','$arrival','$departure','$date')";

		$ret = $db->exec($sql);
		if($ret)
		{
			$db->close();
			?><?php
			header('location:return.html');
		} else {
			echo "fail";
		}
	} else {
		echo "wrong input";
		header('location:../../index.html');
	}
}
?>