<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AppsController;
use App\Http\Controllers\UserInterfaceController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\ComponentsController;
use App\Http\Controllers\ExtensionController;
use App\Http\Controllers\PageLayoutController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\MiscellaneousController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ChartsController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TradingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HelperController;

use App\Http\Controllers\settings\BlockAllowScriptsController;
use App\Http\Controllers\settings\ExpiryController;
use App\Http\Controllers\settings\LiveTvController;
use App\Http\Controllers\settings\MaxQuantityController as SettingsMaxQuantityController;
use App\Http\Controllers\settings\ScriptController as SettingsScriptController;
use App\Http\Controllers\settings\TimeSettingController;
use App\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest:admin'])->group(function () {
    // Main Page Route
    Route::get('/', [AuthController::class, 'index'])->name('auth-login');

    Route::get('login', [AuthController::class, 'index'])->name('auth-login');
    
    Route::get('{is_adminLogin?}/login', [AuthController::class, 'index'])->where(['is_adminLogin' => 'my-login-admin'])->name('auth-login');

    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'ShowRegistration'])->name('showuserregister');
    Route::post('/register', [AuthController::class, 'Registration'])->name('userregister');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Route::get('dashboard', [DashboardController::class, 'dashboardAnalytics'])->name('admin.dashboard');
