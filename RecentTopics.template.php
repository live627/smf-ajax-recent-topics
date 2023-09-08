<?php

function template_main(): void
{
	global $context, $txt, $modSettings, $scripturl;

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
		<script type="text/javascript">
			var last_post = ', (!empty($context['last_post_time']) ? $context['last_post_time'] : 0), ';
			var time_interval = ', $modSettings['recent_topics_refresh_interval'] * 1000, ';
			var max_topics = ', $modSettings['recent_topics_number_topics'], ';

			var interval_id = setInterval( "getTopics()", time_interval);

			function getTopics()
			{
				if (window.XMLHttpRequest)
					getXMLDocument("', $scripturl, '?action=recenttopics;latest=" + last_post + ";xml", gotTopics);
				else
					clearInterval(interval_id);
			}

			function gotTopics(XMLDoc)
			{
				var updated_time = XMLDoc.getElementsByTagName("smf")[0].getElementsByTagName("lastTime")[0];
				var topics = XMLDoc.getElementsByTagName("smf")[0].getElementsByTagName("topic");
				var topic, id_topic, id, subject, board, replies, lastPost, link;
				var myTable = document.getElementById("topicTable"), oldRow, myRow, myCell, myData, rowCount;

				// If this exists, we have at least one updated/new topic
				if (updated_time)
				{
					// Update the last post time
					last_post = updated_time.childNodes[0].nodeValue;

					// No Messages message?  Ditch it!
					// Note, this should only happen if there are literally zero topics
					// on the board when a user visits this page.
					if (document.getElementById("no_topics") != null)
						myTable.deleteRow(-1);

					// If the topic is already in the list, remove it
					for (var i = 0; i < topics.length; i++)
					{
						topic = XMLDoc.getElementsByTagName("smf")[0].getElementsByTagName("topic")[i];
						id_topic = topic.getElementsByTagName("id")[0].childNodes[0].nodeValue;
						if ((oldRow = document.getElementById("topic_" + id_topic)) != null)
							myTable.deleteRow(oldRow.rowIndex);
					}

					// Are we going to exceed the maximum topic count allowed?
					while (((myTable.rows.length - 1 + topics.length) - max_topics) > 0)
						myTable.deleteRow(-1);

					// Now start the insertion
					for (var i = 0; i < topics.length; i++)
					{
						// Lets get all of our data
						topic = XMLDoc.getElementsByTagName("smf")[0].getElementsByTagName("topic")[i];
						id_topic = topic.getElementsByTagName("id")[0].childNodes[0].nodeValue;
						icon = topic.getElementsByTagName("icon")[0].childNodes[0].nodeValue;
						subject = topic.getElementsByTagName("subject")[0].childNodes[0].nodeValue;
						board = topic.getElementsByTagName("board")[0].childNodes[0].nodeValue;
						replies = topic.getElementsByTagName("replies")[0].childNodes[0].nodeValue;
						lastPost = topic.getElementsByTagName("last")[0].childNodes[0].nodeValue;
						link = topic.getElementsByTagName("lastLink")[0].childNodes[0].nodeValue;

						// Now to create the new row...
						myRow = myTable.insertRow(1);
						myRow.className = "windowbg";
						myRow.id = "topic_" + id_topic;

						// First the icon
						myCell = myRow.insertCell(-1);
						myCell.className = "hiddensmall";
						myCell.style.padding = "5px 0";
						setInnerHTML(myCell, icon);

						// Then the subject
						myCell = myRow.insertCell(-1);
						setInnerHTML(myCell, subject);

						// Then Board
						myCell = myRow.insertCell(-1);
						myCell.className = "hiddensmall";
						setInnerHTML(myCell, board);

						// replies
						myCell = myRow.insertCell(-1);
						myCell.className = "smalltext hiddensmall";
						myCell.align = "center"
						setInnerHTML(myCell, replies);

						// last post
						myCell = myRow.insertCell(-1);
						myCell.className = "smalltext lefttext";
						myCell.align = "center"
						setInnerHTML(myCell, lastPost);

						// last post
						myCell = myRow.insertCell(-1);
						myCell.align = "center"
						setInnerHTML(myCell, link);
					}
				}
			}
		</script>';
}
