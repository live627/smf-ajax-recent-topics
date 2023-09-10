<?php

/**
 * Add the action
 * Called by:
 *        integrate_actions
 */
function recent_top_actions(array &$actionArray): void
{
	$actionArray['recenttopics'] = ['RecentTopics.php', 'recent_topMain'];
}

/**
 * Add the menu button
 * Called by:
 *        integrate_menu_buttons
 */
function recent_top_menu_buttons(array &$buttons): void
{
	global $scripturl, $modSettings, $txt;
	loadLanguage('RecentTopics');

	if (!empty($modSettings['recent_topics_menubutton']))
	{
		$counter = 0;
		foreach ($buttons as $name => $array)
		{
			$counter++;
			if ($name == 'search')
				break;
		}

		$buttons = array_merge(
			array_slice($buttons, 0, $counter, true),
			[
				'recenttopics' => [
					'title' => $txt['recent_topics_title'],
					'icon' => 'news',
					'href' => $scripturl . '?action=recenttopics',
					'show' => true,
					'sub_buttons' => [],
				],
			],
			array_slice($buttons, $counter, null, true)
		);
	}
}

/**
 * Add the settings to admin
 * Called by:
 *        integrate_general_mod_settings
 */
function recent_top_general_mod_settings(array &$config_vars): void
{
	global $txt;
	loadLanguage('RecentTopics');

	if ($config_vars != [])
	{
		$config_vars[] = '';
		$config_vars[] = $txt['recent_topics_title'];
	}
	$config_vars[] = ['int', 'recent_topics_number_topics'];
	$config_vars[] = ['int', 'recent_topics_refresh_interval', 3, 1, 'subtext' => $txt['recent_topics_refresh_interval_desc']];
	$config_vars[] = ['check', 'recent_topics_menubutton'];
}
