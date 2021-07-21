{{-- 會員資料 --}}

@extends('common.header')

@section('include_css')
<link href="assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('content')

<div class="row">
    <div class="col-lg-10">
        <div class="card shadow mb-4">
            <div class="card-header">
                問題列表
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="issue_table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>問題敘述</th>
                                <th>回報人員</th>
                                <th>建立時間</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($issue_table as $val)
                                <tr>
                                    <td>{{ $val->issue_id }}</td>
                                    <td>{{ $val->issue_title }}</td>
                                    <td>{{ $val->returner }}</td>
                                    <td>{{ $val->create_time }}</td>
                                    <td>
                                        {!! $val->btn !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if (Auth::user()->authority != "RD")
    <div class="col-lg-2">
        <div class="card shadow mb-4">
            <div class="card-header">
                功能列表
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <button class="btn btn-info form-control add_issue">新增問題</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- 新增issue Modal -->
<div class="modal fade" id="add_issue_modal" tabindex="-1" role="dialog" aria-labelledby="add_issue_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">新增問題</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label class="col-form-label">
                            問題種類
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <select class="form-control" id="add_issue_type">
                            @foreach ($issue_type as $type)
                                <option value="{{ $type->type_id }}">{{ $type->type_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label class="col-form-label">
                            回報對象
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <select class="form-control" id="add_issue_operator">
                            @foreach ($all_users as $users)
                                <option value="{{ $users->id }}">{{ $users->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label class="col-form-label">
                            標題
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" id="add_issue_title" placeholder="請輸入標題...">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label class="col-form-label">
                            問題敘述
                        </label>
                    </div>
                    <div class="col-lg-8">
                        <textarea class="form-control" id="add_issue_text" cols="30" rows="10"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="submit_add_issue">新增</button>
            </div>
        </div>
    </div>
</div>
<!-- ./新增issue Modal -->

<!-- 詳細issue Modal -->
<div class="modal fade" id="issue_info_modal" tabindex="-1" role="dialog" aria-labelledby="issue_info_modal" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <input type="hidden" id="hidden_issue_id">
                <!-- mou info -->
                <div class="col-lg-12" id="info_area">
                    
                </div>
                <!-- ./mou info -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
            <button type="button" class="btn btn-primary" id="submit_reply_issue">回覆</button>
        </div>
        </div>
    </div>
</div>

<!-- ./會員紀錄 Modal -->
@endsection

@section('include_js')

<script src="/assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="/assets/js/jquery-ui.min.js"></script>
<script src="/assets/js/jquery-ui-timepicker-addon.js"></script>
<script src="/assets/js/jquery-ui-timepicker-zh_tw.js"></script>

<script>
    var system_start_time = "{{ date('Y-m-01') }}";
    var system_end_time = "{{ date('Y-m-01', strtotime(date("Y-m-01", time()) . " + 13 months")) }}";
    var issue_table = $('#issue_table').DataTable({
        "language": { url: "{{ ('../assets/json/datatables_zh_tw.json') }}" }, //語系
        "order": [[ 0, "desc" ]],
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000], 
            [10, 25, 50, 100, 500, 1000]
        ],
        "scrollY": "500px",
        "scrollX": "100%",
        "scrollCollapse": true,
        "fixedColumns": {
            leftColumns: 2
        }
    });

    $('#start_time').datepicker({
        dateFormat: 'yy-mm-dd',
        showSecond: true,
        // timeFormat: 'HH:mm:ss',
        controlType:"select",
        showOn: 'button',
        buttonText: '<button class="btn btn-default" type="button"><i class="icon-calendar3" style="color: #c62828;"></i></button>',
        disabled: true
    });

    $('#end_time').datepicker({
        dateFormat: 'yy-mm-dd',
        showSecond: true,
        // timeFormat: 'HH:mm:ss',
        controlType:"select",
        showOn: 'button',
        buttonText: '<button class="btn btn-default" type="button"><i class="icon-calendar3" style="color: #c62828;"></i></button>',
        disabled: true
    });

    $('#start_time').on('change', function(e) {
        $('#end_time').datepicker("option", "minDate", $('#start_time').val());
        if ($('#start_time').val() > $('#end_time').val()) {
            $('#end_time').val($('#start_time').val());
        }
    });

    $(document).ready(function() {
        // 新增issue
        $(".add_issue").on("click", function(){
            $("#add_issue_modal").modal("show")
        });

        // 送出新增
        $("#submit_add_issue").on("click", function(){
            let issue_type = $("#add_issue_type").val();
            let issue_operator = $("#add_issue_operator").val();
            let issue_title = $("#add_issue_title").val();
            let issue_text = $("#add_issue_text").val();

            $.ajax({
                method: "POST",
                url: '/index/json',
                data: {
                    "_token": '{{ csrf_token() }}', 
                    "method": "add_issue",
                    "issue_type": issue_type,
                    "issue_operator": issue_operator,
                    "issue_title": issue_title,
                    "issue_text": issue_text,
                },
                dataType: 'json',
                beforeSend: function() {
                    $("#add_issue_modal").modal("hide");
                    $('.load_mask').addClass('load_mask_show');
                },
                success: function(json) {
                    if(json.msg == "error") {
                        Swal.fire({
                            icon: 'error',
                            title: '錯誤',
                            text: '讀取失敗，請稍後再試或連繫工程師'
                        });
                    } else if (json.msg == "success") {
                        Swal.fire({
                            icon: 'success',
                            title: '成功',
                            text: '新增問題成功'
                        });
                        issue_table.clear().draw();
                        issue_table.rows.add(json.issue_table).draw();
                    }
                    $('.load_mask_show').removeClass('load_mask_show');
                },
                error: function(xhr) {
                    $('.load_mask_show').removeClass('load_mask_show');
                    Swal.fire({
                        icon: 'error',
                        title: '錯誤',
                        text: '讀取失敗，請稍後再試或連繫工程師'
                    });
                }
            });
        })

        // 查看issue詳細內容
        $(document).delegate(".show_info", "click", function(){
            let issue_id = $(this).data("issueid");

            $.ajax({
                method: "POST",
                url: '/index/json',
                data: {
                    "_token": '{{ csrf_token() }}', 
                    "method": "get_issue_info",
                    "issue_id": issue_id,
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
                            text: '讀取失敗，請稍後再試或連繫工程師'
                        });
                    } else if (json.msg == "success") {
                        let issue_info = json.issue_info;
                        let issue_types = json.all_issue_type;
                        let all_users = json.all_users;
                        var info_html = '';
                        var all_issue_type = '';
                        var all_users_html = '';
                        info_html += "<div class='row'>" +
                                "<div class='col-lg-12'>" +
                                    "<div class='table-responsive'>" +
                                        "<table class='table table-bordered' style='width: 100%;'>" +
                                            "<tbody>" +
                                                "<tr>" +
                                                    "<td class='bg-info text-white'>ISSUE主旨: "+ json.issue_title +"</td>" +
                                                "</tr>" +
                                            "</tbody>" +
                                        "</table>" +
                                    "</div>" +
                                "</div>" +
                            "</div>";
                        for (let i = 0; i < issue_info.length; i++) {
                            var edit_html = '';
                            if (issue_info[i].owner == true) {
                                edit_html = "<textarea class='form-control mb-2 edit_issue_text'>" + issue_info[i].issue_text + "</textarea><button class='btn btn-info form-control'>更新</button>";
                            }
                            info_html += "<div class='row'>" +
                                "<div class='col-lg-12'>" +
                                    "<div class='table-responsive'>" +
                                        "<table class='table table-bordered' style='width: 100%;'>" +
                                            "<tbody>" +
                                                "<tr>" +
                                                    "<td class='bg-danger text-white' style='width: 15%'>#" + (i+1) + issue_info[i].type_name + ".</td>" +
                                                    "<td>TO: " + issue_info[i].name + "</td>" +
                                                "</tr>" +
                                                "<tr>" +
                                                    "<td colspan='12'>" +
                                                    // "<pre>回報內容: <br>" + issue_info[i].issue_text + "</pre>" + 
                                                    edit_html + 
                                                    "</td>" +
                                                "</tr>" +
                                                "<tr>" +
                                                    "<td colspan='12'>建立時間: " + issue_info[i].create_time + "</td>" +
                                                "</tr>" +
                                            "</tbody>" +
                                        "</table>" +
                                    "</div><hr>" +
                                "</div>" +
                            "</div>";
                        }

                        for (let i = 0; i < issue_types.length; i++) {
                            all_issue_type += "<option value='" + issue_types[i].type_id + "'>" + issue_types[i].type_name + "</option>";
                        }

                        for (let i = 0; i < all_users.length; i++) {
                            all_users_html += "<option value='" + all_users[i].id + "'>" + all_users[i].name + "</option>";
                        }

                        info_html += "<div class='row'>" +
                                "<div class='col-lg-12'>" +
                                    "<div class='table-responsive'>" +
                                        "<table class='table table-bordered' style='width: 100%;'>" +
                                            "<tbody>" +
                                                "<tr>" +
                                                    "<td>問題回覆</td>" +
                                                "</tr>" +
                                                "<tr>" +
                                                    "<td>" +
                                                    "<label>回應類型</label>" + 
                                                    "<select class='form-control mb-3' id='reply_issue_type'>" +
                                                        all_issue_type +
                                                    "</select>" +
                                                    "<label>回報對象</label>" + 
                                                    "<select class='form-control mb-3' id='reply_issue_operator'>" +
                                                        all_users_html +
                                                    "</select>" +
                                                    "<label>敘述</label>" + 
                                                    "<textarea class='form-control' cols='30' rows='10' placeholder='Say something...' id='reply_issue_text'></textarea>"
                                                    "</td>" +
                                                "</tr>" +
                                            "</tbody>" +
                                        "</table>" +
                                    "</div>" +
                                "</div>" +
                            "</div>";
                        $("#info_area").html(info_html);
                        $("#hidden_issue_id").val(issue_id)
                        $("#issue_info_modal").modal("show");
                    }
                    $('.load_mask_show').removeClass('load_mask_show');
                },
                error: function(xhr) {
                    $('.load_mask_show').removeClass('load_mask_show');
                    Swal.fire({
                        icon: 'error',
                        title: '錯誤',
                        text: '讀取失敗，請稍後再試或連繫工程師'
                    });
                }
            });
        });

        // 送出回覆
        $("#submit_reply_issue").on("click", function(){
            let issue_id = $("#hidden_issue_id").val();
            let issue_type = $("#reply_issue_type").val();
            let issue_operator = $("#reply_issue_operator").val();
            let issue_text = $("#reply_issue_text").val();

            Swal.fire({
                title: '確定送出回覆？',
                text: '點擊確認已繼續動作',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '確認',
                cancelButtonText: '取消'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        method: "POST",
                        url: '/index/json',
                        data: {
                            "_token": '{{ csrf_token() }}', 
                            "method": "reply_issue",
                            "issue_id": issue_id,
                            "issue_type": issue_type,
                            "issue_operator": issue_operator,
                            "issue_text": issue_text,
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $("#issue_info_modal").modal("hide")
                            $('.load_mask').addClass('load_mask_show');
                        },
                        success: function(json) {
                            if(json.msg == "error") {
                                Swal.fire({
                                    icon: 'error',
                                    title: '錯誤',
                                    text: '回覆失敗，請稍後再試'
                                });
                            } else if (json.msg == "success") {
                                Swal.fire({
                                    icon: 'success',
                                    title: '成功',
                                    text: '回覆成功'
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
        }); 
























        // 新增會員btn click
        $("#add_member_btn").on("click", function(){
            $("#add_member_modal").modal("show");
        });

        // 送出新增會員btn click
        $("#submit_add_btn").on("click", function(){
            var check_status = true;
            $('.required_input').each(function() { 
                if($(this).val().trim() == ''){
                    $(this).addClass("is-invalid");
                    check_status = false;
                } else {
                    $(this).removeClass("is-invalid");
                }
            });
            if (!check_status) {
                Swal.fire({
                    icon: 'error',
                    title: '錯誤',
                    text: '必填未填，請確認後再送出'
                });
                return false;
            }

            let start_time = $("#start_time").val();
            let end_time = $("#end_time").val();
            let member_fee = $("#member_fee").val();
            let pay_way = $("input[name='inlineRadioOptions']:checked").val();
            let member_name = $("#member_name").val();
            let identity_card = $("#identity_card").val();
            let local_phone = $("#local_phone").val();
            let mobile_phone = $("#mobile_phone").val();
            let other_phone = $("#other_phone").val();
            let address = $("#address").val();
            let note = $("#note").val();
            
            Swal.fire({
                title: '送件確認',
                text: '是否確定送件?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '是',
                cancelButtonText: '否'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        method: "POST",
                        url: '/index/json',
                        data: {
                            "_token": '{{ csrf_token() }}', 
                            "method": "add_member",
                            "start_time": start_time,
                            "end_time": end_time,
                            "member_fee": member_fee,
                            "pay_way": pay_way,
                            "member_name": member_name,
                            "identity_card": identity_card,
                            "local_phone": local_phone,
                            "mobile_phone": mobile_phone,
                            "other_phone": other_phone,
                            "address": address,
                            "note": note
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $("#add_member_modal").modal("hide");
                            $('.load_mask').addClass('load_mask_show');
                        },
                        success: function(json) {
                            if(json.msg == "error") {
                                Swal.fire({
                                    icon: 'error',
                                    title: '錯誤',
                                    text: '新增失敗，請稍後再試或連繫工程師'
                                });
                            } else if (json.msg == "success") {
                                $("#success_modal .member_id").text(json.member_id);
                                $("#success_modal .member_name").text(member_name);
                                $("#success_modal .mobile_phone").text(mobile_phone);
                                $("#success_modal .time_limit").html(start_time + '<br>~<br>' + end_time);
                                $("#success_modal input[name='pay_status'][value='"+ pay_way +"']").attr("checked", true);
                                $("#success_modal").modal("show");
                            }
                            $('.load_mask_show').removeClass('load_mask_show');
                        },
                        error: function(xhr) {
                            $('.load_mask_show').removeClass('load_mask_show');
                            Swal.fire({
                                icon: 'error',
                                title: '錯誤',
                                text: '新增失敗，請稍後再試或連繫工程師'
                            });
                        }
                    });
                }
            })
        });

        // 資料更新btn
        $("#data_refresh_btn").on("click", function(){
            location.reload();
        });

        // 新增勞務MOU
        $(document).delegate(".add_mou", "click", function(){
            let member_id = $(this).data("memberid");
            
            $.ajax({
                method: "POST",
                url: '/index/json',
                data: {
                    "_token": '{{ csrf_token() }}', 
                    "method": "search_member_mou",
                    "member_id": member_id
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
                            text: '請稍後再試或連繫工程師'
                        });
                    } else if (json.msg == "success") {
                        console.log(json)
                        $("#mou_id").html("單號: " + json.mou_id);
                        $("#add_mou_member_id").val(member_id)
                        $("#add_mou_id").val(json.mou_id);
                        $("#mou_member_id").html(json.member_info[0].member_id);
                        $("#mou_member_name").html(json.member_info[0].member_name);
                        $("#add_mou_modal").modal("show");
                    }
                    $('.load_mask_show').removeClass('load_mask_show');
                },
                error: function(xhr) {
                    $('.load_mask_show').removeClass('load_mask_show');
                    Swal.fire({
                        icon: 'error',
                        title: '錯誤',
                        text: '請稍後再試或連繫工程師'
                    });
                }
            });
        });

        // MOU單數-
        $("#mou_count_less").on("click", function(){
            var mou_count = $("#mou_count").val()
            mou_count--;
            if (mou_count >= 0) {
                $("#mou_count").val(mou_count);
                $("#mou_count").change()
            }
        });

        // MOU單數+
        $("#mou_count_add").on("click", function(){
            var mou_count = $("#mou_count").val()
            mou_count++;
            $("#mou_count").val(mou_count);
            $("#mou_count").change()
        });

        // MOU單數改變時
        $("#mou_count").on("change", function(){
            if ($(this).val() <= 0) {
                $(this).val(0);
            }
            var mou_total_pay = $("#mou_count").val() * $("#mou_pay").val()
            $("#mou_total_pay").html("$ " + formatNumber(mou_total_pay) + " 元" );
        });

        // 每單需繳交focusout
        $("#mou_pay").focusout(function(){
            var mou_total_pay = $("#mou_count").val() * $("#mou_pay").val()
            $("#mou_total_pay").html("$ " + formatNumber(mou_total_pay) + " 元" );
        });

        // 新增MOU btn click
        $("#submit_add_mou_btn").on("click", function(){
            let member_id = $("#add_mou_member_id").val();
            let mou_id = $("#add_mou_id").val();
            let mou_start_time = $("#mou_start_time").val();
            let mou_end_time = $("#mou_end_time").val();
            let mou_count = $("#mou_count").val();
            let mou_pay = $("#mou_pay").val();
            // let mou_pay_way = $("input[name='add_mou_radio']:checked").val();
            Swal.fire({
                title: '送件確認',
                text: '是否確定送件?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '是',
                cancelButtonText: '否'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        method: "POST",
                        url: '/index/json',
                        data: {
                            "_token": '{{ csrf_token() }}', 
                            "method": "add_mou",
                            "mou_id": mou_id,
                            "member_id": member_id,
                            "insert_time": mou_start_time,
                            "start_time": mou_start_time,
                            "end_time": mou_end_time,
                            "order_number": mou_count,
                            "amount_per_order": mou_pay,
                            // "pay_statue": mou_pay_way,
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $("#add_mou_modal").modal("hide");
                            $('.load_mask').addClass('load_mask_show');
                        },
                        success: function(json) {
                            if(json.msg == "error") {
                                Swal.fire({
                                    icon: 'error',
                                    title: '錯誤',
                                    text: '新增失敗，請稍後再試或連繫工程師'
                                });
                            } else if (json.msg == "success") {
                                console.log(json)
                                $("#mou_success_modal .mou_id").html(json.mou_info.mou_id);
                                $("#mou_success_modal .mou_member_id").html(json.mou_info.member_id);
                                $("#mou_success_modal .mou_member_name").html(json.mou_info.member_name);
                                $("#mou_success_modal .mou_insert_time").html(json.mou_info.insert_time);
                                $("#mou_success_modal .mou_fresh_time").html(json.mou_info.start_time + "<br>~<br>" + json.mou_info.end_time);
                                $("#mou_success_modal .mou_order_number").html(json.mou_info.order_number);
                                $("#mou_success_modal").modal("show");
                            }
                            $('.load_mask_show').removeClass('load_mask_show');
                        },
                        error: function(xhr) {
                            $('.load_mask_show').removeClass('load_mask_show');
                            Swal.fire({
                                icon: 'error',
                                title: '錯誤',
                                text: '新增失敗，請稍後再試或連繫工程師'
                            });
                        }
                    });
                }
            })
        });

        // 使modal內table表頭正常顯示
        $('#member_mou_info_modal').on('shown.bs.modal', function (e) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        });

        $(document).delegate(".member_log", "click", function(){
            let member_id = $(this).data("memberid")
            $.ajax({
                method: "POST",
                url: '/index/json',
                data: {
                    "_token": '{{ csrf_token() }}', 
                    "method": "search_member_log",
                    "member_id": member_id
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
                            text: '請稍後再試或連繫工程師'
                        });
                    } else if (json.msg == "success") {
                        console.log(json)
                        var member_info = json.member_info[0];
                        if (member_info.pay_way == 0) {
                            member_info.pay_way = "已繳費";
                        } else {
                            member_info.pay_way = "未繳費";
                        }
                        // member_info
                        $("#member_mou_info_modal .info_member_fee").html(formatNumber(member_info.member_fee) + "元");
                        $("#member_mou_info_modal .info_pay_way").html(member_info.pay_way);
                        $("#member_mou_info_modal .info_member_id").html(member_info.member_id);
                        $("#member_mou_info_modal .info_fresh_time").html(member_info.start_time + "<br>" + member_info.end_time);
                        $("#member_mou_info_modal .info_member_name").html(member_info.member_name);
                        $("#member_mou_info_modal .info_identity_card").html(member_info.identity_card);
                        $("#member_mou_info_modal .info_local_phone").html(member_info.local_phone);
                        $("#member_mou_info_modal .info_mobile_phone").html(member_info.mobile_phone);
                        $("#member_mou_info_modal .info_other_phone").html(member_info.other_phone);
                        $("#member_mou_info_modal .info_address").html(member_info.address);
                        $("#member_mou_info_modal .info_note").html('<pre class="col-form-label">'+member_info.note+'</pre>');

                        member_mou_info_table.clear().draw();
                        member_mou_info_table.rows.add(json.mou_info).draw();
                        $("#mou_execution_status_table .total_mou_order_count").html(formatNumber(json.total_mou_order_count) + "單")
                        $("#mou_execution_status_table .basic_val").html(formatNumber(json.basic_val))
                        $("#mou_execution_status_table .completion_rate").html(formatNumber(json.completion_rate) + "%")
                        $("#member_mou_info_modal").modal("show")
                    }
                    $('.load_mask_show').removeClass('load_mask_show');
                },
                error: function(xhr) {
                    $('.load_mask_show').removeClass('load_mask_show');
                    Swal.fire({
                        icon: 'error',
                        title: '錯誤',
                        text: '請稍後再試或連繫工程師'
                    });
                }
            });
        });
    });

</script>
@endsection