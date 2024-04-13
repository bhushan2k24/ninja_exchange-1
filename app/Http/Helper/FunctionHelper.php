<?php

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\FuncCall;

function demoTest($name = 'user')
{
    return $name;
}

#function for convert the value in encrypt mode
function encrypt_to($value = NULL,$type = NULL)
{
	$new_value = trim($value);
	$value = encryptvalue($new_value);
	return trim($value);
}

#function for convert the value in decrypt mode
function decrypt_to($value = NULL,$type = NULL)
{
	if(empty($value))
		return NULL;

	$new_value = decryptvalue($value);
	return trim($new_value);
}

#function for encrypt the passing paramter
function encryptvalue($string = NULL)
{
	$key = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

	$result = '';
	for($i=0; $i<strlen($string); $i++)
	{
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)+ord($keychar));
		$result.=$char;
	}
	return urlencode(base64_encode($result));
}

#function for decrypt the passing paramter
function decryptvalue($string = NULL)
{
	$key = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

	$result = '';
	$string = base64_decode(urldecode($string));
	for($i=0; $i<strlen($string); $i++)
	{
		$char = substr($string, $i, 1);
		$keychar = substr($key, ($i % strlen($key))-1, 1);
		$char = chr(ord($char)-ord($keychar));
		$result.=$char;
	}
	return $result;
}

#function for create html form content ---------------
function createFormHtmlContent($formArray = [])
{
    $html = '';
    if(!empty($formArray['name']) && !empty($formArray['fieldData']))
    {
        $html .=
        '<form class="row" id="'.$formArray['name'].'" action="'.$formArray['action'].'" method="'.(!empty($formArray['method']) ? $formArray['method']:'get').'">';
        
        foreach($formArray['fieldData'] as $key => $value)
        {
            $element_extra_classes = (!empty($value['element_extra_classes'])?$value['element_extra_classes']:'');

            if ($value['tag'] == 'button')
            {
                $html .='
                <div class="col-12 col-md-'.(!empty($value['grid']) ? $value['grid'] : '12').' mt-2   '.(!empty($value['outer_div_classes']) ? $value['outer_div_classes'] : '').' ">
                <a href="javascript:void(0);" id="'.$value['name'].'" class="btn btn-outline-primary  w-100 waves-effect waves-float waves-light '.(!empty($value['extra-class']) ? $value['extra-class'] : '').' '.$element_extra_classes.'" name="'.$value['name'].'" >'.$value['label'].'</a>
                </div>
                ';
                continue;
            }
            $html .=
            '<div class="col-12 col-md-'.(!empty($value['grid']) ? $value['grid'] : '12').' '.(!empty($value['outer_div_classes']) ? $value['outer_div_classes'] : '').'" style="margin-bottom: 1rem">
                <label class="form-label" for="login-email">'.ucwords($value['label']).'</label>';

                $value['placeholder'] = ucwords(!empty($value['placeholder'])?$value['placeholder']:$value['label']);

                if ($value['tag'] == 'input')
                {
                    if (in_array($value['type'], ['text', 'email', 'number','button','reset']))
                    {
                        $html .=
                        '<input class="form-control '.$element_extra_classes.'" placeholder="'.$value['placeholder'].'" value="'.(!empty($value['value'])?$value['value']:'').'" id="'.$value['name'].'" type="'.$value['type'].'" name="'.$value['name'].'"  aria-describedby="'.$value['name'].'" tabindex="'.($key + 1).'" />';
                    }
                    if(in_array($value['type'], ['password']))
                    {
                        $html .=
                        '<div class="input-group input-group-merge form-password-toggle">
                            <input class="form-control  form-control-merge  '.$element_extra_classes.'" placeholder="'.$value['placeholder'].'" value="'.(!empty($value['value'])?$value['value']:'').'" id="'.$value['name'].'" type="'.$value['type'].'" name="'.$value['name'].'"  aria-describedby="'.$value['name'].'" tabindex="'.($key + 1).'" />
                            <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                        </div>';
                    }
                    if (in_array($value['type'], ['date', 'time', 'datetime-local']))
                    {
                        $html .=
                        '<input class="form-control  '.$element_extra_classes.'"  placeholder="'.$value['placeholder'].'"  value="'.(!empty($value['value'])?$value['value']:'').'" id="'.$value['name'].'" type="'.$value['type'].'" name="'.$value['name'].'"  tabindex="'.($key + 1).'" />';
                    }
                    if (in_array($value['type'], ['radio', 'checkbox']))
                    {
                        $html .=
                        '<div class="demo-inline-spacing-s">';
                        if (!empty($value['data']))
                        {
                            foreach($value['data'] as $childKey => $childValue)
                            {
                                
                                $checked = (!empty($value['value']) && $value['value'] == $childValue['value'] ? 'checked' : '');
                                $html .=
                                '<div class="text-capitalize form-check form-check-primary mt-'.($childKey > 0 ? '1' : '0').'">
                                    <input type="'.$value['type'].'"  placeholder="'.$value['placeholder'].'"  name="'.$value['name'].'" value="'.$childValue['value'].'" class="form-check-input  '.$element_extra_classes.'" id="'.$value['name'].$childKey.'" '.$checked.'/>
                                    <label class="form-check-label fs-09rem" for="'.$value['name'].$childKey.'">'.$childValue['label'].'</label>
                                </div>';
                            }
                        }
                        $html .= '</div>';
                    }
                }
                if ($value['tag'] == 'select')
                {
                    $html .='<select class="select2 form-select">
                                <option value="">select</option>';
                    if (!empty($value['data']))
                    {
                        foreach($value['data'] as $childKey => $childValue)
                        {
                            $html .='<option value="'.$childValue['value'].'">'.$childValue['label'].'</option>';
                        }
                    }
                    $html .='</select>';
                }
                if ($value['tag'] == 'textarea')
                {
                    $html .=
                        '<textarea class="form-control" placeholder="'.ucwords(!empty($value['placeholder'])?$value['placeholder']:$value['name']).'" id="'.$value['name'].'" type="'.$value['type'].'" name="'.$value['name'].'"  aria-describedby="'.$value['name'].'" tabindex="'.($key + 1).'" >'.(!empty($value['value'])?$value['value']:'').'</textarea>';
                   
                }
                
            $html .=
                '<small class="error '.$value['name'].'-error"></small>
            </div>';
        }
        if(empty($formArray['no_submit']))
        {
            $html .=
                '<div class="col-12 col-md-'.(!empty($formArray['btnGrid']) ? $formArray['btnGrid'] : '12').' mt-2">
                    <button type="submit" class="btn btn-primary w-100 text-capitalize" tabindex="4">'.(!empty($formArray['submit']) ? $formArray['submit'] : 'save').'</button>
                </div>';
        }

        $html .=    
        '</form>';
    }
    return $html;
}
#---------------

