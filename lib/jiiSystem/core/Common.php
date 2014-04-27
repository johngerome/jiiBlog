<?php  if ( ! defined('FLUX_ROOT')) exit;

/**
* Determines if the current version of PHP is greater then the supplied value
*
* Since there are a few places where we conditionally test for PHP > 5
* we'll set a static variable.
*
* @access	public
* @param	string
* @return	bool	TRUE if the current version is $version or higher
*/
if ( ! function_exists('isPhp'))
{
	function isPhp($version = '5.0.0')
	{
		static $_is_php;
		$version = (string)$version;

		if ( ! isset($_is_php[$version]))
		{
			$_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;
		}

		return $_is_php[$version];
	}
}


/**
* Class registry
*
* This function acts as a singleton.  If the requested class does not
* exist it is instantiated and set to a static variable.  If it has
* previously been instantiated the variable is returned.
*
* @access	public
* @param	string	the class name being requested
* @param	string	the directory where the class should be found
* @param	string	the class name prefix
* @return	object
*/
if ( ! function_exists('loadClass'))
{
	function loadClass($class, $directory = 'libraries', $prefix = '')
	{
		static $_classes = array();

		// Does the class exist?  If so, we're done...
		if (isset($_classes[$class]))
		{
			return $_classes[$class];
		}

		$name = FALSE;

		// Look for the class first in the local application/libraries folder
		// then in the native system/libraries folder
		foreach (array(JIILIB_PATH) as $path)
		{
			if (file_exists($path.'/'.$directory.'/'.$class.'.php'))
			{
				$name = $prefix.$class;

				if (class_exists($name) === FALSE)
				{
					require($path.'/'.$directory.'/'.$class.'.php');
				}

				break;
			}
		}

		// Did we find the class?
		if ($name === FALSE)
		{
			// Note: We use exit() rather then show_error() in order to avoid a
			// self-referencing loop with the Excptions class
			exit('Unable to locate the specified class: '.$class.'.php');
		}

		// Keep track of what we just loaded
		// is_loaded($class);

		// $_classes[$class] = new $name();

		// return $_classes[$class];
	}
}

/**
 * Load Helper
 *
 * This function loads the specified helper file.
 *
 * @param	mixed
 * @return	void
 */
if ( ! function_exists('loadHelper'))
{
	function loadHelper($helpers = array())
	{
		foreach (_PrepFilename($helpers, '_helper') as $helper)
		{

			$ext_helper = JIILIB_PATH.'/helpers/'.$helper.'.php';

			// Is this a helper extension request?
			if (file_exists($ext_helper))
			{
				include_once($ext_helper);
				continue;
			}
			else
			{
				exit('Unable to load the requested file: helpers/'.$helper.'.php');
			}
		}
	}
}

/**
 * Load Helper
 *
 * This function loads the specified helper file.
 *
 * @param	mixed
 * @return	void
 */
if ( ! function_exists('loadModel'))
{
	function loadModel($models = array())
	{
		foreach (_PrepFilename($models, '_model') as $model)
		{

			$ext_model = JIILIB_PATH.'/models/'.$model.'.php';

			// Is this a model extension request?
			if (file_exists($ext_model))
			{
				include_once($ext_model);
				continue;
			}
			else
			{
				exit('Unable to load the requested file: models/'.$model.'.php');
			}
		}
	}
}


/**
 * Prep filename
 *
 * This function preps the name of various items to make loading them more reliable.
 *
 * @param	mixed
 * @param 	string
 * @return	array
 */
if ( ! function_exists('_PrepFilename'))
{
	function _PrepFilename($filename, $extension)
	{
		if ( ! is_array($filename))
		{
			return array(strtolower(str_replace('.php', '', str_replace($extension, '', $filename)).$extension));
		}
		else
		{
			foreach ($filename as $key => $val)
			{
				$filename[$key] = strtolower(str_replace('.php', '', str_replace($extension, '', $val)).$extension);
			}

			return $filename;
		}
	}
}

/**
* Keeps track of which libraries have been loaded.  This function is
* called by the load_class() function above
*
* @access	public
* @return	array
*/
if ( ! function_exists('is_loaded'))
{
	function &is_loaded($class = '')
	{
		static $_is_loaded = array();

		if ($class != '')
		{
			$_is_loaded[strtolower($class)] = $class;
		}

		return $_is_loaded;
	}
}

/**
 * Remove Invisible Characters
 *
 * This prevents sandwiching null characters
 * between ascii characters, like Java\0script.
 *
 * @access	public
 * @param	string
 * @return	string
 */
if ( ! function_exists('removeInvisibleCharacters'))
{
	function removeInvisibleCharacters($str, $url_encoded = TRUE)
	{
		$non_displayables = array();
		
		// every control character except newline (dec 10)
		// carriage return (dec 13), and horizontal tab (dec 09)
		
		if ($url_encoded)
		{
			$non_displayables[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/';	// url encoded 16-31
		}
		
		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127

		do
		{
			$str = preg_replace($non_displayables, '', $str, -1, $count);
		}
		while ($count);

		return $str;
	}
}

// ------------------------------------------------------------------------

/**
* Returns HTML escaped variable
*
* @access	public
* @param	mixed
* @return	mixed
*/
if ( ! function_exists('htmlEscape'))
{
	function htmlEscape($var)
	{
		if (is_array($var))
		{
			return array_map('htmlEscape', $var);
		}
		else
		{
			return htmlspecialchars($var, ENT_QUOTES, config_item('charset'));
		}
	}
}

?>