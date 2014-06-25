<?php
echo "Hello world<br>";

$variable="Hello World<br>";
echo $variable;




?>
<?php
$db=new mysqli("localhost","root","","finance");
$sql="select * from allocation";
$results=$db->query($sql);

$num_rows=$results->num_rows;
echo $num_rows."<br>";

for($i=0;$i<3;$i++){
	echo $i."<br>"; //012

}
if($i==0){

}
else if($i==1){

}
else {

}

for($i=0;$i<$num_rows;$i++)
{
	$row=$results->fetch_assoc();	
	echo $row['type']."<br>"; //sjtsvdsvt
}



?>

<font color=red>
Tags
</font>
<br>
New Line
<table border=1 width=200>
<tr>
<?php
for($i=1;$i<=3;$i++){
	echo "<td>".$i."</td>";
}
?>
</tr>
<tr>
<td>4</td><td>5</td><td>6</td>
</tr>
<tr>
<td>7</td><td>8</td><td>9</td>
</tr>
</table>
<select>
<option>A</option>
</select>
<input type=text value='hi' />

