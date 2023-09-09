<?php

function template_main(): void
{
	global $context, $txt, $modSettings, $settings, $scripturl;

	echo '
		<div id="display_head" class="information">
			<h2 class="display_title">
				<span>', $txt['recent_topics_title'], '</span>
			</h2>
		</div>';

	// No topics.... just say, "sorry bub".
	if (empty($context['topics']))
		echo '
			<div class="infobox"><p class="centertext">', $txt['recent_topics_no_recent_topics'], '</p></div>';

	// Are there actually any topics to show?
	else
	{
		echo '
			<div class="tborder" ', $context['browser']['needs_size_fix'] && !$context['browser']['is_ie6'] ? 'style="width: 100%;"' : '', '>
				<table class="table_grid" style="width:100%" id="topicTable">
					<thead>
					<tr class="title_bar">
						<th scope="col" class="catbg3 hiddensmall"></th>
						<th scope="col" class="catbg3 lefttext">', $txt['subject'], ' / ', $txt['started_by'], '</th>
						<th scope="col" class="catbg3 lefttext hiddensmall">', $txt['board'], '</th>
						<th scope="col" class="catbg3 hiddensmall">', $txt['replies'], '</th>
						<th scope="col" class="catbg3 lefttext" width="150">', $txt['last_post'], '</th>
						<th scope="col" class="catbg3"></td>
					</tr>
					</thead>';

		foreach ($context['topics'] as $topic)
			echo '
					<tr class="windowbg" id="topic_', $topic['id'], '">
						<td class="hiddensmall" style="padding: 5px 0;"><div class="board_icon"><img src="', $topic['icon_url'], '" alt=""></div></td>
						<td>', $topic['link'], '<br>', $txt['started_by'], ' ', $topic['firstPoster']['link'], '</td>
						<td class="hiddensmall">', $topic['board']['link'], '</td>
						<td align="center" class="smalltext hiddensmall">', $topic['replies'], ' ', $txt['replies'], '<br>', $topic['views'], ' ', $txt['views'], '</td>
						<td align="center" class="smalltext lefttext">', $topic['lastPoster']['time'], '<br>', $txt['by'], ' ', $topic['lastPoster']['link'], '</td>
						<td align="center"><a href="', $topic['lastPost']['href'], '"><i class="main_icons last_post"></i></a></td>
					</tr>';

		echo '
				</table>
			</div>';
	}

	echo '
		<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/recenttopics.js"></script>
		<script type="text/javascript">
			getTopics(', $context['last_post_time'] ?? 0, ', ', $modSettings['recent_topics_refresh_interval'] * 1000, ', ', $modSettings['recent_topics_number_topics'], ');
		</script>';
}
