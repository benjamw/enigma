<?php

namespace Enigma;

class Ring {

	// the following are set the to be the same as the real enigma machines
	// but if you wish to change them, feel free
	// comment out these versions to keep a record if you do

	/**
	 * The step rings (note that 4th rotors did not step)
	 * Syntax: array(string representation of ring [ , zero-based step notch position(s) ])
	 *
	 * @var array
	 */
	private static $RING = array(
		0       => array('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),

// TODO: find out where the notches are for these rings and how they work
		'IC'    => array('DMTWSILRUYQNKFEJCAZBPGXOHV'),
		'IIC'   => array('HQZGPJTMOBLNCIFDYAWVEUSRKX'),
		'IIIC'  => array('UQNTLSZFMREHDPXKIBVYGJCWOA'),

		'Ir'    => array('JGDQOXUSCAMIFRVTPNEWKBLZYH'),
		'IIr'   => array('NTZPSFBOKMWRCJDIVLAEYUXHGQ'),
		'IIIr'  => array('JVIUBHTCDYAKEQZPOSGXNRMWFL'),

		'I-K'   => array('PEZUOHXSCVFMTBGLRINQJWAYDK'),
		'II-K'  => array('ZOUESYDKFWPCIQXHMVBLGNJRAT'),
		'III-K' => array('EHRVXGAOBQUSIMZFLYNWKTPDJC'),

		'I'     => array('EKMFLGDQVZNTOWYHXUSPAIBRCJ', 16), // Q
		'II'    => array('AJDKSIRUXBLHWTMCQGZNPYFVOE',  4), // E
		'III'   => array('BDFHJLCPRTXVZNYEIWGAKMUSQO', 21), // V
		'IV'    => array('ESOVPZJAYQUIRHXLNFTGKDCMWB',  9), // J
		'V'     => array('VZBRGITYUPSDNHLXAWMJQOFECK', 25), // Z

		'VI'    => array('JPGVOUMFYQBENHZRDKASXLICTW', array(25, 12)), // Z + M
		'VII'   => array('NZJHGRCXMYSWBOUFAIVLPEKQDT', array(25, 12)), // Z + M
		'VIII'  => array('FKQHTLXOCBJSPDZRAMEWNIUYGV', array(25, 12)), // Z + M

		'Beta'  => array('LEYJVCNIXWPBQMDRTAKZGFUHOS'),
		'Gamma' => array('FSOKANUERHMBTIYCWLQPZXVGJD'),

		'In'    => array('WTOKASUYVRBXJHQCPZEFMDINLG', 16), // Q
		'IIn'   => array('GJLPUBSWEMCTQVHXAOFZDRKYNI',  4), // E
		'IIIn'  => array('JWFMHNBPUSDYTIXVZGRQLAOEKC', 21), // V
		'IVn'   => array('ESOVPZJAYQUIRHXLNFTGKDCMWB',  9), // J
		'Vn'    => array('HEJXQOTZBVFDASCILWPGYNMURK', 25), // Z
	);


	/**
	 * The reflector rings
	 *
	 * @var array
	 */
	private static $REFLECTOR = array(
		0       => array('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),

		'UKWr'  => array('QYHOGNECVPUZTFDJAXWMKISRBL'),

		'UKW-K' => array('IMETCGFRAYSQBZXWLHKDVUPOJN'),

		'A'     => array('EJMZALYXVBWFCRQUONTSPIKHGD'),
		'B'     => array('YRUHQSLDPXNGOKMIEBFZCWVJAT'),
		'C'     => array('FVPJIAOYEDRZXWGCTKUQSBNMHL'),

		'Bt'    => array('ENKQAUYWJICOPBLMDXZVFTHRGS'),
		'Ct'    => array('RDOBJNTKVEHMLFCWZAXGYIPSUQ'),

		'UKWn'  => array('MOWJYPUXNDSRAIBFVLKZGQCHET'),
	);


	/**
	 * The stator rings
	 *
	 * @var array
	 */
	private static $STATOR = array(
		0       => array('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),

		'ETWr'  => array('QWERTZUIOASDFGHJKPYXCVBNML'),

		'ETW-K' => array('QWERTZUIOASDFGHJKPYXCVBNML'),

		'ETW'   => array('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),
	);


	/**
	 * @var string
	 */
	protected $type;


	/**
	 * The ring string
	 *
	 * @var string
	 */
	protected $ring;


	/**
	 * The ring position
	 *
	 * @var int
	 */
	protected $pos;


	/**
	 * The step position for the ring
	 *
	 * @var array|int
	 */
	protected $step_pos;


	/**
	 * @var bool
	 */
	public $is_reflector = false;


	/**
	 * @var bool
	 */
	public $is_stator = false;


	/**
	 * Does the stepping notch rotate with the letters, or stay fixed to the ring
	 *
	 * @var bool
	 */
	public $has_rotatable_step = true;


	/**
	 * Class constructor
	 *
	 * @param string $ring ID optional
	 * @param int|string $ring_setting optional
	 * @param int|string $ground_setting optional
	 * @param bool $adjust_step optional adjust the step position
	 *
	 * @return Ring this
	 * @throws \InvalidArgumentException
	 */
	public function __construct($ring = null, $ring_setting = null, $ground_setting = null, $adjust_step = true) {
		try {
			if ( ! empty($ring)) {
				$this->setRing($ring);

				if ( ! empty($ring_setting) && ! empty($ground_setting)) {
					$this->setRingPosition($ring_setting, $ground_setting, $adjust_step);
				}
			}
		}
		catch (\InvalidArgumentException $up) {
			throw $up;
		}
	}


	/**
	 * @return string
	 */
	public function getType( ) {
		return $this->type;
	}


	/**
	 * @return string
	 */
	public function getRing( ) {
		return $this->ring;
	}


	/**
	 * @return int
	 */
	public function getPos( ) {
		return $this->pos;
	}


	/**
	 * @return array|int
	 */
	public function getStepPos( ) {
		return $this->step_pos;
	}


	/**
	 * Initialize the rings
	 * NOTE: does not do sanity checking for strict mode
	 *  Do sanity checking before passing settings here.
	 *
	 * @param string $ring ID
	 *
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	public function setRing($ring) {
		if ( ! empty(self::$STATOR[$ring])) {
			$this->is_stator = true;
			$this->has_rotatable_step = false;
			$this->ring = self::$STATOR[$ring][0];
			$this->pos = 0;
		}
		elseif ( ! empty(self::$RING[$ring])) {
			$this->ring = self::$RING[$ring][0];
			$this->step_pos = self::$RING[$ring][1];
			$this->pos = 0;
		}
		elseif ( ! empty(self::$REFLECTOR[$ring])) {
			$this->is_reflector = true;
			$this->has_rotatable_step = false;
			$this->ring = self::$REFLECTOR[$ring][0];
			$this->pos = 0;
		}
		else {
			throw new \InvalidArgumentException("Ring type '{$ring}' not found");
		}

		$this->type = $ring;
	}


	/**
	 * @param int|string $ring_setting
	 * @param int|string $ground_setting
	 * @param bool $adjust_step adjust the step position
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function setRingPosition($ring_setting, $ground_setting, $adjust_step = true) {
		if (empty($this->ring)) {
			throw new \Exception('No ring to set the position of');
		}

		// not possible to set position of stator ring
		if ($this->is_stator) {
			return;
		}

		// reflectors don't have a ring setting
		if ($this->is_reflector) {
			$ring_setting = 0;
		}

		if ( ! is_numeric($ring_setting)) {
			$ring_setting = strpos(self::$RING[0][0], $ring_setting);
		}

		$ring_setting = (int) $ring_setting;

		if ( ! is_numeric($ground_setting)) {
			$ground_setting = strpos(self::$RING[0][0], $ground_setting);
		}

		$ground_setting = (int) $ground_setting;

		$temp_ring = $this->ring;

		// adjust the ring's position based on the ring and ground settings
		$this->pos = self::normalize($ground_setting - $ring_setting);
		$this->ring = (0 != $this->pos)
			? substr($temp_ring, -(26 - $this->pos)) . substr($temp_ring, 0, $this->pos)
			: $temp_ring;

		// subtract the ring setting to get the 'real' step position
		if ($adjust_step && $this->has_rotatable_step) {
			if (is_array($this->step_pos)) {
				foreach ($this->step_pos as & $step_pos) { // mind the reference
					$step_pos = self::normalize($step_pos - $ring_setting);
				}
				unset($step_pos); // kill the reference
			}
			else {
				$this->step_pos = self::normalize($this->step_pos - $ring_setting);
			}
		}
	}


	/**
	 * Step the ring to next position
	 *
	 * @param void
	 *
	 * @return void
	 */
	public function step( ) {
		// step the actual ring (move the first char to the end of the line)
		$first_char = $this->ring{0};
		$remainder = substr($this->ring, 1);
		$this->ring = $remainder . $first_char;

		// step the ring position (increase it by one, unless it's Z, then start over)
		$this->pos = self::normalize($this->pos + 1);
	}


	/**
	 * @param void
	 *
	 * @return bool
	 */
	public function isSteppingNext( ) {
		return ($this->pos === $this->step_pos);
	}


	/**
	 * Encode a letter through the ring
	 *
	 * @param string $letter
	 * @param bool $backward
	 *
	 * @return string
	 */
	public function encode($letter, $backward = false) {
		$letter_idx = strpos(self::$RING[0][0], $letter);

		// TODO: add descriptive comment about how this works
		if ( ! $backward) {
			$ring_pos = self::normalize(strpos(self::$RING[0][0], $this->ring{$letter_idx}) - $this->pos);
		}
		else {
			$ring_pos = strpos($this->ring, self::$RING[0][0]{self::normalize($letter_idx + $this->pos)});
		}

		return self::$RING[0][0]{$ring_pos};
	}


	/**
	 * Function to test if Ring is a stator type
	 *
	 * @param $ring
	 *
	 * @return bool
	 */
	public static function isStator($ring) {
		return ! empty(self::$STATOR[$ring]);
	}


	/**
	 * Function to test if Ring is a reflector type
	 *
	 * @param $ring
	 *
	 * @return bool
	 */
	public static function isReflector($ring) {
		return ! empty(self::$REFLECTOR[$ring]);
	}


	/**
	 * Normalize a value to remain between two values
	 *
	 * @param int $value
	 * @param int $min optional inclusive (default 0)
	 * @param int $max optional exclusive (default 26)
	 *
	 * @return int
	 */
	public static function normalize($value, $min = 0, $max = 26) {
		if ($max == $min) {
			return $min;
		}

		if ($max < $min) {
			list($max, $min) = array($min, $max);
		}

		$diff = $max - $min;

		while ($value < $min) {
			$value += $diff;
		}

		while ($max <= $value) {
			$value -= $diff;
		}

		return $value;
	}

}