Route::middleware(['auth:admin','role:admin|master|user'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'dashboardAnalytics'])->name('admin.dashboard');
    
    /* Route Trading */
    Route::group(['prefix' => 'trading'], function () {       

        Route::group(['prefix' => 'watchlist'], function () {
            Route::get('', [TradingController::class, 'watchList'])->name('view.watchlist');
            Route::post('getwatchlist-filter-values', [TradingController::class, 'getWatchlistFilterValues'])->name('get.WatchlistFilterValues');
        });

        Route::get('traders', [TradingController::class, 'traders'])->name('traders');
        Route::get('portfolio', [TradingController::class, 'portfolio'])->name('portfolio');
        Route::get('blocked-script', [TradingController::class, 'blockedScript'])->name('blocked-script');
        Route::get('margin-management', [TradingController::class, 'marginManagement'])->name('margin-management');
        Route::get('manual-trade', [TradingController::class, 'manualTrade'])->name('manual-trade');
        Route::get('summery-report', [TradingController::class, 'summeryReport'])->name('summery-report');
        Route::get('self-profit-loss', [TradingController::class, 'selfProfitLoss'])->name('self-profit-loss');
        Route::get('brokerage-refresh', [TradingController::class, 'brokerageRefresh'])->name('brokerage-refresh');
        Route::post('watchlist-save', [TradingController::class, 'saveWatchList'])->name('save.watchlist');
        Route::delete('watchlist-remove/{id}', [TradingController::class, 'removewatchlist'])->name('remove.watchlist');
        Route::post('get-watchlist-data', [TradingController::class, 'getwatchlistdata'])->name('get.watchlist.ajax');

        Route::post('store-trade', [TradingController::class, 'store_trade'])->name('save.trade');
    });    
    /* Route Trading */


    /* Route Wallet */
    Route::get('wallet', [WalletController::class, 'wallet'])->name('wallet.view');
    Route::get('/walletpaginate', [WalletController::class, 'wallet_paginate_data'])->name('wallet_paginate_data');
    Route::post('/walletstore', [WalletController::class, 'wallet_store'])->name('wallet.store');

    /* Route Trading */

     /* Profile Pages */
     Route::group(['prefix' => 'profile'], function () {
        Route::get('', [SettingsController::class, 'profile'])->name('profile.edit');
        Route::post('', [SettingsController::class, 'profile_store'])->name('profile.store');
        Route::post('password-update', [SettingsController::class, 'security_setting_store'])->name('password.update');
    });

});
Route::middleware(['auth:admin','role:admin|master'])->group(function () {

    // Route::get('dashboard', [DashboardController::class, 'dashboardAnalytics'])->name('admin.dashboard');
    Route::get('getbrokerlist', [UserController::class, 'getBrokerlist'])->name('getbrokerlist');
    Route::get('getRoleWiseUserlist', [UserController::class, 'getRoleWiseUserlist'])->name('getRoleWiseUserlist');

    /* PanelSettings Pages */
    Route::group(['prefix' => 'panel-settings','middleware' => ['role:admin']], function () {
        Route::get('{panelsettings}', [SettingsController::class, 'panel_settings'])->whereIn('panelsettings', 
        ['panel-settings','mail-settings','design-settings','security-settings'
        // ,'billing-settings','notifications-settings','connections-settings','design-settings'
        ])->name('panel-settings');

        /* Settings Pages Store*/
        Route::post('panel-settings', [SettingsController::class, 'site_setting_store'])->name('store.panel-settings');  
        Route::post('security-settings', [SettingsController::class, 'security_setting_store'])->name('store.security-settings');
        Route::post('mail-settings', [SettingsController::class, 'mail_setting_store'])->name('store.mail-settings');
        Route::post('design-settings', [SettingsController::class, 'design_setting_store'])->name('store.design-settings');      
    });
   /* Settings Pages */
    
    /* Route User */
    // Route::get('user', [UserController::class, 'index']);
    Route::get('user/{type?}', [UserController::class, 'index'])->name('view.user');
    Route::get('create-user', [UserController::class, 'form'])->name('create.user');
    Route::get('edit-user/{id}', [UserController::class, 'form'])->name('edit.user');
    Route::post('user-store', [UserController::class, 'StoreUsers'])->name('store.user');
    Route::get('getUserList', [UserController::class, 'getUserList'])->name('getUserList');
    Route::post('reset-password/{id}', [UserController::class, 'userPasswordReset'])->name('resetpassword.user');
    Route::post('clearLoginAttempts/{code}', [UserController::class, 'clearLoginAttempts'])->name('clearloginlttempts.user');
    
    /* Route User */

    /* Route Settings */
    Route::group(['prefix' => 'settings'], function () {

        Route::get('script', [SettingsScriptController::class, 'viewscript'])->name('view.script');
        Route::get('addscript', [SettingsScriptController::class, 'addscript'])->name('addscriptform');

        Route::post('addscript', [SettingsScriptController::class, 'script_store'])->name('addscript');
        
        Route::get('search', [SettingsScriptController::class, 'search'])->name('search');
        Route::get('/scriptpaginationdata', [SettingsScriptController::class, 'script_paginate_data'])->name('script_paginate_data');
        Route::post('fetch-script-data', [SettingsScriptController::class, 'fetchScriptData'])->name('fetch-script-data');
       
        // Ban Script
        Route::get('banscript', [SettingsScriptController::class, 'banscript'])->name('banscript');
        Route::patch('unbanscript/{id?}', [SettingsScriptController::class, 'unbanscript'])->name('unbanscript');
        Route::post('banscript/{id?}', [SettingsScriptController::class, 'banscript_action'])->name('banscript_action');
        Route::get('searchbanscript', [SettingsScriptController::class, 'searchbanscript'])->name('searchbanscript');
        Route::get('/banscriptpagination', [SettingsScriptController::class, 'banscript_paginate_data'])->name('banscript_paginate_data');
        Route::post('fetch-banscript-data', [SettingsScriptController::class, 'fetchbanScriptData'])->name('fetch-banscript-data');

        // Import script
        Route::post('/script-import', [SettingsScriptController::class, 'importScript'])->name('import.script');
        // Import ban script
        Route::post('/banscript-import', [SettingsScriptController::class, 'importBanScript'])->name('import.banscript');
        
        Route::get('/script-import-status', [SettingsScriptController::class, 'scriptImportStatus'])->name('import.script-status');
        
        // Expiry        
        Route::post('addexpiry', [ExpiryController::class, 'expiry_store'])->name('addexpiry');
        Route::get('viewexpiry', [ExpiryController::class, 'viewexpiry'])->name('viewexpiry');
        Route::get('getscript', [ExpiryController::class, 'getscript'])->name('getscript');
        Route::get('editexpiry/{id?}', [ExpiryController::class, 'editexpiry'])->name('editexpiry');
        Route::delete('deleteexpiry/{id?}', [ExpiryController::class, 'deleteexpiry'])->name('deleteexpiry');
        Route::get('searchexpiry', [ExpiryController::class, 'searchexpiry'])->name('searchexpiry');
        Route::get('/expirypaginationdata', [ExpiryController::class, 'expiry_paginate_data'])->name('expiry_paginate_data');
        Route::get('paginate', [ExpiryController::class, 'paginate'])->name('paginate');
        Route::post('fetch-expiry-data', [ExpiryController::class, 'fetchexpirydata'])->name('fetch-expiry-data');

        // Live Tv        
        Route::get('livetv', [LiveTvController::class, 'livetv'])->name('livetv');
        Route::post('livetvstore', [LiveTvController::class, 'livetv_store'])->name('livetvstore');
        Route::get('/livetvpaginate', [LiveTvController::class, 'livetv_paginate_data'])->name('livetv_paginate_data');
        Route::get('editlivetv/{id?}', [LiveTvController::class, 'editlivetv'])->name('editlivetv');
        Route::delete('deletelivetv/{id?}', [LiveTvController::class, 'deletelivetv'])->name('deletelivetv');

        // Max Quantity
        Route::post('max_quantity', [SettingsMaxQuantityController::class, 'max_quantity_store'])->name('max_quantity_store');
        Route::get('max_quantity', [SettingsMaxQuantityController::class, 'view_max_quantity'])->name('view.max_quantity');
            Route::get('search_max_quantity', [SettingsMaxQuantityController::class, 'search_max_quantity'])->name('search_max_quantity');
        Route::get('maxquantitypaginationdata', [SettingsMaxQuantityController::class, 'max_quantity_paginate_data'])->name('max_quantity_paginate_data');
        Route::get('paginate', [SettingsMaxQuantityController::class, 'paginate'])->name('maxpaginate');
        Route::post('fetch-max-quantity-data', [SettingsMaxQuantityController::class, 'fetchmaxquantitydata'])->name('fetch-max-quantity-data');
        // Level Import
        Route::post('/max_quantity/import', [SettingsMaxQuantityController::class, 'import'])->name('max_quantity_import');

        // Block Allow Scripts 
        Route::get('block_master_script', [BlockAllowScriptsController::class, 'view_block_master_script'])->name('view.block_master_script');
        Route::get('blockmasterscriptpaginationdata', [BlockAllowScriptsController::class, 'block_master_script_paginate_data'])->name('block_master_script_paginate_data');
        Route::post('block_master_script', [BlockAllowScriptsController::class, 'block_allow_script_store'])->name('block_allow_script_store');
        Route::delete('deleteallowscript/{id?}', [BlockAllowScriptsController::class, 'deleteallowscript'])->name('deleteallowscript');

        // Time Setting
        Route::get('time_setting', [TimeSettingController::class, 'view_time_setting'])->name('view.time_setting');
        Route::get('time_setting_paginate_data', [TimeSettingController::class, 'time_setting_paginate_data'])->name('time_setting_paginate_data');
        Route::post('time_setting', [TimeSettingController::class, 'time_setting_store'])->name('time_setting_store');
        Route::delete('deletetime/{id?}', [TimeSettingController::class, 'deletetime'])->name('deletetime');
        });



    Route::controller(HelperController::class)->group(function () {
        Route::group(['prefix' => 'helper'], function () {
            Route::post('/change-stautus/{table_name}/{id}/{status_column_name?}', 'changeStatus')->name('helper.changeStatus');
            Route::post('/delete-record/{table_name}/{id}', 'deleteRecord')->name('helper.deleteRecord');                        
            Route::post('/getRecord/{table_name}/{id}', 'getRecord')->name('helper.getRecord');            
            Route::post('/getmarket-to-scripts', 'getMarketToScripts')->name('helper.getMarkrtToScripts');            
                       
        });
    });

});

