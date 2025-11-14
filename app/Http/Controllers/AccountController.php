<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AccountController extends Controller
{
    // Show registration page
    public function registration()
    {
        return view('front.account.registration');
    }

    // Save user via AJAX
    public function processRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:5|confirmed',
        ]);

        if ($validator->passes()) {
            // Create user
            $user= User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);
            // Use of session for storing the data

            $request->session()->put('user', $user);


            return response()->json([
                'status'  => true,
                'message' => 'Registration successful!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    // Show login page
    public function login()
    {
        return view('front.account.login');
    }
}
