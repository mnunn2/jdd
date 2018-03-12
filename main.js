jQuery().ready(function() {


	jQuery(".frm").hide("fast");
	jQuery("#sf1").show("slow");

	// validate form on keyup and submit
	var v = jQuery("#dd-form").validate({

		rules: {

			firstName: {
				required: true,
				minlength: 2,
				maxlength: 30
			},
			keyname: {
				required: true,
				minlength: 2,
				maxlength: 30
			},
			emailAddress: {
				required: true,
				//emailAddress: true
			},
			postcode: {
				required: true
			},
			accNameOK: {
				required: true,
			},
			accountName: {
				required: true,
				minlength: 2,
				maxlength: 30
			},
			sortCode: {
				required: true,
				number: true,
				minlength: 6,
				maxlength: 6
			},
			accountNumber: {
				required: true,
				number: true,
				minlength: 8,
				maxlength: 8
			}
		},



		highlight: function(element) {
			jQuery(element).closest('.form-group').addClass('has-error');
		},

		unhighlight: function(element) {
			jQuery(element).closest('.form-group').removeClass('has-error');
		},

		messages: {
			accNameOK: "please Please confirm the account is in your name.",
		},

		errorElement: "span",
		errorClass: "help-block",
		errorPlacement: function(error, element) {
			if(element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}

	});


	jQuery(".open1").click(function(e) {
		e.preventDefault();
		if (v.form()) {
			jQuery(".frm").hide("fast");
			jQuery("#sf2").show("slow");
			jQuery("html, body").animate({ scrollTop: 0 }, "slow");
			return false;
		}
	});

	jQuery(".open2").click(function(e) {
		e.preventDefault();
		jQuery("#rName").html(
			jQuery('#title :selected').text() + " " +
			jQuery("#firstName").val() + " " +
			jQuery("#keyname").val()
		);
		jQuery("#rAdd").html(
			jQuery("#addressLine1").val() + "<br>" +
			jQuery("#addressLine3").val() + "<br>" +
			jQuery("#addressLine4").val() + "<br>" +
			jQuery("#postcode").val()
		);

		jQuery("#remailAddress").html(jQuery("#emailAddress").val());

		jQuery("#rPhone").html(
			"m: " + jQuery("#mobileNumber").val() + "<br>" +
			"h: " + jQuery("#eveningTelephone").val()
		);

		jQuery("#raccountName").html(jQuery("#accountName").val());
		jQuery("#rSortCode").html(jQuery("#sortCode").val());
		jQuery("#rAccNum").html(jQuery("#accountNumber").val());

		if (v.form()) {
			// validateBankFlag is set by joomla params 
			if(validateBankFlag) {
				jQuery(".frm").hide("fast");
				jQuery("#sf3").show("slow");
			} else {
				var validateResult = validateBank(jQuery("#sortCode").val(), jQuery("#accountNumber").val());
				//alert("check ok");
			}
		}
	});

	jQuery(".open3").click(function(e) {
		e.preventDefault();
		if (v.form()) {
			jQuery("#loader").show();
			jQuery("#dd-form").submit();
			return false;
		}
	});

	jQuery(".back2").click(function(e) {
		e.preventDefault();
		jQuery(".frm").hide("fast");
		jQuery("#sf1").show("slow");
	});

	jQuery(".back3").click(function(e) {
		e.preventDefault();
		jQuery(".frm").hide("fast");
		jQuery("#sf2").show("slow");
	});

	jQuery(".fred").click(function() {
		alert("hello click me");
	});
});

function prtDiv(divID) {
	var divToPrint = document.getElementById(divID);
	var popupWin = window.open('', '', 'letf=0,top=0,width=850,height=900,toolbar=0,scrollbars=0,status=0');
	popupWin.document.open();
	popupWin.document.write('<html><head><title>Direct Debit Guarantee</title><link rel="stylesheet" ' +
		'type="text/css" href="../css/print.css"></head><body onload="window.print()">' +
		divToPrint.innerHTML + '</html>');
	popupWin.document.close();
	//popupWin.close();
}

// start of crafty clicks js
// var cp_access_token is set through params; 
var cp_obj_1 = CraftyPostcodeCreate();
window.addEventListener("load", jddInit);
function jddInit(){
	cp_obj_1.set("access_token", cp_access_token); 
	cp_obj_1.set("first_res_line", "----- please select your address ----"); 
	cp_obj_1.set("res_autoselect", "0");
	cp_obj_1.set("result_elem_id", "crafty_postcode_result_display_1");
	cp_obj_1.set("form", "dd-form");
	cp_obj_1.set("elem_street1"  , "addressLine1");
	cp_obj_1.set("elem_street2"  , "");
	cp_obj_1.set("elem_street3"  , ""); // or if you have only 2 address lines cp_obj_1.set("elem_street3"  , "");
	cp_obj_1.set("elem_town"     , "addressLine3");
	cp_obj_1.set("elem_county"   , "addressLine4"); // optional
	cp_obj_1.set("elem_postcode" , "postcode");
	// cp_obj_1.set("hide_result" 	 , 1); // optional
	cp_obj_1.set("single_res_autoselect" , 1); // don't show a drop down box if only one matching address is found
	// cp_obj_1.set("debug_mode" , 1);
	cp_obj_1.set("on_result_ready", function(){
		// Do something when result received from server
		var x = document.getElementById("crafty_postcode_result_display_1");
		x.style.padding = "0px 0px 15px";
	});
	cp_obj_1.set("on_error", function(){
		// Do something when an error occurs
		var x = document.getElementById("crafty_postcode_result_display_1");
		x.style.padding = "0px 0px 15px";
		var y = document.getElementById("addressGroup");
		y.style.display = "block";
	}); 
}
// end of crafty clicks js


function validateBank(sortCode, accNum) {
	// key and url are set using joomla params 
	jQuery.getJSON(pcaURL, {
		Key: pcaKey,
		AccountNumber: accNum,
		SortCode: sortCode
	},
		function(data) {
			// Test for an error
			if (data.Items.length == 1 && typeof(data.Items[0].Error) != "undefined") {
				// Show the error message
				alert("Problem " + data.Items[0].Description);
			} else {
				// Check if there were any items found
				if (data.Items.length === 0) {
					alert("Sorry, there were no results");
				} else {
					if (data.Items[0].IsCorrect && data.Items[0].IsDirectDebitCapable) {
						jQuery("#bankName").val(data.Items[0].Bank);
						jQuery("#bankAddress").val(data.Items[0].ContactAddressLine1);
						jQuery("#bankPostCode").val(data.Items[0].ContactPostcode);
						//console.log("ok" + JSON.stringify(data.Items));
						jQuery(".frm").hide("fast");
						jQuery("#sf3").show("slow");
					} else {
						if (data.Items[0].IsCorrect && !(data.Items[0].IsDirectDebitCapable)) {
							//console.log(JSON.stringify(data.Items));
							alert("your account details are correct but it is not direct" +
								"debit enabled eg savings account\n" +
								"bank ok " + data.Items[0].IsCorrect + "\n" +
								"dd ok " + data.Items[0].IsDirectDebitCapable);
						} else {
							//console.log(JSON.stringify(data.Items));
							alert("Your account details have not been validated\n" +
								"please re-enter your account details");
						}
					}
				}
			}
		});

	if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
		var msViewportStyle = document.createElement('style');
		msViewportStyle.appendChild(
			document.createTextNode(
				'@-ms-viewport{width:auto!important}'
			)
		);
		document.querySelector('head').appendChild(msViewportStyle);
	}
}
