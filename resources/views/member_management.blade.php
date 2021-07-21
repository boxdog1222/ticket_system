@extends('common.header')

@section('include_css')
<link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">功能列表</h1>
    <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="add_btn">
        <i class="fas fa-plus fa-sm text-white-50"></i>
        新增帳號
    </button>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                功能
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">用戶</label>
                            <input type="text" class="form-control" id="account" placeholder="輸入用戶帳號/暱稱...">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">國家</label>
                            <select class="form-control" name="country" id="country">
                                <option value="taiwan">台灣</option>
                                <option value="honkong">香港</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group text-right">
                            <button class="btn btn-primary">搜尋</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                玩家列表
            </div>
            <div class="card-body">
                

                <div class="table-responsive">
                    <table class="table table-bordered" id="user_list_table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    <nobr>帳號</nobr>
                                </th>
                                <th>
                                    <nobr>暱稱</nobr>
                                </th>
                                <th>
                                    <nobr>國家</nobr>
                                </th>
                                <th>
                                    <nobr>儲值金額</nobr>
                                </th>
                                <th>
                                    <nobr>總押分</nobr>
                                </th>
                                <th>
                                    <nobr>總贏分</nobr>
                                </th>
                                <th>
                                    <nobr>K鑽</nobr>
                                </th>
                                <th>
                                    <nobr>金幣</nobr>
                                </th>
                                <th>
                                    <nobr>總贈幣 - 金幣</nobr>
                                </th>
                                <th>
                                    <nobr>銀幣</nobr>
                                </th>
                                <th>
                                    <nobr>總贈幣 - 銀幣</nobr>
                                </th>
                                <th>
                                    <nobr>手機號碼</nobr>
                                </th>
                                <th>
                                    <nobr>登入方式</nobr>
                                </th>
                                <th>
                                    <nobr>推薦人帳號/暱稱<br>(上線)</nobr>
                                </th>
                                <th>
                                    <nobr>推薦人數<br>(下線)</nobr>
                                </th>
                                <th>
                                    <nobr>目前回饋</nobr>
                                </th>
                                <th>
                                    <nobr>註冊日</nobr>
                                </th>
                                <th>
                                    <nobr>玩家狀態</nobr>
                                </th>
                                <th>
                                    <nobr>操作</nobr>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user_list as $key => $val)
                                <tr>
                                    <td>{{ $val->UID }}</td>
                                    <td>{{ $val->Account }}</td>
                                    <td>{{ $val->Name }}</td>
                                    <td>{{ $val->Country }}</td>
                                    <td>No data</td>
                                    <td>No data</td>
                                    <td>No data</td>
                                    <td>{{ $val->DPoint }}</td>
                                    <td>{{ $val->GPoint }}</td>
                                    <td>No data</td>
                                    <td>No data</td>
                                    <td>No data</td>
                                    <td>{{ $val->Phone }}</td>
                                    <td>No data</td>
                                    <td>{{ $val->RefParent }}</td>
                                    <td>No data</td>
                                    <td>No data</td>
                                    <td>{{ $val->Create_Date }}</td>
                                    <td>{{ ($val->PlayerOnline == 1 ? '在線' : '離線') }}</td>
                                    <td style="white-space: nowrap;">
                                        <button class="btn btn-outline-danger lock_btn" data-memberid="{{ $val->UID }}" data-account="{{ $val->Account }}">鎖住帳號</button>
                                        <button class="btn btn-outline-warning change_pwd_btn" data-memberid="{{ $val->UID }}">修改密碼</button>
                                        <button class="btn btn-outline-info game_history_btn" data-memberid="{{ $val->UID }}">遊戲紀錄</button>
                                        <button class="btn btn-outline-info login_history_btn" data-memberid="{{ $val->UID }}">登入紀錄</button>
                                        <button class="btn btn-outline-danger del_btn" data-memberid="{{ $val->UID }}" data-account="{{ $val->Account }}">刪除帳號</button>
                                        <button class="btn btn-outline-info give_history_btn" data-memberid="{{ $val->UID }}">贈幣紀錄</button>
                                        <button class="btn btn-outline-warning store_btn" data-memberid="{{ $val->UID }}">直接儲值</button>
                                        <button class="btn btn-outline-danger black_list_btn" data-memberid="{{ $val->UID }}">加入黑名單</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('include_js')

<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function() {
        var user_table = $('#user_list_table').DataTable({
        "language": { url: "{{ ('../assets/json/datatables_zh_tw.json') }}" }, //語系
            "order": [[ 0, "desc" ]],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "scrollY": "500px",
            "scrollX": "70%",
            "scrollCollapse": true,
            "fixedColumns": {
                leftColumns: 2
            }
        });

        // 封鎖btn click
        $(document).delegate(".lock_btn", "click", function(){
            let mid = $(this).data("memberid");
            let account = $(this).data("account");

            Swal.fire({
                title: '確定封鎖該帳號？',
                text: '目標帳號: ' + account,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '確認',
                cancelButtonText: '取消'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        method: "POST",
                        url: '/member_management/json',
                        data: {
                            "_token": '{{ csrf_token() }}', 
                            "method": "member_banned",
                            "mid": mid
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $('.load_mask').addClass('load_mask_show');
                        },
                        success: function(json) {
                            if(json.msg == "error") {
                                Swal.fire({
                                    icon: 'error',
                                    title: '錯誤',
                                    text: '封鎖失敗，請稍後再試'
                                });
                            } else if (json.msg == "success") {
                                Swal.fire({
                                    icon: 'success',
                                    title: '成功',
                                    text: '重置成功'
                                });
                            }
                            $('.load_mask_show').removeClass('load_mask_show');
                        },
                        error: function(xhr) {
                            $('.load_mask_show').removeClass('load_mask_show');
                            Swal.fire({
                                icon: 'error',
                                title: '錯誤',
                                text: '程式錯誤，請聯繫RD人員'
                            });
                        }
                    });
                }
            })
        }); // ./封鎖btn click

        // 刪除帳號btn click
        $(document).delegate(".del_btn", "click", function(){
            let mid = $(this).data("memberid");
            let account = $(this).data("account");
            Swal.fire({
                title: '確定刪除該帳號？',
                text: '刪除帳號: ' + account + "，刪除後該會員相關資料將一併清除，請確認無誤再執行，已刪除的帳號將無法找回",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '確認',
                cancelButtonText: '取消'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        method: "POST",
                        url: '/member_management/json',
                        data: {
                            "_token": '{{ csrf_token() }}', 
                            "method": "member_delete",
                            "mid": mid
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $('.load_mask').addClass('load_mask_show');
                        },
                        success: function(json) {
                            if(json.msg == "error") {
                                Swal.fire({
                                    icon: 'error',
                                    title: '錯誤',
                                    text: '刪除失敗，請稍後再試'
                                });
                            } else if (json.msg == "success") {
                                Swal.fire({
                                    icon: 'success',
                                    title: '成功',
                                    text: '刪除成功'
                                });
                            }
                            $('.load_mask_show').removeClass('load_mask_show');
                        },
                        error: function(xhr) {
                            $('.load_mask_show').removeClass('load_mask_show');
                            Swal.fire({
                                icon: 'error',
                                title: '錯誤',
                                text: '程式錯誤，請聯繫RD人員'
                            });
                        }
                    });
                }
            })
        }); // ./刪除帳號btn click

    });
</script>
@endsection