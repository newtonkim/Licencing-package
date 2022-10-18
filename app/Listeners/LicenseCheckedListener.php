<?php
namespace App\Listeners;

use LaravelReady\LicenseServer\Events\LicenseChecked;

class LicenseCheckedListener
{
    public function __construct()
    {
        //
    }

    public function handle(LicenseChecked $event)
    {
        // $event->license,
        // $event->data,
    }
}
