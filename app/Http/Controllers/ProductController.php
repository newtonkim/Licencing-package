<?php

namespace App\Http\Controllers;

use App\Models\User;
use LicensableTrait;
use App\Models\Product;
use Illuminate\Http\Request;
use LaravelReady\LicenseServer\Services\LicenseService;

class ProductController extends Controller
{
    // use LicensableTrait; 

    public function index(Request $request)
    {
        // get licensable product
            $product = Product::find(1);
            // dump($product);
            $user = User::find(1);
            // dd( $user);

            // add license to user with expiration in days
            $license = LicenseService::addLicense($product, null, $user->id, 2);
                // dd($license);

            return $license;
    }

    public function getLicenseByUserId($licenseKey = null)
    {

        $userId = User::first()->id;
        // dd(  $userId);
        $user_license_id = LicenseService::getLicenseByUserId($userId);

          // check license status
          $license_key = $user_license_id->license_key;

          $licenseStatus = LicenseService::checkLicenseStatus($license_key);



        return $licenseStatus;
    }

    // private function checkLicenseStatus()
    // {
    //     $license_key = $this->getLicenseByUserId($licenseKey = null);

    //     // check license status
    //     $licenseStatus = LicenseService::checkLicenseStatus($license_key);


    // }
}
