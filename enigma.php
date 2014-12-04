<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *\

   enigma machine emulator */ $version = '1.02'; /*

   benjam welker <benjam@iohelix.net>
   http://iohelix.net/misc/enigma.php

								  started on 2006-11-26
					   */ $last_edit_date = '2007-08-22'; /*

 =====================================================================

 CHANGE LOG
 ---------------------------------------------------------------------
   2007-08-22 (1.02)
	 - Functioned out some things that I was doing many times
	   (ife, selected)
	 - Changed license to GPLv3

   2007-04-03 (1.01) . . . . . . . . . . . . . . . . . . . . . . . . .
	 - Fixed some minor PHP errors

   2006-12-02 (1.00) . . . . . . . . . . . . . . . . . . . . . . . . .
	 - Re-did inner workings which fixed an error with the ring
	   settings
	 - First Successful Encoding with complex settings
	 - Finished backbone coding
	 - First Release

   2006-11-28 (0.85) . . . . . . . . . . . . . . . . . . . . . . . . .
	 - Finished basic coding
	 - First Successful Encoding with simple settings

   2006-11-27 (0.81) . . . . . . . . . . . . . . . . . . . . . . . . .
	 - More basic coding completed

   2006-11-26 (0.80) . . . . . . . . . . . . . . . . . . . . . . . . .
	 - Began basic coding

 =====================================================================

 TO DO
 ---------------------------------------------------------------------
   + ajax the output so there is no page refresh
   + add ability to download settings and message
   + add ability to upload saved settings and message so they can be
	 quickly decoded
   + convert core functions to enigma object

 =====================================================================

 LICENSE
 ---------------------------------------------------------------------
   This program is released 'as-is' and the user takes full
   responsibility for any use. I am not responsible for anything.
   blah blah blah

   This program is released under the GPLv3.
   http://www.gnu.org/licenses/gpl-3.0.txt

\* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


/* DECLARATIONS ------
-- These functions are found at the end of the file

(stdout) icall( [(mixed) $var] ) // outputs $var, or asterisks, to the browser (debugging only)

(mixed) ife( (mixed) $var, (mixed) $alt ) // if $var is set, returns $var, else returns $alt

(string) selected( (mixed) $var1, (mixed) $var2 [, (bool) $checked] ) // if $var1 == $var2, then it returns 'selected' based on $checked

(int) normalize( (int) $value ) // adjusts $value to stay between 0 and 25

(stdout) print_option_html( [(string) $value] ) // prints an option list of the alphabet to the browser, optionally pre-selects $value

(null) step_ring( (string) & $ring, (int) & $position ) // steps $ring forward one space and increments $position (position is normalized to between 0 and 25)

----- END DECLARATIONS */


// get the html form values from the POST array (if any)
// to return back into the form
$incoming         = ife($_POST['incoming']        , '');
$outgoing         = ife($_POST['outgoing']        , '');
$reflector        = ife($_POST['reflector']       , '');
$a_ring_number    = ife($_POST['a_ring_number']   , '');
$b_ring_number    = ife($_POST['b_ring_number']   , '');
$c_ring_number    = ife($_POST['c_ring_number']   , '');
$a_ring_setting   = ife($_POST['a_ring_setting']  , '');
$b_ring_setting   = ife($_POST['b_ring_setting']  , '');
$c_ring_setting   = ife($_POST['c_ring_setting']  , '');
$a_ground_setting = ife($_POST['a_ground_setting'], '');
$b_ground_setting = ife($_POST['b_ground_setting'], '');
$c_ground_setting = ife($_POST['c_ground_setting'], '');
$board            = ife($_POST['board']           , array( ));
$grouping         = (int) ife($_POST['grouping']  , 5);

// set some translation arrays
$STECKER = array(
	'A' => 'A',	'B' => 'B',	'C' => 'C',
	'D' => 'D',	'E' => 'E',	'F' => 'F',
	'G' => 'G',	'H' => 'H',	'I' => 'I',
	'J' => 'J',	'K' => 'K',	'L' => 'L',
	'M' => 'M',	'N' => 'N',	'O' => 'O',
	'P' => 'P',	'Q' => 'Q',	'R' => 'R',
	'S' => 'S',	'T' => 'T',	'U' => 'U',
	'V' => 'V',	'W' => 'W',	'X' => 'X',
	'Y' => 'Y',	'Z' => 'Z'
);


