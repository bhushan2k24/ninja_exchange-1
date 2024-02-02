<?php
$assetUrl = config('app.asset_url');
$public_path = public_path('/');

// foreach (['_PATH'=>$public_path,'_URL'=>$assetUrl] as $key => $value)
// {
//     // Access the directory Path and Url using CONST_PATH OR CONST_URL constants
//     define("USER".$key, $value.'upload/user/');
// }

define("URL", $assetUrl);
define("PATH",$public_path);
define("USER",'upload/user/');


