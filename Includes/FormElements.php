<?
class FormElements
{
	function tbVal($id, $text="", $inputclass="", $css="", $value="", $disabled="")
	{
		if ($text == "")
		{
			$text = $id;
		}
		$attrText = "";
		if ($disabled) { $attrText = " disabled=\"disabled\" "; }
		?>
		 <li class="validated" style="<?= $css ?>" id="tb<?= $id ?>_li">
			 <label for="r_tb<?= $id ?>"><?= $text ?>:</label>
						<div class="imgDiv" id="tb<?= $id ?>_img"></div>
					    
					  	<input class="validated" name="<?= $id ?>" id="tb<?= $id ?>" type="text" value="<?= $value ?>" <?=$attrText?>  />		
						<input type="hidden" id="tb<?= $id ?>_val" value="waiting" />
					  
					  <div class="msgDiv" id="tb<?= $id ?>_msg"></div>
			  </li>    
		<?
	}

	function tbNotVal($id, $text="", $inputclass="", $css="", $value="", $disabled="")
	{
		if ($text == "")
		{
			$text = $id;
		}
		$attrText = "";
		if ($disabled) { $attrText = " disabled=\"disabled\" "; }

		$class = "notvalidated";
		if ($inputclass)
		{
			$class = "notvalidated " . $inputclass;
		}

		?>
		 <li class="validated" style="<?=$css?>" id="tb<?= $id ?>_li">
					  <div class="imgDiv" id="tb<?= $id ?>_img"></div>
					  <label for="r_tb<?= $id ?>"><?= $text ?>:</label>

						<input class="<?= $class ?>" name="<?= $id ?>" id="tb<?= $id ?>" type="text" value="<?=$value?>" <?=$attrText?>  />		
						<input type="hidden" id="tb<?= $id ?>_val" value="waiting" />
					  <div id="tb<?= $id ?>_msg"></div>
			  </li>    
		<?
	}


	function tbPassword($id="Password", $text="Password", $css="", $dummytext)
	{
		if ($text == "") { $text = $id; }	
		?>
		 <li class="validated" style="<?= $css ?>" id="tb<?= $id ?>_li">
					  <label for="r_tb<?= $id ?>"><?= $text ?>:</label>
					  <div id="tb<?= $id ?>_img"></div>
					  <input class="validated" name="tb<?= $id ?>" id="tb<?= $id ?>" type="password" maxlength="20" value="<?=$dummytext?>"  />
					  <input type="hidden" id="tb<?= $id ?>_val" value="waiting">
					  <div id="tb<?= $id ?>_msg"></div>
			  </li> 
		<?
	}


	function ddlContactType()
	{
		?>
	 <li class="validated" id="tbContactType_li">
                  <label for="r_tbContactType">ContactType:</label>
                  <div id="tbContactType_img"></div>
					<select class="validated" name="ContactType" id="ddlContactType" >
						<OPTION Value="3">Lead</OPTION>
						<OPTION Value="2">Customer</OPTION>
					</select>	
                  <div id="tbContactType_msg"></div>
          </li>
		  <?
	}

	function ddlHomeType($css="", $selected="")
	{
		?>
	 <li class="validated" style="<?=$css?>" id="tbHomeType_li">
                  <label for="r_tbHomeType">Home Type:</label>
                  <div id="tbHomeType_img"></div>
					<select class="validated" name="HomeType" id="ddlHomeType" >
						<OPTION Value="House">House</OPTION>
						<OPTION Value="Apartment">Apartment</OPTION>
						<OPTION Value="Condo">Condo</OPTION>
						<OPTION Value="Other">Other</OPTION>
					</select>	
                  <div id="tbHomeType_msg"></div>
          </li>
		  <SCRIPT type="text/javascript">
			$('#ddl<?=$id?>').val("<?= $selected ?>");
		  </SCRIPT>
		  <?
	}

