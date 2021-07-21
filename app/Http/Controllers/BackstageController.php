<?php

namespace App\Http\Controllers;
use Auth;
use App;
use App\User;
use App\Http\Controllers\Controller;
use Request;
use Cookie;
use DB;



class BackstageController extends Controller
{
    // 帳號管理頁面
    public function backstage_management()
    {
        $active_1 = "Backstage Management"; // 頁面父名稱
        $active_2 = "Admin Management"; // 頁面子名稱

        $auth_limit = $this->auth_limit_refresh();
        if(isset($Auth_limit[$active_2]) && $Auth_limit[$active_2] != 1) {
            return redirect('/logout');
        }

        $users = $this->users();
        $user_permissions = $this->user_permissions();
        $listbar = $this->listBar();
        $title = "後臺帳號管理";
        return view('backstage_management')
            ->with('title', $title)
            ->with('listbar', $listbar)
            ->with('active_1', $active_1)
            ->with('active_2', $active_2)
            ->with('users', $users)
            ->with('user_permissions', $user_permissions);
    }

    public function backstage_management_json()
    {
        $input = Request::input();
        $json = [];

        if (empty($input['method']) || $input['method'] == "") {
            return $json['msg'] = "error";
        }

        switch($input['method']) {
            case "add_user": 
                $json = $this->add_user($input);
            break;

            case "refresh_table": 
                $json = $this->refresh_table($input);
            break;

            case "reset_pwd": 
                $json = $this->password_update($input);
            break;

            case "reset_self_pwd": 
                $json = $this->new_password_update($input);
            break;

            case "status_change": 
                $json = $this->status_change($input);
            break;

            case "del_user": 
                $json = $this->del_user($input);
            break;

            case "add_permissions":
                $json = $this->add_permissions($input);
                break;

            case "del_permissions":
                $json = $this->del_permissions($input);
                break;

            case "get_permissions_info": 
                $json = $this->get_permissions_info($input);
                break;

            case "edit_username": 
                $json = $this->edit_username($input);
                break;

            case "update_user": 
                $json = $this->update_user($input);
                break;

            case "change_auth": 
                $json = $this->change_auth($input);
                break;

            case "auth_change": 
                $json = $this->auth_change($input);
                break;
        }
        return $json;
    }

    public function auth_management()
    {
        $active_1 = "Backstage Management"; // 頁面父名稱
        $active_2 = "Auth Management"; // 頁面子名稱

        $auth_limit = $this->auth_limit_refresh();
        if(isset($Auth_limit[$active_2]) && $Auth_limit[$active_2] != 1) {
            return redirect('/logout');
        }

        $admin_authority = $this->admin_authority();
        $user_permissions = $this->user_permissions();

        $listbar = $this->listBar();
        $title = "權限管理";
        return view('auth_management')
            ->with('title', $title)
            ->with('listbar', $listbar)
            ->with('active_1', $active_1)
            ->with('active_2', $active_2)
            ->with('user_permissions', $user_permissions)
            ->with('admin_authority', $admin_authority);
    }

    public function reset_pwd_index()
    {
        $active_1 = "Backstage Management"; // 頁面父名稱
        $active_2 = "Reset PWD"; // 頁面子名稱

        $admin_authority = $this->admin_authority();
        $user_permissions = $this->user_permissions();

        $listbar = $this->listBar();
        $title = "重設密碼";
        return view('pwd_update')
            ->with('title', $title)
            ->with('listbar', $listbar)
            ->with('active_1', $active_1)
            ->with('active_2', $active_2);
    }

