<?php

namespace App\Http\Controllers;

use App\Models\Nex_Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Administrator;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class SettingsController extends Controller
{
    // Account Settings security
    public function panel_settings(Request $request)
    {
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Panel Settings"], ['name' => ucwords(str_replace('-',' ',$request->route('panelsettings')))]];        
        return view('/content/settings/'.$request->route('panelsettings'), ['breadcrumbs' => $breadcrumbs]);
    }


    // Account Settings security
    public function site_settings()
    {
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Panel Settings"], ['name' => "Panel"]];
        return view('/content/settings/site-settings', ['breadcrumbs' => $breadcrumbs]);
    }

    // Account Settings security
    public function security_settings()
    {
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Settings"], ['name' => "Security Settings"]];
        return view('/content/settings/security-settings', ['breadcrumbs' => $breadcrumbs]);
    }

    // Account Settings billing
    public function billing_settings()
    {
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Settings"], ['name' => "Billing & Plans Settings"]];
        return view('/content/settings/billing-settings', ['breadcrumbs' => $breadcrumbs]);
    }

    // Account Settings notifications
    public function notifications_settings()
    {
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Settings"], ['name' => "Notifications Settings"]];
        return view('/content/settings/notifications-settings', ['breadcrumbs' => $breadcrumbs]);
    }

    // Account Settings connections
    public function connections_settings()
    {
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Settings"], ['name' => "Connections Settings"]];
        return view('/content/settings/connections-settings', ['breadcrumbs' => $breadcrumbs]);
    }
    
    // Profile
    public function profile()
    {
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Profile"], ['name' => "Edit Profile"]];

        return view('/content/profile/profile-edit', ['breadcrumbs' => $breadcrumbs]);
    }

    // Save Setting
    public function site_setting_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_name' => 'required',
            'site_description' => 'required',
            'site_link' => 'required',
            'site_contact' => 'required',
            'site_whattsapp_number' => 'required',
            'site_email' => 'required',
            'site_postal_address' => 'required',
            'site_about_us' => 'required',
            'site_keywords' => 'required', 
        ]);

        if ($validator->fails()) {
            return faildResponse(['Message'=>'Validation Warning', 'Data'=>$validator->errors()->toArray()]);
        }

        foreach ($request->all() as $key => $value){

            if (($key == 'site_logo' || $key == 'site_favicon_logo'))
            {
                if(strpos($value, ';base64') === false)
                    continue;

                if(file_exists(public_path('images/logo/' . setting($key))))
                    unlink(public_path('images/logo/' . setting($key)));

                $image_parts = explode(";base64,", $value);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $value = date('dmYHis') . '.' . $image_type;
                file_put_contents('public/images/logo/'.$value, $image_base64);
            }

            Nex_Setting::where('setting_field_name', $key)->update(['setting_field_value'=>$value]);
        }

        return successResponse(['Message'=>'Success!', 'Data'=>[],'Redirect'=>url()->previous()]);
    }
    // Save Mail
    public function mail_setting_store(Request $request)
    {
        foreach ($request->all() as $key => $value) 
            Nex_Setting::where('setting_field_name', $key)->update(['setting_field_value'=>$value]);

        return successResponse(['Message'=>'Success!', 'Data'=>[],'Redirect'=>url()->previous()]);
    }

    // Save Design
    public function design_setting_store(Request $request)
    {
        foreach ($request->all() as $key => $value) 
            Nex_Setting::where('setting_field_name', $key)->update(['setting_field_value'=>$value]);
        return successResponse(['Message'=>'Success!', 'Data'=>[],'Redirect'=>url()->previous()]);
    }

    // Save Security
    public function security_setting_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6',
            'retype_password' => 'required|same:password',
            'current_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    $exists = Auth::user('password');
                    if (!$exists || !Hash::check($value,$exists->password))
                        $fail('Current Password is Invalid.');
                },
            ],
        ]);

        if ($validator->fails()) 
            return faildResponse(['Message'=>'Validation Warning', 'Data'=>$validator->errors()->toArray()]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);        
        $user->save();      
        
        return successResponse(['Message'=>'Success!', 'Data'=>[],'Redirect'=>url()->previous()]);
    }
    
    //PROFILE
    public function profile_store(Request $request)
    {
        $validator =Validator::make($request->all(), [
            'name' => 'required',        
            'mobile' => [
                'required','regex:/^([0-9\s\-\+\(\)]*)$/','min:10',
                function ($attribute, $value, $fail) {
                    $exists = Administrator::where('mobile', encrypt_to($value))->where('id', '!=', Auth::id())
                    ->exists();
                    if ($exists)
                        $fail('The '.$attribute.' has already been taken.');
                },
            ],
            // 'email' => 'required',
            // 'user_name' => 'required',
            // 'profile_image' => 'required'
        ]);

        if ($validator->fails()) 
            return faildResponse(['Message'=>'Validation Warning', 'Data'=>$validator->errors()->toArray()]);
        

        $user = Auth::user();
        $profile_picture = $user->profile_picture;

        if ($request->hasFile('profile_image')) 
        {
            $image = $request->file('profile_image');    
            $path = PATH.USER;

            if(url_file_exists($path.'/'.$user->profile_picture))
                unlink($path.'/'.$user->profile_picture);

            $profile_picture = $filename = date('dmYHis') .uniqid() . '.' . $image->getClientOriginalExtension();
            
            $img = Image::make($image)->encode('jpg', 80)->resize(300, 300)->save($path . '/' . $filename);

        }            
        $user->profile_picture = $profile_picture;        
        $user->name = $request->name;        
        $user->email = $request->email?encrypt_to($request->email):'';        
        $user->mobile = $request->mobile?encrypt_to($request->mobile):'';        
        $user->save();   
        
        
        return successResponse(['Message'=>'Success!', 'Data'=>[],'Redirect'=>url()->previous()]);
    }

    // FAQ
    public function faq()
    {
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['name' => "FAQ"]];
        return view('/content/pages/page-faq', ['breadcrumbs' => $breadcrumbs]);
    }

    // Knowledge Base
    public function knowledge_base()
    {
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['name' => "Knowledge Base"]];
        return view('/content/pages/page-knowledge-base', ['breadcrumbs' => $breadcrumbs]);
    }

    // Knowledge Base Category
    public function kb_category()
    {
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['link' => "/page/knowledge-base", 'name' => "Knowledge Base"], ['name' => "Category"]];
        return view('/content/pages/page-kb-category', ['breadcrumbs' => $breadcrumbs]);
    }

    // Knowledge Base Question
    public function kb_question()
    {
        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['link' => "/page/knowledge-base", 'name' => "Knowledge Base"], ['link' => "/page/knowledge-base/category", 'name' => "Category"], ['name' => "Question"]];
        return view('/content/pages/page-kb-question', ['breadcrumbs' => $breadcrumbs]);
    }

    // pricing
    public function pricing()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/pages/page-pricing', ['pageConfigs' => $pageConfigs]);
    }

    // api key
    public function api_key()
    {
        $pageConfigs = ['pageHeader' => false];
        return view('/content/pages/page-api-key', ['pageConfigs' => $pageConfigs]);
    }

    // blog list
    public function blog_list()
    {
        $pageConfigs = ['contentLayout' => 'content-detached-right-sidebar', 'bodyClass' => 'content-detached-right-sidebar'];

        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['link' => "javascript:void(0)", 'name' => "Blog"], ['name' => "List"]];

        return view('/content/pages/page-blog-list', ['breadcrumbs' => $breadcrumbs, 'pageConfigs' => $pageConfigs]);
    }

    // blog detail
    public function blog_detail()
    {
        $pageConfigs = ['contentLayout' => 'content-detached-right-sidebar', 'bodyClass' => 'content-detached-right-sidebar'];

        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['link' => "javascript:void(0)", 'name' => "Blog"], ['name' => "Detail"]];

        return view('/content/pages/page-blog-detail', ['breadcrumbs' => $breadcrumbs, 'pageConfigs' => $pageConfigs]);
    }

    // blog edit
    public function blog_edit()
    {

        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['link' => "javascript:void(0)", 'name' => "Blog"], ['name' => "Edit"]];

        return view('/content/pages/page-blog-edit', ['breadcrumbs' => $breadcrumbs]);
    }

    // modal examples
    public function modal_examples()
    {

        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['name' => "Modal Examples"]];

        return view('/content/pages/modal-examples', ['breadcrumbs' => $breadcrumbs]);
    }

    // license
    public function license()
    {

        $breadcrumbs = [['link' => "/", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Pages"], ['name' => "License"]];

        return view('/content/pages/page-license', ['breadcrumbs' => $breadcrumbs]);
    }
}
