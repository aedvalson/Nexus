<? 
include "./findconfig.php";
include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Top.php";
?>
<? 
if (!UserMay("Admin_ViewProducts")) { AccessDenied(); }
$DB = new conn();

?>




<div class="navMenu">
	<div class="navHeaderdiv"><h1>Manage Products</h1></div>
	<div id="bullets" style="height:auto;" class="navContent">
		<div id="bulletManageInventory" style="height:auto;" class="navContent">
			<div class="divFilters">
				<div>
					Side Filters
				</div>

				<input id="btnSubmit" type="submit" value="Submit">
				<input id="btnReset" type="submit" value="Reset">


			</div>
				
			<div class="spacer"></div>
		</div>
	</div>	

	<div class="navPageSpacing"></div>
	<div class="spacer"></div>

</div>

<div class="pageContent" id="pageContent">

	<div class="contentHeaderDiv">
		<? if (UserMay("Admin_EditProducts")) { ?>
		<a href="AddProduct.php">Add Product</a>
		<? } ?>
	</div>
	<div class="contentDiv">





	<div class="divTable">
		 <TABLE id="theTable" class="data" >
			<thead>
				<TR id="headerRow">
					<? if (UserMay("Admin_EditProducts")) { ?>
					<th style="width:40px"></th>
					<? } ?>
					<th>Product Name</th>
					<th>Type</th>
					<th>Model</th>
					<th>Description</th>
					<th>Status</th>
				</TR>

				<TR id="filterRow" class="filterRow">
					<? if (UserMay("Admin_EditProducts")) { ?>
					<td></td>
					<? } ?>
					<td>
						<input id="tbProductNameH" Type="TEXT">
					</td>
					<td>
						<SELECT id="ddlProductTypeH">
							<OPTION VALUE="%">Any</OPTION>
							<OPTION VALUE="Product">Product</OPTION>
							<OPTION VALUE="Accessory">Accessory</OPTION>
						</SELECT>
					</TD>
					<td><Input id="tbModelH" TYPE="TEXT"></TD>
					<td><Input id="tbDescriptionH" TYPE="TEXT"></TD>
					<td>
						<select id="ddlProductStatusH">
							<OPTION value="%">Any</OPTION>
							<OPTION VALUE="Active" selected>Active</OPTION>
							<OPTION VALUE="Old">Old</OPTION>
						</select>
					</td>
					
				</TR>
			</thead>
				
		</TABLE> 

	</div>
	<div class="spacer"></div>

	</div>
</div>


