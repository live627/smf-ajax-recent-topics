<?php

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

updateSettings(array(
	'art_number_topics' => '25',
	'art_refresh_interval' => '10',
	'art_menubutton' => '1',
));

if(SMF == 'SSI')
	echo 'Database settings successfully made!';

?>