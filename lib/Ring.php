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
		0        => array('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),

		// Enigma D (A26 - Ch. 8)
		// Enigma K (A27 - Ch 11b)
		'I-D'    => array('LPGSZMHAEOQKVXRFYBUTNICJDW', 24), // Y
		'II-D'   => array('SLVGBTFXJQOHEWIRZYAMKPCNDU',  4), // E
		'III-D'  => array('CJGDPSHKTURAWZXFMYNQOBVLIE', 13), // N

		// Swiss Enigma K (Swiss-K)
		'I-SK'   => array('PEZUOHXSCVFMTBGLRINQJWAYDK', 24), // Y
		'II-SK'  => array('ZOUESYDKFWPCIQXHMVBLGNJRAT',  4), // E
		'III-SK' => array('EHRVXGAOBQUSIMZFLYNWKTPDJC', 13), // N
		// note that later on, the Swiss machines were altered so that
		// the fast wheel was made static and the middle wheel was made
		// to step on every keypress, becoming the fast wheel
		// they were also rewired every two years, but that information
		// is unavailable

		// Japanese Enigma T (Tirpitz)
		'I-T'    => array('KPTYUELOCVGRFQDANJMBSWHZXI', array(22, 25,  4, 10, 16)), // WZEKQ
		'II-T'   => array('UPHZLWEQMTDJXCAKSOIGVBYFNR', array(22, 25,  5, 11, 17)), // WZFLR
		'III-T'  => array('QUDLYRFEKONVZAXWHMGPJBSICT', array(22, 25,  4, 10, 16)), // WZEKQ
		'IV-T'   => array('CIWTBKXNRESPFLYDAGVHQUOJZM', array(22, 25,  5, 11, 17)), // WZFLR
		'V-T'    => array('UAXGISNJBVERDYLFZWTPCKOHMQ', array(24,  2,  5, 10, 17)), // YCFKR
		'VI-T'   => array('XFUZGALVHCNYSEWQTDMRBKPIOJ', array(23,  4,  8, 12, 16)), // XEIMQ
		'VII-T'  => array('BJVFTXPLNAYOZIKWGDQERUCHSM', array(24,  2,  5, 10, 17)), // YCFKR
		'VIII-T' => array('YMTPNZHWKODAJXELUQVGCBISFR', array(23,  4,  8, 12, 16)), // XEIMQ

		// Enigma KD
		'I-KD'   => array('VEZIOJCXKYDUNTWAPLQGBHSFMR', array(18, 20, 24,  0,  4,  7, 11, 13, 16)), // SUYAEHLNQ
		'II-KD'  => array('HGRBSJZETDLVPMQYCXAOKINFUW', array(18, 20, 24,  0,  4,  7, 11, 13, 16)), // SUYAEHLNQ
		'III-KD' => array('NWLHXGRBYOJSAZDVTPKFQMEUIC', array(18, 20, 24,  0,  4,  7, 11, 13, 16)), // SUYAEHLNQ

		// Railway Enigma (Rocket)
		'Ir'     => array('JGDQOXUSCAMIFRVTPNEWKBLZYH', 13), // N
		'IIr'    => array('NTZPSFBOKMWRCJDIVLAEYUXHGQ',  4), // E
		'IIIr'   => array('JVIUBHTCDYAKEQZPOSGXNRMWFL', 24), // Y

		// Enigma I
		// Enigma M1, M2, M3
		// Enigma M1, M2, M3 Navy
		// Enigma M4
		'I'      => array('EKMFLGDQVZNTOWYHXUSPAIBRCJ', 16), // Q
		'II'     => array('AJDKSIRUXBLHWTMCQGZNPYFVOE',  4), // E
		'III'    => array('BDFHJLCPRTXVZNYEIWGAKMUSQO', 21), // V
		'IV'     => array('ESOVPZJAYQUIRHXLNFTGKDCMWB',  9), // J
		'V'      => array('VZBRGITYUPSDNHLXAWMJQOFECK', 25), // Z

		// Enigma M1, M2, M3 Navy
		'VI'     => array('JPGVOUMFYQBENHZRDKASXLICTW', array(25, 12)), // ZM
		'VII'    => array('NZJHGRCXMYSWBOUFAIVLPEKQDT', array(25, 12)), // ZM
		'VIII'   => array('FKQHTLXOCBJSPDZRAMEWNIUYGV', array(25, 12)), // ZM

		// Norway Enigma
		'In'     => array('WTOKASUYVRBXJHQCPZEFMDINLG', 16), // Q
		'IIn'    => array('GJLPUBSWEMCTQVHXAOFZDRKYNI',  4), // E
		'IIIn'   => array('JWFMHNBPUSDYTIXVZGRQLAOEKC', 21), // V
		'IVn'    => array('ESOVPZJAYQUIRHXLNFTGKDCMWB',  9), // J
		'Vn'     => array('HEJXQOTZBVFDASCILWPGYNMURK', 25), // Z
	);


	/**
	 * The stator rings
	 *
	 * @var array
	 */
	private static $STATOR = array(
		0        => array('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),

		// Enigma D (A26 - Ch. 8)
		// Enigma K (A27 - Ch 11b)
		// Swiss Enigma K (Swiss-K)
		// Enigma KD
		// Railway Enigma (Rocket)
		'ETW-Q'  => array('QWERTZUIOASDFGHJKPYXCVBNML'),

		// Japanese Enigma T (Tirpitz)
		'ETW-T'  => array('KZROUQHYAIGBLWVSTDXFPNMCJE'),

		// Enigma I
		// Norway Enigma
		// Enigma M1, M2, M3
		// Enigma M1, M2, M3 Navy
		// Enigma M4
		'ETW-A'  => array('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),
	);


	/**
	 * The extra (thin) rings
	 *
	 * @var array
	 */
	private static $EXTRA = array(
		0        => array('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),

		// Enigma M4
		'Beta'   => array('LEYJVCNIXWPBQMDRTAKZGFUHOS'),
		'Gamma'  => array('FSOKANUERHMBTIYCWLQPZXVGJD'),
	);


	/**
	 * The reflector rings
	 *
	 * @var array
	 */
	private static $REFLECTOR = array(
		0       => array('ABCDEFGHIJKLMNOPQRSTUVWXYZ'),

		// Enigma D (A26 - Ch. 8)
		// Enigma K (A27 - Ch 11b)
		// Swiss Enigma K (Swiss-K)
		'UKW'   => array('IMETCGFRAYSQBZXWLHKDVUPOJN'),

		// Japanese Enigma T (Tirpitz)
		'UKW-T' => array('GEKPBTAUMOCNILJDXZYFHWVQSR'),

		// Enigma KD
		'UKW-D' => array('NSUOMKLIHZFGEADVXWBYCPRQTJ'), // found setting, real reflector is changeable

		// Railway Enigma (Rocket)
		'UKWr'  => array('QYHOGNECVPUZTFDJAXWMKISRBL'),

		// Enigma I
		'A'     => array('EJMZALYXVBWFCRQUONTSPIKHGD'),

		// Enigma I
		// Enigma M1, M2, M3
		'B'     => array('YRUHQSLDPXNGOKMIEBFZCWVJAT'),
		'C'     => array('FVPJIAOYEDRZXWGCTKUQSBNMHL'),

		// Norway Enigma
		'UKWn'  => array('MOWJYPUXNDSRAIBFVLKZGQCHET'),

		// Enigma M4
		'Bt'    => array('ENKQAUYWJICOPBLMDXZVFTHRGS'),
		'Ct'    => array('RDOBJNTKVEHMLFCWZAXGYIPSUQ'),
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
	 * Is the ring steppable
	 *
	 * @var bool
	 */
	protected $is_steppable;


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
	 * Don't allow actions that were impossible with a real machine
	 *
	 * @var bool
	 */
	protected $strict = true;


	/**
	 * @var bool
	 */
	public $is_reflector = false;


	/**
	 * @var bool
	 */
	public $is_stator = false;


	/**
	 * @var bool
	 */
	public $is_extra = false;


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
	 * @param bool $steppable optional set steppable flag
	 * @param bool $strict optional set strict mode
	 *
	 * @return Ring this
	 * @throws \InvalidArgumentException
	 */
	public function __construct($ring = null, $ring_setting = null, $ground_setting = null, $adjust_step = true, $steppable = true, $strict = true) {
		try {
			$this->is_steppable = (bool) $steppable;
			$this->strict = (bool) $strict;

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
	 * Initialize the ring with either a ring ID
	 * or ring settings for non-strict mode
	 *
	 * @param string $ring ID or ring value
	 * @param string $type optional ring type ('ring', 'stator', 'reflector')
	 * @param bool $rotatable_step optional ring has a rotatable notch
	 * @param int|string|array $step_pos optional the location of the stepping position
	 *
	 * @return void
	 * @throws \InvalidArgumentException
	 */
	public function setRing($ring, $type = 'ring', $rotatable_step = true, $step_pos = 0) {
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
		elseif ( ! empty(self::$EXTRA[$ring])) {
			$this->is_extra = true;
			$this->has_rotatable_step = false;
			$this->ring = self::$EXTRA[$ring][0];
			$this->pos = 0;
		}
		elseif ( ! empty(self::$REFLECTOR[$ring])) {
			$this->is_reflector = true;
			$this->has_rotatable_step = false;
			$this->ring = self::$REFLECTOR[$ring][0];
			$this->pos = 0;
		}
		elseif ( ! $this->strict && (26 === strlen($ring))) {
			switch (strtolower($type)) {
				case 'stator' : // no break
				case 'entry' : // no break
				case 'eintrittswalze' : // no break
				case 'etw' :
					$this->is_stator = true;
					break;

				case 'extra' : // no break
				case 'thin' : // no break
				case 'zusatzwalze' :
					$this->is_extra = true;
					break;

				case 'reflector' : // no break
				case 'umkehrwalze' : // no break
				case 'ukw' :
					$this->is_reflector = true;
					break;
			}

			$this->has_rotatable_step = (bool) $rotatable_step;
			$this->ring = $ring;
			$this->step_pos = self::toIndex($step_pos);
			$this->pos = 0;

			$ring = 'MANUAL';
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
		if ($this->strict && $this->is_stator) {
			return;
		}

		// reflectors don't have a ring setting
		if ($this->strict && $this->is_reflector) {
			$ring_setting = 0;
		}

		$ring_setting = self::toIndex($ring_setting);
		$ground_setting = self::toIndex($ground_setting);

		$temp_ring = $this->ring;

		// adjust the ring's position based on the ring and ground settings
		$this->pos = self::normalize($ground_setting - $ring_setting);
		$this->ring = (0 != $this->pos)
			? substr($temp_ring, -(26 - $this->pos)) . substr($temp_ring, 0, $this->pos)
			: $temp_ring;

		// subtract the ring setting to get the 'real' step position
		// for rings that have the step notches cut into to the rotatable
		// portion of the ring instead of the ring itself
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
		if ($this->strict && ! $this->is_steppable) {
			return;
		}

		// step the actual ring (move the first char to the end of the line)
		$this->ring = self::stepChar($this->ring);

		// step the ring position (increase it by one, unless it's Z, then start over)
		$this->pos = self::normalize($this->pos + 1);
	}


	/**
	 * @param void
	 *
	 * @return bool
	 */
	public function isSteppingNext( ) {
		if ($this->strict && ! $this->is_steppable) {
			return;
		}

		if ( ! is_array($this->step_pos)) {
			return ($this->pos === $this->step_pos);
		}
		else {
			return in_array($this->pos, $this->step_pos);
		}
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
		$letter_idx = self::toIndex($letter);

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
	 * @param string $ring ring ID
	 *
	 * @return bool
	 */
	public static function isStator($ring) {
		return ! empty(self::$STATOR[$ring]);
	}


	/**
	 * Function to test if Ring is an extra type
	 *
	 * @param string $ring ring ID
	 *
	 * @return bool
	 */
	public static function isExtra($ring) {
		return ! empty(self::$EXTRA[$ring]);
	}


	/**
	 * Function to test if Ring is a reflector type
	 *
	 * @param string $ring ring ID
	 *
	 * @return bool
	 */
	public static function isReflector($ring) {
		return ! empty(self::$REFLECTOR[$ring]);
	}


	/**
	 * Step the ring string by moving the first char to
	 * the end of the string
	 *
	 * @param string $ring
	 * @param int $steps
	 *
	 * @return string
	 */
	public static function stepChar($ring, $steps = 1) {
		while (0 < $steps) {
			$first_char = $ring{0};
			$remainder = substr($ring, 1);
			$ring = $remainder . $first_char;
			--$steps;
		}

		return $ring;
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


	/**
	 * Convert string character indexes to numeric indexes
	 *
	 * @param int|string|array $value index value
	 * @param string $string optional string to index
	 *
	 * @return int|array
	 */
	public static function toIndex($value, $string = null) {
		if (is_array($value)) {
			foreach ($value as & $val) { // mind the reference
				$val = self::toIndex($val);
			}
			unset($val); // kill the reference

			return $value;
		}

		if (is_numeric($value)) {
			return self::normalize($value);
		}

		if (is_null($string)) {
			$string = self::$RING[0][0];
		}

		return (int) strpos($string, $value);
	}

}
