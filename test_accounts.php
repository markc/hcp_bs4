<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'lib/php/config.php';
require_once 'lib/php/db.php';

try {
    $db = new Db((new Config())->db);
    Db::$tbl = 'accounts';
    
    // Test direct query
    $result = Db::read('*');
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'data' => $result
    ]);
} catch (Exception $e) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
