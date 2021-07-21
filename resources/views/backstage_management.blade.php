@extends('common.header')

@section('include_css')
<link href="/assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        {{ $title }}
        <button class="btn btn-primary m-2" id="data_refresh_btn">資料更新</button>
    </h1>
    <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" id="add_btn">
        <i class="fas fa-plus fa-sm text-white-50"></i>
        新增帳號
    </button>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">帳號列表</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>帳號</th>
                                <th>狀態</th>
                                <th>身分</th>
                                <th>手機號碼</th>
                                <th>建立時間</th>
                                <th>功能</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key => $val)
                                @if ($users[$key]->name == "admin")
                                <tr>
                                    <td>{{ $users[$key]->id }}</td>
                                    <td>{{ $users[$key]->name }}</td>
                                    <td>
                                        @if ($users[$key]->status == 0)
                                            停權
                                        @else
                                            啟用
                                        @endif
                                    </td>
                                    <td>{{ $users[$key]->authority }}</td>
                                    <td>{{ $users[$key]->phone }}</td>
                                    <td>{{ $users[$key]->created_at }}</td>
                                    <td>
                                        -
                                    </td>
                                </tr>    
                                @else
                                <tr>
                                    <td>{{ $users[$key]->id }}</td>
                                    <td>{{ $users[$key]->name }}</td>
                                    <td>
                                        <select class="form-control user_status" data-userid="{{ $users[$key]->id }}">
                                            <option value="0" {{ ($users[$key]->status == 0 ? "selected" : "") }}>停權</option>
                                            <option value="1" {{ ($users[$key]->status == 1 ? "selected" : "") }}>啟用</option>
                                        </select>
                                    </td>
                                    <td>
                                        {{-- {{ $users[$key]->authority }} --}}
                                        <select class="form-control user_auth" data-userid="{{ $users[$key]->id }}">
                                            @foreach ($user_permissions as $key2 => $val2)
                                                <option value="{{$val2->name}}" {{ ($users[$key]->authority == $val2->premission_note ? "selected" : "") }}>{{ $val2->premission_note }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>{{ $users[$key]->phone }}</td>
                                    <td>{{ $users[$key]->created_at }}</td>
                                    <td>
                                        <button class="btn btn-warning reset-btn" data-userid="{{ $users[$key]->id }}">重置密碼</button>
                                        <button class="btn btn-danger del-btn" data-userid="{{ $users[$key]->id }}">刪除</button>
                                    </td>
                                </tr>
                                @endif
                                
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="add_user_modal" tabindex="-1" role="dialog" aria-labelledby="add_user_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="add_user_modal">新增帳號</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12 mb-2">
                    <label class="text-danger"># 密碼預設為：0000</label>
                </div>
                <div class="col-lg-12 mb-2">
                    <label class="text-danger"># 狀態預設為：開啟</label>
                    <hr>
                </div>
                <div class="col-lg-12 mb-2">
                    <label for="user_name">新增帳號：</label>
                    <input class="form-control" type="text" id="user_name" value="">
                </div>
                <div class="col-lg-12 mb-2">
                    <label for="phone">手機：</label>
                    <input class="form-control" type="text" id="phone" value="">
                </div>
                <div class="col-lg-12 mb-2">
                    <label for="user_name">身分：</label>
                    <select class="form-control" id="user_permissions">
                        @foreach ($user_permissions as $ley => $val)
                            <option value="{{$val->name}}">{{ $val->premission_note }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
            <button type="button" class="btn btn-primary" id="submit_add_btn">新增</button>
        </div>
        </div>
    </div>
</div>
@endsection

@section('include_js')

<script src="/assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        // 資料更新btn
        $("#data_refresh_btn, #click_and_refresh").on("click", function(){
            location.reload();
        });

        var user_table = $('#dataTable').DataTable({
            "language": { url: "{{ ('../assets/json/datatables_zh_tw.json') }}" }, //語系
        });

        $('body').on('keypress', '#add_user_modal input', function(e){
            if(e.keyCode == 13)
                $('#submit_add_btn').click();
        });

        $("#add_btn").on("click", function(){
            $("#add_user_modal input").val("");
            $("#add_user_modal").modal("show");
        });

        $('#add_user_modal').on('shown.bs.modal', function() {
            $('#user_name').focus();
        });

        $("#submit_add_btn").on("click", function(){
            var user_name = $("#user_name").val();
            var phone = $("#phone").val();
            var user_permissions = $("#user_permissions").val();

            if (user_name == "") {
                Swal.fire({
                    icon: 'error',
                    title: '必填未填',
                    text: '請填寫帳號'
                });
                return false;
            }

            $.ajax({
                method: "POST",
                url: '/backstage_management/json',
                data: {
                    "_token": '{{ csrf_token() }}', 
                    "method": "add_user",
                    "user_name": user_name,
                    "phone": phone,
                    "user_permissions": user_permissions,
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#add_user_modal").modal("hide");
                    $('.load_mask').addClass('load_mask_show');
                },
                success: function(json) {
                    if(json.msg == 'name_repeat') {
                        Swal.fire({
                            icon: 'error',
                            title: '錯誤',
                            text: '帳號已存在'
                        });
                    } else if(json.msg == "error") {
                        Swal.fire({
                            icon: 'error',
                            title: '錯誤',
                            text: '新增失敗，請聯繫工程師'
                        });
                    } else if (json.msg == "success") {
                        Swal.fire({
                            icon: 'success',
                            title: '成功',
                            text: '新增成功'
                        });
                        table_refresh();
                    }
                    $('.load_mask_show').removeClass('load_mask_show');
                },
                error: function(xhr) {
                    $('.load_mask_show').removeClass('load_mask_show');
                    Swal.fire({
                        icon: 'error',
                        title: '錯誤',
                        text: '重置失敗，請聯繫工程師'
                    });
                }
            });
        });

        $(document).delegate(".reset-btn", "click", function(){
            let user_id = $(this).data("userid");
            Swal.fire({
                title: '確定重置？',
                text: '該帳號的密碼將重置為：0000',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '確認',
                cancelButtonText: '取消'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        method: "POST",
                        url: '/backstage_management/json',
                        data: {
                            "_token": '{{ csrf_token() }}', 
                            "method": "reset_pwd",
                            "id": user_id
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $('.load_mask').addClass('load_mask_show');
                        },
                        success: function(json) {
                            console.log(json)
                            if(json.msg == "error") {
                                Swal.fire({
                                    icon: 'error',
                                    title: '錯誤',
                                    text: '重置失敗，請聯繫工程師'
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
                                text: '重置失敗，請聯繫工程師'
                            });
                        }
                    });
                }
            })
        });

        $(document).delegate(".del-btn", "click", function(){
            let user_id = $(this).data("userid");
            Swal.fire({
                title: '確定刪除？',
                text: '即將進行刪除動作',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '確認',
                cancelButtonText: '取消'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        method: "POST",
                        url: '/backstage_management/json',
                        data: {
                            "_token": '{{ csrf_token() }}', 
                            "method": "del_user",
                            "id": user_id
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $("#add_user_modal").modal("hide");
                            $('.load_mask').addClass('load_mask_show');
                        },
                        success: function(json) {
                            if(json.msg == "error") {
                                Swal.fire({
                                    icon: 'error',
                                    title: '錯誤',
                                    text: '刪除失敗，請聯繫工程師'
                                });
                            } else if (json.msg == "success") {
                                Swal.fire({
                                    icon: 'success',
                                    title: '成功',
                                    text: '刪除成功'
                                });
                                table_refresh();
                            }
                            $('.load_mask_show').removeClass('load_mask_show');
                        },
                        error: function(xhr) {
                            $('.load_mask_show').removeClass('load_mask_show');
                            Swal.fire({
                                icon: 'error',
                                title: '錯誤',
                                text: '重置失敗，請聯繫工程師'
                            });
                        }
                    });
                }
            })
        });

        $(document).delegate(".user_status", "change", function(){
            let user_id = $(this).data("userid");
            let status = $(this).val();
            console.log(user_id, status)
            $.ajax({
                method: "POST",
                url: '/backstage_management/json',
                data: {
                    "_token": '{{ csrf_token() }}',
                    "method": "status_change",
                    "id": user_id,
                    "status": status
                },
                dataType: 'json',
                beforeSend: function() {
                    $('.load_mask').addClass('load_mask_show');
                },
                success: function(json) {
                    if (json.msg == "error") {
                        Swal.fire({
                            icon: 'error',
                            title: '錯誤',
                            text: '狀態更新失敗，請聯繫工程師'
                        });
                    } else if (json.msg == "success") {
                        Swal.fire({
                            icon: 'success',
                            title: '成功',
                            text: '狀態更新成功'
                        });
                    }
                    table_refresh();
                    $('.load_mask_show').removeClass('load_mask_show');
                },
                error: function(xhr) {
                    $('.load_mask_show').removeClass('load_mask_show');
                    Swal.fire({
                        icon: 'error',
                        title: '錯誤',
                        text: '狀態失敗，請聯繫工程師'
                    });
                }
            });
        })

        $(document).delegate(".user_auth", "change", function(){
            let user_id = $(this).data("userid");
            let auth = $(this).val();

            console.log(user_id, auth)

            $.ajax({
                method: "POST",
                url: '/backstage_management/json',
                data: {
                    "_token": '{{ csrf_token() }}',
                    "method": "auth_change",
                    "user_id": user_id,
                    "auth": auth
                },
                dataType: 'json',
                beforeSend: function() {
                    $('.load_mask').addClass('load_mask_show');
                },
                success: function(json) {
                    if (json.msg == "error") {
                        Swal.fire({
                            icon: 'error',
                            title: '錯誤',
                            text: '權限更新失敗，請聯繫工程師'
                        });
                    } else if (json.msg == "success") {
                        Swal.fire({
                            icon: 'success',
                            title: '成功',
                            text: '權限更新成功'
                        });
                    }
                    $('.load_mask_show').removeClass('load_mask_show');
                },
                error: function(xhr) {
                    $('.load_mask_show').removeClass('load_mask_show');
                    Swal.fire({
                        icon: 'error',
                        title: '錯誤',
                        text: '權限失敗，請聯繫工程師'
                    });
                }
            });
        });

        function table_refresh()
        {
            $.ajax({
                method: "POST",
                url: '/backstage_management/json',
                data: {
                    "_token": '{{ csrf_token() }}',
                    "method": "refresh_table"
                },
                dataType: 'json',
                beforeSend: function() {
                    $('.load_mask').addClass('load_mask_show');
                },
                success: function(json) {
                    user_table.clear().draw();
                    user_table.rows.add(json).draw();
                    $('.load_mask_show').removeClass('load_mask_show');
                },
                error: function(xhr) {
                    $('.load_mask_show').removeClass('load_mask_show');
                    Swal.fire({
                        icon: 'error',
                        title: '錯誤',
                        text: '重置失敗，請聯繫工程師'
                    });
                }
            });
        }
    });
</script>
@endsection