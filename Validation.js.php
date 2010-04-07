<? 
header("Content-type: text/javascript");
include "./findconfig.php";

?>

var valid = "";


function pause(millis) 
{
	var date = new Date();
	var curDate = null;
	
	do { curDate = new Date(); } 
	while(curDate-date < millis);
} 



// To use: textvalue = replaceAll(textvalue, ':', 'someThingNew')
function replaceAll(stringValue, replaceValue, newValue)
{
 var functionReturn = new String(stringValue);

 while ( true )
 {
  var currentValue = functionReturn;

  functionReturn = functionReturn.replace(replaceValue, newValue);
  if ( functionReturn == currentValue )
   break;
 }

 return functionReturn;
}


function formatCurrency(num) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num))
	num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+','+
	num.substring(num.length-(4*i+3));
	return (((sign)?'':'-') + '$' + num + '.' + cents);
}


/* Client-side access to querystring name=value pairs
	Version 1.3
	28 May 2008
	
	License (Simplified BSD):
	http://adamv.com/dev/javascript/qslicense.txt
*/
function Querystring(qs) { // optionally pass a querystring to parse
	this.params = {};
	
	if (qs == null) qs = location.search.substring(1, location.search.length);
	if (qs.length == 0) return;

// Turn <plus> back to <space>
// See: http://www.w3.org/TR/REC-html40/interact/forms.html#h-17.13.4.1
	qs = qs.replace(/\+/g, ' ');
	var args = qs.split('&'); // parse out name/value pairs separated via &
	
// split out each name=value pair
	for (var i = 0; i < args.length; i++) {
		var pair = args[i].split('=');
		var name = decodeURIComponent(pair[0]);
		
		var value = (pair.length==2)
			? decodeURIComponent(pair[1])
			: name;
		
		this.params[name] = value;
	}
}

Querystring.prototype.get = function(key, default_) {
	var value = this.params[key];
	return (value != null) ? value : default_;
}

Querystring.prototype.contains = function(key) {
	var value = this.params[key];
	return (value != null);
}



function IsNumeric(sText)

{
   var ValidChars = "0123456789.";
   var IsNumber=true;
   var Char;

 
   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
   return IsNumber;
   
   }

function validateForm(form)
{
	
		valid = true;
		if (form.id == "")
		{
			$(form).attr("id","assignedID");
		}
		
		$("#" + form.id + " .validated:li>.validated:div").each(function() {

			if($(this).is(':visible'))
			{
				
				$(this).validate.init(this, false);
				var status = $("#" + this.id + '_val').val();
				if (status == "false")
				{
					valid = false;
				}

				if (status == "waiting")
				{
					while (status == "waiting")
					{
						status = $("#" + this.id + '_val').val();
					}
					var status = $("#" + this.id + '_val').val();
					if (status != "true")
					{
						valid = false;
					}
				}
			}
		});


		if (!valid || valid == 'false')
		{
			return false;
		}

		return valid;
}


$(document).ready(function() {
		$(".validated:input").blur(function() {
          $(this).validate.init(this, false);
		});
		
		
		$("form").submit(function() {
			return (validateForm(this));
		});
		
		
		
		
		var l1 = new Image();
		l1.src = '/<?= $ROOTPATH ?>/images/loading.gif';
		
		//var valid = true;
});


