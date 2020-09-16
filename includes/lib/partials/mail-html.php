<?php
	/**
	 * Default HTML Mail Template.
	 * 
	 * To override this file, add a "mail-html.php" file to a "multi-step-form" directory in your
	 * theme directory. 
	 * 
	 * $data contains the filled form data.
	 * $headline contains a message that can be set per form.
	 */
    if (!defined('ABSPATH')) exit;
?>
<html><body>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		  <tbody><tr>
			  <td bgcolor="#ffffff" align="center" style="padding: 20px 15px 70px;" class="section-padding">
				  <table border="0" cellpadding="0" cellspacing="0" width="500" class="responsive-table">
					  <tbody><tr>
						  <td>
							  <table width="100%" border="0" cellspacing="0" cellpadding="0">
								  <tbody><tr>
									  <td>
										  <table width="100%" border="0" cellspacing="0" cellpadding="0">
											  <tbody><tr>
												  <td align="left" style="font-size: 22px; font-family: Helvetica, Arial, sans-serif; color: #333333; padding-top: 30px;" colspan="2" class="padding-copy"><?php echo $headline; ?></td>
											  </tr>
                                              <?php
                                              foreach ( $data as $key => $value ) {
												  echo '<tr><td align="left" style="padding: 30px 0 10px 0; font-size: 20px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" colspan="2" class="padding-copy"><strong>' . $key . '</strong> </td></tr>';
												  foreach ( $value as $value2 ) {
													  foreach ( $value2 as $key2 => $value3 ) {
															$value3 = str_replace("\n", "<br/>", $value3);
														 	echo '<tr><td align="left" style="border:solid 1px #dadada; border-width:0 0 1px 0; padding: 10px 0 10px 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">'. $key2 .'</td><td align="left" style=" border:solid 1px #dadada; border-width:0 0 1px 0; padding: 10px 0 10px 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">'. $value3 .'</td></tr>';
													  }
												  }
											  } ?>
										  </tbody></table>
									  </td>
								  </tr>
							  </tbody></table>
						  </td>
					  </tr>
				  </tbody></table>
			  </td>
		  </tr>
	  </tbody></table>
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tbody><tr>
			<td bgcolor="#f5f5f5" align="center">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
					<tbody><tr>
						<td style="padding: 40px 0px 40px 0px;">
							<!-- UNSUBSCRIBE COPY -->
							<table width="500" border="0" cellspacing="0" cellpadding="0" align="center" class="responsive-table">
								<tbody><tr>
									<td align="center" valign="middle" style="font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666;">
										<span class="appleFooter" style="color:#666666;">Multi Step Form | powered by <a href="http://mondula.com">Mondula GmbH</a> <?php echo date("Y"); ?></span>
									</td>
								</tr>
							</tbody></table>
						</td>
					</tr>
				</tbody></table>
			</td>
		</tr>
	</tbody></table>
</body></html>
