<?php

namespace App\Http\Controllers;

use Request;
use DB;
use Auth;

class AccountingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // 
    public function info()
    {
        $active_1 = "Accounting"; // 頁面父名稱
        $active_2 = "Accounting Info"; // 頁面子名稱

        $auth_limit = $this->auth_limit_refresh();
        if(isset($Auth_limit[$active_2]) && $Auth_limit[$active_2] != 1) {
            return redirect('/logout');
        }

        // 會員總數
        $all_member_count = DB::select("SELECT COUNT(0) AS member_count FROM member")[0]->member_count;

        // 已繳入會費
        $already_pay_member_fee = DB::select("SELECT SUM(member_fee) AS already_pay_member_fee FROM member WHERE pay_way = 0")[0]->already_pay_member_fee;

        // 未繳入會費
        $unready_pay_member_fee = DB::select("SELECT SUM(member_fee) AS unready_pay_member_fee FROM member WHERE pay_way = 1")[0]->unready_pay_member_fee;

        // 總執行單數
        $total_order = DB::select("SELECT SUM(order_number) AS total_order FROM `member_mou`")[0]->total_order;

        $listbar = $this->listBar();
        $title = "會員資料修改";
        return view('accounting_info')
            ->with('title', $title)
            ->with('listbar', $listbar)
            ->with('active_1', $active_1)
            ->with('active_2', $active_2)
            ->with('all_member_count', $all_member_count)
            ->with('already_pay_member_fee', $already_pay_member_fee)
            ->with('unready_pay_member_fee', $unready_pay_member_fee)
            ->with('total_order', $total_order);
    }

    public function info_json()
    {
        $input = Request::input();
        $json = [];

        if (empty($input['method']) || $input['method'] == "") {
            return $json['msg'] = "error";
        }

        switch($input['method']) {
            
        }
        return $json;
    }

    // 會員資料修改頁面
    public function edit_member()
    {
        $active_1 = "Accounting"; // 頁面父名稱
        $active_2 = "Edit Member"; // 頁面子名稱

        $auth_limit = $this->auth_limit_refresh();
        if(isset($Auth_limit[$active_2]) && $Auth_limit[$active_2] != 1) {
            return redirect('/logout');
        }

        $all_member_data = $this->get_all_member();
        $member_table = $this->build_member_table($all_member_data);

        $listbar = $this->listBar();
        $title = "會員資料修改";
        return view('edit_member')
            ->with('title', $title)
            ->with('listbar', $listbar)
            ->with('active_1', $active_1)
            ->with('active_2', $active_2)
            ->with('member_table', $member_table);
    }

    public function edit_member_json()
    {
        $input = Request::input();
        $json = [];

        if (empty($input['method']) || $input['method'] == "") {
            return $json['msg'] = "error";
        }

        switch($input['method']) {
            // 取得指定會員資料
            case "member_info": 
                $json['msg'] = 'success';
                $json['member_info'] = $this->member_info($input['member_id']);
            break;

            // 更新會員資料
            case "update_member": 
                $json = $this->update_member($input);
                $json['member_info'] = $this->member_info($input['member_id']);
            break;

            // 刪除會員
            case "del_member": 
                $json = $this->del_member($input);
            break;
        }
        return $json;
    }

    // 修改入單MOU
    public function edit_mou()
    {
        $active_1 = "Accounting"; // 頁面父名稱
        $active_2 = "Edit MOU"; // 頁面子名稱

        $auth_limit = $this->auth_limit_refresh();
        if(isset($Auth_limit[$active_2]) && $Auth_limit[$active_2] != 1) {
            return redirect('/logout');
        }

        $mou_info = $this->get_mou_info();
        $mou_list = $mou_info['mou_list'];
        $total_mou_order = $mou_info['total_mou_order'];
        $basic_value = $mou_info['basic_value'];
        $achievement_rate = $mou_info['achievement_rate'];

        $listbar = $this->listBar();
        $title = "修改入單MOU";
        return view('edit_mou')
            ->with('title', $title)
            ->with('listbar', $listbar)
            ->with('active_1', $active_1)
            ->with('active_2', $active_2)
            ->with('mou_list', $mou_list)
            ->with('total_mou_order', $total_mou_order)
            ->with('basic_value', $basic_value)
            ->with('achievement_rate', $achievement_rate);
    }

    public function edit_mou_json()
    {
        $input = Request::input();
        $json = [];

        if (empty($input['method']) || $input['method'] == "") {
            return $json['msg'] = "error";
        }

        switch($input['method']) {
            // 取得指定會員MOU
            case "get_target_mou": 
                $json = $this->get_target_mou($input);
            break;

            // 更新MOU
            case "update_mou": 
                $json = $this->update_mou($input);
            break;
        }
        return $json;
    }

    // 入會費查詢
    public function search_member_fee()
    {
        $active_1 = "Accounting"; // 頁面父名稱
        $active_2 = "Search Member Fee"; // 頁面子名稱

        $auth_limit = $this->auth_limit_refresh();
        if(isset($Auth_limit[$active_2]) && $Auth_limit[$active_2] != 1) {
            return redirect('/logout');
        }

        $member_list = $this->get_all_member();
        $listbar = $this->listBar();
        $title = "入會費查詢";
        return view('member_fee_search')
            ->with('title', $title)
            ->with('listbar', $listbar)
            ->with('active_1', $active_1)
            ->with('active_2', $active_2)
            ->with('member_list', $member_list);
        
    }

    public function search_member_fee_json()
    {
        $input = Request::input();
        $json = [];

        if (empty($input['method']) || $input['method'] == "") {
            return $json['msg'] = "error";
        }

        switch($input['method']) {
            // 繳費
            case "pay_member_fee": 
                $json = $this->pay_member_fee($input);
            break;

            case "search_member_fee_by_input":
                $json = $this->search_member_fee_by_input($input);
            break;
        }
        return $json;
    }

    // 勞務費查詢
    public function search_labor_fee()
    {
        $active_1 = "Accounting"; // 頁面父名稱
        $active_2 = "Search Labor Fee"; // 頁面子名稱

        $auth_limit = $this->auth_limit_refresh();
        if(isset($Auth_limit[$active_2]) && $Auth_limit[$active_2] != 1) {
            return redirect('/logout');
        }

        $labor_fee_list = $this->get_all_labor_fee();
        $listbar = $this->listBar();
        $title = "勞務費查詢";
        return view('search_labor_fee')
            ->with('title', $title)
            ->with('listbar', $listbar)
            ->with('active_1', $active_1)
            ->with('active_2', $active_2)
            ->with('labor_fee_list', $labor_fee_list);
    }

    public function search_labor_fee_json()
    {
        $input = Request::input();
        $json = [];

        if (empty($input['method']) || $input['method'] == "") {
            return $json['msg'] = "error";
        }

        switch($input['method']) {
            // 繳費
            case "pay_labor_fee": 
                $json = $this->pay_labor_fee($input);
            break;

            // 搜尋
            case "search_labor_by_input": 
                $json = $this->search_labor_by_input($input);
            break;
        }
        return $json;
    }

    private function build_member_table($data)
    {
        foreach ($data as $key => $val) {
            $data[$key]->btn = "
            <button class='btn btn-primary m-1 edit_member' data-memberid='" . $data[$key]->member_id . "'>編輯</button>
            <button class='btn btn-danger m-1 del_btn' data-memberid='" . $data[$key]->member_id . "'>刪除</button>
            ";

            // 未停用且會員期限已過
            if ($data[$key]->status == 0 && $data[$key]->end_time < date("Y-m-d H:i:s")) {
                $data[$key]->status = "<span class='text-default'>已過期</span>";
            } else if ($data[$key]->status == 1) { // 確定已中止
                $data[$key]->status = "<span class='text-danger'>已中止</span>";
            } else if ($data[$key]->status == 0) { 
                $data[$key]->status = "<span class='text-success'>執行中</span>";
            }
           
        }

        return $data;
    }

    private function member_info($member_id)
    {
        $member_info = DB::select("
            SELECT *
            FROM member
            WHERE member_id = '{$member_id}'
        ");

        if(strtotime($member_info[0]->start_time) < strtotime('-30 days')) {
            $member_info[0]->check_pass = 0; // 已過30天
        } else {
            $member_info[0]->check_pass = 1;
        }

        return $member_info;
    }

    private function update_member($input)
    {
        $json = [];

        $start_time = $input['start_time'];
        $end_time = $input['end_time'];
        $member_fee = $input['member_fee'];
        $pay_way = $input['pay_way'];
        $member_id = $input['member_id'];
        $member_name = $input['member_name'];
        $identity_card = $input['identity_card'];
        $local_phone = $input['local_phone'];
        $mobile_phone = $input['mobile_phone'];
        $other_phone = $input['other_phone'];
        $address = $input['address'];
        $note = $input['note'];

        try {
            DB::beginTransaction();
            DB::update("
                UPDATE `member` SET 
                `start_time`='{$start_time}',
                `end_time`='{$end_time}',
                `member_fee`='{$member_fee}',
                `pay_way`='{$pay_way}',
                `member_name`='{$member_name}',
                `identity_card`='{$identity_card}',
                `local_phone`='{$local_phone}',
                `mobile_phone`='{$mobile_phone}',
                `other_phone`='{$other_phone}',
                `address`='{$address}',
                `note`='{$note}'
                WHERE member_id = '{$member_id}'
            ");
            DB::commit();
            $json['msg'] = 'success';
        } catch (\Exception $e) {
            DB::rollback();
            $json['msg'] = 'error';
        }
        return $json;
    }

    private function del_member($input)
    {
        $member_id = $input['member_id'];
        try {
            DB::beginTransaction();
            DB::update("
                UPDATE `member` SET 
                `show_status`='1'
                WHERE member_id = '{$member_id}'
            ");
            DB::commit();
            $json['msg'] = 'success';
        } catch (\Exception $e) {
            DB::rollback();
            $json['msg'] = 'error';
        }

        return $json;
    }

    private function get_mou_info()
    {
        $total_mou_order = 0;
        $basic_value = 0;
        $achievement_rate = 0;
        $json = [];

        $mou_list = DB::select("
            SELECT mm.member_id, mm.status, mm.start_time, mm.end_time, m.member_name, mm.mou_id, mm.order_number, mm.change_status 
            FROM member_mou AS mm
            LEFT JOIN member AS m ON mm.member_id = m.member_id
        ");
        $today = date("Y-m-d H:i:s");
        foreach ($mou_list as $key => $val) {
            $total_mou_order += $val->order_number;
            $pass_date = floor( (strtotime($today) - strtotime($val->start_time) ) / 86400);
            // 判斷MOU單是否已超過可更改期限 && 先前沒有更改過
            if ($pass_date < 7 && $val->change_status == 0) {
                $val->btn = '<button class="btn btn-warning edit_btn" data-memberid="' . $val->member_id . '" data-mouid="' . $val->mou_id . '">修改</button>';
            } else {
                $val->btn = '<button class="btn btn-info edit_info_btn" data-memberid="' . $val->member_id . '" data-mouid="' . $val->mou_id . '">修改紀錄</button>';
            }
        }

        $basic_value = $total_mou_order * 120000;
        $achievement_rate = $total_mou_order * 100;

        $json['mou_list'] = $mou_list;
        $json['total_mou_order'] = $total_mou_order;
        $json['basic_value'] = $basic_value;
        $json['achievement_rate'] = $achievement_rate;

        return $json;
    }

    private function get_target_mou($input)
    {
        $member_id = $input['member_id'];
        $mou_id = $input['mou_id'];
        $json = [];

        $mou = DB::select("
            SELECT mm.mou_id, mm.member_id, mm.insert_time, mm.start_time, mm.end_time, mm.order_number, mm.amount_per_order, m.member_name, mm.change_text
            FROM member_mou AS mm
            LEFT JOIN member AS m ON mm.member_id = m.member_id 
            WHERE mm.member_id = '{$member_id}'
            AND mm.mou_id = '{$mou_id}'
        ");

        foreach ($mou as $key => $val) {
            // 只取年月日
            $start = explode(" ", $val->start_time);
            $end = explode(" ", $val->end_time);
            $val->start_time = $start[0];
            $val->end_time = $end[0];
        }

        $json['msg'] = 'success';
        $json['mou_info'] = $mou;

        return $json;
    }

    private function update_mou($input)
    {
        $member_id = $input['member_id'];
        $mou_id = $input['mou_id'];
        $start_time = $input['start_time'] . " 00:00:00";
        $end_time = $input['end_time'] . " 00:00:00";
        $mou_count = $input['mou_count'];
        $mou_pay = $input['mou_pay'];
        $cheange_text = $input['cheange_text'];

        try {
            DB::beginTransaction();
            DB::update("
                UPDATE `member_mou` SET `start_time` = '{$start_time}', `end_time` = '{$end_time}', `order_number` = {$mou_count}, `amount_per_order` = '{$mou_pay}', `change_text` = '{$cheange_text}', `change_status` = 1
                WHERE member_id = '{$member_id}'
                AND mou_id = '{$mou_id}'
            ");
            DB::commit();
            $json['msg'] = 'success';
        } catch (\Exception $e) {
            DB::rollback();
            $json['msg'] = 'error';
        }

        return $json;
    }

    private function pay_member_fee($input)
    {
        $member_id = $input['member_id'];
        $member_fee_give_date = date("Y-m-d H:i:s");
        try {
            DB::beginTransaction();
            DB::update("
                UPDATE `member` SET `member_fee_give_time` = '{$member_fee_give_date}', `pay_way` = 0
                WHERE member_id = '{$member_id}'
            ");
            DB::commit();
            $json['msg'] = 'success';
        } catch (\Exception $e) {
            DB::rollback();
            $json['msg'] = 'error';
        }

        return $json;
    }

    private function get_all_labor_fee()
    {
        $today = date("Y-m-d");
        $labor_fee = DB::select("
            SELECT mmonth.member_id, m.member_name, m.mobile_phone, mm.order_number, mm.amount_per_order, mmonth.id, mmonth.pay_month, mmonth.pay_date, mmonth.pay_status 
            FROM `mou_monthly` AS mmonth 
            LEFT JOIN member AS m ON mmonth.member_id = m.member_id 
            LEFT JOIN member_mou AS mm ON mmonth.member_id = mm.member_id 
            WHERE mmonth.pay_month <= '{$today}' 
            GROUP BY mmonth.id
        ");
        return $labor_fee;
    }

    private function pay_labor_fee($input)
    {
        $monthid = $input['monthid'];
        $today = date("Y-m-d");
        try {
            DB::beginTransaction();
            DB::update("
                UPDATE `mou_monthly` SET `pay_date` = '{$today}', `pay_status` = 0
                WHERE id = '{$monthid}'
            ");
            DB::commit();
            $json['msg'] = 'success';
        } catch (\Exception $e) {
            DB::rollback();
            $json['msg'] = 'error';
        }

        return $json;
    }

    private function search_labor_by_input($input)
    {
        $search_input = $input['search_input'];
        $pay_way = (isset($input['pay_way']) ? $input['pay_way'] : '');
        // echo $pay_way;
        $today = date("Y-m-d");
        $json = [];
        $labor_table_arr = [];
        $sql_text = '';
        if ($pay_way != '') {
            $sql_text = " OR mmonth.pay_status = {$pay_way}";
        }

        $labor_fee = DB::select("
            SELECT mmonth.member_id, m.member_name, m.mobile_phone, mm.order_number, mm.amount_per_order, mmonth.id, mmonth.pay_month, mmonth.pay_date, mmonth.pay_status 
            FROM `mou_monthly` AS mmonth 
            LEFT JOIN member AS m ON mmonth.member_id = m.member_id 
            LEFT JOIN member_mou AS mm ON mmonth.member_id = mm.member_id 
            WHERE (m.member_id = '{$search_input}' OR m.member_name = '{$search_input}' OR m.mobile_phone = '{$search_input}' $sql_text) 
            AND mmonth.pay_month <= '{$today}' 
            GROUP BY mmonth.id
        ");

        foreach ($labor_fee as $key => $val) {

            if ($val->pay_status != 0){
                $val->btn = '<button class="btn btn-info pay_btn" data-memberid="' . $val->member_id .'" data-monthid=" $val->id ">繳費</button>';
            } else {
                $val->btn = '';
            }

            if ($val->pay_status == 0) {
                $val->pay_status = "<span class='text-success'>已繳費</span>";
            } else {
                $val->pay_status = "<span class='text-danger'>未繳費</span>";
            }
            // table arr組合
            $labor_table_arr[] = [
                ($key + 1),
                $val->member_id,
                $val->member_name,
                $val->mobile_phone,
                $val->order_number,
                "$" . number_format($val->order_number * $val->amount_per_order),
                $val->pay_status,
                $val->pay_month,
                $val->pay_date,
                $val->btn
            ];
        }

        $json['msg'] = 'success';
        $json['labor_table_arr'] = $labor_table_arr;

        return $json;
    }

    private function search_member_fee_by_input($input)
    {
        $search_input = $input['search_input'];
        $pay_way = (isset($input['pay_way']) ? $input['pay_way'] : '');
        $sql_text = '';
        if ($pay_way != '') {
            $sql_text = " OR pay_way = {$pay_way}";
        }

        $member_table_arr = [];
        $member_list = DB::select("
            SELECT * 
            FROM member 
            WHERE show_status = 0 
            AND (member_id = '{$search_input}' OR member_name = '{$search_input}' OR mobile_phone = '{$search_input}' $sql_text)
            ORDER BY id DESC
        ");

        foreach ($member_list as $key => $val) {
            if ($val->pay_way == 0) {
                $val->pay_way = "<span class='text-success'>已繳交</span>";
            } else {
                $val->pay_way = "<span class='text-danger'>未繳交</span>";
            }
            $val->btn = '<button class="btn btn-info m-1 member_log" data-memberid="' . $val->member_id . '">會員紀錄</button>';
            if ($val->pay_way == 1) {
                $val->btn .= '<button class="btn btn-primary m-1 pay_btn" data-memberid="' . $val->member_id . '">繳交入會費</button>';
            }
            $member_table_arr[] = [
                $key+1,
                $val->member_id,
                $val->member_name,
                $val->join_time,
                $val->pay_way,
                "$" . number_format($val->member_fee),
                $val->member_fee_give_time,
                $val->btn
            ];
        }

        $json['member_table_arr'] = $member_table_arr;
        $json['msg'] = 'success';
        return $json;
    }



















    
}