<SCRIPT TYPE="TEXT/JAVASCRIPT">
	$(document).ready(function() {
		createFilters();
	});
	$('input[id^=tbProductName]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbProductName]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('select[id^=ddlProductType]').change(function() {
		var newVal = $(this).val();
		$('select[id^=ddlTeam]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('input[id^=tbModel]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbModel]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('input[id^=tbTeamId]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbTeamId]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});

	$('input[id^=tbDescription]').change(function() {
		var newVal = $(this).val();
		$('input[id^=tbDescription]').each( function () {
			$(this).val(newVal);
		});
		createFilters();
	});


	$('#btnSubmit').click(function() {
		createFilters();
	});

	$('#btnReset').click(function() {

		$('input:text').val('');
		$('select').val('%');
		createFilters();
	});

	function createFilters()
	{

		var filterArray = new Object();


		filterArray["productName"] = $('#tbProductNameH').val();
		if (filterArray["productName"] == "") filterArray["productName"] = "%";

		filterArray["productType"] = $('#ddlProductTypeH').val();
		if (filterArray["productType"] == "") filterArray["productType"] = "%";

		filterArray["productModel"] = $('#tbModelH').val();
		if (filterArray["productModel"] == "") filterArray["productModel"] = "%";

		filterArray["productDescription"] = $('#tbDescriptionH').val();
		if (filterArray["productDescription"] == "") filterArray["productDescription"] = "%";

		
		rePost(filterArray);

	}

	function displayJSON(jsonString)
	{
		var output = '';
		var theObject = JSON.parse(jsonString);
		var i = theObject.elements.length;
		for (var i = 0; i < theObject.elements.length ; i++ )
		{
			if (typeof(theObject.elements[i].flatAmount) == 'undefined')
			{
				var amount = 0;
			}
			else
			{
				var amount = theObject.elements[i].flatAmount;
			}

			var currencyStr = '';
			if (amount > 0)
			{
				currencyStr = formatCurrency(amount);
			}

			output = output + theObject.elements[i].payeeType + ": " + currencyStr + " " + theObject.elements[i].paymentType + "<br>";
		}

		return output;
	}


	function rePost(filters)
	{
		filters["id"] = "getNewProductTable";

		$('#theTable tr').each( function () {
			id = $(this).attr('id');
			if (id != 'headerRow' && id != 'filterRow')
			{
				$(this).remove(); // Remove all existing rows
			}
		});
		$('#theTable').append('<tr id="loadingRow"><td style="padding:25px"><center><img src="/<?= $ROOTPATH ?>/images/loading.gif"><br>Filtering Results</center></td></tr>');

       	$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', filters, function(json) {
			eval("var args = " + json);		

			

			if (args.success == "success")
			{

				$('#theTable tbody').remove();
				$('#theTable tfoot').remove();
				$('#theTable').append('<tbody></tbody');

				var row = 0;
				if (args.output)
				{
					for (r in args.output)
					{
						_r = args.output[r];

						var editCellContent = perms.Admin_EditProducts ? '<td><div class="selected" style="display:none" id="commandDivSelected_' + _r.product_id + '"><a href="#" id="btnSave_' + _r.product_id + '" class="btnSave">' + saveLinkContent() + '</a><a href="#" class="cancelLink">' + cancelLinkContent() + '</a></div><div class="unselected" id="commandDiv_' + _r.product_id + '"><a href="#" id="editLink_' + _r.product_id + '" class="editLink" >' + editLinkContent() + '</a></div></td>' : "";

						$('#theTable').append('<tr class="row'+row+'" id="row_' + _r.product_id + '">' + editCellContent + '<td><div class="view"><span id="spnProductName_' + _r.product_id + '">'+_r.product_name+'</span></div><div class="editCell"><input id="tbProductName_' + _r.product_id+'"  value="'+ _r.product_name + '"></input></div></td><td><div class="view"><span id="spnProductType_'+_r.product_id+'">'+ _r.product_type + '</span></div><div class="editCell"><SELECT id="ddlProductType_'+_r.product_id+'"><OPTION VALUE="Product">Product</OPTION><OPTION VALUE="Accessory">Accessory</OPTION></SELECT></div></td><td><div class="view"><span id="spnProductModel">'+_r.product_model+'</span></div><div class="editCell"><input type="text" id="tbProductModel_'+_r.product_id+'" value="'+_r.product_model+'" /></td><td><div class="view"><span id="spnProductDescription">'+_r.product_description+'</span></div><div class="editCell"><input type="text" id="tbProductDescription_'+_r.product_id+'" value="'+_r.product_description+'" /></td><td><div class="view"><span id="spnProductStatus_'+_r.product_id+'">'+_r.status+'</span></div><div class="editCell"><select id="ddlProductStatus_'+_r.product_id+'"><OPTION VALUE="Active" selected>Active</OPTION><OPTION VALUE="Old">Old</OPTION></select></td></tr>');
						row = 1 - row;
					}

					$('#theTable').append('<tfoot><tr style="border-top:1px silver solid" id="pager"><td colspan="7" style="border:0px;"><p class="left">Rows Per Page: <br><a href="#" class="rowSelect" id="rows10">10</a> | <a href="#"  class="rowSelect" id="rows20">20</a> | <a href="#" class="rowSelect" id="rows30">30</a> | <a href="#" class="rowSelect" id="rows40">40</a><input style="display:none;" class="pagesize" value="10"></input></p><p class="right">Search: <input name="filter" id="filter-box" value="" maxlength="30" size="30" type="text"><input id="filter-clear-button" class="button" type="submit" value="Clear"/></p><p class="centered"><img src="/<?= $ROOTPATH ?>/images/first.png" class="first"/><img src="/<?= $ROOTPATH ?>/images/prev.png" class="prev"/><input onkeypress="return false;" type="text" class="pagedisplay"/><img src="/<?= $ROOTPATH ?>/images/next.png" class="next"/><img src="/<?= $ROOTPATH ?>/images/last.png" class="last"/></p></td></tr></tfoot>');


					fixHeight();
					$(".datepicker").datepicker( {duration: 'fast'} );

					if ($('.sorted').size() == 0)
					{
						sortTable();

					}
					else
					{
						$("#theTable").trigger("update"); 
						$("#theTable").trigger("appendCache"); 
						$('#theTable').tablesorterPager({container: $("#pager"), positionFixed: false}).tablesorterFilter({filterContainer: $("#filter-box"),
						  filterClearContainer: $("#filter-clear-button"),
						  filterColumns: [ 1, 2, 3, 4, 5, 6 ],
						  filterCaseSensitive: false});
					}


					$('.deleteLink').click( function() {
						if (confirm("Are you sure you wish to permanently delete this Product?"))
						{
							var Product_id = this.id.replace("deleteLink_", "");
							$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', 
								{ id: "deleteProduct",
									Product_id: Product_id}, function(json) {
								eval("var args = " + json);		
								if (args.success == "success")
								{
									createFilters();

								}
								else alert("Ajax failed.");
							});

						}
						else
						{
							
						}
					});


					$('.editLink').click( function() {

						var Product_id = this.id.replace("editLink_", "");



						// Add Edit Style
						$('#theTable tr').removeClass("edit");
						$('#row_' + Product_id).addClass("edit");

						// Reset Status
						$('.editCell').css("display", "none");
						$('.view').css("display", "block");

						// Reset all fields
						$('#row_' + Product_id + ' .view').css("display", "none");
						$('#row_' + Product_id + ' .editCell').css("display", "block");


						// Reset Commands
						$('.selected').css("display", "none");
						$('.unselected').css("display", "block");

						// Command Column
						$('#commandDivSelected_' + Product_id).css("display", "block");
						$('#commandDiv_' + Product_id).css("display", "none");

						//Product Type Column
						$('#ddlProductType_' + Product_id).val($('#spnProductType_' + Product_id).html());

						// Type Column
						$('#ddlProductStatus_' + Product_id).val($('#spnProductStatus_' + Product_id).html());




						$('.btnSave').unbind();
						$('.btnSave').click( function() {
							var Product_id = this.id.replace("btnSave_", "");
							var Product_name = $('#tbProductName_' + Product_id).val();
							var type = $('#ddlProductType_' + Product_id).val();
							var model = $('#tbProductModel_' + Product_id).val();
							var description = $('#tbProductDescription_' + Product_id).val();
							var status = $('#ddlProductStatus_' + Product_id).val();

							$.post('/<?= $ROOTPATH ?>/Includes/ajax.php', 
								{ id: "updateProduct",
									Product_id: Product_id,
									Product_name: Product_name,
									type: type,
									model: model,
									description: description,
									status: status,
									sender: <?= $_SESSION["user_id"] ?>}, function(json) {
								eval("var args = " + json);		
								if (args.success == "success")
								{
									createFilters();
								}
								else alert("Ajax failed.");
							});

							return false;
						});

						$('.cancelLink').unbind();
						$('.cancelLink').click( function() {

							// reset Rows
							$('#theTable tr').removeClass("edit");

							// Reset Status
							$('.editCell').css("display", "none");
							$('.view').css("display", "block");

							// Reset Commands
							$('.selected').css("display", "none");
							$('.unselected').css("display", "block");

							return false;


						});

						return false;
						
					});


				}
				else
				{
					$('#theTable').append('<tr id="errorRow" style="height:50px"><td><font color="red">No Records Found.</font></td></tr>');
				}
			}
			else
			{
				 alert("Ajax failed.");
			}
		  });



	}
