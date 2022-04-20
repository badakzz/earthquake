<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/app/functions/isMobileDevice.php');

if (isMobileDevice() == 0) {
	echo <<<HTML
<div class="row">
	<div class="col-md-9">
		<p style="color:black"> Mobile device is set as
HTML;
			if ($data['mobile_device'] == 0) :
				echo ' <span style="color:rgba(219, 68, 58, 1)">OFF</span>. Click on the button below to turn off browser-only mode, making it possible to turn off your alarm from a mobile device.</br>
						</div>
						<div class="col-md-1"></div>
						<div class="col-md-2">
							<form method="post" action="profile.php">
								<input id="turn-on" type="submit" class="btn btn-primary" name="mobile-on-btn" value="Turn on">
							</form>
						</div>';
			elseif ($data['mobile_device'] == 1) :
				echo ' <span style="color:#24AA7C">ON</span>. Click on the button below to switch to browser-only mode, making it impossible to turn off your alarm from a mobile device.</br>
						</div>
						<div class="col-md-1"></div>
						<div class="col-md-2">
							<form method="post" action="profile.php">
								<input id="turn-off" type="submit" class="btn btn-primary" name="mobile-off-btn" value="Turn off">
							</form>
						</div>';
			endif;
	echo <<<HTML
		</p>
	</div>
HTML;
}
else {
	echo <<<HTML
<div class="row">
	<div class="col-md-9">
		<p style="color:black"> Mobile device is set as
HTML;
	if ($data['mobile_device'] == 0) :
		echo ' <span style="color:rgba(219, 68, 58, 1)">OFF</span>.
		If you wish to turn it on so you can snooze your alarm from your mobile device, log onto this page from a web browser.</br>
		</div>';
	elseif ($data['mobile_device'] == 1) :
		echo
		' <span style="color:#24AA7C">ON</span>. Click on the button below to switch to browser-only mode, making it impossible to turn off your alarm from a mobile device.</br>
		</div>
		<div class="col-md-1"></div>
		<div class="col-md-2">
			<form method="post" action="profile.php">
				<input id="turn-off" type="submit" class="btn btn-primary" name="mobile-off-btn" value="Turn off">
			</form>
		</div>';
	endif;
	echo <<<HTML
		</p>
	</div>
HTML;
}