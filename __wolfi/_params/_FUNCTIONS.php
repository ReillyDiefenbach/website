<?php

/* translation */

function T ($area = 'A', $lang = 'en') {
	require_once LANGPROGRAM;
	return new Language($area, $lang);
}

function LL($area, $baustein, $lang = LANG) {
	$language = T($area, $lang);
    return $language->getTranslation($baustein);
}

function L($area, $baustein, $lang = LANG, $placeholders = [], $print = true) {
	$defaultItems = array(
	    'memberdomain' => MEMBERDOMAIN,
	    'brand' => MEMBERBRAND
	);
	$placeholders = array_merge($defaultItems, $placeholders);
    $language = T($area, $lang);
    $text = $language->getTranslation($baustein);

    if (isset($text)) {
        foreach ((array)$placeholders as $index => $placeholder) {
            if(isset($placeholder)) 
            	$text = str_replace("{" . $index . "}", htmlspecialchars($placeholder), $text);
        }
		if($print == true) print $text; 
		else return $text;
    } else {
        print 'no info';
    }
}


function allAreas($areas = 'A', $lang = 'en') {
	$langs = array ();
	$allareas = explode(',',$areas);
	foreach($allareas as $key) {
		$langs[$key] = T($key, $lang)->getTranslations();
	}
	return json_encode($langs, true);
}


/* end translation */
