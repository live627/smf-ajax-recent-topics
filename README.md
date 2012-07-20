# AJAX Recent Topics

This mod adds a Recent Topics list that is updated via AJAX to your board.  It can be found at ?action=recenttopics

Developed by live627 and created by SlammedDime.

### Compatibility
SMF 1.1.x and 2.0.x

## Translations
These get added to ./Themes/default/languages/Modifications.{language}.php

### Turkish
```bash
$txt['recent_topics'] = 'Son Konular';
$txt['minutes_ago'] = ' Dakika önce';
$txt['number_recent_topics_interval'] = 'Güncellenmiş konular için kontrol edilicek (saniye cinsinden) bekleme süresi';
$txt['number_recent_topics_interval_desc'] = '5-10 sn kabul edilebilir,çok düşük değil.';
$txt['number_recent_topics'] = 'Son konular sayfasın da gösterilicek konu sayısı';
```

### German
```bash
// AJAX Recent Topics
$txt['recent_topics'] = 'Neueste Themen';
$txt['minutes_ago'] = ' Minuten her';
$txt['number_recent_topics_interval'] = 'Zeit (in Sekunden) zwischen Update-Check nach neuen Beiträgen';
$txt['number_recent_topics_interval_desc'] = 'Nicht zu niedrig wählen, 5-10 Sekunden sind vernünftig';
$txt['number_recent_topics'] = 'Anzahl der anzuzeigenden Themen auf der Neueste-Themen-Seite';
```

## Changelog
```
Version 1.1 - June 09, 2010, 09:56:07 PM
* Update for 1.1.11 and 2.0 RC3

Version 1.0.1 - September 18, 2008, 12:59:27 PM
! Fixed SQL query
```

## Legal

This work is released under the [Creative Commons Attribution-ShareAlike 3.0 Unported License](http://creativecommons.org/licenses/by-sa/3.0/).

## TODO

* Add a description so when the user is viewing the recent topics, they show up as such in the Who's online list
* Exclude AJAX calls for new info from hit count
* Convert template to semantic version