<?php

namespace Syahrulyusuf97\Larator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class CreateAccess extends Controller
{
    public static function store()
    {
    	$results = false;
        $header_access 	= self::headerAccess();
        $access 		= self::access();

        if ($header_access === "true" && $access === "true") {
			$results = "Table access was successfully created";
		} else if ($header_access != "true") {
			$results = "Failed to create a table header_access\n".
				   "Error to create a table header_access => ".$header_access;
		} else if ($access != "true") {
			$results = "Failed to create a table access\n".
				   "Error to create a table access => ".$access;
		}
        return $results;
    }

    public static function headerAccess()
    {
    	DB::beginTransaction();
        try{
            DB::table('header_access')->truncate();

            $data = [
            	[
            		'id' 		=> 1,
	            	'name' 		=> "MAIN NAVIGATION",
	            	'order'		=> 1
            	],
            	[
            		'id' 		=> 2,
	            	'name' 		=> "USERS",
	            	'order'		=> 2
            	],
            	[
            		'id' 		=> 3,
	            	'name' 		=> "MENU",
	            	'order'		=> 3
            	]
            ];

            DB::table('header_access')->insert($data);

            DB::commit();
            return "true";
        }catch (\Exception $e){
            DB::rollback();
            return $e;
        }
    }

    public static function access()
    {
        DB::beginTransaction();
        try{
            $data = [
            	[
            		'id' 		=> 1,
	            	'name' 		=> "Dashboard",
	            	'header'	=> 1,
	            	'link' 		=> "/dashboard",
	            	'icon' 		=> "fa fa-dashboard",
	            	'parent' 	=> 0,
	            	'order' 	=> 1,
	            	'active' 	=> "Y"
            	],
            	[
            		'id' 		=> 2,
	            	'name' 		=> "Profile",
	            	'header'	=> 2,
	            	'link' 		=> "/profile",
	            	'icon' 		=> "fa fa-user",
	            	'parent' 	=> 0,
	            	'order' 	=> 2,
	            	'active' 	=> "Y"
            	],
            	[
            		'id' 		=> 3,
	            	'name' 		=> "Management Users",
	            	'header'	=> 2,
	            	'link' 		=> "/management-user",
	            	'icon' 		=> "fa fa-users",
	            	'parent' 	=> 0,
	            	'order' 	=> 3,
	            	'active' 	=> "Y"
            	],
            	[
            		'id' 		=> 4,
	            	'name' 		=> "Log Activity",
	            	'header'	=> 2,
	            	'link' 		=> "/log-activity",
	            	'icon' 		=> "fa fa-clock-o",
	            	'parent' 	=> 0,
	            	'order' 	=> 4,
	            	'active' 	=> "Y"
            	],
            	[
            		'id' 		=> 5,
	            	'name' 		=> "Management Menu",
	            	'header'	=> 3,
	            	'link' 		=> "/menu",
	            	'icon' 		=> "fa fa-book",
	            	'parent' 	=> 0,
	            	'order' 	=> 5,
	            	'active' 	=> "Y"
            	]
            ];

            DB::table('access')->insert($data);

            DB::commit();
            return "true";
        }catch (\Exception $e){
            DB::rollback();
            return $e;
        }
    }
}