	function ddlHomeStatus($css="")
	{
		?>
	 <li class="validated" style="<?=$css?>" id="tbHomeStatus_li">
                  <label for="r_tbHomeStatus">Home Status:</label>
                  <div id="tbHomeStatus_img"></div>
					<select class="validated" name="HomeStatus" id="ddlHomeStatus" >
						<OPTION Value="Own">Own</OPTION>
						<OPTION Value="Renting">Renting</OPTION>
					</select>	
                  <div id="tbHomeStatus_msg"></div>
          </li>
		  <?
	}



	function ddlPaymentType()
	{
		?>
	 <li class="validated" id="tbPaymentType_li">
                  <label for="r_tbPaymentType">Payment Type:</label>
                  <div id="tbPaymentType_img"></div>
					<select class="validated" name="PaymentType" id="ddlPaymentType" >
						<OPTION value="cash">Cash</OPTION>
						<OPTION value="check">Check</OPTION>
						<OPTION value="credit">Credit</OPTION>
						<OPTION value="finance">Finance</OPTION>
					</select>
                  <div id="tbPaymentType_msg"></div>
          </li>
		  <?
	}

	function ddlCreditTypes()
	{
		?>
	 <li class="validated" id="tbCreditType_li">
                  <label for="r_tbCreditType">Credit Type:</label>
                  <div id="tbCreditType_img"></div>
					<select class="validated" name="CreditType" id="ddlCreditType" >
						<OPTION value="visa">Visa</OPTION>
						<OPTION value="mc">Mastercard</OPTION>
						<OPTION value="amex">American Express</OPTION>
						<OPTION value="other">Other</OPTION>
					</select>
                  <div id="tbCreditType_msg"></div>
          </li>
		  <?
	}

	function creditExpiration()
	{
		?>
		<li class="validated" id="tbCreditExpiration_li">
			<label for="r_tbCreditExpiration">Expiration Date:</label>
			<div id="tbCreditExpiration_img"></div>

			<input style="width:2em;" type="text" maxlength="2" columns="2" name="creditMonth" id="tbCreditExpMonth" />
			 / <input style="width:4em;" type="text" maxlength="4" columns="4" name="creditYear" id="tbCreditExpYear" />
			 <div id="tbCreditExpiration_meg"></div>
		</li>
		<?
	}

	function tbContactNotes($css="")
	{
		?>	
	 <li class="validated" style="<?=$css?>" id="tbNotes_li">
                  <label for="r_tbNotes">Notes:</label>
                  <div id="tbNotes_img"></div>
					<textarea name="Notes" id="tbNotes"></textarea>
                  <div id="tbNotes_msg"></div>
          </li>
		  <?
	}

	function ddlProducts($conn, $type="", $id = "Product", $text="Product")
	{
		if ($type != "") $products = $conn->getProducts($type);
		else $products = $conn->getProducts();
		?>
		 <li class="validated" id="ddlProducts_li">
					  <label for="r_tbProducts"><?= $text ?>:</label>
					  <div id="tbProducts_img"></div>
						<select class="validated" name="Products" id="ddl<?= $id ?>" >
							<? foreach ($products as $product) 
								{?>
									<option value="<?= $product["product_id"] ?>"><?= $product["product_name"] ?></option>
									<?}?>
						</select>	
					  <div id="tbProducts_msg"></div>
			  </li>
		  <?
	}

	function ddlStaff($conn, $id="Staff", $text="User", $name="Staff")
	{

		$staff = $conn->getUsers();
		?>
		 <li class="validated" id="ddlStaff_li">
					  <label for="r_ddlStaff"><?= $text ?>:</label>
					  <div id="tbStaff_img"></div>
						<select class="validated" name="<?= $name ?>" id="ddl<?= $id ?>" >
							<? foreach ($staff as $user) 
								{?>
									<option value="<?= $user["user_id"] ?>"><?= $user["Username"] ?></option>
									<?}?>
						</select>	
					  <div id="tbStaff_msg"></div>
			  </li>
		  <?
	}

