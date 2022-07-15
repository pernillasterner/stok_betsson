<?php
namespace Roots\Sage\Security;

use Roots\Sage\Setup;
/**
 * Security
 */

/**
 * Disable file editor
 */
define( 'DISALLOW_FILE_EDIT', true );

//Disable xmlrpc
add_filter('xmlrpc_enabled', __NAMESPACE__ . '\\__return_false');

//Hide Wordpress version
function remove_version() {
  return '';
}
add_filter('the_generator', __NAMESPACE__ . '\\remove_version');

//Wrong password or username
function wrong_login() {
  return 'Wrong username or password';
}
add_filter('login_errors', __NAMESPACE__ . '\\wrong_login');