new function() {
    // $.fn.validate = validate() {};
 $.fn.validate = {
     init: function(o, blink) {

		if ($('#' + o.id).is(':visible'))
		{
			
			// Regex Checks
		   if(o.id == 'tbFirstName' || o.id == 'tbLastName') { this.limitedCheck(o, blink) }
		   else if(o.id == 'tbFirstName2' || o.id == 'tbLastName2') { this.limitedCheck(o, blink) }
		   else if(o.id == 'tbProductName') {this.limitedCheck(o, blink) }
		   else if(o.id == 'tbProductModel') {this.limitedCheck(o, blink) }
		   else if(o.id == 'ddlProductType') {this.limitedCheck(o, blink) }


		   else if(o.id == 'tbProductDescription') {this.requiredFieldCheck(o, blink) }
		   else if(o.id == 'tbInvoice') {this.requiredFieldCheck(o, blink) }

		   else if(o.id == 'tbcashAmount') {this.requiredFieldCheck(o, blink) }

		   else if(o.id == 'tbcheckAmount') {this.requiredFieldCheck(o, blink) }
		   else if(o.id == 'tbcheckNumber') {this.requiredFieldCheck(o, blink) }
		   else if(o.id == 'tbcheckAccount') {this.requiredFieldCheck(o, blink) }
		   else if(o.id == 'tbcheckBank') {this.requiredFieldCheck(o, blink) }
		   else if(o.id == 'tbcheckRouting') {this.requiredFieldCheck(o, blink) }

			else if(o.id == 'tbcreditAmount') {this.requiredFieldCheck(o, blink) }
			else if(o.id == 'ddlcreditType') {this.requiredFieldCheck(o, blink) }
			else if(o.id == 'tbcreditNumber') {this.requiredFieldCheck(o, blink) }
			else if(o.id == 'tbcreditCVC') {this.requiredFieldCheck(o, blink) }
			else if(o.id == 'tbcreditName') {this.requiredFieldCheck(o, blink) }

			else if(o.id == 'tbAmount') { this.numericCheck(o, blink) }
			else if(o.id == 'tbPercentage') { this.numericCheck(o, blink) }
			

		   else if(o.id.match('tbSerial') != null) {this.requiredMultipleFieldCheck('tbSerial', blink) } 
		   else if(o.id.match('tbcreditExp') != null) {this.requiredMultipleFieldCheck('tbcreditExp', blink) } 
		   
		   else if(o.id == 'tbQuantity') {this.numericCheck(o, blink) }
		   else if(o.id == 'ddlLocationId') {this.numericCheck(o, blink) }
		   else if(o.id == 'ddlProductId') {this.numericCheck(o, blink) }
		   else if(o.id == 'tbprodQuantity') {this.numericCheck(o, blink) }
		   else if(o.id == 'tbaccQuantity') {this.numericCheck(o, blink) }
		   
		   else if(o.id == 'tbPassword') {this.passwordCheck(o, blink) }
		   else if(o.id == 'tbConfirmPassword') {this.confirmPasswordCheck(o, 'tbPassword', blink) }   	   
		   
		   
		   // Ajax Checks
		   else if(o.id == 'tbUsername') { this.UsernameCheck(o, blink) }
		   else if(o.id == 'tbLocationName') {this.ajaxCheck(o, blink) }
		   else if(o.id == 'tbTeamName') {this.ajaxCheck(o, blink) }

		   else {this.requiredFieldCheck(o, blink) };
		}
     },
     limitedCheck: function(o, blink) {
    	 $('#' + o.id + '_img').html('<img src="/<?= $ROOTPATH ?>/images/loading.gif" />');
         var user = /[(\*\(\)\[\]\+\.\,\/\?\:\;\'\"\`\~\\#\$\%\^\&\<\>)+]/;
         if (o.value == "")
         {
      	   doError(o, 'This field is Required.', blink);
         }
         else if (!o.value.match(user)) {
        	 doSuccess(o);
         }
         else {
            doError(o,'no special characters allowed', blink);
         };
       },
	 requiredFieldCheck: function(o, blink) {
    	 $('#' + o.id + '_img').html('<img src="/<?= $ROOTPATH ?>/images/loading.gif" />');

         if (o.value == "")
         {
      	   doError(o, 'This field is Required.', blink);
         }
         else {
			$('#' + o.id + '_li').removeClass("error");
			$('#' + o.id + '_img').html('');
			$('#' + o.id + '_msg').html("");
			$('#' + o.id + '_val').val('true');
         };
       },
	 requiredMultipleFieldCheck: function(o, blink) {
		   if(typeof(o) == 'object') {
			   z = o.id;
		   }
		   if(typeof(o) == 'string') {
			   z = o;
		   }

    	 $('#' + z + '_img').html('<img src="/<?= $ROOTPATH ?>/images/loading.gif" />');

		 var success = true;
		 $('[id^=' + z + ']>input.validated').each(function() {
			if($(this).val() == "") {
				success = false;
			}

		 });


		
         if (!success)
         {
//		 alert(success);
      	   doError(z, 'All fields must be filled out.', blink);
         }
         else {
           doSuccess(z);
         }
       },
       passwordCheck: function(o, blink) {
      	 $('#' + o.id + '_img').html('<img src="/<?= $ROOTPATH ?>/images/loading.gif" />');

         if (o.value == "")
         {
      	   doError(o, 'This field is Required.', blink);
         }
         else if (o.value.length < 6)
         {
        	 doError(o, 'Password must be at least 6 Characters', blink);
         }
         else if (o.value.length > 20)
         {
        	 doError(o, 'Password must be less than 21 Characters', blink);
         }
         else {
           doSuccess(o);
         };
       },
       confirmPasswordCheck: function(o, compare, blink) {
        	 $('#' + o.id + '_img').html('<img src="/<?= $ROOTPATH ?>/images/loading.gif" />');

             if (o.value == "")
             {
          	   doError(o, 'This field is Required.', blink);
             }
             else if (o.value != $('#' + compare).val())
             {
            	 doError(o, 'Passwords do not match.');
             }
             else {
            	 doSuccess(o);
             }
       },
       numericCheck: function(o, blink) {
		   // $('#' + o.id + '_img').html('<img src="/<?= $ROOTPATH ?>/images/loading.gif" />');
           if (o.value == "")
           {
        	   doError(o, 'This field is Required.', blink);
           }
           else if (IsNumeric(o.value)) {
          		$('#' + o.id + '_li').removeClass("error");
				$('#' + o.id + '_img').html('');
				$('#' + o.id + '_msg').html("");
				$('#' + o.id + '_val').val('true');
           }
           else {
              doError(o,'Must be Numeric', blink);
           };
         },
     ajaxCheck: function(o, blink){
		 $('#' + o.id + '_img').html('<img src="/<?= $ROOTPATH ?>/images/loading.gif" />');
		 status = $("#" + o.id + '_val').val();
		 var user = /[(\*\(\)\[\]\+\.\,\/\?\:\;\'\"\`\~\\#\$\%\^\&\<\>)+]/;
		 if (o.value == "")
		 {
		   doError(o, 'Null', blink);
		 }
		 else {
		    doValidation2(o, blink);
		 }
       },
       UsernameCheck: function(o, blink){
		 $('#' + o.id + '_img').html('<img src="/<?= $ROOTPATH ?>/images/loading.gif" />');
		 status = $("#" + o.id + '_val').val();
		 var user = /[(\*\(\)\[\]\+\.\,\/\?\:\;\'\"\`\~\\#\$\%\^\&\<\>)+]/;
		 if (o.value == "")
		 {
		   doError(o, 'Null', blink);
		 }
		 else if (!o.value.match(user)) {
			 doValidation(o, blink);
		 }
		 else {
		    doError(o,'no special characters allowed', blink);
		 }
       }
       
   
	};	 	
     //private helper, validates each type after check
     function doValidation(o, blink) {
        	$('#' + o.id + '_img').html('<img src="/<? $ROOTPATH ?>/images/loading.gif" border="0" style="float:left;" />');
	        $('#' + o.id + '_val').value = 'loading';
        	$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: o.id, value: o.value }, function(json) {
                  	eval("var args = " + json);
                  	
                  	if (args.success == "success")
                  	{
                  	  doSuccess(o);
                  	}
                  	else
                  	{
                          doError(o,args.error, blink);
                  	}
                  });
    };
     function doValidation2(o, blink) {
        	$('#' + o.id + '_img').html('<img src="/<?= $ROOTPATH ?>/images/loading.gif" border="0" style="float:left;" />');
	        $('#' + o.id + '_val').value = 'loading';
        	$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: o.id, value: o.value }, function(json) {
                  	eval("var args = " + json);
                  	if (args.success == "success")
                  	{
                  		doSuccess(o);
                  	}
                  	else
                  	{
                          doError(o,args.error, blink);
                  	}
                  });
    };
    function doSuccess(o) {
		   if(typeof(o) == 'object') {
			   z = o.id;
		   }
		   if(typeof(o) == 'string') {
			   z = o;
		   }
        $('#' + z + '_img').html('<img src="/<?= $ROOTPATH ?>/images/accept.gif" border="0" />');
        $('#' + z + '_li').removeClass("error");
        $('#' + z + '_msg').html("");
        $('#' + z + '_li').addClass("success");
        $('#' + z + '_val').val('true');
    }    
    function doError(o,m, blink) {
		   if(typeof(o) == 'object') {
			   z = o.id;
		   }
		   if(typeof(o) == 'string') {
			   z = o;
		   }
        $('#' + z + '_img').html('<img src="/<?= $ROOTPATH ?>/images/exclamation.gif" border="0" />');
        $('#' + z + '_li').addClass("error");

        if (blink)
        {
            $('#' + z + '_li').fadeOut(50);
            $('#' + z + '_li').fadeIn(50);
            $('#' + z + '_li').fadeOut(50);
            $('#' + z + '_li').fadeIn(50);            
        }
        $('#' + z + '_msg').html(m);
        $('#' + z + '_li').removeClass("success");
        $('#' + z + '_val').val('false');
        valid = "false";
    }

     
 };