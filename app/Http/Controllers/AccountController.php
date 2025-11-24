<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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
    // Login Authentication Code 
    public function authenticate(Request $request)
    {
        // Validation rules
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        // If validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Try to login using email and password
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Login successful
            return response()->json([
                'status'  => true,
                'message' => "Login Successful!",
                'redirect_url_profile' => route('account.profile')
            ]);
        } else {
            // Wrong email or password
            return response()->json([
                'status' => false,
                'errors' => [ 'email' => 'Invalid email or password.' ],
                'redirect_url_login' => route('account.login')
            ]);
        }
    }

    // profile logic
    public function profile(){
        $id= Auth::user()->id;
        // dd($id);  
        $user=User::find($id);
          //dd means dump and die
        dd($user);
        return view('front.account.profile');
    }
    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }
}
