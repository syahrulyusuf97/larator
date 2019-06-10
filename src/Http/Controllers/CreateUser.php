<?php

namespace App\Console\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;

class CreateUser extends Controller
{
    public static function store()
    {
        DB::beginTransaction();
        try{
            if (self::checkLevel('ADMIN') == 0) {
                $level = DB::table('level')->insertGetId([
                    'name'      => 'ADMIN',
                    'note'      => 'Application admin',
                    'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'  => Carbon::now()->format('Y-m-d H:i:s')
                ]);
            } else if (self::checkLevel('ADMIN') == 1) {
                $level = self::getIdLevel('ADMIN');
            }

            if (self::checkUsername('admin') == 0) {
                DB::table('user')->insert([
                    'name'          => 'Your Name',
                    'email'         => 'your@email.com',
                    'username'      => 'admin',
                    'password'      => bcrypt('123456'),
                    'image'         => null,
                    'idlevel'       => $level,
                    'status'        => 'Y',
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
                ]);
            } else if (self::checkUsername('admin') == 1) {
                DB::table('user')->where('id', '=', self::getUser('admin'))->update([
                    'name'          => 'Your Name',
                    'email'         => 'your@email.com',
                    'username'      => 'admin',
                    'password'      => bcrypt('123456'),
                    'image'         => null,
                    'idlevel'       => $level,
                    'status'        => 'Y',
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
                ]);
            }

            DB::commit();
            return "username : admin\n".
                   "password : 123456\n".
                   "The user was successfully created as an admin";
        }catch (\Exception $e){
            DB::rollback();
            return 'Failed to make user as admin => ' . $e;
        }
    }

    public static function dev()
    {
        DB::beginTransaction();
        try{
            if (self::checkLevel('DEVELOPER') == 0) {
                $level = DB::table('level')->insertGetId([
                    'name'      => 'DEVELOPER',
                    'note'      => 'Application developer',
                    'created_at'  => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'  => Carbon::now()->format('Y-m-d H:i:s')
                ]);
            } else if (self::checkLevel('DEVELOPER') == 1) {
                $level = self::getIdLevel('DEVELOPER');
            }
            
            if (self::checkUsername('developer') == 0) {
                DB::table('user')->insert([
                    'name'          => 'Developer',
                    'email'         => 'developer@email.com',
                    'username'      => 'developer',
                    'password'      => bcrypt('123456'),
                    'image'         => null,
                    'idlevel'       => $level,
                    'status'        => 'Y',
                    'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
                ]);
            } else if (self::checkUsername('developer') == 1) {
                DB::table('user')->where('id', '=', self::getUser('developer'))->update([
                    'name'          => 'Developer',
                    'email'         => 'developer@email.com',
                    'username'      => 'developer',
                    'password'      => bcrypt('123456'),
                    'image'         => null,
                    'idlevel'       => $level,
                    'status'        => 'Y',
                    'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
                ]);
            }

            DB::commit();
            return "username : developer\n".
                   "password : 123456\n".
                   "The user was successfully created as a developer";
        }catch (\Exception $e){
            DB::rollback();
            return 'Failed to make user as developer => ' . $e;
        }
    }

    private static function checkLevel($level)
    {
        $query = DB::table('level')
                    ->where('name', '=', $level)
                    ->count();
        return $query;
    }

    private static function checkUsername($username)
    {
        $query = DB::table('user')
                    ->where('username', '=', $username)
                    ->count();
        return $query;
    }

    private static function getIdLevel($level)
    {
        $query = DB::table('level')
                    ->where('name', '=', $level)
                    ->first()->id;
        return $query;
    }

    private static function getUser($username)
    {
        $query = DB::table('user')
                    ->where('username', '=', $username)
                    ->first()->id;
        return $query;
    }
}