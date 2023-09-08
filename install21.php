<?php

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

updateSettings(array(
	'recent_topics_number_topics' => '25',
	'recent_topics_refresh_interval' => '10',
	'recent_topics_menubutton' => '1',
));

if(SMF == 'SSI')
	echo 'Database settings successfully made!';

?>