<?php

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

updateSettings(array(
	'number_recent_topics' => '50',
	'number_recent_topics_interval' => '10',
));

if(SMF == 'SSI')
	echo 'Database settings successfully made!';

?>