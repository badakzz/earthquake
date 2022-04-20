<?php
require 'header.php';
$page_title = 'Setting up your arduino';
?>

<head>

	<!-- JQuery CDN -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js">
	</script>

	<style>
		.popup {
			display: none;
			width: 500px;
			color: red;
		}

		.gist-data {
			max-height: 600px;
			overflow-y: visible;
		}

		img {
			max-width: 100%;
			max-height: 100%;
			display: block;
			/* remove extra space below image */
		}

		body {
			height: 100vh;
			width: 100vw;
		}
	</style>
</head>

<main class="container">
	<div class="bg-light p-5 rounded">
		<h1>Setting up the Arduino WiFi</h1>
		<p>In this example, we're using a MK1010 Arduino card. Theorically, it should work on all <a href="https://www.arduino.cc/en/Reference/WiFiNINA">
			Arduino WiFiNINA compatible cards.</a> We're going to follow TechZero's guide to setup the card.
		</p>
		</br>
		<h2>How does it work ?</h2>
		<p>The Arduino card will retrieve the current time from a NTP server by sending and receiving NTP packets, and compare it to the time you set
			 on this website by constantly sending it GET requests to retrieve it. When the NTP time is greater than the set time, the Arduino will send 
			 a POST request to the website letting it know that it started vibrating, and changing the set time value in our database so the webapp can
			 know it needs to show a button to stop the motor from vibrating. When the button is clicked, the value in the database is changed to a value
			 that sets the Arduino to its initial state when parsed.</br>
			 The current time calculated by the Arduino is based on your system time.
			</br>
		<h2>Components needed :</h2>
		<ul>
			<li>Arduino WiFi NINA card</li>
			<li>Vibration motor</li>
			<li>NPN transistor</li>
			<li>Resistors</li>
			<li>Wires</li>
		</ul>
		</br>
		<img src="src/circuit-vibration-motor-arduino.png">
		</br>
		<p>Vibration motors require more power than an Arduino pin can provide, so a transistor is used to switch the motor current on and off. Any NPN 
			transistor can be used.</br>
			A 1 kilohm resistor connects the output pin to the transistor base; the value is not critical, and you can use values up to 4.7 kilohms or so 
			(the resistor prevents too much current flowing through the output pin).</br>
			The diode absorbs voltages produced by the motor windings as it rotates. The capacitor absorbs voltage spikes produced when the brushes 
			(contacts connecting electric current to the motor windings) open and close.</br>
			The 33-ohm resistor is needed to limit the amount of current flowing through the motor.</br>
		</p>
		</br>
		<h2>Code</h2>
		<p>In order for the Arduino card to behave as a clock, we're going to need to upload code into it. You have been given an ID when you created an 
			account, which can be found in <a href="profile.php">your profile</a> at any given moment.</br>
			You can either <a href="https://www.arduino.cc/en/Main/Software_">download the Arduino IDE</a> and upload to your card the following sketch from 
			there, or upload it from the <a href="https://create.arduino.cc/editor/">Arduino Online Editor</a>. 
			The <a href="https://support.arduino.cc/hc/en-us/articles/360014869820-How-to-install-the-Arduino-Create-Agent">Arduino Create Agent</a> will be required.
			</br>Make sure that all the included libraries are installed.
		</p>
		</br>
		<div class=" popup">
			</br>Make sure to replace myID on line 13 by your personal ID !</br>
		</div>
		</br>
		<div class="a">
			<script src="https://gist.github.com/badakzz/73c6ec73338badb5bf67a439220819c8.js"></script>
		</div>
	</div>
	<script>
		$flag = -1;

		$("div.a").hover(
			function() {
				$("div.popup").attr("style", "display:block");
			},
			function() {
				if ($flag == -1) {
					$("div.popup").attr("style", "display:none");
				}
			}
		);
	</script>
<?php

require 'footer.php';