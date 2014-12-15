<!DOCTYPE html>
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

	<!--[if IE]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

</head>
<body>

<div class="container">
	<div class="row">
		<div class="u-pull-right"><a href="#about">about</a></div>
		<h1>Enigma Emulator</h1>

		<form action="<?= basename(__FILE__); ?>" method="post">
			<div class="row">
				<hr class="bold">
				<h2>setup</h2>

				<p>set up the machine by using the various inputs below<br>
					the final state of the machine will be shown on the inputs after the message has been decoded</p>
				<label><input type="radio" name="strict" value="yes"> strict (real-life) mode (do not allow
					duplicate rings and return only letters; no numbers, punctuation, or original spaces)</label><br>
				<label><input type="radio" name="strict" value="no"> sloppy mode (default - allow
					duplicates; and retain numbers, punctuation, and original spaces)</label><br>

				<p>grouping size (strict-mode only)<br>
					<label><input type="radio" name="grouping" value="4"> 4 letters</label>
					<label><input type="radio" name="grouping" value="5"> 5 letters</label><br>

				<h3>ring settings</h3>
				<label for="reflector">reflector &nbsp; &larr;</label>
				<label for="c_ring_setting">C ring &nbsp; &larr;</label>
				<label for="c_ring_setting">B ring &nbsp; &larr;</label>
				<label for="c_ring_setting">A ring &nbsp; &larr;</label>
				<hr>
				<div class="dropdown">
					<select name="reflector" id="reflector">
						<option>A</option>
						<option>B</option>
						<option>C</option>
					</select>
				</div>
				<div class="dropdown">
					<select name="c_ring_number" id="c_ring_number">
						<option>I</option>
						<option>II</option>
						<option>III</option>
						<option>IV</option>
						<option>V</option>
					</select>
				</div>
				<div class="dropdown">
					<select name="b_ring_number" id="b_ring_number">
						<option>I</option>
						<option>II</option>
						<option>III</option>
						<option>IV</option>
						<option>V</option>
					</select>
				</div>
				<div class="dropdown">
					<select name="a_ring_number" id="a_ring_number">
						<option>I</option>
						<option>II</option>
						<option>III</option>
						<option>IV</option>
						<option>V</option>
					</select>
				</div>
				<div class="dropdown plus">Ring ID</div>
				<br><br>

				<div class="dropdown">&nbsp;</div>
				<div class="dropdown">
					<select name="c_ring_setting" id="c_ring_setting">

					</select>
				</div>
				<div class="dropdown">
					<select name="b_ring_setting" id="b_ring_setting">

					</select>
				</div>
				<div class="dropdown">
					<select name="a_ring_setting" id="a_ring_setting">

					</select>
				</div>
				<div class="dropdown plus">Ring Setting (inside)</div>
				<br><br>

				<div class="dropdown">&nbsp;</div>
				<div class="dropdown">
					<select name="c_ground_setting" id="c_ground_setting">

					</select>
				</div>
				<div class="dropdown">
					<select name="b_ground_setting" id="b_ground_setting">

					</select>
				</div>
				<div class="dropdown">
					<select name="a_ground_setting" id="a_ground_setting">

					</select>
				</div>
				<div class="dropdown plus">Ground Setting (window)</div>
				<hr>
				<h3>plug board settings</h3>
				<?php
					for ($i = 0; $i < 13; ++$i) {
						echo '<input type="text" name="board[]" size="2" maxlength="2" value="" >';
					}
				?>

				<hr class="bold">
				<h2>encoding / decoding</h2>

				<p>enter your message into the 'incoming message' box below and press 'encode' to view your encoded
					message</p>

				<h3>incoming message</h3>
				<textarea name="incoming" rows="10" cols="60"></textarea><br>
				<input type="submit" value="encode"><br>

				<h3>encoded message</h3>
				<textarea name="outgoing" rows="10" cols="60"></textarea>
			</div>
		</form>
		<hr class="bold">
		<h2><a name="about"></a>about</h2>

		<p class="narrow">this script emulates the function of an m3 german enigma machine from wwii.
			this script is a proof of concept for myself. i merely wanted to see if i could do it,
			as well as give people a quick and easy way to encipher / decipher anything they wanted
			and also retain the original spaces and punctuation, which is not allowed in other enigma
			emulators.</p>
	</div>
</div>

</body>
</html>
