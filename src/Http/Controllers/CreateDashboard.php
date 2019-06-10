<?php
namespace App\Console\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class CreateDashboard extends Controller
{

    public static function store()
    {
        $dir_controllers = "app/Http/Controllers";
        $dir_views		 = "resources/views";

		try{
			$makeSign 		 = self::makeSign($dir_controllers, $dir_views);
			$makeDashboard 	 = self::makeDashboard($dir_controllers, $dir_views);
			$makeRoutes 	 = self::makeRoutes();

			if ($makeSign === true && $makeDashboard === true && $makeRoutes === true) {
				$results = "The dashboard was successfully created";
			} else {
				if ($makeSign != true) {
					$results = "Failed to create a dashboard\n".
						   "Error to make sign => ".$makeSign;
				} else if ($makeDashboard != true) {
					$results = "Failed to create a dashboard\n".
						   "Error to make dashboard => ".$makeDashboard;
				} else if ($makeRoutes != true) {
					$results = "Failed to create a dashboard\n".
						   "Error to make routes => ".$makeRoutes;
				}
			}
		}catch (Exception $e){
			$results =  "Failed to create a dashboard\n".
						"Error exception => ".$e;
		}
		return $results;
    }

    private static function makeSign(String $dir_controllers, String $dir_views)
    {
		$dir_sign_controller  		= $dir_controllers . "/Sign";
		$file_sign_controller 		= $dir_sign_controller . '/SignController.php';
		$file_activity_controller 	= $dir_sign_controller . '/ActivityController.php';
		$activity_model				= "app/Activity.php";

		$dir_layouts				= $dir_views . "/layouts";
		$dir_sign_layout			= $dir_layouts . "/sign";
		$file_sign_layout			= $dir_sign_layout . "/login.blade.php";
		$dir_sign_view				= $dir_views . "/sign";
		$file_sign_view				= $dir_sign_view . "/login.blade.php";

		try{

			if (!file_exists($activity_model)) {
				$content_activity_model = "<?php \n".
				"namespace App;\n".
				"use Illuminate\Database\Eloquent\Model;\n".
				"class Activity extends Model\n".
				"{\n".
				"	protected $"."table       = 'log_activity';\n".
				"	protected $"."primaryKey  = 'id';\n".
				"	protected $"."fillable    = ['id','iduser', 'activity', 'date'];\n".
				"}";

				$file = fopen($activity_model,"w");

				fwrite($file, $content_activity_model);

				fclose($file);
			}
			
			if(!is_dir($dir_sign_controller))
			{
			    mkdir($dir_sign_controller, 0777, true);
			}

			if (!file_exists($file_activity_controller)) {
				$content_activity = "<?php \n".
				"namespace App\Http\Controllers\Sign; \n".
				"use Illuminate\Http\Request; \n".
				"use App\Http\Controllers\Controller; \n".
				"use App\Activity; \n".
				"use DB; \n".
				"class ActivityController extends Controller \n".
				"{ \n".
				"	public static function log($"."user, $"."note, $"."date) { \n".
				"		DB::beginTransaction(); \n".
				"		try{ \n".
				"			$"."activity = new Activity(); \n".
				"			$"."activity->iduser = $"."user; \n".
				"			$"."activity->activity = $"."note; \n".
				"			$"."activity->date = $"."date; \n".
				"			$"."activity->save(); \n".
				"			DB::commit(); \n".
				"			return true; \n".
				"		}catch (\Exception $"."e){ \n".
				"			DB::rollback(); \n".
				"			return $"."e; \n".
				"		} \n".
				"	} \n".
				"}";

				$file = fopen($file_activity_controller,"w");

				fwrite($file, $content_activity);

				fclose($file);
			}

			if (!file_exists($file_sign_controller))  
			{ 
				$content_sign = "<?php \n".
				"namespace App\Http\Controllers\Sign; \n".
				"use Illuminate\Http\Request; \n".
				"use Auth; \n".
				"use Session; \n".
				"use App\User; \n".
				"use DB; \n".
				"use Illuminate\Support\Facades\Hash; \n".
				"use App\Http\Controllers\Controller; \n".
				"use App\Http\Controllers\Sign\ActivityController as Activity; \n".
				"use Carbon\Carbon; \n".
				"class SignController extends Controller \n".
				"{ \n".
				"	public function login(Request $"."request) { \n".
				"		if (Session::has('adminSession')) { \n".
				"			return redirect('/dashboard'); \n".
				"		} \n".
				"		if ($"."request->isMethod('post')) { \n".
				"			$"."data = $"."request->input(); \n".
				"			if (Auth::attempt(['username'=>$"."data['username'], 'password'=>$"."data['password']])) { \n".
				"				$"."level = DB::table('level')->where('id', '=', Auth::user()->idlevel)->first()->name; \n".
				"				Session::put('adminSession', Auth::user()->email); \n".
				"				Session::put('adminName', Auth::user()->name); \n".
				"				Session::put('adminLevel', $"."level); \n".
				"				User::where('id', Auth::user()->id)->update([ \n".
				"					'lastlogin' => Carbon::now() \n".
				"				]); \n".
				"				Activity::log(Auth::user()->id, 'IP Address: '. $"."request->ip() . ' Device: '. $"."request->header('User-Agent') . 'Activity: Login', Carbon::now()); \n".
				"				return redirect('/dashboard'); \n".
				"			} else { \n".
				"				return redirect('/')->with('flash_message_error', 'Incorrect username or password'); \n".
				"			} \n".
				"		} \n".
				"		return view('sign.login'); \n".
				"	} \n".
				"	 \n".
				"	public function logout() { \n".
				"		User::where('id', Auth::user()->id)->update([ \n".
				"			'lastlogout' => Carbon::now() \n".
				"		]); \n".
				"		Activity::log(Auth::user()->id, 'IP Address: '. \Request::ip() . ' Device: '. \Request::header('User-Agent') . 'Activity: Logout', Carbon::now()); \n".
				"		Session::flush(); \n".
				"		Auth::logout(); \n".
				"		return redirect('/')->with('flash_message_success', 'Sign out successfully'); \n".
				"	} \n".
				"}";

				$file = fopen($file_sign_controller,"w");

				fwrite($file, $content_sign);

				fclose($file);
			}

			if(!is_dir($dir_layouts))
			{
			    mkdir($dir_layouts, 0777, true);
			}

			if(!is_dir($dir_sign_layout))
			{
			    mkdir($dir_sign_layout, 0777, true);
			}

			if (!file_exists($file_sign_layout)) {
				$content_sign_layout = "<!DOCTYPE html>\n".
				'<html lang="'.'{{ str_replace("_", "-", app()->getLocale()) }}"'.'>'."\n".
				"<head>\n".
				'	<meta charset="utf-8">'."\n".
				'	<meta http-equiv="X-UA-Compatible" content="IE=edge">'."\n".
				'	<meta name="viewport" content="width=device-width, initial-scale=1">'."\n".
				'	<link rel="icon" type="image/png" href="{{ asset("public/images/icon/book.png") }}" />'."\n".
				'	<title>MasterAPPS | @yield("title")</title>'."\n".
				'	<meta name="csrf-token" content="{{ csrf_token() }}">'."\n".
				'	<link rel="stylesheet" href="{{ asset("public/css/bootstrap/bootstrap.min.css") }}">'."\n".
				'	<link rel="stylesheet" href="{{ asset("public/css/font-awesome/css/font-awesome.min.css") }}">'."\n".
				'	<link rel="stylesheet" href="{{ asset("public/css/Ionicons/css/ionicons.min.css") }}">'."\n".
				'	<link rel="stylesheet" href="{{ asset("public/css/adminLTE/AdminLTE.min.css") }}">'."\n".
				'	<link rel="stylesheet" href="{{ asset("public/css/iCheck/square/blue.css") }}">'."\n".
				'	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">'."\n".
				"</head>\n".
				'<body class="hold-transition login-page">'."\n".
				'	@yield("content")'."\n".
				"</body>\n".
				"</html>";

				$file = fopen($file_sign_layout,"w");

				fwrite($file, $content_sign_layout);

				fclose($file);
			}

			if(!is_dir($dir_sign_view))
			{
			    mkdir($dir_sign_view, 0777, true);
			}

			if (!file_exists($file_sign_view)) {
				$content_sign_view = "@extends('layouts.sign.login')\n".
				"@section('title', 'Login')\n".
				"@section('content')\n".
				'<div class="login-box">'."\n".
				'	<div class="login-logo">'."\n".
				'		<a href="#">Master<b>APPS</b></a>'."\n".
				"	</div>\n".
				'	<div class="login-box-body">'."\n".
				'		<p class="login-box-msg">Sign in to start your session</p>'."\n".
				'		@if(Session::has("flash_message_error"))'."\n".
				'			<div class="alert alert-error alert-block">'."\n".
				'				<button type="button" class="close" data-dismiss="alert">&times;</button>'."\n".
				'				<strong>{!! session("flash_message_error") !!}</strong>'."\n".
				'			</div>'."\n".
				'		@endif'."\n".
				'		@if(Session::has("flash_message_success"))'."\n".
				'			<div class="alert alert-success alert-block">'."\n".
				'				<button type="button" class="close" data-dismiss="alert">&times;</button>'."\n".
				'				<strong>{!! session("flash_message_success") !!}</strong>'."\n".
				'			</div>'."\n".
				'		@endif'."\n".
				'		<form action="{{ url("/login") }}" method="post">{{ csrf_field() }}'."\n".
				'			<div class="form-group has-feedback">'."\n".
				'				<input type="text" name="username" id="username" class="form-control" autofocus placeholder="Username">'."\n".
				'				<span class="glyphicon glyphicon-user form-control-feedback"></span>'."\n".
				'			</div>'."\n".
				'			<div class="form-group has-feedback">'."\n".
				'				<input type="password" name="password" id="password" class="form-control" placeholder="Password">'."\n".
				'				<span class="glyphicon glyphicon-lock form-control-feedback"></span>'."\n".
				'			</div>'."\n".
				'			<div class="row">'."\n".
				'				<div class="col-xs-8">'."\n".
				'					<div class="checkbox icheck">'."\n".
				'						<label></label>'."\n".
				'					</div>'."\n".
				'				</div>'."\n".
				'				<div class="col-xs-4">'."\n".
				'					<button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>'."\n".
				'				</div>'."\n".
				'			</div>'."\n".
				'		</form>'."\n".
				"	</div>\n".
				"</div>\n".
				"\n".
				'<script src="{{ asset("public/js/jQuery/jquery.min.js") }}"></script>'."\n".
				'<script src="{{ asset("public/js/bootstrap/bootstrap.min.js") }}"></script>'."\n".
				"@endsection";

				$file = fopen($file_sign_view,"w");

				fwrite($file, $content_sign_view);

				fclose($file);
			}
			return true;
		}catch (Exception $e){
			return $e;
		}
    }