#function for create html form content ---------------
function createDatatableFormFilter($formArray = [])
{
    $html = '';
    if(!empty($formArray['name']) )
    {
        $html .=
        '<form class="row datatable_paginate card-body pb-75" id="'.$formArray['name'].'" action="'.$formArray['action'].'" method="'.(!empty($formArray['method']) ? $formArray['method']:'get').'">';
        if(!empty($formArray['fieldData'])){
            foreach($formArray['fieldData'] as $key => $value)
            {
                if (!empty($value['roles']) && (!Auth::user()->hasRole($value['roles'])))
                    continue;                 

                $element_extra_classes = (!empty($value['element_extra_classes'])?$value['element_extra_classes']:'');
                $ext_attr = (!empty($value['element_extra_attributes'])?$value['element_extra_attributes']:'');
            
                if ($value['tag'] == 'button')
                {
                    $html .='
                    <div class="col col-md-'.(!empty($value['grid']) ? $value['grid'] : '12').' mt-2   '.(!empty($value['outer_div_classes']) ? $value['outer_div_classes'] : '').' ">
                    <a href="javascript:void(0);"  id="'.$value['name'].'" class="btn btn-outline-primary waves-effect waves-float waves-light '.(!empty($value['extra-class']) ? $value['extra-class'] : '').' '.$element_extra_classes.'" name="'.$value['name'].'" >'.$value['label']. '</a>
                    </div>
                    ';
                    continue;
                }
                if (in_array($value['type'], ['hidden']))
                {
                    $html .=
                    '<input class="'.$element_extra_classes.'" value="'.(!empty($value['value'])?$value['value']:'').'" id="'.$value['name'].'" type="'.$value['type'].'" name="'.$value['name'].'"  aria-describedby="'.$value['name'].'" />';
                    continue;
                }

                $html .=
                '<div class="col col-md-'.(!empty($value['grid']) ? $value['grid'] : '12').' '.(!empty($value['outer_div_classes']) ? $value['outer_div_classes'] : '').'" style="margin-bottom: 1rem">
                    <label class="form-label" for="login-email">'.$value['label'].'</label>';

                    $value['placeholder'] = ucwords(!empty($value['placeholder'])?$value['placeholder']:$value['label']);

                    if ($value['tag'] == 'input')
                    {
                        if (in_array($value['type'], ['text', 'email', 'number','button','reset']))
                        {
                            $html .=
                            '<input class="form-control '.$element_extra_classes.'" placeholder="'.$value['placeholder'].'" value="'.(!empty($value['value'])?$value['value']:'').'" id="'.$value['name'].'" type="'.$value['type'].'" name="'.$value['name'].'"  aria-describedby="'.$value['name'].'" tabindex="'.($key + 1).'" />';
                        }
                        if(in_array($value['type'], ['password']))
                        {
                            $html .=
                            '<div class="input-group input-group-merge form-password-toggle">
                                <input class="form-control  form-control-merge  '.$element_extra_classes.'" placeholder="'.$value['placeholder'].'" value="'.(!empty($value['value'])?$value['value']:'').'" id="'.$value['name'].'" type="'.$value['type'].'" name="'.$value['name'].'"  aria-describedby="'.$value['name'].'" tabindex="'.($key + 1).'" />
                                <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                            </div>';
                        }
                        if (in_array($value['type'], ['date', 'time', 'datetime-local']))
                        {
                            $html .=
                            '<input class="form-control  '.$element_extra_classes.'"  placeholder="'.$value['placeholder'].'"  value="'.(!empty($value['value'])?$value['value']:'').'" id="'.$value['name'].'" type="'.$value['type'].'" name="'.$value['name'].'"  tabindex="'.($key + 1).'" />';
                        }
                        if (in_array($value['type'], ['radio', 'checkbox']))
                        {
                            $html .=
                            '<div class="demo-inline-spacing-s">';
                            if (!empty($value['data']))
                            {
                                foreach($value['data'] as $childKey => $childValue)
                                {
                                    $checked = (!empty($value['value']) && $value['value'] == $childValue['value'] ? 'checked' : '');

                                    $html .=
                                    '<div class="text-capitalize form-check form-check-primary mt-'.($childKey > 0 ? '0' : '0').'">
                                        <input type="'.$value['type'].'"  placeholder="'.$value['placeholder'].'"  name="'.$value['name'].'" value="'.$childValue['value'].'" class="form-check-input  '.$element_extra_classes.'" id="'.$value['name'].$childKey.'" '.$checked.' />
                                        <label class="form-check-label fs-09rem"  for="'.$value['name'].$childKey.'">'.$childValue['label'].'</label>
                                    </div>';
                                }
                            }
                            $html .= '</div>';
                        }
                    }
                    if ($value['tag'] == 'select')
                    {
                        $html .='<select class="form-select '.$element_extra_classes.'" '.$ext_attr.' name="'.$value['name'].'" id="'.$value['name'].'" tabindex="'.($key + 1).'">
                                    <option value="">select</option>';
                        if (!empty($value['data']))
                        {
                            foreach($value['data'] as $childKey => $childValue)
                            {
                                $html .='<option value="'.$childValue['value'].'">'.$childValue['label'].'</option>';
                            }
                        }
                        $html .='</select>';
                    }
                    if ($value['tag'] == 'textarea')
                    {
                        $html .=
                            '<textarea class="form-control" placeholder="'.ucwords(!empty($value['placeholder'])?$value['placeholder']:$value['name']).'" id="'.$value['name'].'" type="'.$value['type'].'" name="'.$value['name'].'"  aria-describedby="'.$value['name'].'" tabindex="'.($key + 1).'" >'.(!empty($value['value'])?$value['value']:'').'</textarea>';
                    
                    }
                    
                $html .=
                    '<small class="error '.$value['name'].'-error"></small>
                </div>';
            }
        }
        // if(empty($formArray['no_submit']))
        // {
        //     $html .=
        //         '<div class="col col-md-'.(!empty($formArray['btnGrid']) ? $formArray['btnGrid'] : '12').' mt-2">
        //             <button type="submit" class="btn btn-primary w-100 text-capitalize" tabindex="4">'.(!empty($formArray['submit']) ? $formArray['submit'] : 'save').'</button>
        //         </div>';
        // }

        $html .= '  <div class="col-12 col-md-12 justify-content-between '.(!empty($formArray['fieldData'])?'  mt-1 pt-1 border-top':'').'">
                        <div class="row justify-content-between">
                        <div class="col-4 col-sm-3 col-md-2">
                            <div class="form-group">
                            '.limitDropDownData(10).'
                            </div>
                        </div>
                        <div class="col-8 col-sm-5 col-md-3">
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i data-feather="search"></i></span>
                                <input type="text" class="form-control" name="search" placeholder="Search" />
                            </div>
                        </div>
                        </div>
                    </div>';

        $html .=    
        '</form>';
    }
    return $html;
}
#---------------

