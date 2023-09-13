<?php

function recent_topMain(): void
{
	global $context, $modSettings, $scripturl, $txt;
	global $user_info, $modSettings, $smcFunc, $board;

	loadLanguage('RecentTopics');
	loadTemplate('RecentTopics');
	if (!isset($_REQUEST['xml']))
		$context['page_title'] = $txt['recent_topics_title'];
	$context['sub_template'] = isset($_REQUEST['xml']) ? 'recent_xml' : 'recent';

	$query_parameters = array();
	if (!empty($_REQUEST['c']) && empty($board))
	{
		$_REQUEST['c'] = explode(',', $_REQUEST['c']);
		foreach ($_REQUEST['c'] as $i => $c)
			$_REQUEST['c'][$i] = (int) $c;

		if (count($_REQUEST['c']) == 1)
		{
			$request = $smcFunc['db_query']('', '
				SELECT name
				FROM {db_prefix}categories
				WHERE id_cat = {int:id_cat}
				LIMIT 1',
				[
					'id_cat' => $_REQUEST['c'][0],
				]
			);
			[$name] = $smcFunc['db_fetch_row']($request);
			$smcFunc['db_free_result']($request);

			if (empty($name))
				fatal_lang_error('no_access', false);

			$context['linktree'][] = [
				'url' => $scripturl . '#' . (int) $_REQUEST['c'],
				'name' => $name,
			];
		}

		$request = $smcFunc['db_query']('', '
			SELECT b.id_board
			FROM {db_prefix}boards AS b
			WHERE b.id_cat IN ({array_int:category_list})
				AND {query_see_board}',
			[
				'category_list' => $_REQUEST['c'],
			]
		);
		$boards = [];
		while ($row = $smcFunc['db_fetch_assoc']($request))
			$boards[] = $row['id_board'];
		$smcFunc['db_free_result']($request);

		if (empty($boards))
			fatal_lang_error('error_no_boards_selected');

		$query_this_board = 'b.id_board IN ({array_int:boards})';
		$query_parameters['boards'] = $boards;
	}
	elseif (!empty($_REQUEST['boards']))
	{
		$_REQUEST['boards'] = explode(',', $_REQUEST['boards']);
		foreach ($_REQUEST['boards'] as $i => $b)
			$_REQUEST['boards'][$i] = (int) $b;

		$request = $smcFunc['db_query']('', '
			SELECT b.id_board
			FROM {db_prefix}boards AS b
			WHERE b.id_board IN ({array_int:board_list})
				AND {query_see_board}
			LIMIT {int:limit}',
			[
				'board_list' => $_REQUEST['boards'],
				'limit' => count($_REQUEST['boards']),
			]
		);
		$boards = [];
		while ($row = $smcFunc['db_fetch_assoc']($request))
			$boards[] = $row['id_board'];
		$smcFunc['db_free_result']($request);

		if (empty($boards))
			fatal_lang_error('error_no_boards_selected');

		$query_this_board = 'b.id_board IN ({array_int:boards})';
		$query_parameters['boards'] = $boards;
	}
	elseif (!empty($board))
	{
		$query_this_board = 'b.id_board = {int:board}';
		$query_parameters['board'] = $board;
	}
	else
	{
		$query_this_board = '{query_wanna_see_board}' . (!empty($modSettings['recycle_enable']) && $modSettings['recycle_board'] > 0 ? '
			AND b.id_board != {int:recycle_board}' : '');
		$query_parameters['recycle_board'] = $modSettings['recycle_board'];
	}

	$context['linktree'][] = [
		'url' => $scripturl . '?action=' . $_REQUEST['action'],
		'name' => $txt['recent_topics_title'],
	];

	$latest_post = !empty($_REQUEST['latest']) ? 'AND m.poster_time > {int:latest}' : '';
	if (!empty($latest_post))
		$query_parameters['latest'] = (int) $_REQUEST['latest'];

	$min_msg_id = $modSettings['maxMsgID'] - (int) (($modSettings['totalMessages'] / $modSettings['totalTopics']) * $modSettings['recent_topics_number_topics']);

	$context['topics'] = [];
	$request = $smcFunc['db_query']('', '
		SELECT
			ms.poster_time as firstTime, m.poster_time as lastTime, ms.icon as first_icon, ms.subject, m.id_topic, t.num_replies, t.num_views,
			ms.id_member as id_first_poster, m.id_member as id_last_poster, m.id_msg, b.id_board, b.name AS bName,
			IFNULL(mem2.real_name, ms.poster_name) AS firstPoster,
			IFNULL(mem.real_name, m.poster_name) AS lastPoster
		FROM {db_prefix}topics AS t
			INNER JOIN {db_prefix}messages AS m ON (m.id_msg = t.id_last_msg)
			INNER JOIN {db_prefix}boards AS b ON (b.id_board = t.id_board)
			INNER JOIN {db_prefix}messages AS ms ON (ms.id_msg = t.id_first_msg)
			LEFT JOIN {db_prefix}members AS mem ON (mem.id_member = m.id_member)
			LEFT JOIN {db_prefix}members AS mem2 ON (mem2.id_member = t.id_member_started)
		WHERE t.id_last_msg >= {int:min_msg_id}
			AND ' . $query_this_board . ($modSettings['postmod_active'] ? '
			AND t.approved = {int:is_approved}
			AND m.approved = {int:is_approved}' : '') . '
			' . $latest_post . '
		ORDER BY t.id_last_msg DESC
		LIMIT {int:limit}',
		array_merge($query_parameters, [
			'min_msg_id' => $min_msg_id,
			'is_approved' => 1,
			'limit' => $modSettings['recent_topics_number_topics'],
		])
	);

	while ($row = $smcFunc['db_fetch_assoc']($request))
	{
		$context['topics'][] = array(
			'id' => $row['id_topic'],
			'subject' => $row['subject'],
			'href' => $scripturl . '?topic=' . $row['id_topic'] . '.0',
			'link' => '<a href="' . $scripturl . '?topic=' . $row['id_topic'] . '.0">' . $row['subject'] . '</a>',
			'replies' => $row['num_replies'],
			'board' => array(
				'id' => $row['id_board'],
				'name' => $row['bName'],
				'href' => $scripturl . '?board=' . $row['id_board'] . '.0',
				'link' => '<a href="' . $scripturl . '?board=' . $row['id_board'] . '.0">' . $row['bName'] . '</a>'
			),
			'firstPoster' => array(
				'id' => $row['id_first_poster'],
				'name' => $row['firstPoster'],
				'time' => (time() - $row['firstTime'] < 3600) ? round((time() - $row['firstTime'])/60, 0) . $txt['recent_topics_minutes_ago'] : timeformat($row['firstTime']),
				'href' => $scripturl . '?action=profile;u=' . $row['id_first_poster'],
				'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row['id_first_poster'] . '">' . $row['firstPoster'] . '</a>'
			),
			'lastPoster' => array(
				'id' => $row['id_last_poster'],
				'name' => $row['lastPoster'],
				'time' => (time() - $row['lastTime'] < 3600) ? round((time() - $row['lastTime'])/60, 0) . $txt['recent_topics_minutes_ago'] : timeformat($row['lastTime']),
				'href' => $scripturl . '?action=profile;u=' . $row['id_last_poster'],
				'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row['id_last_poster'] . '">' . $row['lastPoster'] . '</a>'
			),
			'lastPost' => array(
				'time' => $row['lastTime'],
				'href' => $scripturl . '?topic=' . $row['id_topic'] . '.new;topicseen#new',
				'link' => '<a href="' . $scripturl . '?topic=' . $row['id_topic'] . '.new;topicseen#new">' . $row['subject'] . '</a>'
			),
		);
	}
	$smcFunc['db_free_result']($request);
	if (!empty($context['topics']))
		$context['last_post_time'] = $context['topics'][0]['lastPost']['time'];

	// If we have an XML request for latests posts, we need to reverse the array
	if (!empty($latest_post))
		$context['topics'] = array_reverse($context['topics']);
}
