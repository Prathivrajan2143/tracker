<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\OrganizationLoginSetup;
use App\Models\OrganizationsLoginPage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OrganizationLoginController extends Controller
{
    public function loginSetup(Request $request)
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

    public function storeCustomLoginPage(Request $request)
    {
        // Validate the request to ensure 'logo' is present and is an image
        $request->validate([
            // 'org_id' => 'required|uuid',
            // 'logo' => 'required|image|mimes:jpg,jpeg,png,gif',
            'welcomeQuote' => 'required|string',
        ]);
        // Check if 'logo' file is present in the request
        if (!$request->hasFile('logo')) {
            return response()->json([
                'message' => 'Binary data not found'
            ], 404);
        }
        
        // Get the uploaded file
        $file = $request->file('logo');

        // Get binary data of the file
        // $binaryData = $file->get();

        // Store the file and get its path
        $logoPath = $file->store('organization_logo', 'public');

        // Save the data to the database
        OrganizationsLoginPage::create([
            'org_id' => $request->org_id,
            'logo' => $logoPath,
            'welcomeQuote' => $request->welcomeQuote,
        ]);

        // Return a success response
        return response()->json([
            'message' => 'Data saved successfully.',
            'logo_path' => $logoPath
        ], 201);
    }
}
