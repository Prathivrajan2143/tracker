<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\Organization;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OrganizationController extends Controller
{
    public function organizationInvite(Request $request)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'org_name' => 'required|string|max:255',
                'org_admin_email' => 'required|email|unique:organizations,org_admin_email',
                'org_domain_name' => 'required|string|max:255|unique:organizations,org_domain_name',
            ]);
        } catch (ValidationException $e) {
            // Return a custom validation failure response
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 400);
        }

        try{
            // Generate a temporary password
            $temporaryPassword = $validatedData['org_domain_name'] . Str::random(10);
            $password_expires_at = Carbon::now()->addMinutes(60)->format('Y-m-d H:i:s');

            // Create a new organization record
            $organization = Organization::create([
                'org_name' => $validatedData['org_name'],
                'org_admin_email' => $validatedData['org_admin_email'],
                'org_domain_name' => $validatedData['org_domain_name'],
                'temporary_password' => Crypt::encrypt($temporaryPassword),
                'password_expires_at' => $password_expires_at,
            ]);

            if ($organization) {
                $inviteUrl = URL::temporarySignedRoute(
                    'invite.handle',
                    now()->addMinutes(60),
                    ['domain' => $organization->org_domain_name]
                );

                // Set the custom base URL
                $baseUrl = 'http://localhost:3000';
                // Replace the base URL with the desired one
                $inviteUrl = Str::replaceFirst(config('app.url').':8085', $baseUrl, $inviteUrl);

                // Send the invite email
                Mail::to($validatedData['org_admin_email'])->send(new \App\Mail\OrganizationInvite($inviteUrl, $temporaryPassword));

                // Return a success response
                return response()->json([
                    'success' => true,
                    'message' => 'Organization created successfully',
                    'data' => $organization,
                ], 201);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Something Went Wrong',
                ], 500);
            }
        } catch(Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong',
                'errors' => $e,
            ], 400);
        }

        
    }

    public function organizationData()
    {
        try{
            $organizations = Organization::get(['org_id', 'org_name', 'org_admin_email', 'org_domain_name', 'created_at']);
            return response()->json([
                'status' => true,
                'data' => $organizations
            ], 200);
        } catch(Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e
            ], 200);
        }
    }

    public function urlValidate(Request $request)
    {
        try{
            // Validate the signed URL
            if (!$request->hasValidSignature()) {
                $organization = DB::table('organizations')->where('org_domain_name', $request->domain)->first();
                if(!$organization || !$organization->password_expires_at){

                        if (!$organization || !$organization->password_expires_at) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Invalid or expired invitation.',
                            ], 401);
                        }

                        // Check if the current time is past the expiration time
                        if (Carbon::now()->gt(Carbon::parse($organization->password_expires_at))) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Invitation has expired.',
                            ], 401);
                        }
                    }
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired URL.',
                ], 401);
            }
            else {
                return response()->json([
                    'success' => true,
                    'message' => 'Valid',
                ], 200);
            }
        }catch(Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e,
            ], 401);
        }
    }

    public function tempLoginSendOtp(Request $request)
    {
        try{
            // $secretKey = env('APP_SECRET_KEY');  // Use the same secret key used in React
        //Encrypt the data
            //  $encryptedEmail = openssl_encrypt('prathiv@gmail.com', 'AES-128-ECB', $secretKey);
        //Decrypt the data
            // return $decryptedEmail = openssl_decrypt($encryptedEmail, 'AES-128-ECB', $secretKey);


            // Decrypt the incoming data
                // $decryptedEmail = openssl_decrypt($request->email, 'AES-128-ECB', $secretKey);
                // $decryptedPassword = openssl_decrypt($request->password, 'AES-128-ECB', $secretKey);
            
            // Validate the decrypted data
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
            
            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422); // Return a 422 Unprocessable Entity status code
            } 

             
            $orgData = Organization::where('org_admin_email', $request->email)
            ->first();

            // Check if the current time is past the expiration time
            if (Carbon::now()->gt(Carbon::parse($orgData->password_expires_at))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invitation has expired.',
                ], 401);
            } else {
                if($request->password === Crypt::decrypt($orgData->temporary_password) && $request->email === $orgData->org_admin_email) {

                    $otp = rand(10000000,100000000);
                    $otp_expires_at = Carbon::now()->addMinutes(60)->format('Y-m-d H:i:s');

                    // Send the invite email
                    Mail::to($orgData->org_admin_email)->send(new OtpMail($otp));

                    $orgData->otp = $otp;
                    $orgData->otp_expires_at = $otp_expires_at;
                    
                    $orgData->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'OTP sent successfully',
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid Credentials',
                    ], 401);
                }
            }
        } catch(Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong',
                'errors' => $e
            ], 401);
        }    
    }

    public function resendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422); // Return a 422 Unprocessable Entity status code
            }

            $organization = Organization::where('org_admin_email', $request->email)
            ->first(['org_admin_email', 'otp', 'otp_expires_at']);

            // Check if the current time is past the expiration time
            if (Carbon::now()->gt(Carbon::parse($organization->password_expires_at))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password has expired.',
                ], 401);
            } else {

                Mail::to($organization->org_admin_email)->send(new OtpMail($organization->otp));

                return response()->json([
                    'success' => true,
                    'message' => 'OTP sent successfully',
                    'data' => $organization->otp,
                ], 200);
            }
        } catch(Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong',
                'errors' => $e
            ], 401);
        }
    }

    public function verifyOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'otp' => 'required|digits:8', // Ensures OTP is exactly 8 digits long
            ]);
            
            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422); // Return a 422 Unprocessable Entity status code
            }
            
            $organization = Organization::where('org_admin_email', $request->email)
            ->first(['org_id', 'org_admin_email', 'temporary_password', 'otp', 'otp_expires_at']);
            
            
            // Check if the current time is past the expiration time
            if (Carbon::now()->gt(Carbon::parse($organization->password_expires_at))) {
                return response()->json([
                    'success' => false,
                    'message' => 'URL has expired.',
                ], 401);
            }
            
            // Check if the otp time is past the expiration time
            if (Carbon::now()->gt(Carbon::parse($organization->otp_expires_at))) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP has expired.',
                ], 401);
            } else {
                // if(Auth::guard('organizations')->attempt(['org_admin_email' => $request->email, 'temporary_password' => 
                // $temporary_password, 'otp' => $request->otp], $request->get('remember'))) {
                // return $organization->otp.'-'.$request->otp;
                    if($organization->otp == $request->otp) {
                       
                        if (now()->greaterThan($organization->otp_expires_at)) {
                            return response()->json(['message' => 'OTP expired'], 401);
                        }

                        // $token = $organization->createToken('bpo1dFfpVPOtaLQPuxXUnDWDXLC4i6KiXENkjwHk')->accessToken;

                        // Optionally, clear the OTP and expiration
                        $organization->otp = null;
                        $organization->otp_expires_at = null;
                        $organization->save();
                
                        return response()->json([
                            'success' => true,
                            'token' => 'bpo1dFfpVPOtaLQPuxXUnDWDXLC4i6KiXENkjwHk',
                        ]);
                    }
                    else
                    {
                        return 0;
                    }
                // if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))){
                //     $token = auth()->user()->createToken('Token name')->accessToken;
                //     return response()->json(['token' => $token], 200);
                // }
            }


        } catch(Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something Went Wrong',
                'errors' => $e
            ], 401);
        }

    }

    

}