Route::get('/helper/get-trading-extensions', [HelperController::class,'getTradingExtension'])->name('helper.getTradingExtension'); 


// Route::middleware(['auth:admin', 'role:admin'])->group(['prefix' => 'settings'], function () {
//     Route::get('settings-site', [SettingsController::class, 'site_settings'])->name('settings-site');
// });


// Route::middleware(['auth', 'role:admin'])->group(function () {
//     // Admin routes
// });

// Route::middleware(['auth', 'role:master'])->group(function () {
//     // Master routes
// });

// Route::middleware(['auth', 'role:broker'])->group(function () {
//     // Broker routes
// });


/* Route Dashboards */
Route::group(['prefix' => 'dashboard'], function () {
    Route::get('analytics', [DashboardController::class, 'dashboardAnalytics'])->name('dashboard-analytics');
    Route::get('ecommerce', [DashboardController::class, 'dashboardEcommerce'])->name('dashboard-ecommerce');
});
/* Route Dashboards */

/* Route Apps */
Route::group(['prefix' => 'app'], function () {
    Route::get('email', [AppsController::class, 'emailApp'])->name('app-email');
    Route::get('chat', [AppsController::class, 'chatApp'])->name('app-chat');
    Route::get('todo', [AppsController::class, 'todoApp'])->name('app-todo');
    Route::get('calendar', [AppsController::class, 'calendarApp'])->name('app-calendar');
    Route::get('kanban', [AppsController::class, 'kanbanApp'])->name('app-kanban');
    Route::get('invoice/list', [AppsController::class, 'invoice_list'])->name('app-invoice-list');
    Route::get('invoice/preview', [AppsController::class, 'invoice_preview'])->name('app-invoice-preview');
    Route::get('invoice/edit', [AppsController::class, 'invoice_edit'])->name('app-invoice-edit');
    Route::get('invoice/add', [AppsController::class, 'invoice_add'])->name('app-invoice-add');
    Route::get('invoice/print', [AppsController::class, 'invoice_print'])->name('app-invoice-print');
    Route::get('ecommerce/shop', [AppsController::class, 'ecommerce_shop'])->name('app-ecommerce-shop');
    Route::get('ecommerce/details', [AppsController::class, 'ecommerce_details'])->name('app-ecommerce-details');
    Route::get('ecommerce/wishlist', [AppsController::class, 'ecommerce_wishlist'])->name('app-ecommerce-wishlist');
    Route::get('ecommerce/checkout', [AppsController::class, 'ecommerce_checkout'])->name('app-ecommerce-checkout');
    Route::get('file-manager', [AppsController::class, 'file_manager'])->name('app-file-manager');
    Route::get('access-roles', [AppsController::class, 'access_roles'])->name('app-access-roles');
    Route::get('access-permission', [AppsController::class, 'access_permission'])->name('app-access-permission');
    Route::get('user/list', [AppsController::class, 'user_list'])->name('app-user-list');
    Route::get('user/view/account', [AppsController::class, 'user_view_account'])->name('app-user-view-account');
    Route::get('user/view/security', [AppsController::class, 'user_view_security'])->name('app-user-view-security');
    Route::get('user/view/billing', [AppsController::class, 'user_view_billing'])->name('app-user-view-billing');
    Route::get('user/view/notifications', [AppsController::class, 'user_view_notifications'])->name('app-user-view-notifications');
    Route::get('user/view/connections', [AppsController::class, 'user_view_connections'])->name('app-user-view-connections');
});
/* Route Apps */

