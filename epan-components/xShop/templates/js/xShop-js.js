$.each({
	
	// openPage:function( product_id ){

	// 	$(this).univ().frameURL('Product Detail','index.php?epan=web&subpage=xshop-productdetail&product_id='+product_id);
	// }
	calculateRate: function(qty_field,rate,rate_field) {
		if($(qty_field).val() >= 1)
  	    	$(rate_field).val(($(qty_field).val() * 1) * ($(rate).val() * 1));
		else{
			alert("Number should be greater than 1");
			$(qty_field).val(1);
			$(rate_field).val($(rate).val());	
		}
    },
    calculateRow: function(rate_field,qty_field,amount_field){
    	$(qty_field).val($(qty_field).val().replace(/[^0-9\.]/g,''));
    	$(amount_field).val($(rate_field).val() * $(qty_field).val());
  	},
  	calculateTotal: function(amount_fields, total_field){
    	var total=0
    	console.log(amount_fields);
    	$.each(amount_fields, function(index, val) {
      	total += ($(val).val()*1);
    	});
    	$(total_field).val(total);
 	},
 	calculateNet: function(total_field,net_field){
    	$(net_field).val(($(total_field).val()*1));
  	},
  	validateVoucher:function(voucher_field_id, form_id, discount_amount_field_id,total_field_id,net_field_id){
      // $.univ().ajaxec('index.php?page=xecommApp_page_ajaxhandler&cut_page=1&task=validateVoucher&v_no='+$(voucher_no).val()+'&subpage=xecomm-junk');      

      if($(voucher_field_id).val()=="") return;
      $.ajax({
            url: 'index.php?page=xShop_page_ajaxhandler&cut_page=1&task=validateVoucher&v_no='+$(voucher_field_id).val()+'&subpage=xshop-junk',
            type: 'GET',
            data: {
              form : form_id,
              voucher_field : $(voucher_field_id).attr('name'),
              discount_field : discount_amount_field_id,
              total_field : total_field_id,
              net_field : net_field_id
            }
          })
          .done(function(ret) {
            eval(ret);
            console.log(ret);
          })
          .fail(function() {
            $(this).univ().errorMessage('Oops, Activity was not edited');
          })
          .always(function() {
            console.log("complete");
          });
  	},
    copyBillingAddress:function(b_a,b_l,b_c,b_s,b_country,b_p,s_a,s_l,s_c,s_s,s_country,s_p){
      $(s_a).val($(b_a).val());
      $(s_s).val($(b_s).val());
      $(s_l).val($(b_l).val());
      $(s_c).val($(b_c).val());
      $(s_country).val($(b_country).val());
      $(s_p).val($(b_p).val());
    }
},$.univ._import);

$(document).ready(function(){

	$('.xshop-product-enquiry-framurl-btn').click(function(){
		$(this).univ().frameURL('Enquiry Form','index.php?page=xShop_page_productenquiry');
	});
});