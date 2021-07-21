<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Request;
use DB;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function listBar() 
    {
    	$auth_limit = $this->auth_limit_refresh();

    	//側邊攔清單
    	$list = array(
    		array(
				'name'      => 'Issue List',
                'ch_name'   => '回報總覽',
				'icon'      => 'icon-person',
				'url'       => '/index',
			),
    		array(
				'name'      => 'Backstage Management',
                'ch_name'   => '後台管理',
				'icon'      => 'fas fa-fw fa-cog',
				'floor_2nd' => 
				array(
					array(
						'name'      => 'Admin Management',
                        'ch_name'   => '帳號管理',
						'url'       => '../backstage_management/admin_management',
					),
					array(
						'name'      => 'Auth Management',
                        'ch_name'   => '權限管理',
						'url'       => '../backstage_management/auth_management',
					),
				)
			),
    	);

    	//移除權限外的連結
    	if (!empty($auth_limit)) {
		$Auth_limit = $auth_limit;
	    	foreach ($list as $key => $value) {			
	    		if (isset($value['floor_2nd'])) {
	    			foreach ($value['floor_2nd'] as $key2 => $value2) {
	    				if ((isset($Auth_limit[$value2['name']]) && intval($Auth_limit[$value2['name']]) == 0)) {
	    					unset($list[$key]['floor_2nd'][$key2]);
	    				}
	    			}
	    		} else {
    				if (isset($Auth_limit[$value['name']]) && intval($Auth_limit[$value['name']]) == 0) {
    					unset($list[$key]);
    				}
	    		}
	    	}
    	}

    	foreach ($list as $key => $value) {
    		if(isset($value['floor_2nd']) && count($value['floor_2nd']) == 0) {
    			unset($list[$key]);
    		}
    	}

    	return $list;
    }

    //權限重新刷新
    public function auth_limit_refresh() 
    { 
        $Limits = DB::select(
            "SELECT page, user FROM admin_authority"
        );

        $Auth_arr = array();

        foreach ($Limits as $value) {
        	$user_list = unserialize($value->user);
            $Auth_arr[$value->page] = !empty($user_list[Auth::user()->authority]) ? $user_list[Auth::user()->authority] : 0;
        }

        Request::session()->put('Auth_limit', $Auth_arr);

        return $Auth_arr;
    }

	public function get_all_member()
    {
        $all_member = DB::select("
            SELECT * 
            FROM member 
            WHERE show_status = 0 
            ORDER BY id DESC
        ");

        return $all_member;
    }
}