/* Route UI */
Route::group(['prefix' => 'ui'], function () {
    Route::get('typography', [UserInterfaceController::class, 'typography'])->name('ui-typography');
});
/* Route UI */

/* Route Icons */
Route::group(['prefix' => 'icons'], function () {
    Route::get('feather', [UserInterfaceController::class, 'icons_feather'])->name('icons-feather');
});
/* Route Icons */

/* Route Cards */
Route::group(['prefix' => 'card'], function () {
    Route::get('basic', [CardsController::class, 'card_basic'])->name('card-basic');
    Route::get('advance', [CardsController::class, 'card_advance'])->name('card-advance');
    Route::get('statistics', [CardsController::class, 'card_statistics'])->name('card-statistics');
    Route::get('analytics', [CardsController::class, 'card_analytics'])->name('card-analytics');
    Route::get('actions', [CardsController::class, 'card_actions'])->name('card-actions');
});
/* Route Cards */

/* Route Components */
Route::group(['prefix' => 'component'], function () {
    Route::get('accordion', [ComponentsController::class, 'accordion'])->name('component-accordion');
    Route::get('alert', [ComponentsController::class, 'alert'])->name('component-alert');
    Route::get('avatar', [ComponentsController::class, 'avatar'])->name('component-avatar');
    Route::get('badges', [ComponentsController::class, 'badges'])->name('component-badges');
    Route::get('breadcrumbs', [ComponentsController::class, 'breadcrumbs'])->name('component-breadcrumbs');
    Route::get('buttons', [ComponentsController::class, 'buttons'])->name('component-buttons');
    Route::get('carousel', [ComponentsController::class, 'carousel'])->name('component-carousel');
    Route::get('collapse', [ComponentsController::class, 'collapse'])->name('component-collapse');
    Route::get('divider', [ComponentsController::class, 'divider'])->name('component-divider');
    Route::get('dropdowns', [ComponentsController::class, 'dropdowns'])->name('component-dropdowns');
    Route::get('list-group', [ComponentsController::class, 'list_group'])->name('component-list-group');
    Route::get('modals', [ComponentsController::class, 'modals'])->name('component-modals');
    Route::get('pagination', [ComponentsController::class, 'pagination'])->name('component-pagination');
    Route::get('navs', [ComponentsController::class, 'navs'])->name('component-navs');
    Route::get('offcanvas', [ComponentsController::class, 'offcanvas'])->name('component-offcanvas');
    Route::get('tabs', [ComponentsController::class, 'tabs'])->name('component-tabs');
    Route::get('timeline', [ComponentsController::class, 'timeline'])->name('component-timeline');
    Route::get('pills', [ComponentsController::class, 'pills'])->name('component-pills');
    Route::get('tooltips', [ComponentsController::class, 'tooltips'])->name('component-tooltips');
    Route::get('popovers', [ComponentsController::class, 'popovers'])->name('component-popovers');
    Route::get('pill-badges', [ComponentsController::class, 'pill_badges'])->name('component-pill-badges');
    Route::get('progress', [ComponentsController::class, 'progress'])->name('component-progress');
    Route::get('spinner', [ComponentsController::class, 'spinner'])->name('component-spinner');
    Route::get('toast', [ComponentsController::class, 'toast'])->name('component-bs-toast');
});
/* Route Components */

