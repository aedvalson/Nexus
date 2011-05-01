<? 
include "./findconfig.php";
session_start();
?>

	
	var sort_by = function(field, reverse, primer){
	   reverse = (reverse) ? -1 : 1;
	   return function(a,b){
		   a = a[field];
		   b = b[field];
		   if (typeof(primer) != 'undefined'){
			   a = primer(a);
			   b = primer(b);
		   }
		   if (a<b) return reverse * -1;
		   if (a>b) return reverse * 1;
		   return 0;
	   }
	}

	function isNumeric(stext)

	{
	   var ValidChars = "0123456789.";
	   var IsNumber=true;
	   var Char;

	 
	   for (i = 0; i < stext.length && IsNumber == true; i++) 
		  { 
		  Char = stext.charAt(i); 
		  if (ValidChars.indexOf(Char) == -1) 
			 {
			 IsNumber = false;
			 }
		  }
	   return IsNumber;
	   
	   }


	function prettyDate(datetext)
	{		
		var _date = datetext.split(" ")[0];
		_date = replaceAll(_date, "-", "/");
		var myDate = new Date(_date);
		var month = myDate.getMonth() + 1;
		_prettyDate = month + '/' + myDate.getDate() + '/' + myDate.getFullYear();
		return _prettyDate;
	}

	function getPrettyDate(datetext)
	{		
		var _date = datetext.split(" ")[0];
		_date = replaceAll(_date, "-", "/");
		var myDate = new Date(_date);
		var month = myDate.getMonth() + 1;
		_prettyDate = month + '/' + myDate.getDate() + '/' + myDate.getFullYear();
		return _prettyDate;
	}

	function getPrettyTime(datetext)
	{
		var _time = datetext.split(" ")[1];
		return _time;
	}

	function pause(millis)
	{
	var date = new Date();
	var curDate = null;

	do { curDate = new Date(); }
	while(curDate-date < millis);
	} 


	function editLinkContent()
	{
		return ("<img src='/<?= $ROOTPATH ?>/images/edit16.png' border='0' style='margin-right:5px;' />");
	}

	function deleteLinkContent()
	{
		return ("<img src='/<?= $ROOTPATH ?>/images/delete16.png' border='0' />");
	}

	function saveLinkContent()
	{
		return ("<img src='/<?= $ROOTPATH ?>/images/save.png' border='0' width='16'  style='margin-right:5px;' />");
	}

	function cancelLinkContent()
	{
		return ("<img src='/<?= $ROOTPATH ?>/images/no_entry.png' border='0' width='16' />");
	}

	function fixLinks()
	{
		$('a[id^=#editLink_]').html(editLinkContent());
	}
	$(window).load( function() {
		$(".datepicker").datepicker( {duration: 'fast'} );
		fixHeight();

		// Fix Table Links
		fixLinks();	
	});

	function fixHeight()
	{
		// Adjust height of left pane to force line all the way down page
		$('.navMenu').css("height", $('.pageContent').height() + "px");
		//alert($('.contentBody').height());
		//alert($('.navMenu').height());
		//alert($('#bullets').height());
		//alert($('.contentBody').height() - $('#bullets').height() + 'px');
		if ($('.contentBody').height() && $('.navMenu').height())
		{
			$('.contentBody').css("height", $('.navMenu').height() + "px");
			$('.navPageSpacing, .navSpacer').css("height", $('.contentBody').height() - $('#bullets').height() + 'px');
		}

	}

	function pdfReport( $element, save, footer, orient )
	{
		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "generateReport",  value: "<HTML><HEAD></HEAD><BODY>" + $element.html() + "</BODY></HTML>" }, function(json) {
			eval("var args = " + json);		
			if (args.success == "success")
			{
				if (args.output)
				{
					report = args.output;
					url = '<?= $FQDN ?>/<?= $ROOTPATH ?>/reports/automated/_report.php?report_id=' + report;

					if (save)
					{
						url = url + "&output=pdf";
					}
					if (footer)
					{
						url = url + "&footer=1";
					}
					if (orient)
					{
						url = url + "&orient=" + orient;
					}
					window.open(url);
				}
			}
			else
			{
				 alert("Ajax failed.");
			}
		});
	}

	// remove all event bindings , 
	// and the jQ data made for jQ event handling
	jQuery.unbindall = function () { jQuery('*').unbind(); }
	//
	$(document).unload(function() { 
	  jQuery.unbindall()
	});

	var perms = <?= json_encode($_SESSION["perms"]) ?>;
