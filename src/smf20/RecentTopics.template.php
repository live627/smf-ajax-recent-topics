<?php

function template_recent()
{
	global $context, $txt, $modSettings, $scripturl, $settings;

	$alt = false;
	echo '
		<div class="cat_bar">
			<h3 class="catbg">', $txt['recent_topics_title'], '</h3>
		</div>
			<table border="0" width="100%" cellspacing="0" cellpadding="4" class="table_grid" id="topicTable">
				<tr class="titlebg">';

	// Are there actually any topics to show?
	echo '
					<th width="15%" class="first_th lefttext">', $txt['board'], '</th>
					<th class="lefttext">', $txt['topic'], '</th>
					<th width="50" class="catbg3">', $txt['replies'], '</th>
					<th width="150" class="catbg3">', $txt['started_by'], '</th>
					<th width="150" class="catbg3">', $txt['last_post'], '</th>
					<th width="16" class="last_th"></th>
				</tr>';

	// No topics.... just say, "sorry bub".
	if (empty($context['topics']))
		echo '
				<tr id="no_topics">
					<td class="windowbg2" width="100%" colspan="6">', $txt['msg_alert_none'], '</td>
				</tr>';

	else
		foreach ($context['topics'] as $topic)
		{
			echo '
				<tr class="windowbg', ($alt ? '2' : ''), '" id="topic_', $topic['id'], '">
					<td class="smalltext">', $topic['board']['link'], '</td>
					<td>', $topic['link'], '</td>
					<td class="smalltext">', $topic['replies'], '</td>
					<td class="smalltext">', $topic['firstPoster']['link'], '<br />', $topic['firstPoster']['time'], '</td>
					<td class="smalltext">', $topic['lastPoster']['link'], '<br />', $topic['lastPoster']['time'], '</td>
					<td><a href="', $topic['lastPost']['href'], '"><img src="', $settings['images_url'], '/icons/last_post.gif" alt="', $txt['last_post'], '" title="', $txt['last_post'], '" /></a></td>
				</tr>';
			$alt = !$alt;
		}

	echo '
			</table>
		<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/recenttopics.js"></script>
		<script type="text/javascript">
			getTopics(', $context['last_post_time'] ?? 0, ', ', $modSettings['recent_topics_refresh_interval'] * 1000, ', ', $modSettings['recent_topics_number_topics'], ', "', $txt['recent_topics_minutes_ago'], '");
		</script>
			<style>
				@media screen and (max-width: 480px)
				{
					#topicTable th:nth-child(1),
					#topicTable th:nth-child(3),
					#topicTable th:nth-child(4),
					#topicTable td:nth-child(1),
					#topicTable td:nth-child(3),
					#topicTable td:nth-child(4)
					{
						display: none;
					}
				}
			</style>';
}

function template_recent_xml()
{
	global $context, $modSettings, $settings, $txt;

	echo '<?xml version="1.0" encoding="', $context['character_set'], '"?>
<smf>';

	if (!empty($context['topics']))
		echo '
	<lastTime><!', '[CDATA[', $context['last_post_time'], ']', ']></lastTime>';

	foreach ($context['topics'] as $topic)
		echo '
	<topic>
		<id><!', '[CDATA[', $topic['id'], ']', ']></id>
		<board><!', '[CDATA[', str_replace(']'.']>', ']]]'.']><!'.'[CDATA[>', $topic['board']['link']), ']', ']></board>
		<subject><!', '[CDATA[', str_replace(']'.']>', ']]]'.']><!'.'[CDATA[>', $topic['link']), ']', ']></subject>
		<replies><!', '[CDATA[', $topic['replies'], ']', ']></replies>
		<first><!', '[CDATA[', str_replace(']'.']>', ']]]'.']><!'.'[CDATA[>', $topic['firstPoster']['link'] . '<br />' . $topic['firstPoster']['time']), ']', ']></first>
		<last><!', '[CDATA[', str_replace(']'.']>', ']]]'.']><!'.'[CDATA[>', $topic['lastPoster']['link'] . '<br />' . $topic['lastPoster']['time']), ']', ']></last>
		<lastLink><!', '[CDATA[<a href="', $topic['lastPost']['href'], '"><img src="', $settings['images_url'], '/icons/last_post.gif" alt="', $txt['last_post'], '" title="', $txt['last_post'], '" /></a>]', ']></lastLink>
	</topic>';

	echo '
</smf>';
}