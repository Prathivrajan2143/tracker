<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InviteController extends Controller
{
    public function handleInvite(Request $request, $domain)
    {
        // Validate the signed URL
        if (!$request->hasValidSignature()) {
            abort(401, 'Invalid or expired URL.');
        }

        // Retrieve the organization by domain
        $organization = DB::table('organizations')->where('org_domain_name', $domain)->first();

        if (!$organization || !$organization->password_expires_at) {
            abort(401, 'Invalid or expired invitation.');
        }

        // Check if the current time is past the expiration time
        if (Carbon::now()->gt(Carbon::parse($organization->password_expires_at))) {
            abort(401, 'Invitation has expired.');
        }

        // Handle the invite logic, such as showing a view or redirecting to a registration page
        return view('emails.organization_invite', ['domain' => $domain]);
    }
}