#function for pass sidebar array content ---------------
function sideContentData($array = [])
{

    #lable shows menu name in sidebar (STRING)
    #icon shows menu icon in sidebar (STRING)
    #link redirection on click (STRING) 
    #link_attribute is set attribute in link  (ARRAY) 
    #roles is shows menu logged in user role wise (ARRAY) 
    #childData user to create submenu (ARRAY) 

    $data = [
        [
            'label'=> (Auth::user()->hasRole(['admin','master'])?'dashboard':'home'),
            'link'=>'admin.dashboard',
            'icon'=>'home',
            'roles'=> ['admin','master','broker'],
            'childData'=>[]
        ],
        [
            'label'=>'trading',
            'link'=>'',
            'icon'=>'trending-up',
            'open'=>'open',
            'roles'=> ['admin','master','broker', 'user'],
            'childData'=>[
                [
                    'label'=>'watchlist',
                    'link'=>'view.watchlist',
                    'icon'=>'circle',
                    'roles'=> ['admin','master','broker','user']
                ],
                [
                    'label'=>'trades',
                    'link'=>'view.trades',
                    'icon'=>'circle',
                    'roles'=> ['admin','master','broker','user']
                ],
                [
                    'label'=>'portfolio/position',
                    'link'=>'portfolio',
                    'icon'=>'circle',
                    'roles'=> ['admin','master','broker','user']
                ],
                [
                    'label'=>'banned/blocked script',
                    'link'=>'blocked-script',
                    'icon'=>'circle',
                    'roles'=> ['admin','master','user']
                ],
                [
                    'label'=>'margin management',
                    'link'=>'margin-management',
                    'icon'=>'circle',
                    'roles'=> ['admin','master','user']
                ],
                [
                    'label'=>'manual trade',
                    'link'=>'manual-trade',
                    'icon'=>'circle',
                    'roles'=> ['admin','master','user']
                ],
                [
                    'label'=>'summery report',
                    'link'=>'summery-report',
                    'icon'=>'circle',
                    'roles'=> ['admin','master','broker','user']
                ],
                [
                    'label'=>'self P&L',
                    'link'=>'self-profit-loss',
                    'icon'=>'circle',
                    'roles'=> ['admin','master','user']
                ],
                [
                    'label'=>'brokerage refresh',
                    'link'=>'brokerage-refresh',
                    'icon'=>'circle',
                    'roles'=> ['admin','master','user'],
                ]
            ]
        ],
        [
            'label'=>'users',
            'link'=>'',
            'icon'=>'user',
            'open'=>'open',
            'roles'=> ['admin','master', 'broker'],
            'childData'=>[
                [
                    'label'=>'user listing',
                    'link'=>'view.user',
                    'link_attribute'=>[],
                    'icon'=>'circle',
                    'roles'=> ['admin','master', 'broker', 'user'],
                ],
                [
                    'label'=>'master listing',
                    'link'=>'view.user',
                    'link_attribute'=>['master'],
                    'icon'=>'circle',
                    'roles'=> ['admin','master','user'],
                ],
                [
                    'label'=>'broker listing',
                    'link'=>'view.user',
                    'link_attribute'=>['broker'],
                    'icon'=>'circle',
                    'roles'=> ['admin','master','user'],
                ],
                [
                    'label'=>'add account',
                    'link'=>'create.user',
                    'icon'=>'user-plus',
                    'roles'=> ['admin','master','user'],
                ],
                [
                    'label'=>'wallet',
                    'link'=>'wallet.view',
                    'icon'=>'circle',
                    'roles'=> ['admin','master','user'],
                ]
            ]
        ],
        [
            'label'=>'settings',
            'link'=>'',
            'icon'=>'sliders',
            'open'=>'',
            'roles'=> ['admin','master'],
            'childData'=>[                
                [
                    'label'=>'script',
                    'link'=>'view.script',
                    'icon'=>'circle',
                ],

                [
                    'label'=>'ban script',
                    'link'=>'banscript',
                    'icon'=>'circle',
                ],
                [
                    'label'=>'expiry',
                    'link'=>'viewexpiry',
                    'icon'=>'circle',
                ],
                [
                    'label'=>'live tv',
                    'link'=>'livetv',
                    'icon'=>'circle',
                ],
                [
                    'label'=>'max quantity',
                    'link'=>'view.max_quantity',
                    'icon'=>'circle',
                ],
                [
                    'label'=>'block/allow scripts ',
                    'link'=>'view.block_master_script',
                    'icon'=>'circle',
                ],
                [
                    'label'=>'time settings ',
                    'link'=>'view.time_setting',
                    'icon'=>'clock',
                ]               
            ]
        ],    
        [
            'label'=>'wallet',
            'link'=>'wallet.view',
            'icon'=>'pocket',
            'roles'=> ['user'],
            'childData'=>[]
        ],        
        [
            'label'=>'Logout',
            'link'=>'admin.logout',
            'icon'=>'power',
            'childData'=>[]
        ],
    ];

    return $data;
}
#---------------

