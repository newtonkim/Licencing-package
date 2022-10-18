<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use LaravelReady\LicenseServer\Models\IpAddress;
use LaravelReady\UltimateSupport\Support\IpSupport;
use LaravelReady\LicenseServer\Services\LicenseService;

class AuthController extends Controller
{
    
    public function __construct()
    {
        // All the functions should first consume the listed middlewares before proceeding, apart from login function
        $this->middleware("auth:api", ["except" => ["login"]]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string|uuid',
        ]);

        $domain = $request->input('ls_domain');

        $licenseKey = $request->input('license_key');

        $userId = User::first()->id;
        // dd(  $userId);
        // $license = LicenseService::getLicenseByUserId($userId);

        // dd($license);
        $license = LicenseService::getLicenseByKey( $licenseKey);


        if ($license) {
            $license->tokens()->where('name', $domain)->delete();
            
            $ipAddress = IpAddress::where('license_id', $license->id)->first();
            $serverIpAddress = IpSupport::getIP();

            if (!$ipAddress) {
                $ipAddress = IpAddress::create([
                    'license_id' => $license->id,
                    'ip_address' => $serverIpAddress,
                ]);
            }

            if ($ipAddress && $ipAddress->ip_address == $serverIpAddress) {
                $licenseAccessToken = $license->createToken($domain, ['license-access']);
                // dd( $licenseAccessToken );
                return [
                    'status' => true,
                    'message' => 'Successfully logged in.',
                    'access_token' => explode('|', $licenseAccessToken->plainTextToken)[1],
                ];
            }

            return response([
                'status' => false,
                'message' => 'This IP address is not allowed. Please contact the license provider.',
            ], 401);
        }

        return response([
            'status' => false,
            'message' => 'Invalid license key or license source.',
        ], 401);
    }
}
