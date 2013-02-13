<?php

// =================================================
// xdebug-setup.php
//
// 20/04/2011		CDN						Created
//
//
// xdebug settings
//
// These are PHP Settings needed to be set on before
// xdebug will work
// On for Development Off for production
ini_set('error_log', 'php_errors.log');
ini_set('log_errors', 'On');
ini_set('track_errors', 'On');
ini_set('display_errors', 'On');
ini_set('html_errors', 'On');

// show local vars
ini_set('xdebug.show_local_vars', '1');
// show global vars
ini_set('xdebug.dump_globals', 'On');
// show function arguments and contents
ini_set('xdebug.collect_params', '4');
// Do NOT ignore undefined variables
ini_set('xdebug.dump_undefined', 'On');
// which super globals to dump
//ini_set('xdebug.dump.SERVER', '*');
//ini_set('xdebug.dump.GET', '*');
//ini_set('xdebug.dump.POST', '*');

// xdebug function that dumps superglobals
//xdebug_dump_superglobals();
// Show stacktrace whenever an exception is thrown
//ini_set('xdebug.show_exception_trace', 'On');
ini_set('xdebug.show_exception_trace', 'Off');

// end xdebug settings
// =================================================
/***************
 *
 * Example of turning tracing on and off for a block of code
 *
  xdebug_start_trace('c:/data/fac.xt');

  print fac(7);

  function fac($x)
  {
    if (0 == $x) return 1;
    return $x * fac($x - 1);
  }

  xdebug_stop_trace();
 ****************************************
 *
 * How to use xdebug code profiling
	 Enable and disable profiling by passing the special GET or POST parameter XDEBUG_PROFILE
   to a PHP script. This will turn on profiling just for the one PHP script that receives the
   parameter. You need not set a value for XDEBUG_PROFILE, it is sufficient to append the
   parameter to the URL: test.php?XDEBUG_PROFILE.
 *
 * Output profile is /tmp/cachegrind.out
 */

?>