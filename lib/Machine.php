<?php

namespace Enigma;

class Machine {

	/**
	 * ring index constants
	 */
	const STATOR = 0;
	const FAST = 1;
	const MIDDLE = 2;
	const SLOW = 3;

	/**
	 * @var string
	 */
	private static $RING = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';


	/**
	 * Allowed strict mode settings for the various machines
	 *
	 * @var array
	 */
	private $SETTINGS = array(
		// M1, M2, M3
		'I' => array(
			'entry' => array('ETW'),
			'rings' => array('I', 'II', 'III', 'IV', 'V'),
			'reflector' => array('A', 'B', 'C'),
			'stecker' => true,
			'double' => true,
		),
		'Norway' => array(
			'entry' => array('ETW'),
			'rings' => array('In', 'IIn', 'IIIn', 'IVn', 'Vn'),
			'reflector' => array('UKWn'),
			'stecker' => true,
			'double' => true,
		),
// TODO: add more, and make sure the above are correct
	);


	/**
	 * The stecker (plugboard) settings
	 *
	 * @var array
	 */
	protected $stecker = array(
		'A' => 'A',	'B' => 'B',	'C' => 'C',
		'D' => 'D',	'E' => 'E',	'F' => 'F',
		'G' => 'G',	'H' => 'H',	'I' => 'I',
		'J' => 'J',	'K' => 'K',	'L' => 'L',
		'M' => 'M',	'N' => 'N',	'O' => 'O',
		'P' => 'P',	'Q' => 'Q',	'R' => 'R',
		'S' => 'S',	'T' => 'T',	'U' => 'U',
		'V' => 'V',	'W' => 'W',	'X' => 'X',
		'Y' => 'Y',	'Z' => 'Z',
	);


	/**
	 * Number translation array
	 *
	 * @var array
	 */
	protected $numbers = array(
		0 => 'P', 1 => 'Q', 2 => 'W',
		3 => 'E', 4 => 'R', 5 => 'T',
		6 => 'Z', 7 => 'U', 8 => 'I',
		9 => 'O',
	);


	/**
	 * The currently used rings, in order from right to left
	 *
	 * @var array of \Enigma\Ring objects
	 */
	protected $rings = array( );


	/**
	 * Should the emulator emulate a real machine
	 *
	 * @var bool
	 */
	public $strict = false;


	/**
	 * Should the machine do a double step for each middle step?
	 *
	 * @var bool
	 */
	public $double_step = true;


	/**
	 * The grouping size (4, 5) or false to disable
	 *
	 * @var mixed
	 */
	public $groupings = false;


	/**
	 * @var string
	 */
	public $input;


	/**
	 * @var string
	 */
	public $output;


	/**
	 * Class constructor
	 *
	 * @param array $settings optional
	 * @param string $message optional
	 *
	 * @return string coded message
	 * @throws \Exception
	 */
	public function __construct($settings = array( ), $message = '') {
		try {
			$this->processSettings($settings);

			return $this->encode($message);
		}
		catch (\Exception $poo) {
			throw $poo;
		}
	}


