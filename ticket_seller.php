<?php
if(isset($_POST['ticket_seller'])){
	$db=new mysqli("localhost","root","","finance");
	$sql="insert into ticket_seller(ticket_seller_name) values (\"".$_POST['ticket_seller']."\")";
	$rs=$db->query($sql);

}


?>
<script language=javascript>
function submitForm(){

	document.forms['ts_form'].submit();
	window.opener.location.reload();
}

</script>
<form name='ts_form' id='ts_form' action='ticket_seller.php' method='post'>
<table>
<tr>
<th colspan=2>Add New Ticket Seller</th>
</tr>
<tr>
<td>Ticket Seller Name
</td>
<td><input type=text name='ticket_seller' />
</td>
</tr>
<tr>
<td colspan=2 align=center><input type=button onclick='submitForm()' value='Submit' /></td>
</tr>
</form>