<?php 
function getIP(){if(!empty($_SERVER['HTTP_CLIENT_IP'])){$ip=$_SERVER['HTTP_CLIENT_IP'];}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];}else{$ip=$_SERVER['REMOTE_ADDR'];}return $ip;} 

/**
 * Ruft Ländercode und Sprachcode von ipwho.is ab
 * und speichert beides in Cookies (optional)
 *
 * @return array ['country' => 'US', 'language' => 'en']
 */
function getGeoData() {
    $ip = getIP();
    $url = "https://ipwho.is/{$ip}";

    $json = @file_get_contents($url);
    $default = ['country' => 'US', 'language' => 'en'];

    if (!$json) return $default;

    $data = json_decode($json, true);

    if (!isset($data['success']) || !$data['success']) {
        return $default;
    }

    $countryCode = strtoupper($data['country_code'] ?? 'US');
	$languageCode = getLanguageCode($countryCode);
	

    // Cookies setzen
    setcookie('cCode', $countryCode, time() + 60 * 60 * 24 * 365, "/");
    setcookie('lCode', $languageCode, time() + 60 * 60 * 24 * 365, "/");

    return [
        'country' => $countryCode,
        'language' => $languageCode
    ];
}





function getLanguageCode($countryCode = "US") {
	$countryCode = (string) $countryCode;
	$languageMap = [
	    'DE' => 'de', // Deutschland
	    'AT' => 'de', // Österreich
	    'CH' => 'de', // Schweiz (Deutsch ist am meisten gesprochen)
	    'IT' => 'it', // Italien
	    'SM' => 'it', // San Marino
	    'VA' => 'it', // Vatikanstadt
	    'FR' => 'fr', // Frankreich
	    'BE' => 'fr', // Belgien (Französisch und Niederländisch)
	    'LU' => 'fr', // Luxemburg (Französisch, Deutsch, Luxemburgisch)
	    'MC' => 'fr', // Monaco
	    'ES' => 'es', // Spanien
	    'MX' => 'es', // Mexiko
	    'AR' => 'es', // Argentinien
	    'BO' => 'es', // Bolivien
	    'CL' => 'es', // Chile
	    'CO' => 'es', // Kolumbien
	    'CR' => 'es', // Costa Rica
	    'CU' => 'es', // Kuba
	    'DO' => 'es', // Dominikanische Republik
	    'EC' => 'es', // Ecuador
	    'GT' => 'es', // Guatemala
	    'HN' => 'es', // Honduras
	    'NI' => 'es', // Nicaragua
	    'PA' => 'es', // Panama
	    'PE' => 'es', // Peru
	    'PR' => 'es', // Puerto Rico
	    'PY' => 'es', // Paraguay
	    'SV' => 'es', // El Salvador
	    'UY' => 'es', // Uruguay
	    'VE' => 'es', // Venezuela
	    'PT' => 'pt', // Portugal
	    'BR' => 'pt', // Brasilien
	    'AO' => 'pt', // Angola
	    'MZ' => 'pt', // Mosambik
	    'GW' => 'pt', // Guinea-Bissau
	    'TL' => 'pt', // Osttimor
	    'CV' => 'pt', // Kap Verde
	    'ST' => 'pt', // São Tomé und Príncipe
	    'GQ' => 'pt' // Äquatorialguinea (Portugiesisch als eine der Amtssprachen)
	];
    if (array_key_exists(strtoupper($countryCode), $languageMap)) {
        $lCode = $languageMap[$countryCode];
    } else {
    	$lCode = 'en'; // Fallback zu Englisch, wenn der Ländercode nicht gefunden wird
    }
	setcookie('lCode', $lCode, time() + 60 * 60 * 24 * 365, "/");
	return $lCode;
}

?>