	/**
	 * Process the incoming settings
	 *
	 * @param array $settings
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function processSettings($settings) {
		if (empty($settings)) {
			return;
		}

		if ( ! empty($settings['strict'])) {
			$this->strict = true;
		}

		if ( ! empty($settings['machine'])) {
// TODO: create a function that will pull the machine settings for the given machine
// and use those settings to validate the settings passed in below
		}

		try {
			if ( ! empty($settings['stecker'])) {
				$this->setStecker($settings['stecker']);
			}
		}
		catch (\Exception $food) {
			throw $food;
		}

		try {
			if ($this->strict) {
				$this->dupRingTest($settings['rings']);
				$this->ringTest($settings['rings']);
			}
		}
		catch (\Exception $a_curve) {
			throw $a_curve;
		}

		if ( ! empty($settings['rings'])) {
			$this->makeRings($settings['rings']);
		}

		if ( ! empty($settings['group'])) {
			$settings['group'] = (int) $settings['group'];

			if ($this->strict && (4 !== $settings['group']) && (5 !== $settings['group'])) {
				throw new \Exception("Group size ({$settings['group']}) is invalid, must be 4 or 5");
			}

			$this->groupings = $settings['group'];
		}
	}


	/**
	 * @param array $ring_settings array of ring settings arrays
	 *
	 * @return void
	 */
	public function makeRings($ring_settings) {
		$defaults = array(
			'ring' => 0,
			'ground' => 0,
		);

		$has_stator = $has_reflector = false;
		foreach ($ring_settings as $ring_setting) {
			$ring_setting = array_merge($defaults, $ring_setting);

			$new_ring = new Ring($ring_setting['type'], $ring_setting['ring'], $ring_setting['ground']);

			$has_stator = $has_stator || $new_ring->is_stator;
			$has_reflector = $has_reflector || $new_ring->is_reflector;

			$this->rings[] = $new_ring;
		}

		if ( ! $has_stator) {
			// set the most common stator
			array_unshift($this->rings, new Ring('ETW'));
		}

		if ( ! $has_reflector) {
			if (4 === count($this->rings)) {
				// set the most common reflector
				array_push($this->rings, new Ring('B'));
			}
			else {
				// set the most common thin reflector
				array_push($this->rings, new Ring('Bt'));
			}
		}
	}


	/**
	 * Test for duplicate rings
	 *
	 * @param array $rings setting array
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function dupRingTest($rings) {
		if ( ! $this->strict) {
			return;
		}

		for ($i = 0, $len = count($rings); $i < $len; ++$i) {
			for ($ii = $i + 1; $ii < $len; ++$ii) {
				if ($rings[$i]['type'] === $rings[$ii]['type']) {
					throw new \Exception('There are duplicate rings being used in strict mode');
				}
			}
		}
	}


	/**
	 * @param array $rings setting array
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function ringTest($rings) {
		if ( ! $this->strict) {
			return;
		}

// TODO: build this. make sure there are the right number of rings for the given machine
// and that they are the right type and in the right order
	}


	/**
	 * Clear the stecker
	 *
	 * @param void
	 *
	 * @action void
	 */
	public function clearStecker( ) {
		$letters = str_split(self::$RING);
		$this->stecker = array_combine($letters, $letters);
	}


	/**
	 * Initialize the stecker
	 *
	 * @param array $board
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function setStecker($board) {
		$new_board = false;
		foreach ($board as $in => $out) {
			if ( ! $new_board && is_numeric($out)) {
				$new_board = array( );
			}

			$new_board[] = self::$RING{$in - 1} . self::$RING{$out - 1};
		}

		if ($new_board) {
			$board = $new_board;
		}

		try {
			$this->steckerTest($board);
		}
		catch (\Exception $up) {
			throw $up;
		}

		$this->clearStecker( );

		foreach ($board as $plug) {
			// make sure we have a plug board entry here
			if ('' == $plug) {
				continue;
			}

			$plug = strtoupper($plug);
			$this->stecker[$plug{0}] = $plug{1};
			$this->stecker[$plug{1}] = $plug{0};
		}
	}


	/**
	 * @param array $board
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function steckerTest($board) {
		// test for duplicates in the stecker
		$stecker_test = array( );

		foreach ($board as $plug) {
			// make sure we have a plug board entry here
			if ('' == $plug) {
				continue;
			}

			$plug = strtoupper($plug);

			// make sure we have a complete plug board entry here
			if (2 !== strlen($plug)) {
				throw new \Exception("Plug Board (stecker) entry '{$plug}' is not two characters");
			}

			$stecker_test[] = $plug{0};
			$stecker_test[] = $plug{1};
		}

		$stecker_check = array_unique($stecker_test);

		if (count($stecker_check) !== count($stecker_test)) {
			throw new \Exception('Plug Board (stecker) has duplicate entries');
		}
	}


	/**
	 * Clean the incoming message
	 *
	 * @param string $message
	 *
	 * @return string
	 */
	public function cleanMessage($message) {
		$this->input = $message;

		$message = strtoupper($message);

		if ($this->strict) {
			$message = str_replace(array_keys($this->numbers), $this->numbers, $message);
			$message = preg_replace('%[^A-Z]+%', '', $message);
			$message = preg_replace("%([A-Z]{{$this->groupings}})%", '$1 ', $message);
		}

		return trim($message);
	}


