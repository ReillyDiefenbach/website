<?php 

/* before we start: */
$isTestmode = TRUE;
if($isTestmode) {
	define('VER', '?v=1.1' . rand() . '_test'); 
} else {
	define('VER', '');
}


/*define('HEADERBRAND', '/assets/media/bussim.png');
define('FOOTERBRAND', '/branding/media/wu.png');
define('SIDEBARBRAND', '/branding/media/wu-light.png');
define('STANDARD_ERROR_TEXT', 'An error occurred. Please try again later.');
define('STANDARDWIDTH', '700px');*/

/* TOC
 * 1. Variables
 * 2. converter, icons
 * 3. Reader & Writer  >> _READER.php
 * 4. Error Pages
 * 5. Utility Functions
 * ***************************/
 

 /* TODO */
 define('STANDARD_TABLEWIDTH', '700px');
 
 
/* 0 trivia */
function encoder ($dec) { $enc = $dec;$enc = str_replace('A', '0', $enc);$enc = str_replace('L', '9', $enc);$enc = str_replace('Q', '8', $enc);$enc = str_replace('j', '7', $enc);$enc = str_replace('x', '6', $enc);$enc = str_replace('z', '5', $enc);$enc = str_replace('T', '4', $enc);$enc = str_replace('v', '3', $enc);$enc = str_replace('l', '2', $enc);$enc = str_replace('q', '1', $enc);$enc = (int) substr($enc, 0, -6);$enc = $enc-6789;$enc = $enc/1234567;return $enc;}
function decoder ($enc) { $dec = $enc;$dec = $dec*1234567;$dec = $dec+6789;$dec = $dec*1000000 + rand (100000, 999999);$dec = str_replace('1', 'q', $dec);$dec = str_replace('2', 'l', $dec);$dec = str_replace('3', 'v', $dec);$dec = str_replace('4', 'T', $dec);$dec = str_replace('5', 'z', $dec);$dec = str_replace('6', 'x', $dec);$dec = str_replace('7', 'j', $dec);$dec = str_replace('8', 'Q', $dec);$dec = str_replace('9', 'L', $dec);$dec = str_replace('0', 'A', $dec);return $dec;}


define('MEMBERDOMAIN', 'https://carlvon.cloud');
define('WEBSITE', 'https://carlvon.com');
define('MEMBERBRAND', 'carlvon');
define('NOTFOUNDPAGE', 'home/notfoundPage.php');




define('XSEP', 'E');
/* base variables */
define('REQ', isset($_REQUEST["req"]) && !empty($_REQUEST["req"]) ? $req : null );
//define('ID', isset($_REQUEST["ID"]) && !empty($_REQUEST["ID"]) ? $ID : -9999 );


/* here the cookie should work */
if(isset($_REQUEST["admin_id"]) && !empty($_REQUEST["admin_id"])) {
	if(preg_match('/^\d+$/',$_REQUEST['admin_id'])) define('USER', $admin_id); 
	else define('USER', encoder($admin_id)); 
} else define('USER', null);
define('USER_NAME', isset($_REQUEST["admin_username"]) && !empty($_REQUEST["admin_username"]) ? $admin_username : 'no Name' );
define('USERTYPE', isset($_REQUEST["admin_usertype"]) && !empty($_REQUEST["admin_usertype"]) ? $admin_usertype : 100 );

/* managing the cookies */
define('USERCOOKIE', isset($_COOKIE['uCode']) && !empty($_COOKIE['uCode']) ? $_COOKIE['uCode'] : null); 


//define('SEMINAR', isset($_REQUEST["admin_seminar"]) && !empty($_REQUEST["admin_seminar"]) ? $admin_seminar : 0 );
//define('ALLSEMINARS', isset($_REQUEST["admin_allseminars"]) && !empty($_REQUEST["admin_allseminars"]) ? $admin_allseminars : 0 );
//define('COURSE', isset($_REQUEST["course"]) && !empty($_REQUEST["course"]) ? $course : 1 );
//define("COURSEPATH", '_course' . COURSE . '/');   // home of all domaine programme
//define('LANG', isset($_REQUEST["lang"]) && !empty($_REQUEST["lang"]) ? $lang : 'en' );
//define('LAND', isset($_REQUEST["land"]) && !empty($_REQUEST["land"]) ? $land : 'UK' );

//define('LICENSE', isset($_REQUEST["admin_license"]) && !empty($_REQUEST["admin_license"]) ? $_REQUEST["admin_license"] : 1 );
//define('LICENSE_NAME', isset($_REQUEST["admin_license_name"]) && !empty($_REQUEST["admin_license_name"]) ? $admin_license_name : 'no Name' );

