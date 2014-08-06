<?php
function retrieveDb(){
	$db=new mysqli("localhost","root","","finance");
	return $db;

}
?>