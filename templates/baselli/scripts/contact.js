jQuery(function($) 
{
	$('#system-message-container .close').click(function() {
		$('#system-message-container').remove();
	});

	var sendmail_msg = $('#contact-message').html();
	$('.contact-message').remove();
	$('#s-msg').html(sendmail_msg);
	$('#contact').click(function()
	{
		$('#form-claim').hide();
		$('#contact').addClass( "active" );
		$('#claim').removeClass( "active" );
		jQuery('#act').val('contact');
		$('#message-row').show();
	});
	$('#claim').click(function()
	{
		var claim_html = $('#hdn-claim').text();
		$('#form-claim').html(claim_html).fadeIn("slow");
		$('#claim').addClass( "active" );
		$('#contact').removeClass( "active" );
		jQuery('#act').val('claim');
		$('#message-row').hide();
	});

});
function addClaim()
{
	if (
		jQuery.trim(jQuery('#jform_name_of_product').val()) != '' &&
		jQuery.trim(jQuery('#jform_color_of_product').val()) != '' &&
		jQuery.trim(jQuery('#jform_description').val()) != ''
	) {
		type_arr = check_user_lang();
		console.log(type_arr);
		type_1 = jQuery('#jform_type_of_issue_1').attr('checked') == 'checked' ? type_arr[0] : '';
		type_2 = jQuery('#jform_type_of_issue_2').attr('checked') == 'checked' ? type_arr[1] : '';
		if(type_1 != '' && type_2 != '') {
			type = type_1 + ', ' + type_2;
		} else if(type_1 != '' && type_2 == '') {
			type = type_1;
		} else if(type_2 != '' && type_1 == '') {
			type = type_2;
		} else {
			type = '';
		}

		product_name = jQuery('#jform_name_of_product').val();
		product_color = jQuery('#jform_color_of_product').val();
		product_invoice_number = jQuery('#jform_invoice_number').val();
		product_desc = jQuery('#jform_description').val();

		html = jQuery('#tbody-claim').html();
		html += '<div class="form-row claim-element" id="claim-element-0">';
		html += '<div class="col-md-1 claim-index">0</div>';
		html += '<div class="col-md-1 claim-product-name">' + product_name + '</div>';
		html += '<div class="col-md-2">' + product_color + '</div>';
		html += '<div class="col-md-1">' + product_invoice_number + '</div>';
		html += '<div class="col-md-2">' + type + '</div>';
		html += '<div class="col-md-4" style="text-align: left">' + product_desc + '</div>';
		html += '<div class="col-md-1 claim-act" onclick="delClaim()">X</div>';
		html += '</div><div class="clearfix"></div>';
		jQuery('#tbody-claim').html(html);

		// Hidden post data
		hdn_post_data = jQuery('#hdn-claim-data-tmp').html();
		hdn_post_data += '<tr class="claim-elements">';
		hdn_post_data += '<td class="claim-indexs" style=" width: 10%; text-align:center; padding: 12px 0;">0</td>';
		hdn_post_data += '<td style=" width: 15%; padding: 12px 0; text-align:center;">' + product_name + '</td>';
		hdn_post_data += '<td style=" width: 15%; padding: 12px 0; text-align:center;">' + product_color + '</td>';
		hdn_post_data += '<td style=" width: 10%; padding: 12px 0; text-align:center;">' + product_invoice_number + '</td>';
		hdn_post_data += '<td style=" width: 20%; padding: 12px 0; text-align:center;">' + type + '</td>';
		hdn_post_data += '<td style=" width: 30%; text-align: left; padding: 12px 0 12px 10px;">' + product_desc + '</td>';
		hdn_post_data += '</tr>';
		jQuery('#hdn-claim-data-tmp').html(hdn_post_data);
		jQuery('#has_claim').val(parseInt(jQuery('#has_claim').val()) + 1);
		reIndexClaim();
		resetClaim();
	} else alert(add_msg);
}
function resetClaim()
{
	jQuery('#jform_name_of_product').val('');
	jQuery('#jform_color_of_product').val('');
	jQuery('#jform_invoice_number').val('');
	jQuery('#jform_description').val('');
	jQuery('#jform_type_of_issue_1').removeAttr('checked');
	jQuery('#jform_type_of_issue_2').removeAttr('checked');
	jQuery('#box-1').removeClass('box-checked');
	jQuery('#box-2').removeClass('box-checked');
}
function delClaim(id)
{
	if (confirm(del_msg))
	{
		var has_claim_select = jQuery('#has_claim');
		var has_claim = has_claim_select.val();
		if(parseInt(has_claim) > 0) {
			has_claim_select.val(parseInt(has_claim) - 1);
		}
		jQuery('#claim-element-' + id).remove();
		jQuery('#claim-element-tr-' + id).remove();
		reIndexClaim();
	}
}
function reIndexClaim()
{
	var total = 0;
	jQuery('.claim-element').each(function( index ) 
	{
		total++;
	    jQuery( this ).find('.claim-index').html(index + 1);
		jQuery( this ).attr("id", "claim-element-" + (index + 1));
		jQuery( this ).find('.claim-act').attr("onclick", "delClaim(" + (index + 1) + ")");
	});
	jQuery('.claim-elements').each(function( index ) 
	{
	    jQuery( this ).find('.claim-indexs').html(index + 1);
		jQuery( this ).attr("id", "claim-element-tr-" + (index + 1));
	});
	jQuery('#claimed-products').html(total);

	jQuery('#dv-tmp-claim').html(jQuery('#dv-claim').html());
	jQuery('#dv-tmp-claim .claim-act').remove();
	html_data = jQuery('#hdn-claim-data-tmp').html();
	jQuery('#hdn-claim-data').html('<table style="width: 100%; margin-top: 1px; font-size: 14px; color: rgb(51, 51, 51); border-collapse: collapse; border-top: 1px solid rgb(218, 218, 218); border-bottom: 1px solid rgb(218, 218, 218);">' + html_data + '</table>');
	jQuery('#rs_claim').html(jQuery('#dv-tmp-claim').html());
	jQuery('#dv-tmp-claim').html('');
}

