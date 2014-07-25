$(function() {	
	//===== Modal =====//
	
    $('#refund_modal').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {
					$('#refund_form').submit();
				}
			}
		});
		
    $('#refund_open').click(function () {
        $('#refund_modal').dialog('open');
        return false;
    });
	

	
	
    $('#fare_adjustment_modal').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {
					$('#fare_adjustment_form').submit();
				}
			}
		});
		
    $('#fa_open').click(function () {
        $('#fare_adjustment_modal').dialog('open');
        return false;
    });


    $('#discount_modal').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {
					$('#discount_form').submit();
				}
			}
		});
		
    $('#discount_open').click(function () {
        $('#discount_modal').dialog('open');
        return false;
    });

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
		modal: true,
		buttons: {
				"Submit": function() {
					//$('#discount_form').submit();
				}
			}
		});


    $('#open_ctf').click(function () {
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
					//$('#discount_form').submit();
				}
			}
		});


    $('#open_pnb').click(function () {
        $('#pnb_modal').dialog('open');
        return false;
    });

    $('#ticket_order_modal').dialog({
		autoOpen: false, 
		width: 400,
		modal: true,
		buttons: {
				"Submit": function() {
					//$('#discount_form').submit();
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
					//$('#discount_form').submit();
				}
			}
		});


    $('#open_defective').click(function () {
        $('#physically_defective_modal').dialog('open');
        return false;
    });	

	
	
});