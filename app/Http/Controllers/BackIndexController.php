<?php

namespace App\Http\Controllers;

use Request;
use DB;
use Auth;
use File;
use Session;

class BackIndexController extends Controller
{
    // 問題頁面
    public function index()
    {
        $active_1 = "Issue List"; // 頁面父名稱
        $active_2 = ""; // 頁面子名稱

        $auth_limit = $this->auth_limit_refresh();
        if(isset($Auth_limit[$active_2]) && $Auth_limit[$active_2] != 1) {
            return redirect('/logout');
        }

        $member_table = [];
        $listbar = $this->listBar();
        $title = "會員資料";
        return view('index')
            ->with('title', $title)
            ->with('listbar', $listbar)
            ->with('active_1', $active_1)
            ->with('active_2', $active_2)
            ->with('issue_table', $this->get_all_issue())
            ->with('issue_type', $this->get_issue_type())
            ->with('all_users', $this->get_users());
    }

    public function index_json()
    {
        $input = Request::input();
        $json = [];

        if (empty($input['method']) || $input['method'] == "") {
            return $json['msg'] = "error";
        }

        switch($input['method']) {
            // 取得問題詳細內容
            case "get_issue_info": 
                $json = $this->get_issue_info($input);
            break;

            // 新增問題
            case "add_issue": 
                $json = $this->add_issue($input);
            break;

            // 回覆問題
            case "reply_issue": 
                $json = $this->reply_issue($input);
            break;

            // 編輯問題
            case "edit_issue": 
                $json = $this->edit_issue($input);
            break;
        }
        return $json;
    }

    // Function Start
    private function get_all_issue($type = '')
    {
        $all_issue = DB::select("
            SELECT * FROM issue
        ");

        $users = DB::select("
            SELECT *
            FROM users
        ");

        foreach ($all_issue as $issue) {
            foreach ($users as $user) {
                if ($issue->returner == $user->id) {
                    $issue->returner = $user->name;
                }
                $issue->btn = '<button class="btn btn-info m-1 show_info" data-issueid="' . $issue->issue_id . '">查看</button>';
                // if ($issue->returner == Auth::user()->name && Auth::user()->authority == "QA") {
                //     $issue->btn .= '<button class="btn btn-warning  m-1 edit_issue" data-issueid="' . $issue->issue_id . '">編輯</button>';
                // }
            }
        }

        // Table重組用 return array
        if ($type == "json_table") {
            $json_table = [];
            foreach ($all_issue as $issue) {
                $json_table[] = [
                    $issue->issue_id,
                    $issue->issue_title,
                    $issue->returner,
                    $issue->create_time,
                    $issue->btn
                ];
            }
            return $json_table;
        }

        return $all_issue;
    }

    private function get_issue_type()
    {
        $issue_type = [];
        try {
            $issue_type = DB::select("
                SELECT * 
                FROM issue_type
            ");
        } catch (\Exception $e) {
            
        }

        foreach ($issue_type as $key => $type) {
            $type_auth = unserialize($type->user);
            if ($type_auth[Auth::user()->authority] == 0) {
                unset($issue_type[$key]);
            }
        }
        $issue_type = array_values($issue_type);
        

        return $issue_type;
    }

    private function get_users()
    {
        $users = [];
        try {
            $users = DB::select("
                SELECT * 
                FROM users
            ");
        } catch (\Exception $e) {
            
        }

        return $users;
    }

    private function get_issue_info($input)
    {
        $issue_id = $input['issue_id'];
        $json = [];
        $issue_info = [];
        $issue = [];
        
        try {
            DB::beginTransaction();
            $issue = DB::select("
                SELECT * 
                FROM issue
                WHERE issue_id = '{$issue_id}'
            ");

            $issue_info = DB::select("
                SELECT * 
                FROM issue_text AS it
                LEFT JOIN issue_type AS tp ON tp.type_id = it.issue_type
                LEFT JOIN users AS u ON it.issue_operator = u.id
                WHERE issue_id = '{$issue_id}'
                ORDER BY create_time ASC
            ");
            DB::commit();
            $json['msg'] = 'success';
        } catch (\Exception $e) {
            DB::rollback();
            $json['msg'] = 'error';
        }

        $user = Auth::user()->id;
        foreach ($issue_info as $key => $val) {
            $val->owner = '';
            if ($user == $val->creater) {
                $val->owner = true;
            }
        }

        $json['issue_info'] = $issue_info;
        $json['all_issue_type'] = $this->get_issue_type();
        $json['all_users'] = $this->get_users();
        $json['issue_title'] = $issue[0]->issue_title;

        return $json;
    }

    private function add_issue($input)
    {
        // die();
        $issue_type = $input['issue_type'];
        $issue_operator = $input['issue_operator'];
        $issue_title = $input['issue_title'];
        $issue_text = $input['issue_text'];
        $returner = Auth::user()->id;
        $json = [];

        try {
            DB::beginTransaction();
            DB::insert("INSERT INTO `issue` (`issue_title`, `returner`) VALUES ('{$issue_title}', '{$returner}')");
            $max_id = DB::select("SELECT MAX(`issue_id`) AS max FROM `issue`");
            $max_id = ($max_id[0]->max);
            DB::insert("INSERT INTO `issue_text` (`issue_id`, `issue_text`, `issue_type`, `issue_operator`, `creater`) VALUES ('{$max_id}', '{$issue_text}', '{$issue_type}', '{$issue_operator}', '{$returner}')");
            DB::commit();
            $json['msg'] = 'success';
        } catch (\Exception $e) {
            DB::rollback();
            $json['msg'] = 'error';
        }
        $json['issue_table'] = $this->get_all_issue('json_table');

        return $json;
    }

    private function reply_issue($input)
    {
        $issue_id = $input['issue_id'];
        $issue_type = $input['issue_type'];
        $issue_operator = $input['issue_operator'];
        $issue_text = $input['issue_text'];
        $json = [];
        $change_time = date("Y-m-d H:i:s");

        try {
            DB::beginTransaction();
            DB::insert("INSERT INTO `issue_text` (`issue_id`, `issue_text`, `issue_type`, `issue_operator`) VALUES ('{$issue_id}', '{$issue_text}', '{$issue_type}', '{$issue_operator}')");
            DB::commit();
            $json['msg'] = 'success';
        } catch (\Exception $e) {
            DB::rollback();
            $json['msg'] = 'error';
        }

        return $json;
    }

    private function edit_issue($input)
    {
        $issue_id = $input['issue_id'];
        $issue_text = $input['issue_text'];
    }
}
