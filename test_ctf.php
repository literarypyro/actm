<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<link href="css/styles.css" rel="stylesheet" type="text/css" />
<!--[if IE]> <link href="css/ie.css" rel="stylesheet" type="text/css"> <![endif]-->


<script type="text/javascript" src="js/jquery-min.js"></script>

<script type="text/javascript" src="js/plugins/forms/ui.spinner.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.mousewheel.js"></script>
 
<script type="text/javascript" src="js/jquery-ui.min.js"></script>


<script type="text/javascript" src="js/plugins/charts/excanvas.min.js"></script>
<script type="text/javascript" src="js/plugins/charts/jquery.flot.js"></script>
<script type="text/javascript" src="js/plugins/charts/jquery.flot.orderBars.js"></script>
<script type="text/javascript" src="js/plugins/charts/jquery.flot.pie.js"></script>
<script type="text/javascript" src="js/plugins/charts/jquery.flot.resize.js"></script>
<script type="text/javascript" src="js/plugins/charts/jquery.sparkline.min.js"></script>

<script type="text/javascript" src="js/plugins/tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/plugins/tables/jquery.sortable.js"></script>
<script type="text/javascript" src="js/plugins/tables/jquery.resizable.js"></script>

<script type="text/javascript" src="js/plugins/forms/autogrowtextarea.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.uniform.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.inputlimiter.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.maskedinput.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.autotab.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.chosen.min.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.dualListBox.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.cleditor.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.ibutton.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="js/plugins/forms/jquery.validationEngine.js"></script>

<script type="text/javascript" src="js/plugins/uploader/plupload.js"></script>
<script type="text/javascript" src="js/plugins/uploader/plupload.html4.js"></script>
<script type="text/javascript" src="js/plugins/uploader/plupload.html5.js"></script>
<script type="text/javascript" src="js/plugins/uploader/jquery.plupload.queue.js"></script>

<script type="text/javascript" src="js/plugins/wizards/jquery.form.wizard.js"></script>
<script type="text/javascript" src="js/plugins/wizards/jquery.validate.js"></script>
<script type="text/javascript" src="js/plugins/wizards/jquery.form.js"></script>

<script type="text/javascript" src="js/plugins/ui/jquery.collapsible.min.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.breadcrumbs.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.tipsy.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.progress.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.timeentry.min.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.colorpicker.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.jgrowl.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.fancybox.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.fileTree.js"></script>
<script type="text/javascript" src="js/plugins/ui/jquery.sourcerer.js"></script>

<script type="text/javascript" src="js/plugins/others/jquery.fullcalendar.js"></script>
<script type="text/javascript" src="js/plugins/others/jquery.elfinder.js"></script>

<script type="text/javascript" src="js/plugins/ui/jquery.easytabs.min.js"></script>
<script type="text/javascript" src="js/files/bootstrap.js"></script>
<script type="text/javascript" src="js/files/functions.js"></script>
<style type='text/css'>
.formRow {
	
}


</style>



<?php
require("calculateInWords.php");
?>

<script language='javascript'>
function amountCalculate(quantity,denomination,textAmount,e,nextField){
	//document.getElementById(textAmount).value=((denomination*1)*qty)*1;
	var denom=denomination*1;
	var qty=quantity*1;
	
	var amount=denom*qty;
	amount=Math.round(amount*100)/100;
	
	document.getElementById(textAmount).value=amount;	

	calculateTotal();
	
	if(e.keyCode==13){
		document.getElementById(nextField).focus();
		if(nextField=="revolving_remittance"){
			window.scrollBy(0,100);
		}
	
	}	
}

function calculateTotal(){
	var total=0;
	for(i=1;i<13;i++){
		total+=(document.getElementById('amount'+i).value)*1;

	}
	document.getElementById('cash_total').value=Math.round(total*100)/100;

	calculateNumber(Math.round(total*100)/100,"total_in_pesos");	

	var rev=document.getElementById('revolving_remittance').value;
	if(rev>0){
		document.getElementById('for_deposit').value=Math.round((document.getElementById('cash_total').value*1-rev*1)*100)/100;
		
	}
	else {
		document.getElementById('revolving_remittance').value=document.getElementById('cash_total').value;	
		document.getElementById('for_deposit').value=0;
	
	}
	
	
}
function submitForm(){

	document.forms['cash_form'].submit();
	window.opener.location.reload();
}

