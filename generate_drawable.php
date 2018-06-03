<?php
    $text = "<?xml version=\"1.0\" encoding=\"utf-8\"?>

	<resources>

		<version>1</version>

		<category title=\"All\"/>\n";

	$drawable = glob("./app/src/main/res/drawable-nodpi/*.png");

	foreach ($drawable as $an_icon) {
		$drawable = str_replace('./app/src/main/res/drawable-nodpi/', '', str_replace (".png", '', $an_icon));
		if (in_array($drawable, ['clock_bg', 'clock_hour_hand', 'clock_minute_hand', 'app_logo', 'drawer_header', 'iconback', 'iconmask'])) {
			continue;
		}

		$text .= "\t\t<item drawable=\"" . $drawable . "\" />\n";
	}

	$text .= "</resources>\n";
	file_put_contents('./app/src/main/res/xml/drawable.xml', $text);
	
?>
