jQuery.noConflict();
jQuery(document).ready(function($){
$("#jform_btl_checkbox_copy_delevery").attr("disabled", true);
var zipcode_deli = $('#jform_zipcode_delevery').val();
var city_deli = $('#jform_city_delevery').val();
var address_deli = $('#jform_street_delevery').val();

var zipcode = $('#jform_zipcode').val();
var city = $('#jform_city').val();
var address = $('#jform_street').val();
var country = $('#jform_country').val();
if( (zipcode_deli == zipcode) && (city_deli == city) && (address_deli == address) && (zipcode!="") && (city !="") && (address!="") &&  (country!="") ){ 
    $("#jform_btl_checkbox_copy_delevery").prop('checked', true);
}

if(zipcode_deli !="" || city_deli !="" || address_deli !=""){
	$("#jform_btl_checkbox_copy_delevery").removeAttr("disabled");
}    

$('#jform_city').blur(function() {
	var zipcode = $('#jform_zipcode').val();
	var city = $('#jform_city').val();
	var address = $('#jform_street').val();
    var country = $('#jform_country').val();
    if(address !="" && city !="" && zipcode !="" && country != null){
    	$("#jform_btl_checkbox_copy_delevery").removeAttr("disabled");
    }
});

$('#jform_zipcode').blur(function() {
	var zipcode = $('#jform_zipcode').val();
	var city = $('#jform_city').val();
	var address = $('#jform_street').val();
	var country = $('#jform_country').val();
    if(address !="" && city !="" && zipcode !="" && country !=null){
    	$("#jform_btl_checkbox_copy_delevery").removeAttr("disabled");
    }
});

$('#jform_street').blur(function() {
	var zipcode = $('#jform_zipcode').val();
	var city = $('#jform_city').val();
	var address = $('#jform_street').val();
	var country = $('#jform_country').val();
    if(address !="" && city !="" && zipcode !="" && country !=null){
    	$("#jform_btl_checkbox_copy_delevery").removeAttr("disabled");
    }
});


$('#jform_country').on('change',function() {
	var zipcode = $('#jform_zipcode').val();
	var city = $('#jform_city').val();
	var address = $('#jform_street').val();
	var country = $('#jform_country').val();
    if(address !="" && city !="" && zipcode !="" && country !=null){
    	$("#jform_btl_checkbox_copy_delevery").removeAttr("disabled");
    }
});

        
//copy data checkbox  
$('#jform_btl_checkbox_copy_delevery').change(function() {
    if($(this).is(":checked")) {	   
        var zipcode = $('#jform_zipcode').val();
    	var city = $('#jform_city').val();
    	var address = $('#jform_street').val();         		            
        //data copy
        $("#jform_street_delevery").val(address);
        $("#jform_city_delevery").val(city);
        $("#jform_zipcode_delevery").val(zipcode);

        var country = $('#jform_country option:selected').val(); 
        $("#jform_country_delevery option").each(function () {         
            if ($(this).val() == country) { 
                $("#jform_country_delevery_chzn a span").text($(this).text().trim());
                $("#jform_country_delevery option[value='" + $(this).val() + "']").attr("selected", "selected");
                return;
            }
        });
        
    } else {
    	$("#jform_street_delevery").val("");
        $("#jform_city_delevery").val("");
        $("#jform_zipcode_delevery").val("");
        $("#jform_country_delevery_chzn a span").text("Select option");
        //$("#jform_country_delevery option[value='CHE']").attr("selected", "");
    }       
});

});

// custom add new field "subscribe" into users table
 