function openTicketSeller(){
	window.open("ticket_seller.php","ticket_seller","height=200, width=300");

}

function calculateDistribution(type){
	if(type=='revenue'){
		var revolving=document.getElementById('cash_total').value*1-document.getElementById('for_deposit').value*1;
		document.getElementById('revolving_remittance').value=Math.round(revolving*100)/100;
	
	}
	else if(type=='revolving'){
		var deposit=document.getElementById('cash_total').value*1-document.getElementById('revolving_remittance').value*1;
		document.getElementById('for_deposit').value=Math.round(deposit*100)/100;
	
	}

}
function searchTicketSeller(tName){
	var xmlHttp;
	
	var caHTML="";

	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlHttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlHttp.onreadystatechange=function()
	{
		if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{
			caHTML=xmlHttp.responseText;


			if(caHTML=="None available"){
				//document.getElementById('searchResults').innerHTML="<td style='background-color:white; color:black;'></td><td style='background-color:white; color:black;'></td>";

			}
			else {

				var caTerms=caHTML.split("==>");
				
				var count=(caTerms.length)*1-1;

				var optionsGrid="";

			
				optionsGrid+="<select name='ticket_seller' id='ticket_seller'>";				
				

				for(var n=0;n<count;n++){
					var parts=caTerms[n].split(";");

					optionsGrid+="<option value='"+parts[0]+"' >";
					optionsGrid+=parts[2]+", "+parts[1];
					optionsGrid+="</option>";
		
				}
				optionsGrid+="</select>";
					
				document.getElementById('cafill').innerHTML=optionsGrid;
				//document.getElementById('programpageNumber').value="";
			}
		}
	} 

	xmlHttp.open("GET","process search.php?searchTS="+tName,true);
	xmlHttp.send();	
}
function updateLogbook(){
	window.opener.location.reload();


}
</script>




<script language='javascript'>
function amountCalculate(quantity,denomination,textAmount,e,nextField){
	//document.getElementById(textAmount).value=((denomination*1)*qty)*1;
	var denom=denomination*1;
	var qty=quantity*1;
	
	var amount=denom*qty;
	amount=Math.round(amount*100)/100;
	
	document.getElementById(textAmount).value=amount;	

	calculateTotal();
	
	if(e.keyCode==13){
		document.getElementById(nextField).focus();
		if(nextField=="revolving_remittance"){
			window.scrollBy(0,100);
		}
	
	}	
}

function calculateTotal(){
	var total=0;
	for(i=1;i<13;i++){
		total+=(document.getElementById('amount'+i).value)*1;

	}
	document.getElementById('cash_total').value=Math.round(total*100)/100;

	calculateNumber(Math.round(total*100)/100,"total_in_pesos");	

	var rev=document.getElementById('revolving_remittance').value;
	if(rev>0){
		document.getElementById('for_deposit').value=Math.round((document.getElementById('cash_total').value*1-rev*1)*100)/100;
		
	}
	else {
		document.getElementById('revolving_remittance').value=document.getElementById('cash_total').value;	
		document.getElementById('for_deposit').value=0;
	
	}
	
	
}
function submitForm(){

	document.forms['cash_form'].submit();
	window.opener.location.reload();
}

function openTicketSeller(){
	window.open("ticket_seller.php","ticket_seller","height=200, width=300");

}

