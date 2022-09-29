<?php
// If SSI.php is in the same place as this file, and SMF isn't defined, this is being run standalone.
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
	require_once(dirname(__FILE__) . '/SSI.php');
// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
	die();
	
	
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
		<icon><!', '[CDATA[<div class="board_icon"><img src="', $topic['icon_url'], '" alt=""></div>]', ']></icon>
		<subject><!', '[CDATA[', str_replace(']'.']>', ']]]'.']><!'.'[CDATA[>', $topic['link'] . '<br>' . $txt['started_by'] . ' ' . $topic['firstPoster']['link']), '  ]', ']></subject>
		<board><!', '[CDATA[', str_replace(']'.']>', ']]]'.']><!'.'[CDATA[>', $topic['board']['link']), ']', ']></board>
		<replies><!', '[CDATA[', $topic['replies'], ' ', $txt['replies'], '<br>', $topic['views'], ' ', $txt['views'], ']', ']></replies>
		<last><!', '[CDATA[', str_replace(']'.']>', ']]]'.']><!'.'[CDATA[>', $topic['lastPoster']['time'] . '<br>' . $txt['by'] . ' ' . $topic['lastPoster']['link']), ']', ']></last>
		<lastLink><!', '[CDATA[<a href="', $topic['lastPost']['href'], '" class="new_posts">' . $txt['new'] . '</a>]', ']></lastLink>
	</topic>';
	
	echo '
</smf>';

?>