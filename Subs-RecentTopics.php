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
	$actionArray['recenttopics'] = array('RecentTopics.php', 'recent_topMain');
}

/**
 * Add the menu button
 * Called by:
 * 		integrate_menu_buttons
 */
 function recent_top__menu_button(&$buttons)
{
	global $scripturl, $modSettings, $txt;
	loadLanguage('RecentTopics');

	if (!empty($modSettings['recent_topics_menubutton'])){
	
		$counter = 0;
		foreach ($buttons as $name => $array)
		{
			$counter++;
			if ($name == 'search')
				break;
		}

		$buttons = array_merge(
		array_slice($buttons, 0, $counter, TRUE),
			array('recenttopics' => array(
				'title' => $txt['recent_topics_title'],
				'icon' => 'news',
				'href' => $scripturl . '?action=recenttopics',
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
	loadLanguage('RecentTopics');

	$config_vars[] = '';
	$config_vars[] = $txt['recent_topics_title'];
	$config_vars[] = array('int', 'recent_topics_number_topics');
	$config_vars[] = array('int', 'recent_topics_refresh_interval', 3, 1, 'subtext' => $txt['recent_topics_refresh_interval_desc']);
	$config_vars[] = array('check', 'recent_topics_menubutton');
}
