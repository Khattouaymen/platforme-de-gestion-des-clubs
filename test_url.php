<?php
header('Content-Type: text/plain; charset=utf-8');
echo "=== Test de configuration des URL de l'application ===\n";
echo "Date du test: " . date('Y-m-d H:i:s') . "\n";

echo "\nInformations sur le serveur :\n";
echo "------------------------\n";
$server_vars_to_check = [
    'SERVER_NAME', 
    'HTTP_HOST', 
    'SERVER_PORT', 
    'REQUEST_SCHEME', 
    'HTTPS', 
    'HTTP_X_FORWARDED_PROTO', 
    'HTTP_X_FORWARDED_HOST', 
    'HTTP_X_FORWARDED_SERVER', 
    'HTTP_X_FORWARDED_PORT', 
    'HTTP_X_FORWARDED_FOR',
    'SCRIPT_NAME', 
    'PHP_SELF', 
    'REQUEST_URI',
    'SERVER_SOFTWARE'
];
foreach ($server_vars_to_check as $var) {
    echo $var . ": " . (isset($_SERVER[$var]) ? $_SERVER[$var] : 'non défini') . "\n";
}

echo "\nToutes les variables SERVER commençant par HTTP_, X_FORWARDED_, CF_ (Cloudflare), ou contenant ADDR, HOST, PROTO, SCHEME :\n";
echo "-----------------------------------------------------------------------------------------------------------------\n";
foreach ($_SERVER as $key => $value) {
    if (strpos($key, 'HTTP_') === 0 || 
        strpos($key, 'X_FORWARDED_') === 0 || 
        strpos($key, 'X-Forwarded-') === 0 || // Some systems use hyphen
        strpos($key, 'CF_') === 0 || // Cloudflare specific headers
        stripos($key, 'ADDR') !== false ||
        stripos($key, 'HOST') !== false ||
        stripos($key, 'PROTO') !== false ||
        stripos($key, 'SCHEME') !== false) {
        echo $key . ": " . $value . "\n";
    }
}

// Define necessary constants if not already defined (mimicking index.php setup)
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__); // Assumes test_url.php is in the project root
}
if (!defined('APP_PATH')) {
    define('APP_PATH', ROOT_PATH . DS . 'app');
}

$url_helper_path = APP_PATH . DS . 'helpers' . DS . 'url_helper.php';

if (file_exists($url_helper_path)) {
    require_once($url_helper_path);

    if (function_exists('url')) {
        echo "\nTest de la fonction url() :\n";
        echo "------------------------\n";
        echo "url()                 : " . url('') . "\n";
        echo "url('login')          : " . url('login') . "\n";
        echo "url('admin/dashboard'): " . url('admin/dashboard') . "\n";
        echo "url('etudiant/profil'): " . url('etudiant/profil') . "\n";
        echo "url('/some/path')     : " . url('/some/path') . "\n";
    } else {
        echo "\nErreur: La fonction url() n'est pas définie après inclusion de url_helper.php.\n";
        echo "Vérifiez que " . $url_helper_path . " définit bien la fonction url().\n";
    }
} else {
    echo "\nErreur: Le fichier url_helper.php est introuvable à l'emplacement attendu: " . $url_helper_path . "\n";
    echo "ROOT_PATH est défini comme: " . ROOT_PATH . "\n";
    echo "APP_PATH est défini comme: " . APP_PATH . "\n";
    echo "Assurez-vous que le chemin est correct et que le fichier existe.\n";
}

echo "\n=== Fin du test ===\n";
?>
