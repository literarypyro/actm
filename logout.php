<?php 
session_start();
?>
<?php
require("db_page.php");
?>

<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
//if($_SESSION['viewMode']=="login"){
$logTime=date("Y-m-d H:i:s");
$db=retrieveDb();
$updateSQL="update log_history set logout='".$logTime."' where username='".$_SESSION['username']."' and (logout in ('0000-00-00') or logout is null)";

$updateRS=$db->query($updateSQL);
//}
?>
<?php
session_destroy();
?>
<?php


header("Location: index.php");

?>