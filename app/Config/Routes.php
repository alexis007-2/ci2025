<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/test', 'Home::test');
$routes->get('/achat-paypal', 'Payment::achatPayPal');
$routes->get('/paypal-traitement', 'Payment::payPalTraitement');
$routes->get('/achat-stripe', 'Payment::achatStripe');
$routes->get('/stripe-traitement', 'Payment::stripeTraitement');
$routes->get('/success', 'Payment::success');
$routes->get('/echec', 'Payment::echec');
$routes->get('/monpdf', 'PdfController::monpdf');
