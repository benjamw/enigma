<?php

// get the classes to create the dropdowns, because lazy

namespace Enigma;

//include 'vendor/autoload.php';
include 'autoloader.php';

$helper = new Helper( );

// TODO: create a function that will convert input settings to a URL query string and append to window.location
// so that the settings can be bookmarked, shared, etc.

// TODO: create an output box that will show the current settings so they can be shared
// have this be in both plaintext (json) and a base64_url encoded version

// TODO: create an output box that will show the path the cipher takes through the machine

// TODO: have a checkbox that when set, will automatically decode the operator's chosen
// message settings. The first three characters get translated using the given settings,
// then those new values get set in the ground settings (grundstellung) and the rest of the
// message goes from there.

// TEMP DEBUGGING
if ( ! empty($_POST)) {
	g($_POST);

	$E = new Machine($_POST);
	d($E);
	g($E->encode($_POST['incoming'], true));
	d($E);
}

?><!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<title>Enigma Emulator</title>
	<meta name="description" content="An Enigma Emulator that emulates most Enigma Machines">
	<meta name="author" content="Benjam">

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>

	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/skeleton.css">

	<link rel="icon" type="image/png" href="images/favicon.png">

	<script
		src="https://code.jquery.com/jquery-3.4.0.min.js"
		integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg="
		crossorigin="anonymous"></script>

</head>
<body>

