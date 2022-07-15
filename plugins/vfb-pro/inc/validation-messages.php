<?php
/**
 * Default Validation Messages
 *
 * Returns an array of default validation messages.
 *
 * @package     VFB Pro
 * @version     3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

return array(
	'default'   => __( 'This value seems to be invalid.', 'vfb-pro' ),
	'email'     => __( 'This value should be a valid email.', 'vfb-pro' ),
	'url'       => __( 'This value should be a valid url.', 'vfb-pro' ),
	'number'    => __( 'This value should be a valid number.', 'vfb-pro' ),
	'integer'   => __( 'This value should be a valid integer.', 'vfb-pro' ),
	'digits'    => __( 'This value should be digits.', 'vfb-pro' ),
	'alphanum'  => __( 'This value should be alphanumeric.', 'vfb-pro' ),
	'notblank'  => __( 'This value should not be blank.', 'vfb-pro' ),
	'required'  => __( 'This value is required', 'vfb-pro' ),
	'pattern'   => __( 'This value seems to be invalid.', 'vfb-pro' ),
	'min'       => __( 'This value should be greater than or equal to %s.', 'vfb-pro' ),
	'max'       => __( 'This value should be lower than or equal to %s.', 'vfb-pro' ),
	'range'     => __( 'This value should be between %s and %s.', 'vfb-pro' ),
	'minlength' => __( 'This value is too short. It should have %s characters or more.', 'vfb-pro' ),
	'maxlength' => __( 'This value is too long. It should have %s characters or fewer.', 'vfb-pro' ),
	'length'    => __( 'This value length is invalid. It should be between %s and %s characters long.', 'vfb-pro' ),
	'mincheck'  => __( 'You must select at least %s choices.', 'vfb-pro' ),
	'maxcheck'  => __( 'You must select %s choices or fewer.', 'vfb-pro' ),
	'check'     => __( 'You must select between %s and %s choices.', 'vfb-pro' ),
	'equalto'   => __( 'This value should be the same.', 'vfb-pro' ),
	'minwords'  => __( 'This value is too short. It should have %s words or more.', 'vfb-pro' ),
	'maxwords'  => __( 'This value is too long. It should have %s words or fewer.', 'vfb-pro' ),
	'words'     => __( 'This value length is invalid. It should be between %s and %s words long.', 'vfb-pro' ),
	'gt'        => __( 'This value should be greater.', 'vfb-pro' ),
	'gte'       => __( 'This value should be greater or equal.', 'vfb-pro' ),
	'lt'        => __( 'This value should be less.', 'vfb-pro' ),
	'lte'       => __( 'This value should be less or equal.', 'vfb-pro' ),
);