//define('PROJECT', isset($_REQUEST["admin_project"]) && !empty($_REQUEST["admin_project"]) ? $admin_project : 0 );
//define('PERIOD', isset($_REQUEST["admin_period"]) && !empty($_REQUEST["admin_period"]) ? $admin_period : 0 );
//define('STUDENT', isset($_REQUEST["admin_student"]) && !empty($_REQUEST["admin_student"]) ? $admin_student : USER );
//define('SEMINARCAT', isset($_REQUEST["admin_seminarcat"]) && !empty($_REQUEST["admin_seminarcat"]) ? $admin_seminarcat : 1 );


define('DOMAIN', 'https://' . $_SERVER['SERVER_NAME']);  
define('ABSDIR', realpath(dirname(__FILE__)));
		

/* core */
define("HOME", '__wolfi/');  					  // home of all main sources
define('PARAMPATH', HOME . '_params/');			  // parameters, main functions
define('PROGRAMS',  HOME . '_programs/');   	  // phps
define("SUPERPATH", HOME . '_super/');       	  // nur für CEO from licensees
define("BASICPATH", HOME . 'basics/');       	  // home of basic programmen

/* assets */
define('ASSETPATH', '_assets/');
define('UTILPATH', 'frame/');
define('MEDIAPATH', ASSETPATH . 'media/');
define('FONT_THEME', 'gotham'); // gotham | montserrat
define('NATIONPATH', ASSETPATH . 'nations/'); 	//stuff like flag svgs, selects, etc.
define('FLAGPATH', ASSETPATH . 'nations/json/flags/'); 	//png flags
define('SELFPATH', ASSETPATH . 'self/'); 		//carlvon stuff.
define('DUMMYPATH', ASSETPATH . '_dummies/'); 	//placeholders
define('PRESENTATIONPATH', '_pres/');

define('OFFICEPATH', PROGRAMS . 'Office/');

define("AUTOLOADER", OFFICEPATH . 'vendor/autoload.php');  // vendor library
define('STRIPEPATH', PROGRAMS . 'Stripe/');
define("STRIPELOADER", STRIPEPATH . 'vendor/autoload.php');  // vendor library
define("STRIPE_SECRET_KEY", "sk_test_51RcpZbQTKUSR7jHiHCIaQAyxsDwN75Zrz3P170ZHyZC6fv22XlmmELqJMq9pyfY1WpvEUe0CbK3H4ARTUyNUMUfl00FNhe7z5l");
define("STRIPE_2_FACTOR", "lnua-xral-pmfq-ysdo-wofj");
define("PAYPAL_LIVE_SECRET_KEY", "EIG5Osxqr8mvS0ET70rUAwVSJEkFu8Xz_BlIay-xv3rG1cufU2PBhJfk3EEvkdx3FQ2abMAeZnkX6ZMW");
define("PAYPAL_LIVE_CLIENT_ID", "AXiDDInBWMXoFcDzMVdBtZRAgqRG9D-CyJNqoFBzzYnpgHF394bgjaU5e_EcyGytiZfGePqHvtTsCkk8");
/*go to pp -> castor@carlvon.com */
define("PAYPAL_SANDBOX_SECRET_KEY", "EIG5Osxqr8mvS0ET70rUAwVSJEkFu8Xz_BlIay-xv3rG1cufU2PBhJfk3EEvkdx3FQ2abMAeZnkX6ZMW");
define("PAYPAL_SANDBOX_CLIENT_ID", "AXiDDInBWMXoFcDzMVdBtZRAgqRG9D-CyJNqoFBzzYnpgHF394bgjaU5e_EcyGytiZfGePqHvtTsCkk8");

define("STANDARDLOGO", ASSETPATH . "self/carlvon.png");
define("SVG", ASSETPATH . "self/carlvon.svg");





define('WORKSHOPPATH', HOME . 'basics/');
define('WORKSHOP', WORKSHOPPATH . 'workshop/'); 








/*
define('MEDIA_TERMIN', UPLOADPATH . 't/');
define('MEDIA_TERMININATOR', UPLOADPATH . 'o/');
define('MEDIA_USER', UPLOADPATH . 'u/');
define('MEDIA_EVENT', UPLOADPATH . 'e/');
define('MEDIA_LIZENZ', UPLOADPATH . 'l/');
define('MEDIA_MODUL', UPLOADPATH . 'm/');*/

