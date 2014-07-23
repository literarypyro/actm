<script language='javascript' src='ajax.js'></script>
<script language='javascript'>
function openSummary(action){
	if(action=="sales"){
		makeajax('processing.php?summary_sales=Y','retrieveSales');
		
	
	}
	else if(action=="cash"){
		makeajax('processing.php?summary_cash=Y','retrieveCash');
	
	}
	else if(action=="ticket"){
		makeajax('processing.php?summary_tickets=Y','retrieveTickets');	
	}

}

function retrieveSales(response){
	var salesObject=JSON.parse(response);

	document.getElementById('sj_sales').innerHTML=salesObject.sj_sales;
	document.getElementById('sjd_sales').innerHTML=salesObject.sjd_sales;
	document.getElementById('sv_sales').innerHTML=salesObject.sv_sales;
	document.getElementById('svd_sales').innerHTML=salesObject.svd_sales;
	
	document.getElementById('fare_sales').innerHTML=salesObject.fare_sales;
	document.getElementById('ot_sales').innerHTML=salesObject.ot_sales;
	document.getElementById('unreg_sales').innerHTML=salesObject.unreg_sales;
	document.getElementById('gross_sales').innerHTML=salesObject.gross_sales;
	document.getElementById('refund_sales').innerHTML=salesObject.refund_sales;
	document.getElementById('disc_sales').innerHTML=salesObject.disc_sales;
	document.getElementById('net_sales').innerHTML=salesObject.net_sales;
	
	$('#summary_sales').dialog('open');

}
function retrieveCash(response){
	var salesObject=JSON.parse(response);

	document.getElementById('cash_beginning').innerHTML=salesObject.cash_beginning;
	document.getElementById('revolving_fund').innerHTML=salesObject.revolving_fund;
	document.getElementById('for_deposit').innerHTML=salesObject.for_deposit;
	document.getElementById('subtotal').innerHTML=salesObject.subtotal;
	
	document.getElementById('pnb_deposit_c').innerHTML=salesObject.pnb_deposit_c;
	document.getElementById('pnb_deposit_p').innerHTML=salesObject.pnb_deposit_p;
	document.getElementById('subtotal_2').innerHTML=salesObject.subtotal_2;
	document.getElementById('overage').innerHTML=salesObject.overage;
	document.getElementById('unpaid_shortage').innerHTML=salesObject.unpaid_shortage;
	document.getElementById('cash_ending').innerHTML=salesObject.cash_ending;
	$('#summary_cash').dialog('open');

}

function retrieveTickets(response){
	var salesObject=JSON.parse(response);
			
	document.getElementById('sjt_beginning_balance').innerHTML=salesObject.sjt_beginning_balance;
	document.getElementById('sjd_beginning_balance').innerHTML=salesObject.sjd_beginning_balance;
	document.getElementById('svt_beginning_balance').innerHTML=salesObject.svt_beginning_balance;
	document.getElementById('svd_beginning_balance').innerHTML=salesObject.svd_beginning_balance;
	
	document.getElementById('sjt_initial_amount').innerHTML=salesObject.sjt_initial_amount;
	document.getElementById('sjd_initial_amount').innerHTML=salesObject.sjd_initial_amount;
	document.getElementById('svt_initial_amount').innerHTML=salesObject.svt_initial_amount;
	document.getElementById('svd_initial_amount').innerHTML=salesObject.svd_initial_amount;
	
	document.getElementById('sjt_additional_amount').innerHTML=salesObject.sjt_additional_amount;
	document.getElementById('sjd_additional_amount').innerHTML=salesObject.sjd_additional_amount;
	document.getElementById('svt_additional_amount').innerHTML=salesObject.svt_additional_amount;
	document.getElementById('svd_additional_amount').innerHTML=salesObject.svd_additional_amount;
	
	document.getElementById('sjt_subtotal').innerHTML=salesObject.sjt_subtotal;
	document.getElementById('sjd_subtotal').innerHTML=salesObject.sjd_subtotal;
	document.getElementById('svt_subtotal').innerHTML=salesObject.svt_subtotal;
	document.getElementById('svd_subtotal').innerHTML=salesObject.svd_subtotal;

	document.getElementById('sjt_sold').innerHTML=salesObject.sjt_sold;
	document.getElementById('sjd_sold').innerHTML=salesObject.sjd_sold;
	document.getElementById('svt_sold').innerHTML=salesObject.svt_sold;
	document.getElementById('svd_sold').innerHTML=salesObject.svd_sold;

	document.getElementById('sjt_physically_defective').innerHTML=salesObject.sjt_physically_defective;
	document.getElementById('sjd_physically_defective').innerHTML=salesObject.sjd_physically_defective;
	document.getElementById('svt_physically_defective').innerHTML=salesObject.svt_physically_defective;
	document.getElementById('svd_physically_defective').innerHTML=salesObject.svd_physically_defective;
	
	document.getElementById('cash_beginning').innerHTML=salesObject.cash_beginning;
	document.getElementById('revolving_fund').innerHTML=salesObject.revolving_fund;
	document.getElementById('for_deposit').innerHTML=salesObject.for_deposit;
	document.getElementById('subtotal').innerHTML=salesObject.subtotal;
	
	
	document.getElementById('sjt_defective').innerHTML=salesObject.sjt_defective;
	document.getElementById('sjd_defective').innerHTML=salesObject.sjd_defective;
	document.getElementById('svt_defective').innerHTML=salesObject.svt_defective;
	document.getElementById('svd_defective').innerHTML=salesObject.svd_defective;
	
	document.getElementById('sjt_label').innerHTML=salesObject.sjt_label;
	document.getElementById('sjd_label').innerHTML=salesObject.sjd_label;
	document.getElementById('svt_label').innerHTML=salesObject.svt_label;
	document.getElementById('svd_label').innerHTML=salesObject.svd_label;
	
	document.getElementById('sjt_grand_total').innerHTML=salesObject.sjt_grand_total;
	document.getElementById('sjd_grand_total').innerHTML=salesObject.sjd_grand_total;
	document.getElementById('svt_grand_total').innerHTML=salesObject.svt_grand_total;
	document.getElementById('svd_grand_total').innerHTML=salesObject.svd_grand_total;

	$('#summary_ticket').dialog('open');

}



