<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Lib\SplynxApi;
use Illuminate\Http\Request; 

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function serviceCount(Request $request)
    {
        $api_url = env('SPLYNX_API_URL'); // please set your Splynx URL
        $admin_login = env('SPLYNX_API_USERNAME'); // Splynx administrator login
        $admin_password = env('SPLYNX_API_PASSWORD'); // Splynx administrator password
        $api = new SplynxApi($api_url);
        $api->setVersion(SplynxApi::API_VERSION_2);
        $isAuthorized = $api->login([
            'auth_type' => SplynxApi::AUTH_TYPE_ADMIN,
            'login' => $admin_login,
            'password' => $admin_password,
        ]);
        if (!$isAuthorized) {
            exit("Authorization failed!\n");
        }
        $client_id = "28004";
        $ApiUrl = "admin/customers/customer/$client_id/internet-services";

        $result = $api->api_call_get($ApiUrl);

        if ($result) {
            
            // print_r($result);
            return "Service Count for SplynxID - $client_id: " . $result;

        } else {
            print "Fail! Error code: $api->response_code\n";
            print_r($api->response);
        }
    }

    public function searchSplynx(Request $request)
    {
        $api_url = env('SPLYNX_API_URL'); // please set your Splynx URL
        $admin_login = env('SPLYNX_API_USERNAME'); // Splynx administrator login
        $admin_password = env('SPLYNX_API_PASSWORD'); // Splynx administrator password
        $api = new SplynxApi($api_url);
        $api->setVersion(SplynxApi::API_VERSION_2);
        $isAuthorized = $api->login([
            'auth_type' => SplynxApi::AUTH_TYPE_ADMIN,
            'login' => $admin_login,
            'password' => $admin_password,
        ]);
        if (!$isAuthorized) {
            exit("Authorization failed!\n");
        }
        
        $searchParam = "jozekj@clearaccess.co.za";
        $sname = [
            'main_attributes' => [
                'email' => ['LIKE', $searchParam],
                // 'status' => 'active',
            ],
        ];
        
        $apiUrl= "admin/customers/customer/?".http_build_query($sname);
        $result = $api->api_call_get($apiUrl);
        $json = ($api->response);


        if ($result) {
            if(empty($json)){
                    echo $emptyResult;
                }else{
                    foreach ($json as $jkey => $value) {
                        $name = $value['name'];
                        $splynx_customer_id = $value['id'];
                        $email = $value['email'];
                        $status = $value['status'];
                        return $name ." . ". $splynx_customer_id ." . ". $email ." . ". $status;
                    }
            }
        } else {
            print "Fail! Error code: $api->response_code\n";
            print_r($api->response);
        }
        return $result;
    }

}
