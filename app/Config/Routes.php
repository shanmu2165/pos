<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Login::index');
$routes->get('/login', 'Login::index');




$routes->group('',['filter'=>'isLoggedIn'],function($routes){
	$routes->add('/show/(:any)','Shows::details/$1');
	$routes->add('/shows','Shows::index');
    $routes->get('/', 'Home::index');
	$routes->post('/login/logout', 'Login::logout');
	$routes->post('/shows/search','Shows::search');
	$routes->post('/shows/search_one','Shows::search_one');
	$routes->add('/shows/select/(:any)','Shows::select_section/$1');
	$routes->add('/shows/ticket', 'Shows::ticket_booking');
	$routes->add('/cart', 'Cart::index');
	$routes->add('/remove_item/(:num)', 'Cart::remove_cart_item/$1');
    $routes->add('/shows/remove_coupon', 'Cart::remove_coupon');
	$routes->add('/cart/apply_coupon', 'Cart::apply_coupon');
	$routes->add('/cart/cancel_transaction', 'Cart::cancel_transaction');
	$routes->add('/shows/pay', 'Transactions::index');
	$routes->add('/seatings', 'Shows::seating');
	$routes->add('/section', 'Shows::ticket_booking');
	$routes->add('/sms', 'Transactions::send_sms');
	//$routes->add('/print', 'Shows::print_tickets');
	$routes->add('/transactions', 'Transactions::index');
	$routes->post('/shows/lookup', 'Transactions::lookup_transaction');
	$routes->add('/get_token','StripeController::connectionToken');
	$routes->add('/create_intent','StripeController::createPaymentIntent');
	$routes->add('/capture_pay','StripeController::capturePayment');
	//$routes->add('/')
	$routes->add('/payment_success','Transactions::pay_success');
    //$routes->add('/send_natty_sms','Shows::sendd');
	$routes->add('/lock_seats', 'Shows::lock_tickets');
	$routes->add('/qrcode_reader', 'Shows::qrcode_reader');
	$routes->add('/pdf', 'Shows::print_pdf');
	$routes->add('/check_selectedseats_booked', 'Shows::check_selectedseats_booked');
});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}