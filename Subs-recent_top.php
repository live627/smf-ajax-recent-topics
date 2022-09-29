<?php
if (!defined('SMF'))
	die('Hacking attempt...');

/**
 * Add the action
 * Called by:
 * 		integrate_actions
 */
 function recent_top_action(&$actionArray)
{
	global $txt;
	loadLanguage('Recent_top');
	
	$actionArray['recent_top'] = array('Recent_top.php', 'recent_topMain');
}

/**
 * Add the menu button
 * Called by:
 * 		integrate_menu_buttons
 */
 function recent_top__menu_button(&$buttons)
{
	global $boardurl, $modSettings, $txt;
	loadLanguage('Recent_top');

	if ($modSettings['art_menubutton'] == '1') {
	
		$counter = 0;
		foreach ($buttons as $name => $array)
		{
			$counter++;
			if ($name == 'search')
				break;
		}

		$buttons = array_merge(
		array_slice($buttons, 0, $counter, TRUE),
			array('recent_top' => array(
				'title' => $txt['art_menu_title'],
				'icon' => 'news',
				'href' => $boardurl.'/index.php?action=recent_top',
				'show' => true,
				'sub_buttons' => array(),
			)
		),
		array_slice($buttons, $counter, NULL, TRUE)
		);
	}
}

/**
 * Add the settings to admin
 * Called by:
 * 		integrate_general_mod_settings
 */
function recent_top_settings(&$config_vars)
{
	global $txt;
	loadLanguage('Recent_top');

	$config_vars[] = '';
	$config_vars[] = $txt['art_title'];
	$config_vars[] = array('int', 'art_number_topics');
	$config_vars[] = array('int', 'art_refresh_interval', 3, 1, 'subtext' => $txt['art_refresh_interval_desc']);
	$config_vars[] = array('check', 'art_menubutton');
}

/**
 * Mention who made this thing
 * Called by:
 * 		integrate_credits
 */
function recent_top_credits()
{
	global $context;
	$context['copyrights']['mods'][] = 'AJAX Recent Topics fork for SMF2.1 by @rjen &copy; 2022';
}
?>