	function ddlOrderStatuses($conn)
	{
		$statuses = $conn->getOrderStatuses();
		?>
		<select class="validated" name="Products" id="ddlOrderStatuses" >
			<? foreach ($statuses as $status) 
				{?>
					<option value="<?= $status["order_status_id"] ?>"><?= $status["order_status_name"] ?></option>
					<?}?>
		</select>	
		  <?
	}


	function ddlGeneric( $name, $labeltext, $css="" )
	{
		?>
		 <li class="validated" style="<?=$css?>" id="ddl<?= $name ?>_li">
			  <label for="r_ddl<?= $name ?>"><?= $labeltext ?>:</label>
			  <div id="ddl<?= $name ?>_img"></div>
				<select class="validated" name="<?= $name ?>" id="ddl<?= $name ?>" >
				</select>	
			  <div id="ddl<?= $name ?>_msg"></div>
			</li>
		  <?
	}

	function ddlDTOffices( $name="dtoffice", $labeltext="DT Office", $css="", $DTOffices, $user )
	{
		$nonselected = (!$user["dtoffice"] || $user["dtoffice"] == "_") ? "selected" : "";
		?>
		 <li class="validated" style="<?=$css?>" id="ddl<?= $name ?>_li">
			  <label for="r_ddl<?= $name ?>"><?= $labeltext ?>:</label>
			  <div id="ddl<?= $name ?>_img"></div>
				<select class="validated" name="<?= $name ?>" id="ddl<?= $name ?>" >
					<option value="_" <?= $nonselected ?> ></option>
					<?
					$i = 0;
					foreach ($DTOffices as $DTOffice)
						{
						$selected = "";
						
						if (strcmp($i, $user["dtoffice"]) == 0)
							{
							echo $i . "is" . $user["dtoffice"];
							$selected = "selected='selected'";
							}
						print "<option value='" . $i . "' " . $selected . ">" . $DTOffice . "</option>";
						$i++;
						}
						?>
				</select>	
			  <div id="ddl<?= $name ?>_msg"></div>
			</li>
		  <?
	}

	function ddlCommissionTemplates($conn, $price)
	{

		?>
		 <li class="validated" id="ddlcommTemplate_li">
			  <label for="r_tbcommTemplate">Payee Type:</label>
			  <div id="ddlcommTemplate_img"></div>
				<select class="validated" name="commTemplate" id="ddlcommTemplate" >
				</select>	
			  <div id="ddlcommTemplate_msg"></div>
			</li>
		  <?

	}


	function ddlPayeeTypes()
	{
		?>
		 <li class="validated" id="ddlPayeeType_li">
			  <label for="r_tbPayeeType">Commission Type:</label>
			  <div id="tbPayeetype_img"></div>
				<select class="validated" name="PayeeType" id="ddlPayeeType" >
						<option value="corporate">Corporate</option>
						<option value="employee">Dealer(s)</option>
						<option value="adjustment">Adjustment</option>
				</select>	
			  <div id="ddlPayeeType_msg"></div>
			</li>
		  <?
	}

	function ddlCommPaymentTypes($id = "ddlPaymentType")
	{
		?>
		 <li class="validated" id="<?=$id?>_li">
			  <label for="r_<?=$id?>">Payment Type:</label>
			  <div id="<?=$id?>_img"></div>
				<SELECT name="<?=$id?>" id="<?=$id?>">
					<option value="flat" SELECTED>Flat Rate</option>
					<option value="percentage">Percentage</option>
					<option value="remaining">All Remaining</option>
				</SELECT>
			  <div id="<?=$id?>_msg"></div>
			</li>
		  <?
	}

	function ddlCountries($id="Country", $text="Country", $css="")
	{
		?>
	 <li class="validated" style="<?=$css?>" id="tb<?= $id ?>_li">
                  <label for="r_tb<?= $id ?>"><?= $text ?>:</label>
                  <div id="tb<?= $id ?>_img"></div>
					<select class="validated" name="<?= $id ?>" id="ddl<?= $id ?>" >
						<? echoCountryOptions(); ?>
					</select>	
                  <div id="tb<?= $id ?>_msg"></div>
          </li>
		  <?
	}

