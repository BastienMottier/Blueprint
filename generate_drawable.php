<?php
    $text = "<?xml version=\"1.0\" encoding=\"utf-8\"?>

	<resources>

		<version>1</version>

		<category title=\"All\"/>\n";

	$drawable = glob("./app/src/main/res/drawable-nodpi/*.webp");
	$count = 0;
	foreach ($drawable as $an_icon) {
		$drawable = str_replace('./app/src/main/res/drawable-nodpi/', '', str_replace (".webp", '', $an_icon));
		if (in_array($drawable, ['clock_bg', 'clock_hour_hand', 'clock_minute_hand', 'app_logo', 'drawer_header', 'iconback', 'iconmask', 'ic_star'])) {
			continue;
		}

		$text .= "\t\t<item drawable=\"" . $drawable . "\" />\n";
		$count++;
	}

	$text .= "</resources>\n";
	file_put_contents('./app/src/main/res/xml/drawable.xml', $text);
	file_put_contents('./app/src/main/assets/drawable.xml', $text);
	echo "NB Icons : $count\n";
?>
