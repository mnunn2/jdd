<?php defined('_JEXEC') or die('Restricted access'); ?>
<!-- <script src="/media/com_jdd/js/main.js" type="text/javascript"></script> -->
<div class="container main-form-body">
  <div class="row">
	<div class="col-sm-12 fm-bg">
	  <form id="dd-form" action="<?php echo JRoute::_('index.php?option=com_jdd&task=donor.submit'); ?>"
			enctype="multipart/form-data" method="post">
		<?php echo JHtml::_( 'form.token' ); ?>
		<h2>Direct Debit Form</h2>
		<div id="sf1" class="frm">
		  <fieldset>
			<legend>Step 1 of 3 Your Details:</legend>
			<div class="col-sm-4">
			  <div class="row">
				<div class="col-sm-12">
				  <div class="form-group">
					<label for="title" class="control-label">Title</label>
					<select id="title" name="title" class="form-control">
					  <option value="Mr">Mr</option>
					  <option value="Mrs">Mrs</option>
					  <option value="Ms">Ms</option>
					  <option value="Miss">Miss</option>
					  <option value="Dr">Dr</option>
					</select>
				  </div>
				  <div class="form-group">
					<label for="firstName" class="control-label">Firstname</label>
					<input id="firstName" name="firstName" type="text" class="form-control">
				  </div>
				  <div class="form-group">
					<label for="keyname" class="control-label">Lastname</label>
					<input id="keyname" name="keyname" type="text" class="form-control">
				  </div>
				  <div class="row">
					  <div class="form-group col-sm-6"> 
						  <label for="postcode" class="control-label">post code</label>
						  <input id="postcode" type="text" name="postcode" class="form-control">
					  </div>
					  <div class="form-group col-sm-4">
						  <label for="postcode" class="control-label">&nbsp;</label>
						  <button id="pcbtn" type="button" class="btn btn-secondary" onclick="cp_obj_1.doLookup()">Find Address</button>
					  </div>
				  </div>
				  <div id="crafty_postcode_result_display_1" >&nbsp;</div>
				  <div class="form-group" id="addressGroup">
					  <label for="addressLine1" class="control-label">Address</label>
					  <input id="addressLine1" name="addressLine1" type="text" class="form-control">
					  <label for="addressLine3" class="control-label">Town</label>
					  <input id="addressLine3" name="addressLine3" type="text" class="form-control">
					  <label for="addressLine4" class="control-label">County</label>
					  <input id="addressLine4" name="addressLine4" type="text" class="form-control">
				  </div>
				  <div class="form-group">
					<label for="emailAddress" class="control-label">Email</label>
					<input id="emailAddress" name="emailAddress" type="text" class="form-control">
				  </div>
				  <div class="form-group">
					  <label for="doNotEmail">
						  You will receive an email to confirm your direct debit mandate. If you would like 
						  to receive emails keeping you up to date on how we are making a difference please
						  tick here:&#160;
						  <input id="doNotEmail" type="checkbox" name="doNotEmail" value="0">
					  </label>
				  </div>
				  <div class="form-group">
					<label for="mobileNumber" class="control-label">Telephone Mobile</label>
					<input id="mobileNumber" name="mobileNumber" type="text" class="form-control">
				  </div>
				  <div class="form-group">
					<label for="eveningTelephone" class="control-label">Telephone Home</label>
					<input id="eveningTelephone" name="eveningTelephone" type="text" class="form-control">
				  </div>
				  <div class="form-group">
					  <label for="doNotMail">
						  If you would like to be kept up to date by Post please tick here:&#160;
						  <input id="doNotMail" type="checkbox" name="doNotMail" value="0">
					  </label>
				  </div>
				</div>
			  </div>
			</div>
			<div class="col-sm-1"></div>
			<div class="col-sm-5">
			  <div class="row">
				<div class="col-sm-12">
				  <div id="dd" class="prtContainer"><img src="media/com_jdd/images/dd.png" alt="" style="float:right">
					<h2>The Direct Debit Guarantee</h2>
					<ul>
					  <li>This Guarantee is offered by all banks and building societies that accept instructions to pay Direct Debits</li>
					  <li>If there are any changes to the amount, date or frequency of your Direct Debit <strong>The Karuna Trust</strong> will notify you 10 working days in advance of your account being debited or as otherwise agreed. If you request <strong>The Karuna Trust</strong> to collect a payment, confirmation of the amount and date will be given to you at the time of the request </li>
					  <li>If an error is made in the payment of your Direct Debit, by the <strong>The Karuna Trust</strong> or your bank or building society, you are entitled to a full and immediate refund of the amount paid from your bank or building society
						<ul>
						  <li id="guarantee">- If you receive a refund you are not entitled to, you must pay it back when the <strong>The Karuna Trust</strong> asks you to</li>
						</ul>
					  </li>
					  <li>
						You can cancel a Direct Debit at any time by simply contacting your bank or building society. Written confirmation
						may be required. Please also notify us.
					  </li>
					</ul>
				  </div>
				  <p>If you would like to print a copy of the guarantee,click <a href="#" onClick="prtDiv('dd');return false">here</a></p>
				</div>
			  </div>
			  <div class="row">
				<div class="col-sm-12">
				  <ul class="pager">
					<li class="next"><a href="#" class="open1">&#8594; Next</a></li>
				  </ul>
				</div>
			  </div>
			</div>
		  </fieldset>
		</div>
		<div id="sf2" class="frm">
		  <fieldset>
			<legend>Step 2 of 3 Your Bank Details</legend>
			<div class="col-sm-5">
			  <div class="row">
				<div class="col-sm-12">
				  <h3 class="head-sm">Account holders</h3>
				  <p>
					If you are not the account holder or your account requires more than
					one signature a paper Direct Debit Instruction will be required to be completed and posted to us. Click <a href="images/Karuna_Direct_Debit_form_Jan_13.pdf">here</a> to print off a paper Direct Debit Instruction.
				  </p>
				  <p>
					  To set-up your Direct Debit you must confirm that you are the account holder and that
					  you are the only person required to authorise Direct Debits from this account.
				  </p>
				  <div class="form-group">
					  <label for="accNameOk">
						  To confirm the account is in your name, and that you are the only person
						  required to authorise Direct Debits on this account please tick here:&#160; &#160;
						  <input id="accNameOk" type="checkbox" name="accNameOK" value="1">
					  </label>
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="col-sm-12">
				  <div class="form-group">
					<label for="accountName" class="control-label">Account Name</label>
					<input id="accountName" name="accountName" type="text" class="form-control">
				  </div>
				  <div class="form-group">
					<label for="sortCode" class="control-label">Sort Code</label>
					<input id="sortCode" name="sortCode" type="text" class="form-control">
				  </div>
				  <div class="form-group">
					<label for="accountNumber" class="control-label">Account Number</label>
					<input id="accountNumber" name="accountNumber" type="text" class="form-control">
					<input id="bankName" name="bankName" hidden="hidden">
					<input id="bankAddress" name="bankAddress" hidden="hidden">
					<input id="bankPostCode" name="bankPostCode" hidden="hidden">
				  </div>
				</div>
			  </div>
			</div>
			<div class="col-sm-1"></div>
			<div class="col-sm-5">
			  <div class="row">
				<div class="col-sm-12">
				  <h3 class="head-sm">Postal Confirmation and the Set-up</h3>
				  <p>
					The details of your Direct Debit Instruction will be sent to you within 3 working days
					or no later than 10 working days before the first collection.
				  </p>
				  <p>
					Any change to the date, amount and/or frequency of your Direct Debit collection will be
					notified to you at least 10 working days in advance.
				  </p>
				  <p>We shall lodge the Direct Debit Instruction on your account within 10 working days.</p>
				  <h3>Your Bank Statement</h3>
				  <p>Donations taken by us using Direct Debit will appear on your bank statement as The Karuna Trust.</p>
				  <h3>Our Postal Address</h3>
				  <address>
					72 Holloway Road<br>
					London<br>
					N7 8JG<br>
				  </address>
				  <h3>Direct Debit Enquiries</h3>
				  <p>If you have any queries, please phone 0207 700 3434 or email info@karuna.org.</p>
				</div>
			  </div>
			  <div class="row">
				<div class="col-sm-12">
				  <ul class="pager">
					<li class="previous"><a href="#" class="back2">&#8592; Previous</a></li>
					<li class="next"><a href="#" class="open2">&#8594; Next</a></li>
				  </ul>
				</div>
			  </div>
			</div>
		  </fieldset>
		</div>
		<div id="sf3" class="frm">
		  <fieldset>
			<legend>Step 3 of 3 Review your details</legend>
			<div class="col-sm-6"><strong>Please check the details you have entered:</strong>
			  <p>If any of the details below are incorrect please click on the  <strong>Previous</strong> button at the bottom of the  screen to amend your details. If your details are correct please click on the <strong>Submit</strong> button at the bottom of the screen</p>
			  <div class="row">
				<div class="col-xs-4">
				  <div class="form-group">
					<label for="instalmentValue" class="control-label">Amount</label>
					<div class="input-group"><span class="input-group-addon">£</span>
					  <input id="instalmentValue" name="instalmentValue" type="text" value="<?php echo $this->jddAmount; ?>" class="form-control">
					</div>
				  </div>
				</div>
				<div class="col-xs-4">
				  <div class="form-group">
					<label class="control-label">Start Date</label>
					<select name="startDate" class="form-control">
					  <option value="<?php echo $this->dd1; ?>"><?php echo $this->dd1; ?></option>
					  <option value="<?php echo $this->dd2; ?>"><?php echo $this->dd2; ?></option>
					</select>
				  </div>
				</div>
				<div class="col-xs-4">
				  <div class="form-group">
					<label class="control-label">Gift Aid</label>
					<select name="taxClaimable" class="form-control">
					  <option value="yes" <?php if ($this->isGad === 1) echo 'selected="selected"'; ?>>Yes</option>
					  <option value="no" <?php if ($this->isGad === 0) echo 'selected="selected"'; ?> >No</option>
					</select>
					<input id="isTest" hidden="hidden" name="isTest" value="FE-test">
				  </div>
				</div>
			  </div>
			  <div class="row">
				<div class="col-sm-12">
				  <table class="table">
					<tbody>
					  <tr>
						<td colspan="2"><b>Your contact details:</b></td>
					  </tr>
					  <tr>
						<td>Name:</td>
						<td id="rName"></td>
					  </tr>
					  <tr>
						<td>Address:</td>
						<td id="rAdd"></td>
					  </tr>
					  <tr>
						<td>Email:</td>
						<td id="remailAddress"></td>
					  </tr>
					  <tr>
						<td>Telephone:</td>
						<td id="rPhone"></td>
					  </tr>
					  <tr>
						<td colspan="2"><b>Your bank details:</b></td>
					  </tr>
					  <tr>
						<td>Account Name:</td>
						<td id="raccountName"></td>
					  </tr>
					  <tr>
						<td>Sortcode:</td>
						<td id="rSortCode"></td>
					  </tr>
					  <tr>
						<td>Number:</td>
						<td id="rAccNum"></td>
					  </tr>
					</tbody>
				  </table>
				</div>
			  </div>
			  <div class="row">
				<div class="col-sm-12">
				  <ul class="pager">
					<li class="previous"><a href="#" class="back3">&#8592; Previous</a></li>
					<li class="next"><a href="#" value="submit" class="open3">&#8594; Submit</a></li>
				  </ul>
				</div>
			  </div>
			</div>
			<div class="col-sm-1"></div>
			<div class="col-sm-5"></div>
		  </fieldset>
		</div>
	  </form>
	</div>
  </div>
</div>
<!-- vim: set filetype=html: -->
