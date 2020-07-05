<?php
function getAllIcons() {
	$drawable = glob("./app/src/main/res/drawable-nodpi/*.webp");

	$array = [];
	foreach ($drawable as $an_icon) {
		$an_icon = str_replace('./app/src/main/res/drawable-nodpi/', '', str_replace (".webp", '', $an_icon));
		if (in_array($an_icon, ['clock_bg', 'clock_hour_hand', 'clock_minute_hand', 'app_logo', 'drawer_header', 'iconback', 'iconmask', 'ic_star', 'ic_launcher'])) {
			continue;
		}

		$array [] = $an_icon;
	}

	return $array;
}

function getCategory() {
    $jsoncategoy = file_get_contents('./app/categories.json');
    $icons = explode("\n", file_get_contents('./app/src/main/res/xml/appfilter.xml'));
    
    $component_array = [];

    if (!empty($jsoncategoy)) {
        $component_array = json_decode($jsoncategoy, true);
    }

    foreach ($icons as $a_line) {
        if (preg_match('@<item component="ComponentInfo\{(.*)\}"\s*(?:drawable|prefix)="([a-z0-9_]+)"\s*/>@i', $a_line, $match)) {
            $package = explode('/', $match[1])[0];

            if (empty($component_array[$match[2]]) || $component_array[$match[2]] == 404) {
                $html = file_get_contents("https://play.google.com/store/apps/details?id=$package&hl=fr");

                if (empty($html) || $html === 404) {
                    $component_array[$match[2]] = 'no_playstore';

                    continue;
                }

                preg_match ('@"applicationCategory":"([a-z0-9_\-]+)"@i', $html, $matches);

                if (empty($matches)) {
                    $component_array[$match[2]] = 'no_category';

                    continue;
                }

                $component_array[$match[2]] = str_replace(' ', '_', strtolower($matches[1]));
            }
        }
    }

    file_put_contents('./app/categories.json', json_encode($component_array, JSON_PRETTY_PRINT));

    return $component_array;
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
$category_by_icon = getCategory();

$icon_by_category = [];

foreach ($category_by_icon as $an_icon => $a_category) {
    if (in_array($a_category, ['no_playstore', 'no_category'])) {
        continue;
    }
    
    $icon_by_category[$a_category][] = $an_icon;
}

$text = <<<EOF
<?xml version="1.0" encoding="UTF-8"?>

<resources xmlns:tools="http://schemas.android.com/tools" tools:ignore="MissingTranslation">

EOF;

$text .= makeCategory('icons_preview', $new_all);
$text .= makeCategory('all_icons', $new_all);
$text .= makeCategory('recently_added', $new_icons);
ksort($icon_by_category);
foreach ($icon_by_category as $a_category_titles => $icons) {
    asort($icons);
	$text .= makeCategory($a_category_titles, $icons);
}

$text .= makeCategory('icon_filters', array_merge(['all_icons', 'recently_added'], array_keys($icon_by_category)));

$text .= "</resources>";

file_put_contents('./app/src/main/res/values/icon_pack.xml', $text);
file_put_contents('./app/categories/all', implode("\n", $new_all));

?>