</script>



<div id="summary_sales" name='summary_sales' class="customDialog" title="Total Sales">

<table class="tDefault checkAll tMedia " id="checkAll" width=100%>
<tbody>
<tr class='grid'>
	<td align=center width=40%>SJ</td>
	<td align=right width=30%>PHP</td>
	<td align=right width=30% id='sj_sales' name='sj_sales'><?php //echo number_format($sjt_sales*1,2); ?></td>
</tr>	
<tr class='grid'>
	<td >DSJ</td>
	<td>&nbsp;</td>
	<td align=right id='sjd_sales' name='sjd_sales'><?php //echo number_format($sjd_sales*1,2); ?></td>
</tr>	
<tr class='grid'>
	<td>SV</td>
	<td>&nbsp;</td>
	<td align=right id='sv_sales' name='sv_sales'><?php// echo number_format($svt_sales*1,2); ?></td>
</tr>	
<tr class='grid'>
	<td >DSV</td>
	<td >&nbsp;</td>
	<td  align=right id='svd_sales' name='svd_sales'><?php// echo number_format($svd_sales*1,2); ?></td>
</tr>	
<tr class='grid'>
	<td>Fare Adjust.</td>
	<td >&nbsp;</td>
	<td align=right id='fare_sales' name='fare_sales'><?php //echo number_format($fare_adjustment*1,2); ?></td>
</tr>	
<tr class='grid'>
	<td>O.T.</td>
	<td>&nbsp;</td>
	<td align=right id='ot_sales' name='ot_sales'><?php //echo number_format($ot_amount*1,2); ?></td>
</tr>	
<tr class='grid'>
	<td>Unreg Sale</td>
	<td>&nbsp;</td>
	<td align=right id='unreg_sales' name='unreg_sales'><?php //echo number_format($unreg_sale*1,2); ?></td>
</tr>	
<tr class='subheader'>
	<td>Grand Total</td>
	<td>&nbsp;</td>
	<td align=right id='gross_sales' name='gross_sales'><b><?php //echo number_format($grandTotal*1,2); ?></b></td>
</tr>	
<tr class='grid'>
	<td>Less: Refund</td>
	<td>&nbsp;</td>
	<td align=right  id='refund_sales' name='refund_sales'><?php //echo number_format($refund*1,2); ?></td>
</tr>	
<tr class='grid'>
	<td>Discount</td>
	<td>&nbsp;</td>
	<td align=right id='disc_sales' name='disc_sales'><?php //echo number_format($discount*1,2); ?></td>