//define('FORMPATH', HOME . 'forms/');









//define('TEMPDIR', '_temp/');

/* external sources */
define('CDN', '_cdn/');

/* menu */
define('MENUPATH', HOME . 'menu/');


/* translations */
define('LANGPATH', HOME . 'translation/');
define('LANGPROGRAM', PROGRAMS . 'Translate.php');
//define('LANGADMIN', LANGPATH . 'A/');
//define('LANGBIG', LANGPATH . 'B/');
//define('LANGCARE', LANGPATH . 'C/');

/* makers - core vendors */
define('FILEMAKER', HOME . '_fileMaker/');
define('FRAMEPATH', HOME . '_frameMaker/');
define('LOGINPATH', FRAMEPATH . '_login/');
define('INDEXPATH', FRAMEPATH . '_regular/');
define('TICKETPATH', FRAMEPATH . '_ticketing/');
define('DOMAINTICKETPATH', DOMAIN . '/' . TICKETPATH);

define('BUILDERPATH', PROGRAMS . 'Builder/');    //Builder interface, pdf
define("PDFBUILDER", BUILDERPATH . 'pdfBuilder.php');
define("INVOICEBUILDER", BUILDERPATH . 'invoiceBuilder.php');
define("PDFPATH", '_pdf/');       	  		      // pdf library

define("TICKETPROGRAM", PROGRAMS . 'Basics/Ticketing.php');
define("TICKETPRICES", "7;39;79");

define('MAKECSS', FILEMAKER . 'makeCDN.php');
define('MINIFYJS', FILEMAKER . 'makeCDN.php');

/* specialsites */
define('FRAMES', HOME . 'sites/');

/* courses */
//define('COURSEPROGRAMS', PROGRAMS); 

define('UPLOADPATH', '_l/');



/* dummy placeholder */
define('NOAVATAR', '/' . DUMMYPATH . 'no_person.jpg');
define('NOLOGO',   '/' . DUMMYPATH . 'no_logo.jpg');	
define('NOICON',   '/' . DUMMYPATH . 'noicon.png');	
define('NOIMAGE',   '/' . DUMMYPATH . 'noimage.jpg');	
//define('STYLEVAR', '--b:32,42,62;--bt:255,255,255;--bd:22,32,52;--bl:42,52,72;--bt:255,255,255;--m:255,193,7;--mt:0,0,0;--tc:255,255,255;-t:0,0,0;--fs:13');
//define('STYLEVAR', '--b:240,240,240;--bt:0,0,0;--bd:220,220,220;--bl:255,250,250;--m:255,193,7;--mt:0,0,0;--tc:255,255,255;-t:0,0,0;--fs:14');
define('STYLEVAR', '--b:250,250,250;--bt:0,0,0;--bd:255,255,255;--bl:255,255,255;--m:0,0,0;--mt:240,240,240;--tc:255,255,255;-t:0,0,0;--fs:14');


// === Voucher Encode/Decode Helper ===
// Konfiguration
const VOUCHER_PREFIX = 'V';
/*const VOUCHER_SECRET = 48291;     // darf beliebig geändert werden (geheim halten!)
const VOUCHER_MULTIPLIER = 7;
const VOUCHER_OFFSET = 12345;
const VOUCHER_PAD = 6;            // Mindestlänge der Base36-Zahl (ohne Prefix)*/
const VOUCHER_DIGIT_ALPHABET = [
    '0' => 'A',
    '1' => 'D',
    '2' => 'F',
    '3' => 'G',
    '4' => 'K',
    '5' => 'L',
    '6' => 'P',
    '7' => 'R',
    '8' => 'T',
    '9' => 'X',
];


include_once '_FUNCTIONS.php';

/* see db - usertypes */
define ('SUPERUSER', -99);
define ('DIST', -90);
define ('CHIEF', -20); // --> LICENZNEHMER -> Executive
define ('SUPERVISOR', -10); // -> Senior
define ('COACH', -5); // Junior
define ('ASSISTANT', -2); //
define ('TRAINEE', 1);
define ('PLAYER', 2);
define ('GUEST', 10);
//define('ADMINPRIV',  USERTYPE < VERTRIEB || USERTYPE == ASSISTANT ? TRUE : FALSE);		


//define('LOGINSITES', FORMPATH . 'login/');
//define('DOCPATH', ASSETPATH . 'docs/');
//define('DRAFTPATH', ASSETPATH . 'draft/');
//define('EXPORTCSS', EXPORTPATH . 'export.css');





?>
