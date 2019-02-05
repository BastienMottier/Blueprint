<?php
function getAllIcons() {
	$drawable = glob("./app/src/main/res/drawable-nodpi/*.webp");

	$array = [];
	foreach ($drawable as $an_icon) {
		$an_icon = str_replace('./app/src/main/res/drawable-nodpi/', '', str_replace (".webp", '', $an_icon));
		if (in_array($an_icon, ['clock_bg', 'clock_hour_hand', 'clock_minute_hand', 'app_logo', 'drawer_header', 'iconback', 'iconmask', 'ic_star'])) {
			continue;
		}

		$array [] = $an_icon;
	}

	return $array;
}

function getAllFromFile() {
	$all = file_get_contents('./app/categories/all');

	return explode("\n", $all);
}

function makeCategory($title, $icons) {
	$category ="\t<string-array name=\"$title\">\n";

	foreach ($icons as $an_icon) {
		if(empty($an_icon))
			continue;

		$category .= "\t\t<item>$an_icon</item>\n";
	}

	$category .= "\t</string-array>\n\n";

	return $category;
}


$old_all = getAllFromFile();
$new_all = getAllIcons();
$new_icons = array_diff($new_all, $old_all);

$text = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>

<resources xmlns:tools="http://schemas.android.com/tools" tools:ignore="MissingTranslation">

EOF;

$text .= makeCategory('icons_preview', $new_all);
$text .= makeCategory('all', $new_all);
$text .= makeCategory('recently_added', $new_icons);

$files = glob('./app/categories/*');
$categories_array = [
	'all',
	'recently_added'
];

foreach ($files as $a_file) {
	$category_name = str_replace('./app/categories/', '', $a_file);

	if ($category_name == 'all')
		continue;
	
	$categories_array[] = $category_name;
	$icons = explode("\n", file_get_contents($a_file));

	$text .= makeCategory($category_name, $icons);
}

$text .= makeCategory('icon_filters', $categories_array);

$text .= "</resources>";

file_put_contents('./app/src/main/res/values/icon_pack.xml', $text);
file_put_contents('./app/categories/all', implode("\n", $new_all));

?>