</tr>	
<tr class='header'>
	<td>NET SALES</td>
	<td  align=right>PHP</td>
	<td align=right ><b id='net_sales' name='net_sales'><?php //echo number_format($netSales*1,2); ?></b></td>
</tr>	
</tbody>
</table>
						
						
</div>						




<div id="summary_ticket" name='summary_ticket' class="customDialog" title="Tickets">

<table style='height:400px;' class="tDefault checkAll tMedia" id="checkAll" width=100%>
<thead>
<tr class='header'>
	<td style='width:40%'>Tickets</td>
	<td>SJT</td>
	<td>DSJT</td>
	<td>SVT</td>
	<td>DSVT</td>
</tr>
</thead>
<tbody>
<tr class='grid' >
	<td>Beginning Balance</td>
	<td align=right id='sjt_beginning_balance' name='sjt_beginning_balance'><?php// echo number_format($sjt_beginning_balance*1,0); ?></td>
	<td align=right id='sjd_beginning_balance' name='sjd_beginning_balance'><?php// echo number_format($sjd_beginning_balance*1,0); ?></td>
	<td align=right id='svt_beginning_balance' name='svt_beginning_balance'><?php// echo number_format($svt_beginning_balance*1,0); ?></td>
	<td align=right id='svd_beginning_balance' name='svd_beginning_balance'><?php// echo number_format($svd_beginning_balance*1,0); ?></td>
</tr>	
<tr class='grid'>
	<td>Initial</td>
	<td align=right id='sjt_initial_amount' name='sjt_initial_amount'><?php// echo number_format($sjt_initial_amount*1,0); ?></td>
	<td align=right id='sjd_initial_amount' name='sjd_initial_amount'><?php// echo number_format($sjd_initial_amount*1,0); ?></td>
	<td align=right id='svt_initial_amount' name='svt_initial_amount'><?php// echo number_format($svt_initial_amount*1,0); ?></td>
	<td align=right id='svd_initial_amount' name='svd_initial_amount'><?php// echo number_format($svd_initial_amount*1,0); ?></td>
</tr>	
<tr class='grid'>
	<td>Additional</td>
	<td align=right id='sjt_additional_amount' name='sjt_additional_amount'><?php //echo number_format($sjt_additional_amount*1,0); ?></td>
	<td align=right id='sjd_additional_amount' name='sjd_additional_amount'><?php// echo number_format($sjd_additional_amount*1,0); ?></td>
	<td align=right id='svt_additional_amount' name='svt_additional_amount'><?php //echo number_format($svt_additional_amount*1,0); ?></td>
	<td align=right id='svd_additional_amount' name='svd_additional_amount'><?php //echo number_format($svd_additional_amount*1,0); ?></td>
</tr>	
<tr class='subheader'>
	<td><font>Total</font></td>
	<td align=right id='sjt_subtotal' name='sjt_subtotal'><font><?php// echo number_format($sjt_subtotal*1,0); ?></font></td>
	<td align=right id='sjd_subtotal' name='sjd_subtotal'><font><?php //echo number_format($sjd_subtotal*1,0); ?></font></td>
	<td align=right id='svt_subtotal' name='svt_subtotal'><font><?php //echo number_format($svt_subtotal*1,0); ?></font></td>
	<td align=right id='svd_subtotal' name='svd_subtotal'><font><?php //echo number_format($svd_subtotal*1,0); ?></font></td>
</tr>	
<tr class='grid'>
	<td>Less: Tickets Sold</td>
	<td align=right id='sjt_sold' name='sjt_sold'><?php// echo number_format($sjt_sold*1,0); ?></td>
	<td align=right id='sjd_sold' name='sjd_sold'><?php// echo number_format($sjd_sold*1,0); ?></td>
	<td align=right id='svt_sold' name='svt_sold'><?php// echo number_format($svt_sold*1,0); ?></td>
	<td align=right id='svd_sold' name='svd_sold'><?php// echo number_format($svd_sold*1,0); ?></td>	
</tr>	
<tr class='grid'>
	<td>Physically Defective</td>
	
	<td align=right id='sjt_physically_defective' name='sjt_physically_defective'><?php //echo number_format($sjt_physically_defective*1,0); ?></td>
	<td align=right id='sjd_physically_defective' name='sjd_physically_defective'><?php //echo number_format($sjd_physically_defective*1,0); ?></td>
	<td align=right id='svt_physically_defective' name='svt_physically_defective'><?php //echo number_format($svt_physically_defective*1,0); ?></td>
	<td align=right id='svd_physically_defective' name='svd_physically_defective'><?php //echo number_format($svd_physically_defective*1,0); ?></td>		