/* Route Extensions */
Route::group(['prefix' => 'ext-component'], function () {
    Route::get('sweet-alerts', [ExtensionController::class, 'sweet_alert'])->name('ext-component-sweet-alerts');
    Route::get('block-ui', [ExtensionController::class, 'block_ui'])->name('ext-component-block-ui');
    Route::get('toastr', [ExtensionController::class, 'toastr'])->name('ext-component-toastr');
    Route::get('sliders', [ExtensionController::class, 'sliders'])->name('ext-component-sliders');
    Route::get('drag-drop', [ExtensionController::class, 'drag_drop'])->name('ext-component-drag-drop');
    Route::get('tour', [ExtensionController::class, 'tour'])->name('ext-component-tour');
    Route::get('clipboard', [ExtensionController::class, 'clipboard'])->name('ext-component-clipboard');
    Route::get('plyr', [ExtensionController::class, 'plyr'])->name('ext-component-plyr');
    Route::get('context-menu', [ExtensionController::class, 'context_menu'])->name('ext-component-context-menu');
    Route::get('swiper', [ExtensionController::class, 'swiper'])->name('ext-component-swiper');
    Route::get('tree', [ExtensionController::class, 'tree'])->name('ext-component-tree');
    Route::get('ratings', [ExtensionController::class, 'ratings'])->name('ext-component-ratings');
    Route::get('locale', [ExtensionController::class, 'locale'])->name('ext-component-locale');
});
/* Route Extensions */