<div class="container">
	<div class="u-pull-right"><a href="#about">about</a></div>
	<h1>Enigma Emulator</h1>
	<hr class="bold">

	<form action="<?= basename(__FILE__); ?>" method="post">
		<h2>setup</h2>

		<p>set up the machine using the inputs below<br>
			the final state of the machine will be shown on the inputs after the message has been decoded.
			or during encoding if encoding each keypress</p>

		<h5>processing mode</h5>
		<div class="row">
			<div class="twelve columns">
				<label><input type="radio" name="strict" id="strict-yes" value="yes" checked="checked">
					<span class="label-body">strict (real-life) mode
						<small>do not allow duplicate/incorrect rings and return only letters; no numbers,
							punctuation, or original spaces</small>
					</span>
				</label>
				<label><input type="radio" name="strict" id="strict-no" value="no">
					<span class="label-body">sloppy mode
						<small>allow duplicate/incorrect rings and retain numbers, punctuation, and original spaces</small>
					</span>
				</label>
			</div>
		</div>

		<h5>machine selector</h5>
		<p>select a machine from the following list, and the settings available below will update for that machine.<br>
			<span class="warn">this setting is only valid in strict mode.</span></p>
		<div class="row">
			<select name="machine" id="machine">
				<?= $helper->getMachineListOptions( ); ?>
			</select>
		</div>

		<h5>grouping size (strict-mode only)</h5>
		<div class="row">
			<div class="two columns">
				<label><input type="radio" name="group" id="grouping-4" value="4">
					<span class="label-body">4 letters</span>
				</label>
			</div>
			<div class="two columns">
				<label><input type="radio" name="group" id="grouping-5" value="5" checked="checked">
					<span class="label-body">5 letters</span>
				</label>
			</div>
		</div>

		<h5>ring settings</h5>
		<div class="row">
			<div class="two columns">reflector &nbsp; &larr;</div>
			<div class="two columns">thin ring &nbsp; &larr;</div>
			<div class="two columns">C ring &nbsp; &larr;</div>
			<div class="two columns">B ring &nbsp; &larr;</div>
			<div class="two columns">A ring &nbsp; &larr;</div>
			<div class="two columns">stator &nbsp; &larr;</div>
		</div>
		<hr class="wide">

		<div>Ring ID</div>
		<div class="row">
			<div class="two columns">
				<select name="rings[5][type]" id="reflector" title="Reflector Ring ID">
					<?= $helper->getRingListOptions('reflector'); ?>
				</select>
			</div>
			<div class="two columns">
				<select name="rings[4][type]" id="thin_ring" title="Thin Ring ID">
					<?= $helper->getRingListOptions( ); ?>
				</select>
			</div>
			<div class="two columns">
				<select name="rings[3][type]" id="c_ring" title="C (slow) Ring ID">
					<?= $helper->getRingListOptions( ); ?>
				</select>
			</div>
			<div class="two columns">
				<select name="rings[2][type]" id="b_ring" title="B (middle) Ring ID">
					<?= $helper->getRingListOptions( ); ?>
				</select>
			</div>
			<div class="two columns">
				<select name="rings[1][type]" id="a_ring" title="A (fast) Ring ID">
					<?= $helper->getRingListOptions( ); ?>
				</select>
			</div>
			<div class="two columns">
				<select name="rings[0][type]" id="stator" title="Stator ID">
					<?= $helper->getRingListOptions( ); ?>
				</select>
			</div>
		</div>

		<div>Ring Setting <small>(Ringstellung) (inside)</small></div>
		<div class="row">
			<div class="two columns">
				<select name="rings[5][ring]" id="reflector_ring_ring_setting" title="Reflector Ring Setting">
					<?= $helper->getRingSettingListOptions( ); ?>
				</select>
			</div>
			<div class="two columns">
				<select name="rings[4][ring]" id="thin_ring_ring_setting" title="Thin Ring Setting">
					<?= $helper->getRingSettingListOptions( ); ?>
				</select>
			</div>
			<div class="two columns">
				<select name="rings[3][ring]" id="c_ring_ring_setting" title="C (slow) Ring Setting">
					<?= $helper->getRingSettingListOptions( ); ?>
				</select>
			</div>
			<div class="two columns">
				<select name="rings[2][ring]" id="b_ring_ring_setting" title="B (middle) Ring Setting">
					<?= $helper->getRingSettingListOptions( ); ?>
				</select>
			</div>
			<div class="two columns">
				<select name="rings[1][ring]" id="a_ring_ring_setting" title="A (fast) Ring Setting">
					<?= $helper->getRingSettingListOptions( ); ?>
				</select>
			</div>
			<div class="two columns">
				<select name="rings[0][ring]" id="stator_ring_setting" title="Stator Ring Setting">
					<?= $helper->getRingSettingListOptions( ); ?>
				</select>
			</div>
		</div>

		<div>Ground Setting <small>(Grundstellung) (window)</small></div>
		<div class="row">
			<div class="two columns">
				<select name="rings[5][ground]" id="reflector_ring_ground_setting" title="Reflector Ground Setting">
					<?= $helper->getRingSettingListOptions( ); ?>
				</select>
			</div>
			<div class="two columns">
				<select name="rings[4][ground]" id="thin_ring_ground_setting" title="Thin Ground Setting">
					<?= $helper->getRingSettingListOptions( ); ?>
				</select>
			</div>
			<div class="two columns">
				<select name="rings[3][ground]" id="c_ring_ground_setting" title="C (slow) Ground Setting">
					<?= $helper->getRingSettingListOptions( ); ?>
				</select>
			</div>
			<div class="two columns">
				<select name="rings[2][ground]" id="b_ring_ground_setting" title="B (middle) Ground Setting">
					<?= $helper->getRingSettingListOptions( ); ?>
				</select>
			</div>
			<div class="two columns">
				<select name="rings[1][ground]" id="a_ring_ground_setting" title="A (fast) Ground Setting">
					<?= $helper->getRingSettingListOptions( ); ?>
				</select>
			</div>
			<div class="two columns">
				<select name="rings[0][ground]" id="stator_ground_setting" title="Stator Ground Setting">
					<?= $helper->getRingSettingListOptions( ); ?>
				</select>
			</div>
		</div>

		<hr class="wide">
		<h5>plug board settings <small>(Steckerbrett)</small></h5>
		<p>enter a pair of letters into each box. those pairs will be swapped during encoding. use as many or as few
			as you like.</p>
		<p class="warning" style="display:none;">the steckerbrett has been disabled for this machine.</p>
		<div class="row">
		<?php
			for ($i = 0; $i < 13; ++$i) {
				echo '<input type="text" name="stecker[]" size="2" maxlength="2" value="" class="stecker upper"> ';
			}
		?>
		</div>

		<hr class="bold">
		<h2>encoding / decoding</h2>

		<p>enter your message into the 'incoming message' box below and press 'encode' to view your encoded
			message</p>

		<h3>incoming message</h3>
		<div class="row">
			<textarea name="incoming" rows="30" cols="60" class="u-full-width upper"></textarea><br>
		</div>

		<div class="row">
			<div class="two columns">
				<input type="submit" value="encode" class="button-primary" disabled="disabled">
			</div>
			<div class="ten columns">
				<label><input type="checkbox" id="live" name="live" checked="checked">
					<span class="label-body">Live encoding
						<small>encode the message and update the machine status while typing</small>
					</span>
				</label>
			</div>
		</div>

		<h3>encoded message</h3>
		<div class="row">
			<div id="outgoing" class="u-full-width upper" ></div>
		</div>
	</form>

	<hr class="bold">
	<h2><a name="about"></a>about</h2>

	<p>this script emulates the function of various enigma machines from WWII.
		this script is a proof of concept for myself. i merely wanted to see if i could do it,
		as well as provide a quick and easy way to encipher / decipher anything while also retaining
		the original spaces and	punctuation, which is not allowed in other enigma emulators.
		i also wanted to be able to use some of the more rare versions that aren't usually
		included in enigma emulators.</p>
</div>

<script src="scripts/enigma.js"></script>
</body>
</html>
