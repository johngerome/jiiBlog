<?php  if ( ! defined('FLUX_ROOT')) exit('No direct script access allowed');


/**
 *
 * 
 */
if ( ! function_exists('set_checkbox'))
{
	function set_checkbox($field = 0, $default = FALSE)
	{
		if($default == TRUE)
		{
			return 'checked="checked"';
		}
		else
		{
			return ($field) ? 'checked="checked"':'';	
		}
		
	}
}