<?php
declare(strict_types=1);

$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'); // true en HTTPS

session_set_cookie_params([
  'lifetime' => 0,
  'path' => '/',
  'domain' => '',
  'secure' => $secure,       // en local http -> false
  'httponly' => true,
  'samesite' => 'Lax',
]);

session_start();


require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/config.php';

use App\Core\Router;
use App\Core\Security;

Security::setSecurityHeaders();

$router = new Router();

/** Routes publiques */
$router->get('/', 'OfferController@index');
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->post('/logout', 'AuthController@logout');

$router->get('/offers', 'OfferController@index');
$router->get('/offers/{id}', 'OfferController@show');

$router->get('/companies', 'CompanyController@index');
$router->get('/companies/{id}', 'CompanyController@show');

/** Dispatch */
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