	/**
	 * @param string $message
	 *
	 * @return string
	 */
	public function cleanReturn($message) {
		$message = strtoupper($message);

		if ($this->strict) {
			$message = preg_replace('%\s+%', '', $message);
			$message = preg_replace("%([A-Z]{{$this->groupings}})%", '$1 ', $message);
		}

		$this->output = trim($message);

		return $this->output;
	}


	/**
	 * Encode the message through the machine
	 *
	 * @param string $incoming message
	 *
	 * @return string outgoing message
	 */
	public function encode($incoming) {
		$message = $this->cleanMessage($incoming);
		$enc_message = '';

		while (0 !== strlen($message)) {
			$letter = self::charPop($message);

			// make sure the next character is a letter
			if (false === strpos(self::$RING, $letter)) {
				// add the non-letter to the outgoing message unchanged
				$enc_message .= $letter;
				continue;
			}

			$this->stepRings( );

			$enc_message .= $this->encode_letter($letter);
		}

		return $this->cleanReturn($enc_message);
	}


	/**
	 * @param void
	 *
	 * @return void
	 */
	protected function stepRings( ) {
		// step any rings that need to be stepped
		// step from slow to fast because the position will change when the ring is stepped
		// and we need to know the position to know which rings to step
		// if the fast ring is stepping the middle ring
		$middle_stepped = false; // we need to keep track of the middle ring stepping
		if ($this->rings[self::FAST]->isSteppingNext( )) {
			// if the middle ring is stepping the slow ring
			if ($this->rings[self::MIDDLE]->isSteppingNext( )) {
				// step the slow ring
				$this->rings[self::SLOW]->step( );
			}

			// step the middle ring
			$this->rings[self::MIDDLE]->step( );
			$middle_stepped = true; // make sure we don't step the middle ring twice if it's at it's own stepping point
		}

		// there is a quirk with the enigma where, even though
		// the fast ring is not stepping the middle ring,
		// if the middle ring is on it's stepping position
		// the middle ring will step the slow ring (and itself)
		// account for that here (but only if we didn't just barely step the middle ring above)
		if ($this->double_step && (false === $middle_stepped) && $this->rings[2]->isSteppingNext( )) {
			// step the slow ring
			$this->rings[self::SLOW]->step( );

			// step the middle ring
			$this->rings[self::MIDDLE]->step( );
		}

		// always step the fast ring
		$this->rings[self::FAST]->step( );
	}


	/**
	 * Encode a letter through all the rings
	 *
	 * @param $letter
	 *
	 * @return string
	 */
	public function encode_letter($letter) {
		// run it through the stecker
		$letter = $this->stecker[$letter];

		// run it forward through the rings (including the reflector)
		foreach ($this->rings as $ring) {
			$letter = $ring->encode($letter);
		}

		// == REFLECTED ====================================

		// run it backward through the rings (skipping the reflector)
		$skipped = false;
		foreach (array_reverse($this->rings, true) as $ring) {
			if ( ! $skipped) {
				$skipped = true;
				continue;
			}

			$letter = $ring->encode($letter, true);
		}

		// run it back through the stecker
		$letter = $this->stecker[$letter];

		return $letter;
	}


	/**
	 * Pop the leading character off the message
	 *
	 * @param string $message reference
	 *
	 * @return string
	 */
	public static function charPop(& $message) {
		// pop the leading character off the message
		$letter  = $message[0];
		$message = substr($message, 1);

		return $letter;
	}

}
