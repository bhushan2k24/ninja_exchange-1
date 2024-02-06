<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Administrator;
use GrahamCampbell\ResultType\Success;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use App\Http\Requests\AdminLoginRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    // Function for open login form ---------------
    public function index($is_adminLogin = '')
    {

        $file['title'] = '';
        $file['loginFormData'] = [
            'name'=>'login-form',
            'action'=>route('login'),
            'method'=>'post',
            'submit'=>'login',
            'fieldData'=>[
                [
                    'tag'=>'input',
                    'type'=>'text',
                    'label'=>'User Id',
                    'name'=>'userid',
                    'validation'=>'required',
                    'grid'=>12,
                ],
                [
                    'tag'=>'input',
                    'type'=>'password',
                    'label'=>'password',
                    'name'=>'password',
                    'validation'=>'required',
                ]
            ],
        ];

        session(['is_adminLogin' => ($is_adminLogin!='' ? $is_adminLogin : false)]);

        return view(($is_adminLogin!='' ? 'auth.AdminLogin' : 'auth.Login'), $file);
    }
    // ---------------

    // public function login(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'email' => 'required',
    //         'password' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return faildResponse(['Message'=>'Validaiton Warning', 'Data'=>$validator->errors()->toArray()]);
    //     }

    //     if ($request->email !== 'admin@gmail.com')
    //         return faildResponse(['Message'=>'user not found', 'Data'=>['email'=>['user not found']]]);

    //     if ($request->password !== 'admin')
    //         return faildResponse(['Message'=>'user not found', 'Data'=>['password'=>['invalid password']]]);

    //     return successResponse(['Message'=>'login success','Redirect'=>getenv('APP_URL').'dashboard']);
    // }

    public function login(AdminLoginRequest $request)
    {

        // RateLimiter::tooManyAttempts($this->throttleKey(), 3);
        // if (!RateLimiter::remaining($this->throttleKey(), $perMinute = 3))
        // RateLimiter::clear($this->throttleKey());
        // RateLimiter::hit($this->throttleKey());

        // dd(RateLimiter::tooManyAttempts($this->throttleKey(), 3),RateLimiter::remaining($this->throttleKey(),3));

        // dd(RateLimiter::tooManyAttempts($this->throttleKey(), 3),RateLimiter::remaining($this->throttleKey(), $perMinute = 3));
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 4))
        {
            $seconds = RateLimiter::availableIn($this->throttleKey());
            return faildResponse(['Message'=>'Validaiton Warning', 'Data'=>['userid'=>['Too many login attempts! '.'Contect your admin!']]]);
        }           
        $credentials  = $request->getCredentials();
        // Attempt to log in the user
        if (Auth::guard('admin')->attempt($credentials) && Auth::guard('admin')->user()->user_status == 'active')
        {

            if(session()->get('is_adminLogin') && !Auth::guard('admin')->user()->hasRole('admin'))
            {
                Auth::guard('admin')->logout();
                return faildResponse(['Message'=>'Validaiton Warning', 'Data'=>['userid'=>['Your Account  Not Found!']]]);
            }
            elseif(!session()->get('is_adminLogin') && Auth::guard('admin')->user()->hasRole('admin'))
            {
                Auth::guard('admin')->logout();
                return faildResponse(['Message'=>'Validaiton Warning', 'Data'=>['userid'=>['Your Account  Not Found!']]]);
            }

            Auth::guard('admin')->logoutOtherDevices($credentials['password']);
            
            Auth::guard('admin')->user()->update([
                'last_login_at' => Carbon::now()->toDateTimeString(),
                'last_login_ip' => $request->getClientIp()
            ]);
            RateLimiter::clear($this->throttleKey());
            return successResponse(['Message'=>'Success!', 'Data'=>[],'Redirect'=>route('admin.dashboard')]);
        }

        RateLimiter::hit($this->throttleKey());
        $error = ['userid'=>['Invalid User Id or Password!']];        
        if(Auth::guard('admin')->check())
        {
            if(Auth::guard('admin')->user()->user_status != 'active')
                $error = ['userid'=>['Your account has been disabled!']];
            Auth::guard('admin')->logout();
        }
        return faildResponse(['Message'=>'Validaiton Warning', 'Data'=>$error]);
    }

    public function ShowRegistration(Request $request)
    {
        if ($request->has('ref')) {
            session(['referrer' => $request->query('ref')]);
            return redirect()->route('showuserregister');
        }

        $file['title'] = 'register';
        $file['loginFormData'] = [
            'name'=>'login-form',
            'action'=>route('showuserregister'),
            'method'=>'post',
            'submit'=>'Register',
            'fieldData'=>[
                [
                    'tag'=>'input',
                    'type'=>'text',
                    'label'=>'name',
                    'name'=>'name',
                    'validation'=>'required',
                    'grid'=>12,
                ],
                [
                    'tag'=>'input',
                    'type'=>'number',
                    'label'=>'mobile',
                    'name'=>'mobile',
                    'validation'=>'required',
                    'grid'=>12,
                ],
                [
                    'tag'=>'input',
                    'type'=>'password',
                    'label'=>'password',
                    'name'=>'password',
                    'validation'=>'required',
                ],
                [
                    'tag'=>'input',
                    'type'=>'text',
                    'label'=>'Referral Code',
                    'name'=>'referral_code',
                    'validation'=>'required',
                    'value'=>session()->get('referrer'),
                    'grid'=>12,
                ],
            ],
        ];
        return view('auth.Register', $file);

    }

    protected function Registration(Request $request)
    {
        $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'mobile' => 'required',
                    'password' => 'required',
                ]);
        if ($validator->fails()) 
            return faildResponse(['Message'=>'Validaiton Warning', 'Data'=>$validator->errors()->toArray()]);
        
        $referrer = Administrator::whereReferral_token(session()->pull('referrer'))->first();
       
        $user = Administrator::create([
            'name' => $request['name'],
            'mobile' => encrypt_to($request['mobile']),
            'username' => str_replace(' ','',$request['name']),
            'usercode' => $request['mobile'],
            'referral_token' => str_replace(' ','',$request['name']),
            'parent_id' => $referrer ? $referrer->id : 0,
            'referrer_id' => $referrer ? $referrer->id : 0,
            'password' => bcrypt($request['password']),
        ]);
        $usercode = strlen($user->id)>=6 ? $user->id : (str_pad(rand(1, 9999), (6-(strlen($user->id)>6 ? 6 : strlen($user->id))), '0', STR_PAD_RIGHT)).$user->id;

        $user->update(['referral_token'=>"NXCREF".numberToCharacterString($user->id),
                        'usercode'=>$usercode]);
        
        // Role::create(['guard_name'=>'admin','name' => 'user']);

        $user->assignRole('user');

        return successResponse(['Message'=>'Success!', 'Data'=>[],'Redirect'=>route('auth-login')]);
    }
    #----------------------------------------------------------------
    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return request('userid');
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     */
    public function checkTooManyFailedAttempts()
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), 2)) {
            return faildResponse(['Message'=>'Validaiton Warning', 'Data'=>['userid'=>['Too many login attempts!']]]);
        }
       
    }

    /**
     * Clear Login limite  Attempts .
     *
     * @return void
     */
    public function clearLoginAttempts()
    {
        RateLimiter::clear($this->throttleKey());
    }

    // use Auth; 
    public function logout()
    {
        if(Auth::guard('admin')->check()) // this means that the admin was logged in.
        {
            // $redirect = Auth::guard('admin')->user()->hasRole('admin') ? ['my-login-admin'] : [];
            Auth::guard('admin')->logout();

            return redirect()->route('auth-login',[(session()->get('is_adminLogin') ? session()->get('is_adminLogin') : '')]);

            // return redirect()->route('auth-login',$redirect);
        }

        // $this->guard()->logout();
        // $request->session()->invalidate();
        return  Auth::guard('admin')->check() ? : redirect('/');
    }
}
