<?php  if ( ! defined('FLUX_ROOT')) exit('No direct script access allowed');


/**
 * JiiSystem
 *
 * An open source Addons Libraries for FluxCP
 *
 * @package		JiiLib
 * @author		John Gerome Baldonado
 * @copyright	Copyright (c) 2013
 * @since		Version 1.0
 * @filesource
 */


/**
 * JiiSystem Version
 *
 * @var string
 *
 */
	define('JIISYSTEM_VERSION', '1.0');

/**
 * JiiLib Base Path
 *
 * @var string
 *
 */
	define('JIILIB_PATH', str_replace("\\", "/", dirname(__FILE__)));

/*
 * ------------------------------------------------------
 *  Load some common functions
 * ------------------------------------------------------
 */
	require(JIILIB_PATH. '/core/Common.php');