    // 取得帳號列表
    private function users() 
    {
        $user_permissions = $this->user_permissions();
        $users = DB::select("
            SELECT * 
            FROM users
        ");

        // var_dump($users);
        foreach ($users as $key => $val) {
            foreach ($user_permissions as $val2) {
                if ($val->authority == $val2->name) {
                    $users[$key]->authority = $val2->premission_note;
                }
            }
        }
        return $users;
    }

    private function user_permissions()
    {
        $user_permissions = DB::select("
            SELECT *
            FROM user_permissions
        ");

        return $user_permissions;
    }

    // table重新讀取
    public function refresh_table()
    {
        $user_list = $this->users();
        $user_list_new = [];
        foreach ($user_list as $key => $val) {
            if ($user_list[$key]->name == "admin") {
                $user_list_new[$key] = [
                    $user_list[$key]->id,
                    $user_list[$key]->name,
                    ($user_list[$key]->status == 0 ? '停權' : '啟用'),
                    $user_list[$key]->authority,
                    $user_list[$key]->created_at,
                    "-",
                ];
            } else {
                $user_list_new[$key] = [
                    $user_list[$key]->id,
                    $user_list[$key]->name,
                    "<select class='form-control user_status' data-userid='" . $user_list[$key]->id . "'>
                        <option value='0' " . ($user_list[$key]->status == 0 ? 'selected' : '') . ">停權</option>
                        <option value='1' " . ($user_list[$key]->status == 1 ? 'selected' : '') . ">啟用</option>
                    </select>",
                    $user_list[$key]->authority,
                    $user_list[$key]->created_at,
                    "<button class='btn btn-warning reset-btn' data-userid='" . $user_list[$key]->id . "'>重置密碼</button>
                    <button class='btn btn-danger del-btn' data-userid='" . $user_list[$key]->id . "'>刪除</button>",
                ];
            }
            
        }

        return $user_list_new;
    }

    // 帳號狀態更新
    public function status_change($input) 
    {
        $json = [];
        $sql = "UPDATE users SET status = :status, `updated_at` = NOW() WHERE `id` = :id";
            
        try {
            DB::beginTransaction();
            $json['msg'] = 'success';    
            DB::update($sql, [
                'status' => $input['status'],
                'id' => $input['id'],
            ]);
            date_default_timezone_set('Asia/Taipei');
            $json['date'] = date("Y-m-d H:i:s");            

            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            $json['msg'] = 'error';
        }
        return $json;
    }

    // 密碼重置0000
    public function password_update($input) 
    {
        $json = [];
            
        try {
            DB::beginTransaction();
            $sql = "UPDATE users SET `password` = :password, `updated_at` = NOW() WHERE `id` = :id";
            DB::update($sql, [
                'password' => bcrypt('0000'),
                'id' => $input['id'],
            ]);
            $json['msg'] = 'success';    
            date_default_timezone_set('Asia/Taipei');
            $json['date'] = date("Y-m-d H:i:s");            
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            $json['msg'] = 'error';
        }
        return $json;
        // file_put_contents("msg.txt", print_r($input, 1) . "\n", FILE_APPEND);
    }

    // 新增會員
    public function add_user($input) 
    {
        $json = [];

        try {
            DB::beginTransaction();
            $data = DB::select("SELECT * FROM users WHERE `name` = :name", [
                'name' => $input['user_name'],
            ]);

            if(count($data) > 0) {
                $json['msg'] = 'name_repeat';
                return $json;
            }

            $primary_key = DB::select("SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'vip_backstage' AND TABLE_NAME = 'users'")[0] -> AUTO_INCREMENT;

            $sql = "INSERT INTO users (`name`, `password`, `phone`, `status`, `authority`) VALUES (:name, :password, :phone, :status, :authority)";
            DB::insert($sql, [
                'name' => $input['user_name'],
                'password' => bcrypt(0000),
                'phone' => $input['phone'],
                'status' => 1,
                'authority' => $input['user_permissions'],
            ]);
            $json['msg'] = 'success';    
            date_default_timezone_set('Asia/Taipei');
            $json['date'] = date("Y-m-d H:i:s");            
            $json['primary_key'] = $primary_key;

            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            file_put_contents("msg.txt", print_r($e -> getMessage(), 1) . "\n", FILE_APPEND);
            $json['msg'] = 'error';
        }

        return $json;
    }

    // 顯示修改密碼頁面
    public function show_pwd_update_page() 
    {
        return view('pwd_update', array(
            'page' => 'show_pwd_update_page',
            'page_title' => '修改密碼'
        ));
    }

    // 自身密碼更新
    public function new_password_update($input) 
    {
        $json = [];
        $user = Auth::user();
        try {
            DB::beginTransaction();
            DB::update("UPDATE users SET `password` = ? WHERE `id` = ?", array(
                bcrypt($input['password']),
                $user->id
            ));
            $json['msg'] = 'success';  
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            file_put_contents("msg.txt", print_r($e -> getMessage(), 1) . "\n", FILE_APPEND);
            $json['msg'] = 'error';
        }
        return $json;
    }

    // 刪除帳號
    public function del_user($input)
    {
        $json = [];
        $sql = "DELETE FROM users WHERE `id` = :id";
        try {
            DB::beginTransaction();
            DB::delete($sql, [
                'id' => $input['id'],
            ]);        
            $json['msg'] = 'success';
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            $json['msg'] = 'error';
        }
        return $json;
    }

    // 新增角色/身分
    private function add_permissions($input)
    {
        $permissions_en = $input['permissions_en'];
        $permissions_tw = $input['permissions_tw'];
        $all_page = DB::select("SELECT * FROM admin_authority");
        
        $json = [];
        try {
            DB::beginTransaction();
            DB::insert("
                INSERT INTO user_permissions (name, premission_note) VALUES ('{$permissions_en}', '{$permissions_tw}')
            ");
            foreach ($all_page as $key => $val) {
                $val->user = unserialize($val->user);
                $val->user[$permissions_en] = 0;
                $new_user = serialize($val->user);
                DB::update("UPDATE `admin_authority` SET user = '{$new_user}' WHERE id = '{$val->id}'");
            }
            $json['msg'] = 'success';  
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            file_put_contents("msg.txt", print_r($e -> getMessage(), 1) . "\n", FILE_APPEND);
            $json['msg'] = 'error';
        }

        return $json;
    }

    private function admin_authority()
    {
        $admin_authority = DB::select("
            SELECT *
            FROM admin_authority
            ORDER BY id ASC
        ");
        return $admin_authority;
    }

    private function get_permissions_info($input)
    {
        $permissions_id = $input['permissions_id'];
        $json = [];

        $permissions_info = DB::select("
            SELECT *
            FROM admin_authority
            WHERE id = '{$permissions_id}'
        ");

        $json['msg'] = "success";
        $json['permissions_info'] = $permissions_info;

        return $json;
    }

    private function edit_username($input)
    {
        $user_id = $input['userid'];
        $json = [];
        $user_permissions_info = DB::select("SELECT * FROM user_permissions WHERE id = '{$user_id}'");

        $json['msg'] = 'success';
        $json['user_permissions_info'] = $user_permissions_info;
        return $json;
    }

    private function update_user($input)
    {
        $json = [];
        $user_id = $input['userid'];
        $name_tw = $input['name_tw'];

        try {
            DB::beginTransaction();
            DB::update("
                UPDATE user_permissions
                SET premission_note = '{$name_tw}'
                WHERE id = '{$user_id}'
            ");
            $json['msg'] = 'success';              
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            $json['msg'] = 'error';
        }
        
        return $json;
    }

    private function change_auth($input)
    {
        $page_id = $input['pageid'];
        $user_name = $input['user'];
        $select_option = $input['select_option'];
        $json = [];
        $page_info = DB::select("SELECT * FROM admin_authority WHERE id = '{$page_id}'");

        foreach ($page_info as $val) {
            $users = unserialize($val->user);
            $users[$user_name] = $select_option;
        }
        $users = serialize($users);

        try {
            DB::beginTransaction();
            DB::update("
                UPDATE admin_authority 
                SET user = '{$users}' 
                WHERE id = '{$page_id}'
            ");
            $json['msg'] = 'success';              
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            $json['msg'] = 'error';
        }

        return $json;
    }

    private function auth_change($input)
    {
        $user_id = $input['user_id'];
        $auth = $input['auth'];
        $json = [];

        try {
            DB::beginTransaction();
            DB::update("
                UPDATE users SET 
                `authority` = '{$auth}'
                WHERE id = '{$user_id}'
            ");
            $json['msg'] = 'success';              
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            $json['msg'] = 'error';
        }
        
        return $json;
    }
}