/* Route Page Layouts */
Route::group(['prefix' => 'page-layouts'], function () {
    Route::get('collapsed-menu', [PageLayoutController::class, 'layout_collapsed_menu'])->name('layout-collapsed-menu');
    Route::get('full', [PageLayoutController::class, 'layout_full'])->name('layout-full');
    Route::get('without-menu', [PageLayoutController::class, 'layout_without_menu'])->name('layout-without-menu');
    Route::get('empty', [PageLayoutController::class, 'layout_empty'])->name('layout-empty');
    Route::get('blank', [PageLayoutController::class, 'layout_blank'])->name('layout-blank');
});
/* Route Page Layouts */

/* Route Forms */
Route::group(['prefix' => 'form'], function () {
    Route::get('input', [FormsController::class, 'input'])->name('form-input');
    Route::get('input-groups', [FormsController::class, 'input_groups'])->name('form-input-groups');
    Route::get('input-mask', [FormsController::class, 'input_mask'])->name('form-input-mask');
    Route::get('textarea', [FormsController::class, 'textarea'])->name('form-textarea');
    Route::get('checkbox', [FormsController::class, 'checkbox'])->name('form-checkbox');
    Route::get('radio', [FormsController::class, 'radio'])->name('form-radio');
    Route::get('custom-options', [FormsController::class, 'custom_options'])->name('form-custom-options');
    Route::get('switch', [FormsController::class, 'switch'])->name('form-switch');
    Route::get('select', [FormsController::class, 'select'])->name('form-select');
    Route::get('number-input', [FormsController::class, 'number_input'])->name('form-number-input');
    Route::get('file-uploader', [FormsController::class, 'file_uploader'])->name('form-file-uploader');
    Route::get('quill-editor', [FormsController::class, 'quill_editor'])->name('form-quill-editor');
    Route::get('date-time-picker', [FormsController::class, 'date_time_picker'])->name('form-date-time-picker');
    Route::get('layout', [FormsController::class, 'layouts'])->name('form-layout');
    Route::get('wizard', [FormsController::class, 'wizard'])->name('form-wizard');
    Route::get('validation', [FormsController::class, 'validation'])->name('form-validation');
    Route::get('repeater', [FormsController::class, 'form_repeater'])->name('form-repeater');
});
/* Route Forms */

/* Route Tables */
Route::group(['prefix' => 'table'], function () {
    Route::get('', [TableController::class, 'table'])->name('table');
    Route::get('datatable/basic', [TableController::class, 'datatable_basic'])->name('datatable-basic');
    Route::get('datatable/advance', [TableController::class, 'datatable_advance'])->name('datatable-advance');
});
/* Route Tables */

