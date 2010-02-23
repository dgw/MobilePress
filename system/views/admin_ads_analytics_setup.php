<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2>Setup Ads & Analytics</h2>
	<p>MobilePress gives you the ability to integrate with <a href="http://aduity.com/register/preview">Aduity</a> for mobile ad serving and analytics.</p>
	<p>You will have to <a href="http://aduity.com/register/preview">register an account at Aduity</a>. Once you have done that, grab the account key and secret key so that to authenticate your MobilePress plugin with Aduity. You will then be able to configure ad serving and analytics from within MobilePress.</p>
	<h2>Account Settings</h2>
	<p>Please enter your Aduity <em><strong>account public key</strong></em> and <em><strong>account secret key</strong></em>.</p>
	<form method="post" action="admin.php?page=mobilepress-ads-analytics">
		<table class="form-table">
			<tr>
				<th scope="row">Account Public Key:</th>
				<td>
					<input type="text" name="apk" class="regular-text" /> <span class="description">Find in your account settings.</span>
				</td>
			</tr>
			<tr>
				<th scope="row">Account Secret Key:</th>
				<td>
					<input type="text" name="ask" class="regular-text" /> <span class="description">Find in your account settings.</span>
				</td>
			</tr>
		</table>
		<p class="submit"><input type="submit" name="add" class="button-primary" value="Save Account Details!" /></p>
	</form>
</div>