</SCRIPT>




<SCRIPT TYPE="TEXT/JAVASCRIPT">
	$(document).ready(function() {

		$("a[id^=lbDelete]").each(
			function() {
				$(this).click(function() {
					var id = this.id;
					var Product_id = id.replace("lbDelete", "");
					$("#hv_Product_id").val(Product_id);
					$("#hv_Action").val("Delete");
					if (confirm("Are you sure you wish to delete User #"+Product_id+"?"))
					{
						$("#theForm").submit();	
					}
					return false;
				});
			});

		});
	var myTextExtraction = function(node)
	{
		var text = "";
		$(node).children('.view:visible').children('span:visible').each( function() {
			text = text + $(this).html();
			//alert(text);
		});
		
		return text;
	}

	function sortTable()
	{
		$('#theTable').addClass('sorted');
		$('#theTable').tablesorter( { widgets: ['zebra'], textExtraction: myTextExtraction } )
			.tablesorterPager({container: $("#pager"), positionFixed: false})
			.tablesorterFilter({filterContainer: $("#filter-box"),
			  filterClearContainer: $("#filter-clear-button"),
			  filterColumns: [ 1, 2, 3, 4, 5, 6],
			  filterCaseSensitive: false});
	}

</SCRIPT>



<FORM id="theForm"  action="" method="post">
	<INPUT TYPE="HIDDEN" id="hv_Action" NAME="Action" Value="Nothing">
	<INPUT TYPE="HIDDEN" id="hv_Product_id" NAME="Product_id" Value="0">
</FORM>
<? include $_SERVER['DOCUMENT_ROOT']."/".$ROOTPATH."/Includes/Bottom.php" ?>