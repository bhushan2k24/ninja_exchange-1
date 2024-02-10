<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

use Config;
use Illuminate\Support\Str;

class FunctionHelpers
{
    public static function createFormHtmlContent($formArray = [])
    {

        $html = '';
        if(!empty($formArray['name']) && !empty($formArray['fieldData']))
        {
            $html .=
            '<form class="row" id="'.$formArray['name'].'" action="'.$formArray['action'].'" method="'.($formArray['method']?$formArray['method']:'get').'">';
            foreach($formArray['fieldData'] as $key => $value)
            {

                if ($value['tag'] == 'button')
                {
                    $html .='
                    <div class="col col-md-'.(!empty($value['grid']) ? $value['grid'] : '12').' mt-2">
                        <a href="javascript:void(0);" id="'.$value['name'].'" class="btn btn-outline-primary  w-100 waves-effect waves-float waves-light '.(!empty($value['extra-class']) ? $value['extra-class'] : '').'" name="'.$value['name'].'" >'.$value['label'].'</a>
                    </div>
                    ';
                    continue;
                }

                $html .=
                '<div class="mb-1 col col-md-'.(!empty($value['grid']) ? $value['grid'] : '12').'">
                    <label class="form-label" for="login-email">'.ucwords($value['label']).'</label>';
                    if ($value['tag'] == 'input')
                    {
                        if (in_array($value['type'], ['text', 'email', 'password', 'number','button']))
                        {
                            $html .=
                            '<input class="form-control" id="'.$value['name'].'" type="'.$value['type'].'" name="'.$value['name'].'" placeholder="" aria-describedby="'.$value['name'].'" tabindex="'.($key + 1).'" />';
                        }
                        if (in_array($value['type'], ['date', 'time', 'datetime-local']))
                        {
                            $html .=
                            '<input class="form-control" id="'.$value['name'].'" type="'.$value['type'].'" name="'.$value['name'].'" placeholder="" tabindex="'.($key + 1).'" />';
                        }
                        if (in_array($value['type'], ['radio', 'checkbox']))
                        {
                            $html .=
                            '<div class="demo-inline-spacing-s">';
                            if (!empty($value['data']))
                            {
                                foreach($value['data'] as $childKey => $childValue)
                                {
                                    $html .=
                                    '<div class="text-capitalize form-check form-check-primary mt-'.($childKey > 0 ? '1' : '0').'">
                                        <input type="'.$value['type'].'" name="'.$value['name'].'" value="'.$childValue['value'].'" class="form-check-input" id="'.$value['name'].$childKey.'" />
                                        <label class="form-check-label" for="'.$value['name'].$childKey.'">'.$childValue['label'].'</label>
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

                    }
                    
                $html .=
                    '<small class="text-danger"></small>
                </div>';
            }
            $html .=
                '<div class="col col-md-'.(!empty($formArray['btnGrid']) ? $formArray['btnGrid'] : '12').' mt-2">
                    <button type="submit" class="btn btn-primary w-100 text-capitalize" tabindex="4">'.(!empty($formArray['submit']) ? $formArray['submit'] : 'save').'</button>
                </div>
            </form>';
        }
        return $html;
    }

    // function for return sidebar content
    public static function sideContentData($array = [])
    {
        $data = [
            [
                'label'=>'dashboards',
                'link'=>'dashboard',
                'icon'=>'home',
                'childData'=>[]
            ],
            [
                'label'=>'trading',
                'link'=>'',
                'icon'=>'trending-up',
                'open'=>'open',
                'childData'=>[
                    [
                        'label'=>'watchlist',
                        'link'=>'trading/watchlist',
                        'icon'=>'circle',
                    ],
                    [
                        'label'=>'traders',
                        'link'=>'trading/traders',
                        'icon'=>'circle',
                    ],
                    [
                        'label'=>'portfolio/position',
                        'link'=>'trading/portfolio',
                        'icon'=>'circle',
                    ],
                    [
                        'label'=>'banned/blocked script',
                        'link'=>'trading/blocked-script',
                        'icon'=>'circle',
                    ],
                    [
                        'label'=>'margin management',
                        'link'=>'trading/margin-management',
                        'icon'=>'circle',
                    ],
                    [
                        'label'=>'manual trade',
                        'link'=>'trading/manual-trade',
                        'icon'=>'circle',
                    ],
                    [
                        'label'=>'summery report',
                        'link'=>'trading/summery-report',
                        'icon'=>'circle',
                    ],
                    [
                        'label'=>'self P&L',
                        'link'=>'trading/self-profit-loss',
                        'icon'=>'circle',
                    ],
                    [
                        'label'=>'brokerage refresh',
                        'link'=>'trading/brokerage-refresh',
                        'icon'=>'circle',
                    ]
                ]
            ]
        ];
        return $data;
    }
}