function calculateDistribution(type){
	if(type=='revenue'){
		var revolving=document.getElementById('cash_total').value*1-document.getElementById('for_deposit').value*1;
		document.getElementById('revolving_remittance').value=Math.round(revolving*100)/100;
	
	}
	else if(type=='revolving'){
		var deposit=document.getElementById('cash_total').value*1-document.getElementById('revolving_remittance').value*1;
		document.getElementById('for_deposit').value=Math.round(deposit*100)/100;
	
	}

}
function searchTicketSeller(tName){
	var xmlHttp;
	
	var caHTML="";

	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlHttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlHttp.onreadystatechange=function()
	{
		if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{
			caHTML=xmlHttp.responseText;


			if(caHTML=="None available"){
				//document.getElementById('searchResults').innerHTML="<td style='background-color:white; color:black;'></td><td style='background-color:white; color:black;'></td>";

			}
			else {

				var caTerms=caHTML.split("==>");
				
				var count=(caTerms.length)*1-1;

				var optionsGrid="";

			
				optionsGrid+="<select name='ticket_seller' id='ticket_seller'>";				
				

				for(var n=0;n<count;n++){
					var parts=caTerms[n].split(";");

					optionsGrid+="<option value='"+parts[0]+"' >";
					optionsGrid+=parts[2]+", "+parts[1];
					optionsGrid+="</option>";
		
				}
				optionsGrid+="</select>";
					
				document.getElementById('cafill').innerHTML=optionsGrid;
				//document.getElementById('programpageNumber').value="";
			}
		}
	} 

	xmlHttp.open("GET","process search.php?searchTS="+tName,true);
	xmlHttp.send();	
}
function updateLogbook(){
	window.opener.location.reload();


}
</script>


    <div class="wrapper">
    
        <form action="test_ctf.php" method='post' class="main">
            <fieldset>
                <div class="widget fluid grid3">
                    <div class="whead"><h6>Cash Transfer Form</h6><div class="clear"></div></div>
                    <div class="formRow">
                        <div class="grid3"><label>Reference ID (CTF)</label></div>
                        <div class="grid9"><input type="text" name="regular" /></div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Cash Assistant</label></div>
                        <div class="grid9  searchDrop">
						<select name='cash_assistant' class='select'>
						<?php
						$db=new mysqli("localhost","root","","finance");
						$sql="select * from login where status='active' order by lastName";
						$rs=$db->query($sql);
						$nm=$rs->num_rows;
						for($i=0;$i<$nm;$i++){
						$row=$rs->fetch_assoc();
						?>
						<option value='<?php echo $row['username']; ?>' 
						<?php 
						if(isset($cash_assist)){
						if($row['username']==$cash_assist){ 
						echo "selected"; 

						}
						}
						else {
						if($row['username']==$_SESSION['username']){ 
						echo "selected"; 

						} 
						}?> >
						<?php
						echo strtoupper($row['lastName']).", ".$row['firstName'];
						?>
						</option>
						<?php
						}
						?>
						</select>						
						
						
						
						</div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Ticket Seller</label></div>
                        <div class="grid9  searchDrop">
						<?php
						$db=new mysqli("localhost","root","","finance");
						$sql="select * from ticket_seller order by last_name";
						$rs=$db->query($sql);
						$nm=$rs->num_rows;
						?>
						<select name='ticket_seller' class="select" >
						<?php 
						for($i=0;$i<$nm;$i++){
							$row=$rs->fetch_assoc();
						?>
							<option value='<?php echo $row['id']; ?>' <?php if($ticketsellerpost==$row['id']){ echo "selected"; } ?>><?php echo strtoupper($row['last_name']).", ".$row['first_name']; ?></option>
						<?php
						}
						?>
						
						</select>						
						</div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Select Station</label></div>
                        <div class="grid9 noSearch">
						<select name='station' style='width:200px' data-placeholder='Select Station' class='select'>

					<?php
					$db=new mysqli("localhost","root","","finance");
					$logSQL="select * from logbook where id='".$log_id."'";

					$logRS=$db->query($logSQL);
					$logNM=$logRS->num_rows;
					if($logNM>0){
					$logRow=$logRS->fetch_assoc();
					$cash_assistant=$logRow['cash_assistant'];


					$stationSQL="select * from station where id='".$logRow['station']."'";
					$stationRS=$db->query($stationSQL);
					$stationRow=$stationRS->fetch_assoc();
					$station_name=$stationRow['station_name'];
					$station_id=$stationRow['id'];





					}
					?>
						<option value='<?php echo $station_id; ?>' <?php if($station_id==$station){ echo "selected"; } ?>><?php echo $station_name; ?></option>
						<?php
						$extensionSQL="select * from extension inner join station on extension.extension=station.id where extension.station='".$logRow['station']."'";
						$extensionRS=$db->query($extensionSQL);
						$extensionNM=$extensionRS->num_rows;
						if($extensionNM>0){
							$extensionRow=$extensionRS->fetch_assoc();
							$extensionID=$extensionRow['extension'];
							$extensionName=$extensionRow['station_name'];
						?>
						<option value='<?php echo $extensionID; ?>' <?php if($extensionID==$station){ echo "selected"; } ?>><?php echo $extensionName; ?></option>
						<?php
						}
						?>
						<option value='annex'>Annex</option>

						</select>						
						
						
						
						
						
						
						
						
						
						
						
						
						</div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Unit</label></div>
                        <div class="grid9 noSearch">
						<select name='unit' id='unit' class='select' style='width:100px'>
						<option <?php if($unit=="A/D1"){ echo "selected"; } ?> value='A/D1'>AD1</option>
						<option <?php if($unit=="A/D2"){ echo "selected"; } ?> value='A/D2'>AD2</option>
						<option <?php if($unit=="TIM1"){ echo "selected"; } ?> value='TIM1'>TIM1</option>
						<option <?php if($unit=="TIM2"){ echo "selected"; } ?> value='TIM2'>TIM2</option>
						<option <?php if($unit=="TIM3"){ echo "selected"; } ?> value='TIM3'>TIM3</option>
						</select>
						</div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Transaction Type</label></div>
                        <div class="grid9 noSearch">
						<select name='type' id='type' class='select'>
						<option <?php if($transactType=="allocation"){ echo "selected"; } ?> value='allocation'>Allocation</option>
						<option <?php if($transactType=="remittance"){ echo "selected"; } ?> value='remittance'>Remittance</option>
						<option <?php if($transactType=="shortage"){ echo "selected"; } ?> value='shortage'>Shortage Payment</option>
						<option <?php if($transactType=="catransfer"){ echo "selected"; } ?> value='catransfer'>Turnover to CA</option>

						</select>						
						
						
						
						</div>
                        <div class="clear"></div>
                    </div>                    
					<div class="formRow">
						<div class="grid3"><label>To CA (Turnover only)</label></div>
                        <div class="grid9 searchDrop">
						<select name='destination_cash_assistant' class='select'>
						<?php
						$db=new mysqli("localhost","root","","finance");
						$sql="select * from login where status='active'  order by lastName";
						$rs=$db->query($sql);
						$nm=$rs->num_rows;
						for($i=0;$i<$nm;$i++){
						$row=$rs->fetch_assoc();
						?>
						<option value='<?php echo $row['username']; ?>' <?php if($row['username']==$destination_ca){ echo "selected"; } ?> >
						<?php
						echo strtoupper($row['lastName']).", ".$row['firstName'];
						?>
						</option>
						<?php
						}
						?>
						</select>						
						
						
						
						</div>
                        <div class="clear"></div>
                    </div>
					
					<div class="formRow">
						<div class="grid3"><label>Date and Time</label></div>

                        <div class="grid9"><div class='grid3'><input type="text" class="datepicker" /></div><div  class='grid3'> <input type="text" class="timepicker" size="10" /></div></div>
                        <div class="clear"></div>
                    </div>

				</div>	
							

                <div class="widget fluid">
                    <div class="whead">
						<div class="grid3"><h6>Denomination</h6></div>
						<div class="grid4"><h6>Quantity</h6></div>
						<div class="grid5"><h6>Amount</h6></div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>1000</label></div>
                        <div class="grid4"><input type="text" id='1000denom' name='1000denom' onkeyup="amountCalculate(this.value,'1000','amount1',event,'500denom')"  /></div>
                        <div class="grid5"><input type="text" name="amount1" id='amount1' /></div>

                        <div class="clear"></div>
                    </div>

					<div class="formRow">
						<div class="grid3"><label>500</label></div>
                        <div class="grid4"><input type="text" id='500denom' name='500denom' onkeyup="amountCalculate(this.value,'500','amount2',event,'200denom')"  /></div>
                        <div class="grid5"><input type="text" name="amount2" id='amount2' /></div>

                        <div class="clear"></div>
                    </div>
					
					<div class="formRow">
						<div class="grid3"><label>200</label></div>
                        <div class="grid4"><input type="text" id='200denom' name='200denom' onkeyup="amountCalculate(this.value,'200','amount3',event,'100denom')"  /></div>
                        <div class="grid5"><input type="text" name="amount3" id='amount3' /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>100</label></div>
                        <div class="grid4"><input type="text" id='100denom' name='100denom' onkeyup="amountCalculate(this.value,'100','amount4',event,'50denom')"  /></div>
                        <div class="grid5"><input type="text" name="amount4" id='amount4' /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>50</label></div>
                        <div class="grid4"><input type="text" id='50denom' name='50denom' onkeyup="amountCalculate(this.value,'50','amount5',event,'20denom')"   /></div>
                        <div class="grid5"><input type="text" name="amount5" id='amount5' /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>20</label></div>
                        <div class="grid4"><input type="text" id='20denom' name='20denom' onkeyup="amountCalculate(this.value,'20','amount6',event,'10denom')"  /></div>
                        <div class="grid5"><input type="text" name="amount6" id='amount6' /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>10</label></div>
                        <div class="grid4"><input type="text" id='10denom' name='10denom'   onkeyup="amountCalculate(this.value,'10','amount7',event,'5denom')"  /></div>
                        <div class="grid5"><input type="text" name="amount7" id='amount7' /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>5</label></div>
                        <div class="grid4"><input type="text" id='5denom' name='5denom'  onkeyup="amountCalculate(this.value,'5','amount8',event,'1denom')"   /></div>
                        <div class="grid5"><input type="text" name="amount8" id='amount8' /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>1</label></div>
                        <div class="grid4"><input type="text"  id='1denom' name='1denom'  onkeyup="amountCalculate(this.value,'1','amount9',event,'25cdenom')"  /></div>
                        <div class="grid5"><input type="text" name="amount9" id='amount9' /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>.25</label></div>
                        <div class="grid4"><input type="text" id='25cdenom' name='25cdenom'  onkeyup="amountCalculate(this.value,'.25','amount10',event,'10cdenom')"   /></div>
                        <div class="grid5"><input type="text" name="amount10" id='amount10' /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>.10</label></div>
                        <div class="grid4"><input type="text" id='10cdenom' name='10cdenom'   onkeyup="amountCalculate(this.value,'.10','amount11',event,'5cdenom')"  /></div>
                        <div class="grid5"><input type="text" name="amount11" id='amount11' /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>.05</label></div>
                        <div class="grid4"><input type="text" id='5cdenom' name='5cdenom'  onkeyup="amountCalculate(this.value,'.05','amount12',event,'revolving_remittance')"  /></div>
                        <div class="grid5"><input type="text" name="amount12" id='amount12' /></div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
					
						<div class='grid4'>&nbsp;</div>
						<div class="grid3"><label>Total</label></div>
                        <div class="grid5"><input type="text" name="regular" id='cash_total' name='cash_total'/></div>
                        <div class="clear"></div>
                    </div>

				</div>	
 
				
				<div class="widget fluid">
                    <div class="formRow">
                        <div class="grid3"><label>Total In Words</label></div>
                        <div class="grid9"><textarea id='total_in_pesos' name='total_in_pesos' rows="3" cols="" name="textarea" class="auto"></textarea></div>

                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Revolving Fund</label></div>
                        <div class="grid4"><input type="text" name="regular" id='revolving_remittance' name='revolving_remittance' /></div>
						<div class='grid5'>&nbsp;</div>

                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>For Deposit/Net Revenue</label></div>
                        <div class="grid4"><input type="text" name="regular" id='for_deposit' name='for_deposit' /></div>
						<div class='grid5'>&nbsp;</div>

                        <div class="clear"></div>
                    </div>

                    <div class="formRow">
						<div class='grid12' align=center><input  class='btn btn-primary' type='submit' value='Submit' /></div>
					</div>		
				</div>	
				
			</fieldset>
		</form>
	</div>