<?php
namespace App\Controllers;

class StripeController extends BaseController {

    // function index() {
    
    // }

    function createPaymentIntent() {

        \Stripe\Stripe::setApiKey('sk_test_Xzsj5A6cVQL5haA83JeSlznZ');
        header('Content-Type: application/json');

        try {
        $json_str = file_get_contents('php://input');
        $json_obj = json_decode($json_str);

        // For Terminal payments, the 'payment_method_types' parameter must include
        // 'card_present' and the 'capture_method' must be set to 'manual'
        
        $intent = \Stripe\PaymentIntent::create([
            'amount' => $json_obj->amount,
            'currency' => 'usd',
            'payment_method_types' => ['card_present'],
            'capture_method' => 'manual',
        ]);
        echo json_encode(array('client_secret' => $intent->client_secret));


        } catch (Error $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        }
    }

    function capturePayment() {

        \Stripe\Stripe::setApiKey('sk_test_Xzsj5A6cVQL5haA83JeSlznZ');
        header('Content-Type: application/json');

        try {
        // retrieve JSON from POST body
        $json_str = file_get_contents('php://input');
        $json_obj = json_decode($json_str);

        $intent = \Stripe\PaymentIntent::retrieve($json_obj->id);
        $intent = $intent->capture();

        echo json_encode($intent);

        } catch (Error $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        }
    }

    function connectionToken() {
        \Stripe\Stripe::setApiKey('sk_test_Xzsj5A6cVQL5haA83JeSlznZ');
        header('Content-Type: application/json');

        try {
        // The ConnectionToken's secret lets you connect to any Stripe Terminal reader
        // and take payments with your Stripe account.
        // Be sure to authenticate the endpoint for creating connection tokens.
        $connectionToken = \Stripe\Terminal\ConnectionToken::create();
        echo json_encode(array('secret' => $connectionToken->secret));

        } catch (Error $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        }
    }

    function createLocation() {
        $location = \Stripe\Location::create([
          'display_name' => 'HQ',
          'address' => [
            'line1' => "1272 Valencia Street",
            'city' => "San Francisco",
            'state' => "CA",
            'country' => "US",
            'postal_code' => "94110",
          ]
        ]);
      
        return location;
    }
}