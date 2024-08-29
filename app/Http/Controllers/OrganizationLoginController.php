<?php

namespace App\Http\Controllers;

use App\Models\OrganizationLoginSetup;
use App\Models\OrganizationsLoginPage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrganizationLoginController extends Controller
{
    public function organizationLoginSetup(Request $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'login_type' => 'required|string|max:255',
                'email' => 'required|email|unique:organization_login_setup,email',
            ]);
        } catch (ValidationException $e) {
            // Return a custom validation failure response
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        try {
            $data = $request->except('login_type');

            $organization = OrganizationLoginSetup::create([
                'login_type' => $validatedData['login_type'],
                'email' => $validatedData['email'],
                'login_crediantials' => $data,
            ]);

            
        } catch (Exception $e) {

        }
    }

    public function storeCustomizeLoginPage(Request $request)
    {
        $request->validate([
            'org_id' => 'required|uuid',
            'logo' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'welcomeQuote' => 'required|string',
        ]);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoPath = $logo->store('organization_logo', 'public');
        }

        OrganizationsLoginPage::create([
            'org_id' => $request->org_id,
            'logo' => $logoPath,
            'welcomeQuote' => $request->welcomeQuote,
        ]);

        return response()->json(['message' => 'Data saved successfully.'], 201);
    }



}
