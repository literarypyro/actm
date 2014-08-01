$(function() {	
	//===== Modal =====//
	


	

    $('#unreg_sale_modal').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {
					$('#unreg_form').submit();
				}
			}
		});
		
    $('#unreg_open').click(function () {
        $('#unreg_sale_modal').dialog('open');
        return false;
    });


    $('#cash_transfer_modal').dialog({
		autoOpen: false, 
		width: 400,
		height:700,
		modal: true,
		buttons: {
				"Submit": function() {
					$("#ctf_denom").prependTo("#beforesubmit");

					if($('#type').val()=="remittance"){
						var control_remittance=$('#net_remittance').val();
						var cash_remittance=$('#cash_total').val();
						var discrepancy="none";
						var discrep_amount=0;
						$.ajax({url:"processing.php?submitRemittance="+$('#cs_ticket_seller').val()+"&amount="+$('#net_remittance').val() });
						
						
						$.ajax({url:"processing.php?getPartial="+$('#cs_ticket_seller').val(),success:function(result){
							var partial_remittance=result;
							
							var remittance=control_remittance+partial_remittance;
							
							
							if(remittance>cash_remittance){
								discrep_amount=remittance-cash_remittance;
								var check=confirm("You have a Shortage amount of P"+discrep_amount);
						
								if(check==true){
									document.getElementById('ctf_form').action=document.getElementById('ctf_form').action+"?shortage_payment=Y&type=shortage&amount="+discrep_amount;

									$('#ctf_form').submit();	
									//document.forms['cash_form'].submit();
									//window.opener.location.reload();
											
								}
							}
							else if(remittance<cash_remittance){
								discrep_amount=cash_remittance-remittance;
								var check=confirm("You have an Overage amount of P"+discrep_amount);
						
								if(check==true){
									document.getElementById('ctf_form').action=document.getElementById('ctf_form').action+"?type=overage&amount="+discrep_amount;
									$('#ctf_form').submit();	

									//document.forms['cash_form'].submit();
									//window.opener.location.reload();
											
								}
							
							
							}
							else {
								alert("You have no discrepancy");
								$('#ctf_form').submit();	

							
							}
						}});
						
					}
					else {
						$('#ctf_form').submit();

					}

				}
				
				
				
			}
		});

	$('#open_remit').show();
    $('#open_ctf').click(function () {
        $('#cash_transfer_modal').show();
		$('#cash_transfer_modal').dialog('open');
        return false;
    });



    $('#denomination_modal').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {

					$(this).dialog('close');

				}
			}
		});


    $('#open_denomination').click(function () {
        $('#denomination_modal').dialog('open');
        return false;
    });



    $('#denomination_modal2').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {
					$(this).dialog('close');

				}
			}
		});


    $('#open_denomination2').click(function () {
        $('#denomination_modal2').dialog('open');
        return false;
    });


    $('#pnb_modal').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {
					$("#denomination_modal2").prependTo("#beforesubmit2");

					$('#pnb_submit_form').submit();
				}
			}
		});


    $('#open_pnb').click(function () {
		$('#pnb_modal').show();
        $('#pnb_modal').dialog('open');
        return false;
    });

    $('#ticket_order_modal').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {
					$('#ticket_order_form').submit();
				}
			}
		});


    $('#open_ticket').click(function () {
        $('#ticket_order_modal').dialog('open');
        return false;
    });	
	
    $('#physically_defective_modal').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {
					$('#physically_defective_form').submit();
				}
			}
		});


    $('#open_defective').click(function () {
        $('#physically_defective_modal').dialog('open');
        return false;
    });	



    $('#open_entry').click(function () {
        $('#begin_balance_cash').dialog('open');
        return false;
    });


    $('#begin_balance_cash').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {

					$('#bb_cash_form').submit();
				}
			}
		});

    $('#sj_entry').click(function () {
        $('#begin_balance_sj').dialog('open');
        return false;
    });


    $('#begin_balance_sj').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {

					$('#bb_sj_form').submit();
				}
			}
		});	

    $('#sv_entry').click(function () {
        $('#begin_balance_sv').dialog('open');
        return false;
    });


    $('#begin_balance_sv').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {

					$('#bb_sv_form').submit();
				}
			}
		});	
	
		
});