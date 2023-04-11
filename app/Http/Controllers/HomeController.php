<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function payment()
    {
        $data['amount'] = 1;
        $data['currency'] = 'USD';
        $data['customer'] ['first_name'] = 'ziyad';
        $data['customer'] ['email'] = 'ziyad@gmail.com';
        $data['customer'] ['phone']['country_code'] = '213';
        $data['customer'] ['phone']['number'] = '0654879865';
        $data['source'] ['id'] = 'src_card';
        $data['redirect'] ['url'] = 'http://127.0.0.1:8000/callback';

        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer sk_test_XKokBfNWv6FIYuTMg5sLPjhJ",
        ];

        
        $ch = curl_init(); //initialize curl
        $url = 'https://api.tap.company/v2/charges';//where you want to post data
        curl_setopt( $ch, CURLOPT_URL, $url ); //set url to send to
        curl_setopt( $ch, CURLOPT_POST, true ); //set method to post
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($data)); //set data to be posted
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers); //set headers
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); //return output
        $output = curl_exec( $ch ); //execute
        
        curl_close( $ch ); //close curl
        $response = json_decode($output);
        
        return redirect()->to($response->transaction->url);
    }

    public function callback(Request $request)
    {
        $input = $request->all();

        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer sk_test_XKokBfNWv6FIYuTMg5sLPjhJ",
        ];

        $ch = curl_init(); //initialize curl
        $url = 'https://api.tap.company/v2/charges/'.$input['tap_id'];//where you want to post data
        curl_setopt( $ch, CURLOPT_URL, $url ); //set url to send to
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers); //set headers
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); //return output
        $output = curl_exec( $ch ); //execute

        curl_close( $ch ); //close curl
        dd(json_decode($output));
    }
}
