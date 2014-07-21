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












});