</tr>	




<tr class='grid'>
	<td>Defective Tickets</td>
	<td align=right id='sjt_defective' name='sjt_defective'><?php //echo number_format($sjt_defective*1,0); ?></td>
	<td align=right id='sjd_defective' name='sjd_defective'><?php //echo number_format($sjd_defective*1,0); ?></td>
	<td align=right id='svt_defective' name='svt_defective'><?php //echo number_format($svt_defective*1,0); ?></td>
	<td align=right id='svd_defective' name='svd_defective'><?php //echo number_format($svd_defective*1,0); ?></td>		
</tr>	
<tr class='grid'>
	<td>Over (Lacking)</td>
	<td align=right id='sjt_label' name='sjt_label'><?php //echo $sjt_label; ?></td>
	<td align=right id='sjd_label' name='sjd_label'><?php// echo $sjd_label; ?></td>
	<td align=right id='svt_label' name='svt_label'><?php //echo $svt_label; ?></td>
	<td align=right id='svd_label' name='svd_label'><?php //echo $svd_label; ?></td>		
</tr>	
<tr class='header'>
	<td><font>Ending Balance</font></td>
	<td align=right><font id='sjt_grand_total' name='sjt_grand_total'><?php //echo number_format($sjt_grand_total*1,0); ?></font></td>
	<td align=right><font id='sjd_grand_total' name='sjd_grand_total'><?php //echo number_format($sjd_grand_total*1,0); ?></font></td>
	<td align=right><font id='svt_grand_total' name='svt_grand_total'><?php //echo number_format($svt_grand_total*1,0); ?></font></td>
	<td align=right><font id='svd_grand_total' name='svd_grand_total'><?php //echo number_format($svd_grand_total*1,0); ?></font></td>
</tr>	


</tbody>
</table>
						
						
</div>						


<div id="summary_cash" name='summary_cash' class="customDialog" title="Cash">

<table style='height:300px;' class="tDefault checkAll tMedia" id="checkAll" width=100%>
<tbody>
<tr class='grid'>
	<td style='width:50%' >Beginning Balance</td>
	<td style='width:50%' id='cash_beginning' name='cash_beginning'><?php //echo number_format($cash_beginning*1,2); ?></td>
</tr>	
<tr class='grid'>
	<td>Revolving Fund</td>
	<td align=right id='revolving_fund' name='revolving_fund'><?php// echo number_format($revolving_fund*1,2); ?></td>
</tr>	
<tr class='grid'>
	<td>Net Sales</td>
	<td align=right id='for_deposit' name='for_deposit'><?php// echo number_format($for_deposit*1,2); ?></td>
</tr>	
<tr class='subheader'>
	<td><font>Total</font></td>
	<td align=right><font id='subtotal' name='subtotal'><?php //echo number_format($subtotal*1,2); ?></font></td>
</tr>
<tr class='grid'>
	<td>PNB (Current)</td>
	<td align=right id='pnb_deposit_c' name='pnb_deposit_c'><?php //echo number_format($pnb_deposit_c*1,2); ?></td>
</tr>
<tr class='grid'>
	<td>PNB (Previous)</td>
	<td align=right id='pnb_deposit_p' name='pnb_deposit_p'><?php// echo number_format($pnb_deposit_p*1,2); ?></td>
</tr>
<tr class='subheader'>
	<td><font>Cash b-4 shortage</font></td>
	<td align=right><font id='subtotal_2' name='subtotal_2'><?php// echo number_format($subtotal_2*1,2); ?></font></td>
</tr>
<tr class='grid'>
	<td>Add: Overage</td>
	<td align=right id='overage' name='overage'><?php //echo number_format($overage*1,2); ?></td>
</tr>
<tr class='grid'>
	<td>Less: Unpaid Shortage</td>
	<td align=right id='unpaid_shortage' name='unpaid_shortage'><?php// echo number_format($unpaid_shortage*1,2); ?></td>
</tr>
<tr class='header'>
	<td><font>Cash Ending Balance</font></td>
	<td  align=right><font id='cash_ending' name='cash_ending'><?php// echo number_format($cash_ending*1,2); ?></font></td>
</tr>


</tbody>
</table>
						
						
</div>						