// the following are set the to be the same as the real enigma machine
// but if you wish to change them, feel free
// comment out these versions to keep a record if you do
$RING      = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

$RING_I    = 'EKMFLGDQVZNTOWYHXUSPAIBRCJ';
$RING_II   = 'AJDKSIRUXBLHWTMCQGZNPYFVOE';
$RING_III  = 'BDFHJLCPRTXVZNYEIWGAKMUSQO';
$RING_IV   = 'ESOVPZJAYQUIRHXLNFTGKDCMWB';
$RING_V    = 'VZBRGITYUPSDNHLXAWMJQOFECK';

$REFLECT_A = 'EJMZALYXVBWFCRQUONTSPIKHGD';
$REFLECT_B = 'YRUHQSLDPXNGOKMIEBFZCWVJAT';
$REFLECT_C = 'FVPJIAOYEDRZXWGCTKUQSBNMHL';

$STEP_I    = 16; // Q
$STEP_II   = 4;  // E
$STEP_III  = 21; // V
$STEP_IV   = 9;  // J
$STEP_V    = 25; // Z


// set the strict flag
$strict = (isset($_POST['strict']) && ('yes' == $_POST['strict'])) ? true : false;

// if we have some text to encode, lets do it
if (isset($_POST['incoming']) && ('' != $_POST['incoming']))
{
	if ($strict)
	{
		// test for duplicates in the rings
		if (($_POST['a_ring_number'] == $_POST['b_ring_number'])
			|| ($_POST['b_ring_number'] == $_POST['c_ring_number'])
			|| ($_POST['c_ring_number'] == $_POST['a_ring_number']))
		{
			$error = 'There are duplicate rings being used in strict mode';
		}
	}

	// test for duplicates in the stecker
	$stecker_test = array( );

	foreach ($_POST['board'] as $plug)
	{
		// make sure we have a plug board entry here
		if ('' == $plug)
		{
			continue;
		}

		// make sure we have a complete plug board entry here
		if (2 != strlen($plug))
		{
			$error = 'Plug Board (stecker) entry is not two characters';
			break;
		}

		$plug = strtoupper($plug);
		$stecker_test[] = $plug[0];
		$stecker_test[] = $plug[1];
	}

	$stecker_check = array_unique($stecker_test);

	if (count($stecker_check) != count($stecker_test))
	{
		$error = 'Plug Board (stecker) has duplicate entries';
	}

	// convert the message to upper case
	$incoming = strtoupper($_POST['incoming']);

	if ($strict)
	{
		// remove all but the letters & numbers from the message
		$incoming = preg_replace('/[^A-Z]+/', '', $incoming);
	}

	if ( ! isset($error) || ('' == $error))
	{
				icall('_POST');
		// set up the machine based on the given settings
		$a = 'RING_' . $_POST['a_ring_number'];
		$b = 'RING_' . $_POST['b_ring_number'];
		$c = 'RING_' . $_POST['c_ring_number'];
		$r = 'REFLECT_' . $_POST['reflector'];
				icall('a');icall('b');icall('c');icall('r');

		$as = 'STEP_' . $_POST['a_ring_number'];
		$bs = 'STEP_' . $_POST['b_ring_number'];
				icall('as');icall('bs');

		// adjust the ring's position based on the ring and ground settings
				icall('RING');icall($_POST['a_ground_setting']);icall(strpos($RING, $_POST['a_ground_setting']));
				icall('RING');icall($_POST['a_ring_setting']);icall(strpos($RING, $_POST['a_ring_setting']));
		$fast_pos   = normalize(strpos($RING, $_POST['a_ground_setting']) - strpos($RING, $_POST['a_ring_setting']));
				icall('fast_pos');icall($RING[normalize($fast_pos + strpos($RING, $_POST['a_ring_setting']))]);

				icall('RING');icall($_POST['b_ground_setting']);icall(strpos($RING, $_POST['b_ground_setting']));
				icall('RING');icall($_POST['b_ring_setting']);icall(strpos($RING, $_POST['b_ring_setting']));
		$middle_pos = normalize(strpos($RING, $_POST['b_ground_setting']) - strpos($RING, $_POST['b_ring_setting']));
				icall('middle_pos');icall($RING[normalize($middle_pos + strpos($RING, $_POST['b_ring_setting']))]);

				icall('RING');icall($_POST['c_ground_setting']);icall(strpos($RING, $_POST['c_ground_setting']));
				icall('RING');icall($_POST['c_ring_setting']);icall(strpos($RING, $_POST['c_ring_setting']));
		$slow_pos   = normalize(strpos($RING, $_POST['c_ground_setting']) - strpos($RING, $_POST['c_ring_setting']));
				icall('slow_pos');icall($RING[normalize($slow_pos + strpos($RING, $_POST['c_ring_setting']))]);

		$fast   = (0 != $fast_pos) ? substr(${$a}, -(26 - $fast_pos)) . substr(${$a}, 0, $fast_pos) : ${$a};
		$middle = (0 != $middle_pos) ? substr(${$b}, -(26 - $middle_pos)) . substr(${$b}, 0, $middle_pos) : ${$b};
		$slow   = (0 != $slow_pos) ? substr(${$c}, -(26 - $slow_pos)) . substr(${$c}, 0, $slow_pos) : ${$c};
		$refl   = ${$r};
				icall('fast');icall('middle');icall('slow');icall('refl');

		// subtract the ring setting to get the 'real' position
		$fast_step   = normalize(${$as} - strpos($RING, $_POST['a_ring_setting']));
		$middle_step = normalize(${$bs} - strpos($RING, $_POST['b_ring_setting']));
				icall('fast_step');icall('middle_step');

		// edit the stecker array
		foreach ($_POST['board'] as $plug)
		{
			// make sure we have a plug board entry here
			if ('' == $plug)
			{
				continue;
			}

			$plug = strtoupper($plug);
			$STECKER[$plug[0]] = $plug[1];
			$STECKER[$plug[1]] = $plug[0];
		}

		// now begin parsing the message through the enigma machine
		$message = $incoming; // initialize the destructible message
		$enc_message = ''; // initialize the encoded message
		while (0 != strlen($message))
		{
					if (isset($_GET['debug'])) { echo '<hr />'; }

			// step any rings that need to be stepped
			// step from slow to fast because the position will change when the ring is stepped
			// and we need to know the position to know which rings to step
			// if the fast ring is stepping the middle ring
			$middle_stepped = false; // we need to keep track of the middle ring stepping
			if ($fast_pos == $fast_step)
			{
				// if the middle ring is stepping the slow ring
				if ($middle_pos == $middle_step)
				{
					// step the slow ring
					step_ring($slow, $slow_pos);
							icall('STEPPING SLOW');icall('slow');icall('slow_pos');icall($RING[normalize($slow_pos + strpos($RING, $_POST['c_ring_setting']))]);
				}

				// step the middle ring
				step_ring($middle, $middle_pos);
				$middle_stepped = true; // make sure we don't step the middle ring twice if it's at it's stepping point
						icall('STEPPING MIDDLE');icall('middle');icall('middle_pos');icall($RING[normalize($middle_pos + strpos($RING, $_POST['b_ring_setting']))]);
			}

			// there is a quirk with the enigma where, even though
			// the fast ring is not stepping the middle ring,
			// the middle ring steps the slow ring (and itself)
			// account for that here (but only if we didn't just barely step the middle ring)
			if ((false === $middle_stepped) && ($middle_pos == $middle_step))
			{
				// step the slow ring
				step_ring($slow, $slow_pos);
						icall('STEPPING SLOW');icall('slow');icall('slow_pos');icall($RING[normalize($slow_pos + strpos($RING, $_POST['c_ring_setting']))]);

				// step the middle ring
				step_ring($middle, $middle_pos);
						icall('STEPPING MIDDLE');icall('middle');icall('middle_pos');icall($RING[normalize($middle_pos + strpos($RING, $_POST['b_ring_setting']))]);
			}

			// always step the fast ring

			step_ring($fast, $fast_pos);
					icall('STEPPING FAST');icall('fast');icall('fast_pos');icall($RING[normalize($fast_pos + strpos($RING, $_POST['a_ring_setting']))]);icall('---------------------------------');

			// pop the leading character off the message
			$letter  = $message[0];
			$message = substr($message, 1);
					icall('letter');icall($message);

			// make sure the next character is a letter (should only run in non-strict mode)
			if (false === strpos($RING, $letter))
			{
				// add the non-letter to the outgoing message unchanged
				$enc_message .= $letter;
				continue;
			}

			// encode the letter
			// run it through the stecker
			$enc_letter = $STECKER[$letter];
					icall('enc_letter');

			// run it through the fast ring
			$enc_letter = $RING[normalize(strpos($RING, $fast[strpos($RING, $enc_letter)]) - $fast_pos)];
					icall('enc_letter');

			// run it through the middle ring
			$enc_letter = $RING[normalize(strpos($RING, $middle[strpos($RING, $enc_letter)]) - $middle_pos)];
					icall('enc_letter');

			// run it through the slow ring
			$enc_letter = $RING[normalize(strpos($RING, $slow[strpos($RING, $enc_letter)]) - $slow_pos)];
					icall('enc_letter');

			// run it through the reflector
			$enc_letter = $refl[strpos($RING, $enc_letter)];
					icall('REFLECTION---');icall('enc_letter');

			// run it backwards through the slow ring
			$enc_letter = $RING[strpos($slow, $RING[normalize(strpos($RING, $enc_letter) + $slow_pos)])];
					icall('enc_letter');

			// run it backwards through the middle ring
			$enc_letter = $RING[strpos($middle, $RING[normalize(strpos($RING, $enc_letter) + $middle_pos)])];
					icall('enc_letter');

			// run it backwards through the fast ring
			$enc_letter = $RING[strpos($fast, $RING[normalize(strpos($RING, $enc_letter) + $fast_pos)])];
					icall('enc_letter');

			// run it back through the stecker
			$enc_letter = $STECKER[$enc_letter];
					icall('enc_letter');

			// add the encoded letter to the message
			$enc_message .= $enc_letter;
		} // end of message encode while loop

		if ($strict)
		{
			// split outgoing message into $grouping size chunks
			$outgoing = preg_replace("/([A-Z]{{$grouping}})/", '$1 ', $enc_message);

			// split incoming message into $grouping size chunks
			$incoming = preg_replace("/([A-Z]{{$grouping}})/", '$1 ', $incoming);
		}
		else
		{
			// return the encoded message
			$outgoing = $enc_message;
		}
	} // end of error check
} // end of incoming check


echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<!-- last edited on <?php echo $last_edit_date; ?> (<?php echo $version; ?>) -->
	<title>the enigma emulator</title>
	<!-- original url: http://iohelix.net/enigma.php -->

	<meta http-equiv="Content-Language"   content="en-us" />
	<meta http-equiv="Content-Type"       content="text/html; charset=iso-8859-1" />
	<meta http-equiv="Content-Style-Type" content="text/css" />

	<meta name="author"    content="Benjam Welker" />

	<style type="text/css">
		label, .dropdown { float:left; width:100px; }
		.plus { width:200px; }
		hr { clear:both; border:0; border-bottom:1px solid #777; width:80%; margin-left:0; }
		hr.bold { border-bottom: 2px solid #000; width: 100%; }
		input[type=text], select, textarea { font-family:monospace; text-transform:uppercase; }
		input[type=text], textarea { margin:3px 2px; padding:2px 4px; }
		.error { font-size:large; font-weight:bold; color:red; }
		.narrow { width:70ex; }
	</style>
</head>
<body>
	<form action="" method="post">
		<h1>the enigma emulator</h1><?php

		if (isset($error) && ('' != $error))
		{
			echo "\n		<p class=\"error\">{$error}</p>";
		}

		?>

		<p><a href="#about">about this script</a></p>
		<hr class="bold" />
		<h2>setup</h2>
		<p>set up the machine by using the various inputs below<br />
			the final state of the machine will be shown on the inputs after the message has been decoded</p>
		<input type="radio" name="strict" value="yes"<?php if ($strict) { echo ' checked="checked"'; } ?> />strict (real-life) mode (do not allow duplicate rings and return only letters; no numbers, punctuation, or original spaces)<br />
		<input type="radio" name="strict" value="no"<?php if ( ! isset($strict) || ! $strict) { echo ' checked="checked"'; } ?> />sloppy mode (default - allow duplicates; and retain numbers, punctuation, and original spaces)<br />
		<p>grouping size (strict-mode only)<br />
		<input type="radio" name="grouping" value="4"<?php if (4 == $grouping) { echo ' checked="checked"'; } ?> />4 letters
		<input type="radio" name="grouping" value="5"<?php if (5 == $grouping) { echo ' checked="checked"'; } ?> />5 letters<br />
		<h3>ring settings</h3>
		<label for="reflector">reflector &nbsp; &larr;</label>
		<label for="c_ring_setting">C ring &nbsp; &larr;</label>
		<label for="c_ring_setting">B ring &nbsp; &larr;</label>
		<label for="c_ring_setting">A ring &nbsp; &larr;</label>
		<hr />
		<div class="dropdown">
			<select name="reflector" id="reflector">
				<option<?php echo selected('A', $reflector); ?>>A</option>
				<option<?php echo selected('B', $reflector); ?>>B</option>
				<option<?php echo selected('C', $reflector); ?>>C</option>
			</select>
		</div>
		<div class="dropdown">
			<select name="c_ring_number" id="c_ring_number">
				<option<?php echo selected('I'  , $c_ring_number); ?>>I</option>
				<option<?php echo selected('II' , $c_ring_number); ?>>II</option>
				<option<?php echo selected('III', $c_ring_number); ?>>III</option>
				<option<?php echo selected('IV' , $c_ring_number); ?>>IV</option>
				<option<?php echo selected('V'  , $c_ring_number); ?>>V</option>
			</select>
		</div>
		<div class="dropdown">
			<select name="b_ring_number" id="b_ring_number">
				<option<?php echo selected('I'  , $b_ring_number); ?>>I</option>
				<option<?php echo selected('II' , $b_ring_number); ?>>II</option>
				<option<?php echo selected('III', $b_ring_number); ?>>III</option>
				<option<?php echo selected('IV' , $b_ring_number); ?>>IV</option>
				<option<?php echo selected('V'  , $b_ring_number); ?>>V</option>
			</select>
		</div>
		<div class="dropdown">
			<select name="a_ring_number" id="a_ring_number">
				<option<?php echo selected('I'  , $a_ring_number); ?>>I</option>
				<option<?php echo selected('II' , $a_ring_number); ?>>II</option>
				<option<?php echo selected('III', $a_ring_number); ?>>III</option>
				<option<?php echo selected('IV' , $a_ring_number); ?>>IV</option>
				<option<?php echo selected('V'  , $a_ring_number); ?>>V</option>
			</select>
		</div>
		<div class="dropdown plus">Ring ID</div>
		<br /><br />
		<div class="dropdown">&nbsp;</div>
		<div class="dropdown">
			<select name="c_ring_setting" id="c_ring_setting">
				<?php print_option_html($c_ring_setting); ?>
			</select>
		</div>
		<div class="dropdown">
			<select name="b_ring_setting" id="b_ring_setting">
				<?php print_option_html($b_ring_setting); ?>
			</select>
		</div>
		<div class="dropdown">
			<select name="a_ring_setting" id="a_ring_setting">
				<?php print_option_html($a_ring_setting); ?>
			</select>
		</div>
		<div class="dropdown plus">Ring Setting (inside)</div>
		<br /><br />
		<div class="dropdown">&nbsp;</div>
		<div class="dropdown">
			<select name="c_ground_setting" id="c_ground_setting">
				<?php print_option_html($c_ground_setting); ?>
			</select>
		</div>
		<div class="dropdown">
			<select name="b_ground_setting" id="b_ground_setting">
				<?php print_option_html($b_ground_setting); ?>
			</select>
		</div>
		<div class="dropdown">
			<select name="a_ground_setting" id="a_ground_setting">
				<?php print_option_html($a_ground_setting); ?>
			</select>
		</div>
		<div class="dropdown plus">Ground Setting (window)</div>
		<hr />
		<h3>plug board settings</h3>
		<?php

			for ($i = 0; $i < 13; ++$i)
			{
				$value = (isset($board[$i])) ? $board[$i] : '';
				echo '		<input type="text" name="board[]" size="2" maxlength="2" value="' . $value . "\" />\n";
			}
		?>

		<hr class="bold" />
		<h2>encoding / decoding</h2>
		<p>enter your message into the 'incoming message' box below and press 'encode' to view your encoded message</p>
		<h3>incoming message</h3>
		<textarea name="incoming" rows="10" cols="60"><?php echo $incoming; ?></textarea><br />
		<input type="submit" value="encode" /><br />
		<h3>encoded message</h3>
		<textarea name="outgoing" rows="10" cols="60"><?php echo $outgoing; ?></textarea>
	</form>
	<hr class="bold" />
	<h2><a name="about" />about</h2>
	<p class="narrow">this script emulates the function of an m3 german enigma machine from wwii.
	this script is a proof of concept for myself.  i merely wanted to see if i could do it,
	as well as give people a quick and easy way to encipher / decipher anything they wanted
	and also retain the original spaces and punctuation, which is not allowed in other enigma
	emulators.</p>
	<p>last updated: <?php echo $last_edit_date; ?><br />
	version: <?php echo $version; ?></p>
	<pre>
	<?php icall($GLOBALS); ?>
	</pre>
</body>
</html><?php


// normalizes the value to stay between 0 and 25
function normalize($value)
{
	while ($value >= 26)
	{
		$value -= 26;
	}

	while ($value < 0)
	{
		$value += 26;
	}

	return $value;
}


// selection element creation functions
// $value is the selected value (if any)
function print_option_html($value)
{
	$alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	for ($i = 0; $i < 26; ++$i)
	{
		$sel = ($alpha[$i] === $value) ? ' selected="selected"' : '';
		echo "<option{$sel}>{$alpha[$i]}</option>";
	}
}


// steps the ring
function step_ring(& $ring, & $position)
{
	// step the actual ring (move the first char to the end of the line)
	$first_char = $ring[0];
	$remainder = substr($ring, 1);
	$ring = $remainder . $first_char; // returned by reference

	// step the ring position (increase it by one, unless it's Z, then start over)
	if (25 == $position)
	{
		$position = 0;
	}
	else
	{
		++$position;
	}
}


// sets default values
function ife( & $value, $alt) {
	return ((isset($value)) ? $value : $alt);
}


// selects the html option
function selected($var1, $var2, $checked = false) {
	if (($var1 === (int) $var2) || ($var1 === (string) $var2)) {
		return (($checked) ? ' checked="checked"' : ' selected="selected" ');
	}

	return ' ';
}


// debug function
function icall($var = '^^k8)SJ2di!U')
{
	// only output the debugging info if we are in debugging mode
	if ( ! isset($_GET['debug']))
	{
		return false;
	}

	if ('^^k8)SJ2di!U' === $var)
	{
		echo '<span style="font-weight:bold;background:white;color:red;">*****</span>';
	}
	else
	{
		// begin output buffering so we can escape any html
		ob_start( );

		if (is_string($var) && isset($GLOBALS[$var]))
		{
			echo '$' . $var . ' = ';
			$var = $GLOBALS[$var];
		}

		if (is_bool($var) || is_null($var))
		{
			var_dump($var);
		}
		else
		{
			print_r($var);
		}

		// end output buffering and output the result
		$contents = htmlentities(ob_get_contents( ));
		ob_end_clean( );

		echo '<pre style="background:#FFF;color:#000;font-size:larger;">'.$contents.'</pre>';
	}
}

?>