	function ddlStates($state = "AL", $id = "State", $text="State", $css="")
	{
		?>
	 <li class="validated" style="<?= $css ?>" id="tb<?=$id?>_li">
                  <label for="r_tb<?=$id?>"><?=$text?>:</label>
                  <div id="tb<?=$id?>_img"></div>
					<select style="width: 180px" class="validated" name="<?=$id?>" id="ddl<?=$id?>" >
						<? echoUsStateOptions(); ?>
					</select>	
                  <div id="tb<?=$id?>_msg"></div>
				  <SCRIPT type="text/javascript">
					$('#ddl<?=$id?>').val("<?= $state ?>");
				  </SCRIPT>
          </li>
		  <?
	}

		  
	function ddlPermissionRoles($selected="", $css="")
	{
		?>
	 <li class="validated" style="<?=$css?>" id="tbPermissionRole_li">
                  <label for="r_tbPermissionRole">PermissionRole:</label>
                  <div id="tbPermissionRole_img"></div>
					<select class="validated" name="PermissionRole" id="ddlPermissionRole" >
						<?
						$DB = new conn();
						$DB->connect();
						$perms = $DB->getPermissionRoles();
						$DB->close();
						foreach($perms as $perm)
						{
							?><OPTION value="<?= $perm["id"] ?>"><?= $perm["name"] ?></OPTION><?
						}
						?>
					</SELECT>
                  <div id="tbPermissionRole_msg"></div>
          </li>
			<?  if ($selected) { ?>
		  <SCRIPT type="text/javascript">
			$('#ddlPermissionRole').val("<?= $selected ?>");
		  </SCRIPT>
			<? } ?>
		<?

	}

	function ddlDealerRoles($showButton=true, $id="DealerRole")
	{
		?>
	 <li class="validated" id="tb<?= $id ?>_li">
			  <label for="r_tb<?= $id ?>">Dealer Role:</label>
			  <div id="tb<?= $id ?>_img"></div>
				<select class="validated" name="<?= $id ?>" id="ddl<?= $id ?>" >
					<?
					$DB = new conn();
					$DB->connect();
					$roles = $DB->getDealerRoles();
					$DB->close();
					foreach($roles as $role)
					{
						?><OPTION value="<?= $role["id"] ?>"><? echo $role["role"]; ?></OPTION><?
					}
					?>
				</SELECT><? if ($showButton) { ?><input type="button" id="btnAddDealer" value="Add Dealer"><? } ?>
			  <div id="tb<?= $id ?>_msg"></div>
          </li>
		<?

	}



	function ddlTeams($selected="", $css="")
	{
		?>
	 <li class="validated" style="<?= $css ?>" id="tbTeams_li">
                  <label for="r_tbTeams">Team:</label>
                  <div id="tbTeams_img"></div>
					<select class="validated" name="Teams" id="ddlTeams" >
						<?
						$DB = new conn();
						$DB->connect();
						$perms = $DB->getTeams();
						$DB->close();
						foreach($perms as $perm)
						{
							?><OPTION value="<?= $perm["team_id"] ?>"><?= $perm["team_name"] ?> (<?= $perm["LastName"] ?>)</OPTION><?
						}
						?>
					</SELECT>
                  <div id="tbTeams_msg"></div>
          </li>
			<?  if ($selected) { ?>
		  <SCRIPT type="text/javascript">
			$('#ddl<?=$id?>').val("<?= $selected ?>");
		  </SCRIPT>
			<? } ?>
		<?

	}


	function submitButton($ButtonText, $id="btnSubmit", $css="")
	{
		?>
		 <li class="validated" style="<?=$css?>" id="btnSubmit_li">
					  <label for="r_btnSubmit"></label>
					  <div id="btnSubmit_img"></div>
					  <input id="<?= $id ?>" type="Submit" maxlength="20" value="<?= $ButtonText ?>"  />
					  <div id="btnSubmit_msg"></div>
			  </li>        
		<?
	}
}