#function for create html form content ---------------
function limitDropDownData($limit = 5)
{
    $limitData = [5, 10, 25, 50, 100, 250, 500, 1000];
    $html = '<select class="form-select select2" name="limit">
                <option value="1">1 row</option>';
                foreach($limitData as $val)
                {
                    $html .= '<option '.($limit == $val ? ' selected ' : '').' value="'.$val.'">'.$val.' rows</option>';
                }
    $html .= '</select>';
    return $html;
}
#---------------

#function for return faild json data ---------------
function faildResponse($array = [])
{
    $array['Status'] = (int)400;
    $array['Code'] = !empty($array['Code']) ? $array['Code'] : 400;
    return response()->json($array);
}
#---------------

#function for return success json data ---------------
function successResponse($array = [])
{
    $array['Status'] = (int)200;
    $array['Code'] = !empty($array['Code']) ? $array['Code'] : 200;
    $array['Data'] = !empty($array['Data']) ? $array['Data'] : [];
    
    return response()->json($array);
}
#---------------
// Assuming you want to convert 1 to 'A', 2 to 'B', and so on
function numberToCharacterString($number) 
{
    $numberStr = strval($number);
    // Initialize an empty result string
    $result = '';
    // Iterate through each digit in the number and map it to a character
    for ($i = 0; $i < strlen($numberStr); $i++) {
        $digit = intval($numberStr[$i]);

        if ($digit >= 1 && $digit <= 26) {
            // Assuming you want to convert 1 to 'A', 2 to 'B', and so on
            // Using ASCII values to convert
            $result .= chr(ord('A') + $digit - 1);
        }
    }
    return $result;
}

