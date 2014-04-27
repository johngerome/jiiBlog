<?php
if (!defined('FLUX_ROOT')) exit;


if (Flux::config('UseCleanUrls') == true && Flux::config('BaseURI') == '/')
{
	$bpath = $this->basePath.FLUX_ADDON_DIR.'/jiiBlog/';
}
else if (Flux::config('UseCleanUrls') == true)
{
	$bpath = $this->basePath.'/'.FLUX_ADDON_DIR.'/jiiBlog/';
}
else
{
	$bpath = FLUX_ADDON_DIR.'/jiiBlog/';
}

?>
