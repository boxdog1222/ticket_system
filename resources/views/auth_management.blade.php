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
        新增身分
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
                                <th>角色名稱(英)</th>
                                <th>角色名稱(中)</th>
                                <th>功能</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user_permissions as $val)
                                <tr>
                                    <td>{{ $val->id }}</td>
                                    <td>{{ $val->name }}</td>
                                    <td>{{ $val->premission_note }}</td>
                                    <td>
                                        <button class="btn btn-info edit_btn" data-userid="{{ $val->id }}">編輯</button>
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

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">權限列表</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>操作</th>
                                <th>頁面(英)</th>
                                <th>頁面(中)</th>
                                @foreach ($user_permissions as $value)
                                    <th>{{$value->premission_note}}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admin_authority as $key => $value)
                                <tr>
                                    <td>{{ $value->id }}</td>
                                    <td>
                                        <button class="btn btn-primary edit_permissions" data-permissionsid="{{ $value->id }}">編輯</button>
                                    </td>
                                    <td>{{ $value->page }}</td>
                                    <td>{{ $value->page_tw }}</td>
                                    
                                    @foreach ($user_permissions as $value2)
                                        <?php 
                                        $tmp_auth = unserialize($value->user); 
                                        ?>
                                        <td>
                                            <select class="form-control auth_select text-white {{ $tmp_auth[$value2->name] == 0 ? 'bg-danger' : 'bg-success' }}" data-pageid={{ $value->id }} data-user={{ $value2->name }}>
                                                <option value="0" {{ $tmp_auth[$value2->name] == 0 ? 'selected="selected"' : '' }}>關閉</option>
                                                <option value="1" {{ $tmp_auth[$value2->name] == 1 ? 'selected="selected"' : '' }}>啟用</option>
                                            </select>
                                        </td>
                                    @endforeach
                                    
                                </tr>
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
            <h5 class="modal-title" id="add_user_modal">新增身分</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12 mb-2">
                    <label class="text-danger"># 英文名稱僅供系統辨識用，不可重複，新增後不可修改</label>
                    <label class="text-danger"># 後續操作身分相關功能以中文為主</label>
                </div>
                <div class="col-lg-12 mb-2">
                    <label for="user_name">身分名稱(英)：</label>
                    <input class="form-control" type="text" id="permissions_en" value="">
                </div>
                <div class="col-lg-12 mb-2">
                    <label for="phone">身分名稱(中)：</label>
                    <input class="form-control" type="text" id="permissions_tw" value="">
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

