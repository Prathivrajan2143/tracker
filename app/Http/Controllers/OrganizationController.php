<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class OrganizationController extends Controller
{
    public function organizationInvite(Request $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'org_name' => 'required|string|max:255',
                'org_admin_email' => 'required|email|unique:organization,org_admin_email',
                'org_domain_name' => 'required|string|max:255|unique:organization,org_domain_name',
            ]);
        } catch (ValidationException $e) {
            // Return a custom validation failure response
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        // Create a new organization record
        $organization = Organization::create([
            'org_name' => $validatedData['org_name'],
            'org_admin_email' => $validatedData['org_admin_email'],
            'org_domain_name' => $validatedData['org_domain_name'],
        ]);

        
            // Generate the signed invite URL
            $inviteUrl = URL::temporarySignedRoute(
                'invite.handle',
                Carbon::now()->addMinutes(15),
                ['domain' => $organization->org_domain_name]
            ); 

            // Send the invite email
            Mail::to($validatedData['org_admin_email'])->send(new \App\Mail\OrganizationInvite($inviteUrl));

            // Return a success response
            return response()->json([
                'success' => true,
                'message' => 'Organization created successfully',
                'data' => $organization,
            ], 201);
    }

}