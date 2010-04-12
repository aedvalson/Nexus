<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">


<html>
 <head>
  <title> Nexus </title>
  
<? $time = time(); ?>
	<link rel="StyleSheet" type="text/css" href="/<?= $ROOTPATH ?>/CSS/main.css.php" />
	<link rel="StyleSheet" href="/<?= $ROOTPATH ?>/CSS/smoothness/smoothness.css" />

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" ></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="/<?= $ROOTPATH ?>/tablesorter.min.js"></script>
	<script type="text/javascript" src="/<?= $ROOTPATH ?>/Validation.js.php"></script>
	<script type="text/javascript" src="/<?= $ROOTPATH ?>/json2.js"></script>
	<script type="text/javascript" src="/<?= $ROOTPATH ?>/jeditable.js"></script>
	<script type="text/javascript">
	<!--
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
		if ($('.contentBody').height())
		{
			$('.contentBody').css("height", $('.navMenu').height() + "px");
			$('.navPageSpacing, .navSpacer').css("height", $('.contentBody').height() - $('#bullets').height() + 'px');
		}

	}

	function pdfReport( $element )
	{
		$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', { id: "generateReport",  value: "<HTML><HEAD></HEAD><BODY>" + $element.html() + "</BODY></HTML>" }, function(json) {
			eval("var args = " + json);		
			if (args.success == "success")
			{
				if (args.output)
				{
					report = args.output;
					url = '<?= $FQDN ?>/<?= $ROOTPATH ?>/reports/pdf/generate/html2ps.php?process_mode=single&URL=URLGOESHERE&proxy=&pixels=1024&scalepoints=1&renderimages=1&renderlinks=1&renderfields=1&media=Letter&cssmedia=Screen&leftmargin=15&rightmargin=15&topmargin=15&bottommargin=15&encoding=&headerhtml=&footerhtml=&watermarkhtml=&toc-location=before&smartpagebreak=1&pslevel=3&method=fpdf&pdfversion=1.3&output=0&convert=Convert+File457'
					.replace('URLGOESHERE', escape('<?= $FQDN ?>/<?= $ROOTPATH ?>/reports/automated/_report.php?report_id=' + report + '&output=pdf'));
					window.open(url);
				}
			}
			else
			{
				 alert("Ajax failed.");
			}
		});
	}

	-->
	</script>



 </head>

 <body>
<div class="divHeadBackground">
	<div class="divHeadTopBar"></div>
	<div class="divHeadline">
		<img style="float:left;" src="/<?= $ROOTPATH ?>/images/mainLogo.png" alt="Nexus Logo" />
		<?
			if (isset($_SESSION["firstname"]))
			{
				?><span style="padding:2px; color: white; float:right;">Logged in as <?= $_SESSION["firstname"] ?> <?= $_SESSION["lastname"] ?> (<a href="/<?= $ROOTPATH ?>/logout.php">Log Out</a>)</span><?
			} ?>
		<div class="spacer"></div>
	</div>
	<div class="divHeadNavBar">
		<ul class="horizontal">
			<li ><a href="/<?= $ROOTPATH ?>/index.php">Home</a></li>
			<li><a href="/<?= $ROOTPATH ?>/Inventory/ManageInventory.php">Inventory</a></li>
			<li><a href="/<?= $ROOTPATH ?>/Sales/ManageSales.php">Sales / Leads</a></li>
			<li><a href="/<?= $ROOTPATH ?>/Customers/ManageContacts.php">Contacts</a></li>
			<li><a href="#">Reports</a></li>
			<li><a href="/<?= $ROOTPATH ?>/Admin/index.php">Admin</a></li>
		</ul>
	</div>
</div>


<div class="contentBody">

<? 
	$currentUrl = $_SERVER["REQUEST_URI"];
?>


<?
echo $pagemaincontent;
?>

<? //echo "Sane"; ?>
	<div class="spacer"></div>
</div>

<div id="divFooter">
	<div style="z-index: 2; position:absolute; top:5px; background-color: #7690bf; height: 20px;width:100%"></div>
	<div id="divFooterContent">
		<div style="vertical-align:top; text-align:center; height:1em;">About Us | Contact Us | Features Request</div>

		<div style="position: absolute; bottom:2px; width:100%; text-align:center;">
		&copy; 2009 Republic Technologies Corporation. All Rights Reserved. <br>
		Version 0.0.1
		</div>
		
	</div>
</div>




 </body>
</html>
