<?php
function retrieveDb(){
	$db=new mysqli("localhost","root","","actm");
	return $db;

}
?>