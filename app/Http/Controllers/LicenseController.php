<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Event;
    
    use LaravelReady\LicenseServer\Models\License;
    use LaravelReady\LicenseServer\Events\LicenseChecked;

class LicenseController extends Controller
{


    /**
     * Custom license validation
     *
     * @param Request $request
     * @param License $license
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function licenseValidate(Request $request, License $license)
    {
        // this controller is works under 'auth:sanctum' and 'ls-license-guard' middleware
        // in this case 'auth()->user()' will be is our license

        $_license = $license->select(
            'domain',
            'license_key',
            'status',
            'expiration_date',
            'is_trial',
            'is_lifetime'
        )->where([
            ['id', '=', auth()->user()->id],
            ['is_trial', '!=', true]
        ])->first();

        $data = $request->input();

        // event will be fired after license is checked
        // this part depends to your logic, you can remove it or change it
        Event::dispatch(new LicenseChecked($_license, $data));


        return $_license;
    }
}