    private static function makeDashboard(String $dir_controllers, String $dir_views)
    {
    	$dir_dashboard_controller 	= $dir_controllers . "/Dashboard";
    	$file_dashboard_controller 	= $dir_dashboard_controller . '/DashboardController.php';

    	$dir_layouts				= $dir_views . "/layouts";
		$dir_dashboard_layout		= $dir_layouts . "/dashboard";
		$header_dashboard_layout	= $dir_dashboard_layout . "/header.blade.php";
		$content_dashboard_layout	= $dir_dashboard_layout . "/content.blade.php";
		$footer_dashboard_layout	= $dir_dashboard_layout . "/footer.blade.php";
		$sidebar_dashboard_layout	= $dir_dashboard_layout . "/sidebar.blade.php";

		$dir_admin					= $dir_views . "/admin";
		$dir_admin_dashboard		= $dir_admin . "/dashboard";
		$file_admin_dashboard		= $dir_admin_dashboard . "/dashboard.blade.php";

    	try{
    		if(!is_dir($dir_dashboard_controller))
			{
			    mkdir($dir_dashboard_controller, 0777, true);
			}

			if (!file_exists($file_dashboard_controller))  
			{ 
				$content_dashboard_controller = "<?php \n".
				"namespace App\Http\Controllers\Dashboard;\n".
				"use Illuminate\Http\Request;\n".
				"use Illuminate\Support\Facades\Input;\n".
				"use Illuminate\Support\Facades\Hash;\n".
				"use App\Http\Controllers\Controller; \n".
				"use App\Http\Controllers\Sign\ActivityController as Activity;\n".
				"use App\User;\n".
				"use File;\n".
				"use Auth;\n".
				"use DB;\n".
				"use Carbon\Carbon;\n".
				"class DashboardController extends Controller\n".
				"{\n".
				"	public function dashboard()\n".
				"	{\n".
				"		return view('admin.dashboard.dashboard');\n".
				"	}\n".
				"\n".
				"	public function profile()\n".
				"	{\n".
				"		return view('admin.dashboard.dashboard');\n".
				"	}\n".
				"}";

				$file = fopen($file_dashboard_controller,"w");

				fwrite($file, $content_dashboard_controller);

				fclose($file);
			}

			if (!is_dir($dir_layouts)) {
				mkdir($dir_layouts, 0777, true);
			}

			if (!is_dir($dir_dashboard_layout)) {
				mkdir($dir_dashboard_layout, 0777, true);
			}

			if (!file_exists($header_dashboard_layout))
			{
				$content_header = '<header class="main-header">'."\n".
				'	<a href="#" class="logo">'."\n".
				'		<span class="logo-mini"><b>APPS</b></span>'."\n".
				'		<span class="logo-lg">Master<b>APPS</b></span>'."\n".
				"	</a>\n".
				'	<nav class="navbar navbar-static-top">'."\n".
				'		<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">'."\n".
				'			<span class="sr-only">Toggle navigation</span>'."\n".
				"		</a>\n".
				'		<div class="navbar-custom-menu">'."\n".
				'			<ul class="nav navbar-nav">'."\n".
				'				<li class="dropdown user user-menu">'."\n".
				'					<a href="#" class="dropdown-toggle" data-toggle="dropdown">'."\n".
				'						@if(auth()->user()->image == "")'."\n".
				'							<img src="{{ asset("public/images/user/default.jpg") }}" class="user-image" alt="User Image">'."\n".
				"						@else\n".
				'							<img src="{{ asset("public/images/user/".auth()->user()->image) }}" class="user-image" alt="User Image">'."\n".
				"						@endif\n".
				'						<span class="hidden-xs">'."\n".
				'							@if(Session::has("adminName"))'."\n".
				"								{!! auth()->user()->name !!}\n".
				"							@endif\n".
				"						</span>\n".
				"					</a>\n".
				'					<ul class="dropdown-menu">'."\n".
				'						<li class="user-header">'."\n".
				'							@if(auth()->user()->image == "")'."\n".
				'								<img src="{{ asset("public/images/user/default.jpg") }}" class="img-circle" alt="User Image">'."\n".
				"							@else\n".
				'								<img src="{{ asset("public/images/user/".auth()->user()->image) }}" class="img-circle" alt="User Image">'."\n".
				"							@endif\n".
				"							<p>\n".
				'								@if(Session::has("adminName")){!! auth()->user()->name !!}@endif'."\n".
				'								<small>@if(Session::has("adminLevel")){!! Session::get("adminLevel") !!}@endif MasterAPPS</small>'."\n".
				"							</p>\n".
				"						</li>\n".
				'						<li class="user-footer">'."\n".
				'							<div class="pull-left">'."\n".
				'								<a href="{{ url("/profile") }}" class="btn btn-default btn-flat">Profile</a>'."\n".
				"							</div>\n".
				'							<div class="pull-right">'."\n".
				'								<a href="{{ url("/logout") }}" class="btn btn-default btn-flat">Sign Out</a>'."\n".
				"							</div>\n".
				"						</li>\n".
				"					</ul>\n".
				"				</li>\n".
				"				<li>\n".
				'					<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>'."\n".
				"				</li>\n".
				"			</ul>\n".
				"		</div>\n".
				"	</nav>\n".
				'</header>';

				$file = fopen($header_dashboard_layout,"w");

				fwrite($file, $content_header);

				fclose($file);
			}

			if (!file_exists($content_dashboard_layout))
			{
				$content_content = "<!DOCTYPE html>\n".
				'<html lang="'.'{{ str_replace("_", "-", app()->getLocale()) }}"'.'>'."\n".
				'	<head>'."\n".
				'		<title>MasterAPPS | @yield("title")</title>'."\n".
				'		<meta charset="UTF-8" />'."\n".
				'		<meta name="csrf-token" content="{{ csrf_token() }}">'."\n".
				'		<meta name="viewport" content="width=device-width, initial-scale=1.0" />'."\n".
				'		<link rel="icon" type="image/png" href="{{ asset("public/images/icon/book.png") }}" />'."\n".
				'		<link rel="stylesheet" href="{{ asset("public/css/bootstrap/bootstrap.min.css") }}">'."\n".
				'		<link rel="stylesheet" href="{{ asset("public/css/font-awesome/css/font-awesome.min.css") }}">'."\n".
				'		<link rel="stylesheet" href="{{ asset("public/css/Ionicons/css/ionicons.min.css") }}">'."\n".
				'		<link rel="stylesheet" href="{{ asset("public/css/datatables.net-bs/css/dataTables.bootstrap.min.css") }}">'."\n".
				'		<link rel="stylesheet" href="{{ asset("public/css/morris/morris.css") }}">'."\n".
				'		<link rel="stylesheet" href="{{ asset("public/css/adminLTE/AdminLTE.min.css") }}">'."\n".
				'		<link rel="stylesheet" href="{{ asset("public/css/skins/_all-skins.min.css") }}">'."\n".
				'		<link rel="stylesheet" href="{{ asset("public/css/bootstrap/bootstrap-datepicker.min.css") }}">'."\n".
				'		<link rel="stylesheet" href="{{ asset("public/css/style/style.css") }}">'."\n".
				'		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">'."\n".
				'	</head>'."\n".
				'	<body class="hold-transition skin-blue sidebar-mini fixed">'."\n".
				'		<div class="wrapper">'."\n".
				'			@include("layouts.dashboard.header")'."\n".
				'			@include("layouts.dashboard.sidebar")'."\n".
				'			<div class="content-wrapper">'."\n".
				'				@yield("content")'."\n".
				'			</div>'."\n".
				'			@include("layouts.dashboard.footer")'."\n".
				'		</div>'."\n".
				'		<script src="{{ asset("public/js/jQuery/jquery.min.js") }}"></script>'."\n".
				'		<script src="{{ asset("public/js/jQuery/jquery-ui.min.js") }}"></script>'."\n".
				'		<script>'."\n".
				'			$.widget.bridge("uibutton", $'.'.ui.button);'."\n".
				'		</script>'."\n".
				'		<script src="{{ asset("public/js/bootstrap/bootstrap.min.js") }}"></script>'."\n".
				'		<script src="{{ asset("public/js/jQuery/jquery.dataTables.min.js") }}"></script>'."\n".
				'		<script src="{{ asset("public/js/bootstrap/dataTables.bootstrap.min.js") }}"></script>'."\n".
				'		<script src="{{ asset("public/js/moment/moment.min.js") }}"></script>'."\n".
				'		<script src="{{ asset("public/js/bootstrap/daterangepicker.js") }}"></script>'."\n".
				'		<script src="{{ asset("public/js/bootstrap/bootstrap-datepicker.min.js") }}"></script>'."\n".
				'		<script src="{{ asset("public/js/jQuery/jquery.slimscroll.min.js") }}"></script>'."\n".
				'		<script src="{{ asset("public/js/fastclick/fastclick.js") }}"></script>'."\n".
				'		<script src="{{ asset("public/js/adminLTE/adminlte.min.js") }}"></script>'."\n".
				'		<script src="{{ asset("public/js/adminLTE/dashboard.js") }}"></script>'."\n".
				'		<script src="{{ asset("public/js/adminLTE/demo.js") }}"></script>'."\n".
				'		<script src="{{ asset("public/js/chart/Chart.js") }}"></script>'."\n".
				'		<script src="{{ asset("public/js/morris/morris.min.js") }}"></script>'."\n".
				'		<script src="{{ asset("public/js/raphael/raphael.min.js") }}"></script>'."\n".
				'		<script src="{{ asset("public/js/dobpicker.js") }}"></script>'."\n".
				'	</body>'."\n".
				'</html>';

				$file = fopen($content_dashboard_layout,"w");

				fwrite($file, $content_content);

				fclose($file);
			}

			if (!file_exists($footer_dashboard_layout))
			{
				$content_footer = '<footer class="main-footer">'."\n".
				'	<div class="pull-right hidden-xs">'."\n".
				"		<b>Version</b> 1.0.0\n".
				"	</div>\n".
				'	<strong>Copyright &copy; {{\Carbon\Carbon::now()->format("Y")}} <a href="https://github.com/syahrulyusuf97/masterapps">Syahrul Yusuf</a>.</strong> All rights reserved.'."\n".
				"</footer>\n".
				'<aside class="control-sidebar control-sidebar-dark">'."\n".
				'	<ul class="nav nav-tabs nav-justified control-sidebar-tabs">'."\n".
				'		<li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>'."\n".
				'		<li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>'."\n".
				"	</ul>\n".
				'	<div class="tab-content">'."\n".
				'		<div class="tab-pane" id="control-sidebar-home-tab">'."\n".
				'			<h3 class="control-sidebar-heading">Recent Activity</h3>'."\n".
				'			<ul class="control-sidebar-menu">'."\n".
				"				<li>\n".
				'					<a href="javascript:void(0)">'."\n".
				'						<i class="menu-icon fa fa-birthday-cake bg-red"></i>'."\n".
				'						<div class="menu-info">'."\n".
				'							<h4 class="control-sidebar-subheading">Langdon'."'".'s Birthday</h4>'."\n".
				'							<p>Will be 23 on April 24th</p>'."\n".
				"						</div>\n".
				"					</a>\n".
				"				</li>\n".
				"				<li>\n".
				'					<a href="javascript:void(0)">'."\n".
				'						<i class="menu-icon fa fa-user bg-yellow"></i>'."\n".
				'						<div class="menu-info">'."\n".
				'							<h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>'."\n".
				'							<p>New phone +1(800)555-1234</p>'."\n".
				"						</div>\n".
				"					</a>\n".
				"				</li>\n".
				"				<li>\n".
				'					<a href="javascript:void(0)">'."\n".
				'						<i class="menu-icon fa fa-envelope-o bg-light-blue"></i>'."\n".
				'						<div class="menu-info">'."\n".
				'							<h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>'."\n".
				'							<p>nora@example.com</p>'."\n".
				"						</div>\n".
				"					</a>\n".
				"				</li>\n".
				"				<li>\n".
				'					<a href="javascript:void(0)">'."\n".
				'						<i class="menu-icon fa fa-file-code-o bg-green"></i>'."\n".
				'						<div class="menu-info">'."\n".
				'							<h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>'."\n".
				'							<p>Execution time 5 seconds</p>'."\n".
				"						</div>\n".
				"					</a>\n".
				"				</li>\n".
				"			</ul\n".
				'			<h3 class="control-sidebar-heading">Tasks Progress</h3>'."\n".
				'			<ul class="control-sidebar-menu">'."\n".
				"				<li>\n".
				'					<a href="javascript:void(0)">'."\n".
				'						<h4 class="control-sidebar-subheading">'."\n".
				"							Custom Template Design\n".
				'							<span class="label label-danger pull-right">70%</span>'."\n".
				"						</h4>\n".
				'						<div class="progress progress-xxs">'."\n".
				'							<div class="progress-bar progress-bar-danger" style="width: 70%"></div>'."\n".
				"						</div>\n".
				"					</a>\n".
				"				</li>\n".
				"				<li>\n".
				'					<a href="javascript:void(0)">'."\n".
				'						<h4 class="control-sidebar-subheading">'."\n".
				"							Update Resume\n".
				'							<span class="label label-success pull-right">95%</span>'."\n".
				"						</h4>\n".
				'						<div class="progress progress-xxs">'."\n".
				'							<div class="progress-bar progress-bar-danger" style="width: 95%"></div>'."\n".
				"						</div>\n".
				"					</a>\n".
				"				</li>\n".
				"				<li>\n".
				'					<a href="javascript:void(0)">'."\n".
				'						<h4 class="control-sidebar-subheading">'."\n".
				"							Laravel Integration\n".
				'							<span class="label label-warning pull-right">50%</span>'."\n".
				"						</h4>\n".
				'						<div class="progress progress-xxs">'."\n".
				'							<div class="progress-bar progress-bar-warning" style="width: 50%"></div>'."\n".
				"						</div>\n".
				"					</a>\n".
				"				</li>\n".
				"				<li>\n".
				'					<a href="javascript:void(0)">'."\n".
				'						<h4 class="control-sidebar-subheading">'."\n".
				"							Back End Framework\n".
				'							<span class="label label-primary pull-right">68%</span>'."\n".
				"						</h4>\n".
				'						<div class="progress progress-xxs">'."\n".
				'							<div class="progress-bar progress-bar-primary" style="width: 68%"></div>'."\n".
				"						</div>\n".
				"					</a>\n".
				"				</li>\n".
				"			</ul>\n".
				"		</div>\n".
				'		<div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>'."\n".
				'		<div class="tab-pane" id="control-sidebar-settings-tab">'."\n".
				'			<form method="post">'."\n".
				'				<h3 class="control-sidebar-heading">General Settings</h3>'."\n".
				'				<div class="form-group">'."\n".
				'					<label class="control-sidebar-subheading">'."\n".
				"						Report panel usage\n".
				'						<input type="checkbox" class="pull-right" checked>'."\n".
				"					</label>\n".
				"					<p>Some information about this general settings option</p>\n".
				"				</div>\n".
				'				<div class="form-group">'."\n".
				'					<label class="control-sidebar-subheading">'."\n".
				"						Allow mail redirect\n".
				'						<input type="checkbox" class="pull-right" checked>'."\n".
				"					</label>\n".
				"					<p>Other sets of options are available</p>\n".
				"				</div>\n".
				'				<div class="form-group">'."\n".
				'					<label class="control-sidebar-subheading">'."\n".
				"						Expose author name in posts\n".
				'						<input type="checkbox" class="pull-right" checked>'."\n".
				"					</label>\n".
				"					<p>Allow the user to show his name in blog posts</p>\n".
				"				</div>\n".
				'				<h3 class="control-sidebar-heading">Chat Settings</h3>'."\n".
				'				<div class="form-group">'."\n".
				'					<label class="control-sidebar-subheading">'."\n".
				"						Show me as online\n".
				'						<input type="checkbox" class="pull-right" checked>'."\n".
				"					</label>\n".
				"				</div>\n".
				'				<div class="form-group">'."\n".
				'					<label class="control-sidebar-subheading">'."\n".
				"						Turn off notifications\n".
				'						<input type="checkbox" class="pull-right" checked>'."\n".
				"					</label>\n".
				"				</div>\n".
				'				<div class="form-group">'."\n".
				'					<label class="control-sidebar-subheading">'."\n".
				"						Delete chat history\n".
				'						<a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>'."\n".
				"					</label>\n".
				"				</div>\n".
				"			</form>\n".
				"		</div>\n".
				"	</div>\n".
				"</aside>\n".
				'<div class="control-sidebar-bg"></div>';

				$file = fopen($footer_dashboard_layout,"w");

				fwrite($file, $content_footer);

				fclose($file);
			}

			if (!file_exists($sidebar_dashboard_layout))
			{
				$content_sidebar = "<?php $"."url = url()->current(); ?>\n".
				'<aside class="main-sidebar">'."\n".
				'	<section class="sidebar">'."\n".
				'		<div class="user-panel">'."\n".
				'			<div class="pull-left image">'."\n".
				'				@if(auth()->user()->image == "")'."\n".
				'					<img src="{{ asset("public/images/user/default.jpg") }}" class="img-circle" alt="User Image">'."\n".
				"				@else\n".
				'					<img src="{{ asset("public/images/user/". auth()->user()->image) }}" class="img-circle" alt="User Image">'."\n".
				"				@endif\n".
				"			</div>\n".
				'			<div class="pull-left info">'."\n".
				"				<p>\n".
				'					@if(Session::has("adminName"))'."\n".
				"						{!! auth()->user()->name !!}\n".
				"					@endif\n".
				"				</p>\n".
				'				<a href="#"><i class="fa fa-circle text-success"></i> Online</a>'."\n".
				"			</div>\n".
				"		</div>\n".
				'		<ul class="sidebar-menu" data-widget="tree">'."\n".
				'			<li class="header">MAIN NAVIGATION</li>'."\n".
				'			<li <?php if(preg_match("/dashboard/i", $url)) { ?> class="active" <?php } ?>>'."\n".
				'				<a href="{{ url("/dashboard") }}">'."\n".
				'					<i class="fa fa-dashboard"></i> <span>Dashboard</span>'."\n".
				"				</a>\n".
				"			</li>\n".
				'			<li <?php if(preg_match("/log-activity/i", $url)) { ?> class="active" <?php } ?>>'."\n".
				'				<a href="{{ url("/log-activity") }}">'."\n".
				'					<i class="fa fa-clock-o"></i> <span>Log Activity</span>'."\n".
				"				</a>\n".
				"			</li>\n".
				"		</ul>\n".
				"	</section>\n".
				"</aside>";

				$file = fopen($sidebar_dashboard_layout,"w");

				fwrite($file, $content_sidebar);

				fclose($file);
			}

			if(!is_dir($dir_admin))
			{
			    mkdir($dir_admin, 0777, true);
			}

			if(!is_dir($dir_admin_dashboard))
			{
			    mkdir($dir_admin_dashboard, 0777, true);
			}

			if (!file_exists($file_admin_dashboard))
			{
				$content_dashboard = '@extends("layouts.dashboard.content")'."\n".
				'@section("title", "Dashboard")'."\n".
				'@section("content")'."\n".
				'<section class="content-header">'."\n".
				"	<h1>\n".
				"		Dashboard\n".
				"		<small>Control panel</small>\n".
				"	</h1>\n".
				'	<ol class="breadcrumb">'."\n".
				'		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>'."\n".
				'		<li class="active">Dashboard</li>'."\n".
				"	</ol>\n".
				"</section>\n".
				'<section class="content">'."\n".
				'	<div class="row">'."\n".
				'		<div class="col-lg-12">'."\n".
				'			<div class="alert alert-success alert-block">'."\n".
				'				<button type="button" class="close" data-dismiss="alert">&times;</button>'."\n".
				'				<strong>Welcome to the dashboard MasterAPPS</strong>'."\n".
				'			</div>'."\n".
				"		</div>\n".
				"	</div>\n".
				"</section>\n".
				"@endsection\n".
				'<script src="{{ asset("public/js/jQuery/jquery.min.js") }}"></script>';

				$file = fopen($file_admin_dashboard,"w");

				fwrite($file, $content_dashboard);

				fclose($file);
			}
    		return true;
    	}catch (Exception $e){
    		return $e;
    	}
    }

    private static function makeRoutes()
    {
    	$routes = "routes/web.php";
    	try{
    		$handle = fopen($routes, 'a') or die('Cannot open file:  '.$routes);
			$new_route = "\n".'Route::group(["namespace" => "Sign"], function(){'."\n".
						 '	Route::get("/", "SignController@login");'."\n".
						 '	Route::match(["get", "post"], "/login", "SignController@login")->name("login");'."\n".
						 '	Route::get("/logout", "SignController@logout");'."\n".
						 "});\n".
						 "\n".
						 'Route::group(["middleware"=>["auth"]], function(){'."\n".
						 '	Route::group(["namespace" => "Dashboard"], function(){'."\n".
						 '		//route dashboard'."\n".
						 '		Route::get("/dashboard", "DashboardController@dashboard");'."\n".
						 '		//route profile'."\n".
						 '		Route::get("/profile", "DashboardController@profile");'."\n".
						 "	});\n".
						 "});";
			fwrite($handle, $new_route);
			return true;
    	}catch (Exception $e){
    		return $e;
    	}
		
    }
}