/* Route Pages */
Route::group(['prefix' => 'page'], function () {
    Route::get('account-settings-account', [PagesController::class, 'account_settings_account'])->name('page-account-settings-account');
    Route::get('account-settings-security', [PagesController::class, 'account_settings_security'])->name('page-account-settings-security');
    Route::get('account-settings-billing', [PagesController::class, 'account_settings_billing'])->name('page-account-settings-billing');
    Route::get('account-settings-notifications', [PagesController::class, 'account_settings_notifications'])->name('page-account-settings-notifications');
    Route::get('account-settings-connections', [PagesController::class, 'account_settings_connections'])->name('page-account-settings-connections');
    Route::get('profile', [PagesController::class, 'profile'])->name('page-profile');
    Route::get('faq', [PagesController::class, 'faq'])->name('page-faq');
    Route::get('knowledge-base', [PagesController::class, 'knowledge_base'])->name('page-knowledge-base');
    Route::get('knowledge-base/category', [PagesController::class, 'kb_category'])->name('page-knowledge-base');
    Route::get('knowledge-base/category/question', [PagesController::class, 'kb_question'])->name('page-knowledge-base');
    Route::get('pricing', [PagesController::class, 'pricing'])->name('page-pricing');
    Route::get('api-key', [PagesController::class, 'api_key'])->name('page-api-key');
    Route::get('blog/list', [PagesController::class, 'blog_list'])->name('page-blog-list');
    Route::get('blog/detail', [PagesController::class, 'blog_detail'])->name('page-blog-detail');
    Route::get('blog/edit', [PagesController::class, 'blog_edit'])->name('page-blog-edit');

    // Miscellaneous Pages With Page Prefix
    Route::get('coming-soon', [MiscellaneousController::class, 'coming_soon'])->name('misc-coming-soon');
    Route::get('not-authorized', [MiscellaneousController::class, 'not_authorized'])->name('misc-not-authorized');
    Route::get('maintenance', [MiscellaneousController::class, 'maintenance'])->name('misc-maintenance');
    Route::get('license', [PagesController::class, 'license'])->name('page-license');
});

/* Modal Examples */
Route::get('/modal-examples', [PagesController::class, 'modal_examples'])->name('modal-examples');

/* Route Pages */
Route::get('/error', [MiscellaneousController::class, 'error'])->name('error');

/* Route Authentication Pages */
Route::group(['prefix' => 'auth'], function () {
    Route::get('login-basic', [AuthenticationController::class, 'login_basic'])->name('auth-login-basic');
    Route::get('login-cover', [AuthenticationController::class, 'login_cover'])->name('auth-login-cover');
    Route::get('register-basic', [AuthenticationController::class, 'register_basic'])->name('auth-register-basic');
    Route::get('register-cover', [AuthenticationController::class, 'register_cover'])->name('auth-register-cover');
    Route::get('forgot-password-basic', [AuthenticationController::class, 'forgot_password_basic'])->name('auth-forgot-password-basic');
    Route::get('forgot-password-cover', [AuthenticationController::class, 'forgot_password_cover'])->name('auth-forgot-password-cover');
    Route::get('reset-password-basic', [AuthenticationController::class, 'reset_password_basic'])->name('auth-reset-password-basic');
    Route::get('reset-password-cover', [AuthenticationController::class, 'reset_password_cover'])->name('auth-reset-password-cover');
    Route::get('verify-email-basic', [AuthenticationController::class, 'verify_email_basic'])->name('auth-verify-email-basic');
    Route::get('verify-email-cover', [AuthenticationController::class, 'verify_email_cover'])->name('auth-verify-email-cover');
    Route::get('two-steps-basic', [AuthenticationController::class, 'two_steps_basic'])->name('auth-two-steps-basic');
    Route::get('two-steps-cover', [AuthenticationController::class, 'two_steps_cover'])->name('auth-two-steps-cover');
    Route::get('register-multisteps', [AuthenticationController::class, 'register_multi_steps'])->name('auth-register-multisteps');
    Route::get('lock-screen', [AuthenticationController::class, 'lock_screen'])->name('auth-lock_screen');
});
/* Route Authentication Pages */

/* Route Charts */
Route::group(['prefix' => 'chart'], function () {
    Route::get('apex', [ChartsController::class, 'apex'])->name('chart-apex');
    Route::get('chartjs', [ChartsController::class, 'chartjs'])->name('chart-chartjs');
    Route::get('echarts', [ChartsController::class, 'echarts'])->name('chart-echarts');
});
/* Route Charts */

// map leaflet
Route::get('/maps/leaflet', [ChartsController::class, 'maps_leaflet'])->name('map-leaflet');

// locale Route
Route::get('lang/{locale}', [LanguageController::class, 'swap']);

