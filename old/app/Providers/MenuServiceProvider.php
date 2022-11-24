<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {   // get all data from menu.json file


        view()->composer('*', function ($view) 
        {   

            $role = 0;
            $verticalMenuData = "";
            if (!empty(session('role'))){
                $role =  session('role');
            }
            $verticalMenuJson = '';
            $verticalMenuData = '';
            if ($role == 0){
                $verticalMenuJson = file_get_contents(base_path('resources/data/menus/superadmin-menu.json'));
                $verticalMenuData = json_decode($verticalMenuJson);
            }   else if ($role == 1){
                $verticalMenuJson = file_get_contents(base_path('resources/data/menus/admin-menu.json'));
                $verticalMenuData = json_decode($verticalMenuJson);
            }   else if ($role == 2){
                $verticalMenuJson = file_get_contents(base_path('resources/data/menus/budget_officer-menu.json'));
                $verticalMenuData = json_decode($verticalMenuJson);
            }   else if ($role == 3){
                $verticalMenuJson = file_get_contents(base_path('resources/data/menus/canvasser-menu.json'));
                $verticalMenuData = json_decode($verticalMenuJson);
            }   else if ($role == 4){
                $verticalMenuJson = file_get_contents(base_path('resources/data/menus/department-menu.json'));
                $verticalMenuData = json_decode($verticalMenuJson);
            }   else if ($role == 5){
                $verticalMenuJson = file_get_contents(base_path('resources/data/menus/supply_officer-menu.json'));
                $verticalMenuData = json_decode($verticalMenuJson);
            }  else if ($role == 6){
                $verticalMenuJson = file_get_contents(base_path('resources/data/menus/supply_custodian-menu.json'));
                $verticalMenuData = json_decode($verticalMenuJson);
            }   else if ($role == 7){
                $verticalMenuJson = file_get_contents(base_path('resources/data/menus/procurement_officer-menu.json'));
                $verticalMenuData = json_decode($verticalMenuJson);
            }   elseif($role == 8){      
                $verticalMenuJson = file_get_contents(base_path('resources/data/menus/employee-menu.json'));
                $verticalMenuData = json_decode($verticalMenuJson);
            }   elseif($role == 9){      
                $verticalMenuJson = file_get_contents(base_path('resources/data/menus/supplier-menu.json'));
                $verticalMenuData = json_decode($verticalMenuJson);
            }   elseif($role == 10){      
                $verticalMenuJson = file_get_contents(base_path('resources/data/menus/bac_secretariat-menu.json'));
                $verticalMenuData = json_decode($verticalMenuJson);
            }   elseif($role == 11){      
                $verticalMenuJson = file_get_contents(base_path('resources/data/menus/supervisor-menu.json'));
                $verticalMenuData = json_decode($verticalMenuJson);
            }   

            $horizontalMenuJson = file_get_contents(base_path('resources/data/menus/horizontal-menu.json'));
            $horizontalMenuData = json_decode($horizontalMenuJson);
            $verticalMenuBoxiconsJson = file_get_contents(base_path('resources/data/menus/vertical-menu-boxicons.json'));
            $verticalMenuBoxiconsData = json_decode($verticalMenuBoxiconsJson);
            // $verticalOverlayMenu = file_get_contents(base_path('resources/data/menus/vertical-overlay-menu.json'));
            // $verticalOverlayMenuData = json_decode($verticalOverlayMenu);

            // share all menuData to all the views
            \View::share('menuData',[$verticalMenuData, $horizontalMenuData,$verticalMenuBoxiconsData]);
        });      
        // $verticalOverlayMenu = file_get_contents(base_path('resources/data/menus/vertical-overlay-menu.json'));
        // $verticalOverlayMenuData = json_decode($verticalOverlayMenu);

        // share all menuData to all the views
      
    }
}
