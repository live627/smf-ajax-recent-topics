function getTopics(last, delay, max)
{
	const correctAltBG = (oElement, sChild) =>
	{
		element = oElement.getElementsByTagName(sChild);
		var i = element.length;
		while (i--)
		{
			if (element[i].id.indexOf("topic_") > -1)
				if (i % 2 == 0)
					element[i].className = "windowbg2";
				else
					element[i].className = "windowbg";
		}
	};

	const got = XMLDoc =>
	{
		var updated_time = XMLDoc.getElementsByTagName("smf")[0].getElementsByTagName("lastTime")[0];
		var topics = XMLDoc.getElementsByTagName("smf")[0].getElementsByTagName("topic");
		var topic, id_topic, board, subject, replies, firstPost, lastPost, link;
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
			while (((myTable.rows.length - 1 + topics.length) - max) > 0)
				myTable.deleteRow(-1);

			// Now start the insertion
			for (var i = 0; i < topics.length; i++)
			{
				// Lets get all of our data
				topic = XMLDoc.getElementsByTagName("smf")[0].getElementsByTagName("topic")[i];
				id_topic = topic.getElementsByTagName("id")[0].childNodes[0].nodeValue;
				board = topic.getElementsByTagName("board")[0].childNodes[0].nodeValue;
				subject = topic.getElementsByTagName("subject")[0].childNodes[0].nodeValue;
				replies = topic.getElementsByTagName("replies")[0].childNodes[0].nodeValue;
				firstPost = topic.getElementsByTagName("first")[0].childNodes[0].nodeValue;
				lastPost = topic.getElementsByTagName("last")[0].childNodes[0].nodeValue;
				link = topic.getElementsByTagName("lastLink")[0].childNodes[0].nodeValue;

				// Now to create the new row...
				myRow = myTable.insertRow(1);
				myRow.id = "topic_" + id_topic;
				myRow.className = "windowbg";

				// First the Board
				myCell = myRow.insertCell(-1);
				myCell.className = "smalltext";
				setInnerHTML(myCell, board);

				// Then subject
				myCell = myRow.insertCell(-1);
				setInnerHTML(myCell, subject);

				// replies
				myCell = myRow.insertCell(-1);
				myCell.className = "smalltext";
				myCell.align = "center"
				setInnerHTML(myCell, replies);

				// first post
				myCell = myRow.insertCell(-1);
				myCell.className = "smalltext";
				myCell.align = "center"
				setInnerHTML(myCell, firstPost);

				// last post
				myCell = myRow.insertCell(-1);
				myCell.className = "smalltext";
				myCell.align = "center"
				setInnerHTML(myCell, lastPost);

				// last post
				myCell = myRow.insertCell(-1);
				myCell.align = "center"
				setInnerHTML(myCell, link);
			}

			correctAltBG(myTable, "tr");
		}

		setTimeout(heartbeat, delay);
	};

	const heartbeat = () =>
	{
		//~ getXMLDocument(smf_prepareScriptUrl(smf_scripturl) + "action=recenttopics;latest=" + last + ";xml", got);
		fetch(smf_prepareScriptUrl(smf_scripturl) + "action=recenttopics;latest=" + last + ";xml").then(res => res.text()).then(str => (new DOMParser()).parseFromString(str, "text/xml")).then(got);
	};

	setTimeout(heartbeat, delay);
}