<div class="modal fade" id="edit_user_modal" tabindex="-1" role="dialog" aria-labelledby="edit_user_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="edit_user_modal">編輯身分</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <input type="hidden" id="edit_userid" value="">
                <div class="col-lg-12 mb-2">
                    <label class="text-danger"># 英文名稱僅供系統辨識用，不可重複，新增後不可修改</label>
                    <label class="text-danger"># 後續操作身分相關功能以中文為主</label>
                </div>
                <div class="col-lg-12 mb-2">
                    <label for="user_name">身分名稱(英)：</label>
                    <input class="form-control" type="text" id="edit_permissions_en" value="" disabled>
                </div>
                <div class="col-lg-12 mb-2">
                    <label for="phone">身分名稱(中)：</label>
                    <input class="form-control" type="text" id="edit_permissions_tw" value="">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
            <button type="button" class="btn btn-primary" id="submit_edit_btn">更新</button>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_permissions_modal" tabindex="-1" role="dialog" aria-labelledby="edit_permissions_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="edit_permissions_modal">編輯</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12 mb-2">
                    <label class="text-danger"># 英文名稱請勿空格，更新後將以中文名稱提供選擇</label>
                </div>
                <div class="col-lg-12 mb-2">
                    <label for="user_name">頁面名稱(英)：</label>
                    <input class="form-control" type="text" id="page_en" value="">
                </div>
                <div class="col-lg-12 mb-2">
                    <label for="phone">頁面名稱(中)：</label>
                    <input class="form-control" type="text" id="page_tw" value="">
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
        $("#data_refresh_btn").on("click", function(){
            location.reload();
        });

        $("#add_btn").on("click", function(){
            $("#add_user_modal input").val("");
            $("#add_user_modal").modal("show");
        });

        $('body').on('keypress', '#add_user_modal input', function(e){
            if(e.keyCode == 13)
                $('#submit_add_btn').click();
        });

        $("#submit_add_btn").on("click", function(){
            var permissions_en = $("#permissions_en").val();
            var permissions_tw = $("#permissions_tw").val();

            if (permissions_en == "" || permissions_tw == "") {
                Swal.fire({
                    icon: 'error',
                    title: '必填未填',
                    text: '請填寫名稱'
                });
                return false;
            }

            $.ajax({
                method: "POST",
                url: '/backstage_management/json',
                data: {
                    "_token": '{{ csrf_token() }}', 
                    "method": "add_permissions",
                    "permissions_en": permissions_en,
                    "permissions_tw": permissions_tw,
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
                            text: '新增失敗，請聯繫工程師'
                        });
                    } else if (json.msg == "success") {
                        Swal.fire({
                            icon: 'success',
                            title: '成功',
                            text: '新增成功'
                        });
                        location.reload();
                    }
                    $('.load_mask_show').removeClass('load_mask_show');
                },
                error: function(xhr) {
                    $('.load_mask_show').removeClass('load_mask_show');
                    Swal.fire({
                        icon: 'error',
                        title: '錯誤',
                        text: '新增失敗，請聯繫工程師'
                    });
                }
            });
        });

        $(document).delegate(".edit_permissions", "click", function(){
            let permissions_id = $(this).data("permissionsid");
            $.ajax({
                method: "POST",
                url: '/backstage_management/json',
                data: {
                    "_token": '{{ csrf_token() }}', 
                    "method": "get_permissions_info",
                    "permissions_id": permissions_id,
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
                            text: '失敗，請聯繫工程師'
                        });
                    } else if (json.msg == "success") {
                        var permissions_info = json.permissions_info[0];
                        $("#page_en").val(permissions_info.page);
                        $("#page_tw").val(permissions_info.page_tw);

                        $("#edit_permissions_modal").modal("show")
                    }
                    $('.load_mask_show').removeClass('load_mask_show');
                },
                error: function(xhr) {
                    $('.load_mask_show').removeClass('load_mask_show');
                    Swal.fire({
                        icon: 'error',
                        title: '錯誤',
                        text: '失敗，請聯繫工程師'
                    });
                }
            });
        });

        $(document).delegate(".edit_btn", "click", function(){
            var userid = $(this).data("userid");

            $.ajax({
                method: "POST",
                url: '/backstage_management/json',
                data: {
                    "_token": '{{ csrf_token() }}', 
                    "method": "edit_username",
                    "userid": userid,
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
                            text: '新增失敗，請聯繫工程師'
                        });
                    } else if (json.msg == "success") {
                        let user_permissions_info = json.user_permissions_info[0];
                        $("#edit_userid").val(user_permissions_info.id);
                        $("#edit_permissions_en").val(user_permissions_info.name);
                        $("#edit_permissions_tw").val(user_permissions_info.premission_note);
                        $("#edit_user_modal").modal("show");
                    }
                    $('.load_mask_show').removeClass('load_mask_show');
                },
                error: function(xhr) {
                    $('.load_mask_show').removeClass('load_mask_show');
                    Swal.fire({
                        icon: 'error',
                        title: '錯誤',
                        text: '新增失敗，請聯繫工程師'
                    });
                }
            });
        });

        $("#submit_edit_btn").on("click", function(){
            let userid = $("#edit_userid").val();
            let name_tw = $("#edit_permissions_tw").val();

            $.ajax({
                method: "POST",
                url: '/backstage_management/json',
                data: {
                    "_token": '{{ csrf_token() }}', 
                    "method": "update_user",
                    "userid": userid,
                    "name_tw": name_tw,
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
                            text: '失敗，請聯繫工程師'
                        });
                    } else if (json.msg == "success") {
                        Swal.fire({
                            icon: 'success',
                            title: '成功',
                            text: '新增成功'
                        });
                        location.reload();
                    }
                    $('.load_mask_show').removeClass('load_mask_show');
                },
                error: function(xhr) {
                    $('.load_mask_show').removeClass('load_mask_show');
                    Swal.fire({
                        icon: 'error',
                        title: '錯誤',
                        text: '失敗，請聯繫工程師'
                    });
                }
            });
        });

        $(document).delegate(".auth_select", "change", function(){
            let pageid = $(this).data("pageid");
            let user = $(this).data("user");
            let select_option = $(this).val();

            console.log(pageid, user, select_option)
            $.ajax({
                method: "POST",
                url: '/backstage_management/json',
                data: {
                    "_token": '{{ csrf_token() }}', 
                    "method": "change_auth",
                    "pageid": pageid,
                    "user": user,
                    "select_option": select_option,
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
                            text: '調整失敗，請聯繫工程師'
                        });
                    } else if (json.msg == "success") {
                        location.reload();
                    }
                    $('.load_mask_show').removeClass('load_mask_show');
                },
                error: function(xhr) {
                    $('.load_mask_show').removeClass('load_mask_show');
                    Swal.fire({
                        icon: 'error',
                        title: '錯誤',
                        text: '調整失敗，請聯繫工程師'
                    });
                }
            });
        })
    });
</script>
@endsection