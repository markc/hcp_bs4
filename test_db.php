<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

echo "Current working directory: " . getcwd() . "\n";
echo "Database path exists: " . (file_exists('sysadm/sysadm.db') ? 'yes' : 'no') . "\n";
echo "Database path is readable: " . (is_readable('sysadm/sysadm.db') ? 'yes' : 'no') . "\n";
echo "Database path is writable: " . (is_writable('sysadm/sysadm.db') ? 'yes' : 'no') . "\n";

try {
    echo "Attempting to connect to database...\n";
    $db = new PDO('sqlite:../../sysadm/sysadm.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Successfully connected to SQLite database\n";
    
    echo "Checking available PDO drivers:\n";
    print_r(PDO::getAvailableDrivers());
    
    echo "\nAttempting to read tables...\n";
    $result = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
    echo "Tables in database:\n";
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
} catch(PDOException $e) {
    echo "PDO Error: " . $e->getMessage() . "\n";
    echo "Error code: " . $e->getCode() . "\n";
    echo "Error trace:\n" . $e->getTraceAsString() . "\n";
} catch(Exception $e) {
    echo "General Error: " . $e->getMessage() . "\n";
    echo "Error trace:\n" . $e->getTraceAsString() . "\n";
}
?>