function htmlEncode(value){
  //create a in-memory div, set it's inner text(which jQuery automatically encodes)
  //then grab the encoded contents back out.  The div never exists on the page.
  return jQuery('<div/>').text(value).html();
}

function check_user_lang() {
	switch(CURRENT_LANG) {
		case 'NULL' : label = [MECHANICAL_ISSUE, COLOR_ISSUE]; break;
		case 'de-DE'	: label = ['Beanstandung der Mechanik', 'Beanstandung der Farbe']; break;
		case 'it-IT'	: label = ['Problema meccanico', 'L\'edizione di colore']; break;
		case 'fr-FR'	: label = ['Problème mécanique', 'Problème de la couleur']; break;
		default			: label = ['Mechanical issue', 'Color issue']; break;
	}
	return label;
}

function checked_element(id, this_id) {
	if(jQuery(id).attr('checked') == 'checked') {
		jQuery(id).removeAttr('checked');
		if(jQuery(this_id).hasClass('box-checked')) {
			jQuery(this_id).removeClass('box-checked');
		}
	} else {
		jQuery(id).attr('checked', 'checked');
		jQuery(this).remove();
		jQuery(this_id).addClass('box-checked');
	}
}

var geocodePosition = function (pos) {
	geocoder.geocode({
		latLng: pos
	}, function (responses) {
		if (responses && responses.length > 0) {
			updateMarkerAddress(responses[0].formatted_address);
		} else {
			updateMarkerAddress('Cannot determine address at this location.');
		}
	});
}


var initialize = function () {
	
	var map = new google.maps.Map(document.getElementById('mapCanvas'), {
		zoom: 8,
		center: latLng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	});
	var marker = new google.maps.Marker({
		position: latLng,
		title: 'Baselli',
		map: map,
		icon: _icon
		//draggable: true
	});
	
}