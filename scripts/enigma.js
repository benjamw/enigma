;
(function($) {

	"use strict";

	const URL = 'enigma.php';

	const noop = function(){};

	let strict = $('#strict-yes').prop('checked');
	let group = $('#grouping-5').prop('checked') ? 5 : 4;

	$('input[name="strict"]').on('change', function() {
		strict = $('#strict-yes').prop('checked');

		$('#machine').prop('disabled', ! strict);
		$('input[name="group"]').prop('disabled', ! strict);

		if ( ! strict) {
			set_settings(null);
		}
	});

	$('input[name="group"]').on('change', function() {
		group = $('#grouping-5').prop('checked') ? 5 : 4;
	});

	$('#machine').on('change', function() {
		if ( ! strict) {
			return;
		}

		let data = {
			settings: true,
			machine: $(this).val(),
		};

		$.post(URL, data, noop, 'json')
			.done( function(data) {
				set_settings(data);
			})
			.fail( function( ) {
				alert('Something went wrong');
			});
	});

	/**
	 * Set the select options to disabled or enabled
	 * as needed for the given machine settings
	 *
	 * @param machine object
	 */
	function set_settings(machine) {
		let $st_ring = $('#stator').prop('disabled', false).val('')
				.find('option').prop('disabled', false),
			$a_ring = $('#a_ring').prop('disabled', false).val('')
				.find('option').prop('disabled', false),
			$b_ring = $('#b_ring').prop('disabled', false).val('')
				.find('option').prop('disabled', false),
			$c_ring = $('#c_ring').prop('disabled', false).val('')
				.find('option').prop('disabled', false),
			$th_ring = $('#thin_ring').prop('disabled', false).val('')
				.find('option').prop('disabled', false),
			$re_ring = $('#reflector').prop('disabled', false).val('')
				.find('option').prop('disabled', false),

			$st_set = $('#stator_ring_setting').prop('disabled', false).val('0'),
			$a_set = $('#a_ring_ring_setting').prop('disabled', false).val('0'),
			$b_set = $('#b_ring_ring_setting').prop('disabled', false).val('0'),
			$c_set = $('#c_ring_ring_setting').prop('disabled', false).val('0'),
			$th_set = $('#thin_ring_ring_setting').prop('disabled', false).val('0'),
			$re_set = $('#reflector_ring_ring_setting').prop('disabled', false).val('0'),

			$st_grd = $('#stator_ground_setting').prop('disabled', false).val('0'),
			$a_grd = $('#a_ring_ground_setting').prop('disabled', false).val('0'),
			$b_grd = $('#b_ring_ground_setting').prop('disabled', false).val('0'),
			$c_grd = $('#c_ring_ground_setting').prop('disabled', false).val('0'),
			$th_grd = $('#thin_ring_ground_setting').prop('disabled', false).val('0'),
			$re_grd = $('#reflector_ring_ground_setting').prop('disabled', false).val('0');

		filter_ring($st_ring, machine.entry, $st_set, $st_grd); // the stator ring
		filter_ring($a_ring, machine.rings, $a_set, $a_grd); // the a ring
		filter_ring($b_ring, machine.rings, $b_set, $b_grd); // the b ring
		filter_ring($c_ring, machine.rings, $c_set, $c_grd); // the c ring
		filter_ring($th_ring, machine.extra, $th_set, $th_grd); // the thin ring
		filter_ring($re_ring, machine.reflector, $re_set, $re_grd); // the reflector ring

		filter_stecker(machine);

		// none of the machines have movable stators
		// disable the settings in strict mode
		$st_set.prop('disabled', true);
		$st_grd.prop('disabled', true);

		// reflector options
		$re_set.prop('disabled', ! machine.movable_reflector);
		$re_grd.prop('disabled', ! machine.movable_reflector);
	}

	function filter_ring($ring, opts, $ring_set, $grd_set) {
		if (opts && Array.isArray(opts)) {
			if (0 === opts.length) {
				$ring.parent('select').prop('disabled', true);
				$ring_set.prop('disabled', true);
				$grd_set.prop('disabled', true);
				return;
			}

			$ring.each(function (idx, elem) {
				if (-1 === opts.indexOf(elem.getAttribute('value'))) {
					elem.disabled = true;
				}
			});

			$ring.parent('select').val(opts[0]);
		}
	}

	function filter_stecker(machine) {
		let $stecker = $('.stecker').prop('disabled', false);

		if (machine && false === !!machine.stecker) {
			$stecker.prop('disabled', true);
		}
	}

})(jQuery);