function site_logo()
{
    return (file_exists(public_path('images/logo/' . setting('site_logo'))) ? asset('images/logo/' . setting('site_logo')) : asset('images/logo/logo.png'));
} 

function site_favicon_logo()
{
    return (file_exists(public_path('images/logo/' . setting('site_favicon_logo'))) ? asset('images/logo/' . setting('site_favicon_logo')) : asset('images/logo/logo.png'));
} 

function getInitials($full_name) {
    if(count(explode(' ', $full_name)) == 1)
        return strtoupper(substr($full_name,0,2));
    return substr(implode('', array_map(fn($word) => (strtoupper(substr($word, 0, 1)) ), explode(' ', $full_name,2))),0,2);
}
// return implode('', array_map(fn($word) => ((explode(' ', $full_name)>1)?strtoupper(substr($word, 0, 1)):strtoupper(substr($word, 2))), explode(' ', $full_name)));

function getRandomColorState(){
    $color_states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];        
    return $color_states[array_rand($color_states)];
}

function url_file_exists($pathOrUrl)
{
    $extension = pathinfo($pathOrUrl, PATHINFO_EXTENSION);
    // Check if the URL has a file extension
    if (!empty($extension))
        return file_exists(str_replace(URL,PATH,$pathOrUrl));    
    return false;  
}

 #----------------------------------------------------------------
#Function to get User Avatar
function getUserNameWithAvatar($name='',$image=NULL,$position=' ')
{
    if($name=='')
        return $name;

    $Initials = getInitials($name);
    $randomState = getRandomColorState();
    $user_parent_profile = '<span class="avatar-content">'.$Initials.'</span>';

    $user_parent_profile = url_file_exists($image)?                
    '<img src="'.$image.'" alt="'.$name.'" height="32" width="32">':$user_parent_profile;
    
    $user_parent_profile = 
        '<div class="d-flex justify-content-left align-items-center">
            <div class="avatar-wrapper">
                <div class="avatar  bg-light-'.$randomState.'  me-1">
                '.$user_parent_profile.'
                </div>
            </div>
            <div class="d-flex flex-column">
                <a href="javascript:void(0);" class="user_name text-truncate text-body">
                <span class="fw-bolder">'.ucwords($name).'</span>
                </a>
                <small class="emp_post text-muted">'. $position.'</small>
            </div>
        </div>';
    return $user_parent_profile;
}
#----------------------------------------------------------------

?>