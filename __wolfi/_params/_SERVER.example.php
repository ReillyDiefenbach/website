<?php

/*
 * Lokale Konfigurationsvorlage fuer carlvon.com.
 *
 * 1. Diese Datei nach _SERVER.php kopieren.
 * 2. CHANGE_ME-Werte nur in _SERVER.php ersetzen.
 * 3. _SERVER.php niemals committen oder weitergeben.
 */

define('BASEKLASS', 'carlvon');
define('COMPANYNAME', 'carlvon - analytica');
define('MYDOMAIN', 'carlvon.com');
define('MYAKA', 'Carl Von');
define('QUICKNAME', 'carlvon');
define('FAVICONPATH', '_assets/favicon');

if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (int)($_SERVER['SERVER_PORT'] ?? 0) === 443) {
    define('PROT', 'https://');
} else {
    define('PROT', 'http://');
}

define('meta_title', 'carlvon - Analysis tools based on Clausewitz');
define('meta_description', 'Analysis tools based on Clausewitz');
define('meta_favicons', '');

define('HOST_JS', '__wolfi/_fileMaker/js/');
define('HOST_CSS', '_cdn/');

/* Private Werte: lokal oder ueber Umgebungsvariablen setzen. */
define('DBHOST', getenv('CARLVON_DB_HOST') ?: 'localhost:3306');
define('DBUSER', getenv('CARLVON_DB_USER') ?: 'CHANGE_ME');
define('DBPASS', getenv('CARLVON_DB_PASS') ?: 'CHANGE_ME');
define('DBASE', getenv('CARLVON_DB_NAME') ?: 'CHANGE_ME');
define('PBASE', 'seminar');

define('LOGOPATH', 'assets/media/');
define('MAINLOGO', LOGOPATH . 'bussim.png');
define('MAINLOGO_2LINES', LOGOPATH . 'bussim-v2.png');
define('MAINLOGO_DARK', LOGOPATH . 'bussim.png');
define('MAINLOGO_LIGHT', LOGOPATH . 'bussim-v1.png');
define('BRANDLOGO_DARK', LOGOPATH . 'doktor-furti.png');
define('BRANDLOGO_LIGHT', LOGOPATH . 'doktor-furti-white.png');
define('MAINICON_DARK', LOGOPATH . 'df-icon.png');
define('MAINICON_LIGHT', LOGOPATH . 'df-icon-white.png');
define('FAVICON', LOGOPATH . 'favicons/');

define('LINKFACEBOOK', 'https://www.facebook.com/carlvon/');
define('LINKTWITTER', 'https://www.x.com/carlvon/');
define('LINKINSTAGRAM', 'https://www.instagram.com/carlvon/');
define('LINKLINKEDIN', 'https://www.linkedin.com/carlvon/');
define('LINKYOUTUBE', 'https://www.youtube.com/channel/UCyYhmmQabEEk1GjHjMikERw');
define('LINKPINTEREST', null);

define('HTTPSADDRESS', 'https://' . MYDOMAIN);
define('LINKWEB', HTTPSADDRESS);
define('LINKFAQ', HTTPSADDRESS . '?page=about_faq');
define('LINKTERM', HTTPSADDRESS . '?legal=terms');
define('LINKPRIVACY', HTTPSADDRESS . '?legal=privacy');
define('LINKDISCLAIMER', HTTPSADDRESS . '?legal=disclaimer');
define('LINKNEWSLETTER', HTTPSADDRESS . '?about=news');
define('LINKLOGIN', HTTPSADDRESS . '?about=login');
define('LINKUNSUBSCRIBE', HTTPSADDRESS . '?unsubscribe=');

define('MAILDOMAIN', MYDOMAIN);
define('MAILFIRM', MYAKA);
define('MAILICONBASE', 'https://' . MYDOMAIN . '/_assets/email/media/');
define('MAILICONEXT', '.svg');
define('MAILANREDE', 'Hallo ');
define('MAILSIGNATURE', '<b>CARL VON</b>[ character studies ]');
define('MAILTEMPLATE', 'https://' . MYDOMAIN . '__wolfi/programs/EMAIL/mail-template.php');
define('MAILHEADLOGO', 'https://' . MYDOMAIN . '/_assets/email/header.png');
define('MAILFOOTERLOGO', 'https://' . MYDOMAIN . '/_assets/email/footer.png');
define('MAILSTYLESHEET', 'https://' . MYDOMAIN . '/_assets/email/style.css');
define('MAILICON', 'https://' . MYDOMAIN . '/_assets/email/favicon.png');

define('MAILHOST', getenv('CARLVON_MAIL_HOST') ?: MAILDOMAIN);
define('MAILUSER', getenv('CARLVON_MAIL_USER') ?: 'CHANGE_ME');
define('MAILPASS', getenv('CARLVON_MAIL_PASS') ?: 'CHANGE_ME');
define('MAILPORT', (int)(getenv('CARLVON_MAIL_PORT') ?: 25));
define('MAILFROM', getenv('CARLVON_MAIL_FROM') ?: 'CHANGE_ME');
define('MAILFROMNAME', 'carlvon Service');
define('MAILREPLY', getenv('CARLVON_MAIL_REPLY') ?: 'no-reply@' . MAILDOMAIN);

define('MAILCOMPANYLINE', COMPANYNAME . ', TM  ' . date('Y'));
define('MAILUNSUBSCRIBE', HTTPSADDRESS . '/login.php?req=confirm&type=nonewsletter&id=');
define('MAILWEB', LINKWEB);
define('MAILTERMS', LINKTERM);
define('MAILPRIVACY', LINKPRIVACY);
define('MAILNEWSLETTER', LINKNEWSLETTER);
define('MAILFAQ', LINKFAQ);
define('MAILLOGIN', LINKLOGIN);

define('ERRORSITE', '__wolfi/sites/error.php');
define('MAINREDIRECT', 'https://google.com');

