<?php

ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);

session_start();

foreach ($_REQUEST as $key => $value) {
    $$key = $value;
}

include_once '__wolfi/_params/_SERVER.php';
include_once '__wolfi/_params/_VARIABLES.php';
include_once '__wolfi/_params/_COOKIES.php';

require_once PROGRAMS . '__Database_Main.php';

if (isset($_REQUEST['req']) && !empty($_REQUEST['req'])) {
    if ($_REQUEST['req'] === 'ticketing') {
        require_once __DIR__ . '/admin/ticketing/Ticketing.php';

        if (!function_exists('startInvoice')) {
            function startInvoice()
            {
                require_once __DIR__ . '/admin/ticketing/invoiceBuilder.php';
                return new Invoice();
            }
        }

        $action = (string)($_REQUEST['action'] ?? '');
        $ticket = new Ticket();
        $result = $ticket->dispatch($action, $_REQUEST);

        if (is_array($result)) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($result);
            die();
        }

        if (is_string($result) && $result !== '') {
            $trimmed = ltrim($result);
            if ($trimmed[0] === '{' || $trimmed[0] === '[') {
                header('Content-Type: application/json; charset=utf-8');
            }
            echo $result;
        }

        die();
    }

    require_once HOME . '_programs/__Backbone.php'; 
    die();
}

?>
