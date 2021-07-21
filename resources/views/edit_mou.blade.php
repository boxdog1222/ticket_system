{{-- 會員資料 --}}

@extends('common.header')

@section('include_css')
<link href="/assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 mb-3">
        <button class="btn btn-primary m-2" id="data_refresh_btn">資料更新</button>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-lg-3">
                        <label>總執行單數: {{ $total_mou_order }}</label>
                    </div>
                    <div class="col-lg-3">
                        <label>基本數值: {{ $basic_value }}</label>
                    </div>
                    <div class="col-lg-3">
                        <label>達成率: {{ $achievement_rate }}%</label>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="member_table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    <nobr>會員編號</nobr>
                                </th>
                                <th>
                                    <nobr>狀態</nobr>
                                </th>
                                <th>
                                    <nobr>MOU期限</nobr>
                                </th>
                                <th>
                                    <nobr>會員姓名</nobr>
                                </th>
                                <th>
                                    <nobr>會員單號</nobr>
                                </th>
                                <th>
                                    <nobr>入單數</nobr>
                                </th>
                                <th>
                                    <nobr>操作</nobr>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mou_list as $key => $val)
                                <tr>
                                    <td>{{ $key +1 }}</td>
                                    <td>{{ $val->member_id }}</td>
                                    <td>{!! ($val->status == 0 ? "<span class='text-success'>執行中</span>" : "<span class='text-defult'>已過期</span>") !!}</td>
                                    <td>{!! $val->start_time . "<br>~<br>" . $val->end_time !!}</td>
                                    <td>{{ $val->member_name }}</td>
                                    <td>{{ $val->mou_id }}</td>
                                    <td>{{ $val->order_number }}</td>
                                    <td>{!! $val->btn !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 修改勞務MOU Modal -->
<div class="modal fade" id="edit_mou_modal" tabindex="-1" role="dialog" aria-labelledby="edit_mou_modal" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">修改勞務MOU</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_mou_member_id" value="">
                <input type="hidden" id="edit_mou_id" value="">
                <div class="row mb-3">
                    <div class="col-lg-12 text-right">
                        <label id="mou_id"></label>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label class="col-form-label">
                            會員編號
                        </label>
                    </div>
                    <div class="col-lg-8 text-center">
                        <label class="col-form-label" id="mou_member_id"></label>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label class="col-form-label">
                            會員姓名
                        </label>
                    </div>
                    <div class="col-lg-8 text-center">
                        <label class="col-form-label" id="mou_member_name"></label>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label class="col-form-label">
                            入單日期
                        </label>
                    </div>
                    <div class="col-lg-8 text-center">
                        <label class="col-form-label" id="mou_insert_time">{{ date('Y-m-01') }}</label>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label class="col-form-label">
                            <small class="text-danger">* </small>
                            此MOU期限
                        </label>
                    </div>
                    <div class="col-lg-8 text-center">
                        <div class="input-group">
                            <input type="text" name="time" id="mou_start_time" class="form-control required_input" placeholder="年-月-日 時:分:秒" maxlength="19" value="{{ date('Y-m-01') }}" style="z-index: 1050;">
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label class="col-form-label">
                            
                        </label>
                    </div>
                    <div class="col-lg-8 text-center">
                        <div class="input-group">
                            <input type="text" name="time" id="mou_end_time" class="form-control required_input" placeholder="年-月-日 時:分:秒" maxlength="19" value="{{ date('Y-m-01', strtotime(date("Y-m-01", time()) . " + 13 months")) }}" style="z-index: 1050;">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label class="col-form-label">
                            
                        </label>
                    </div>
                    <div class="col-lg-8 text-center">
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="mou_checkbox">
                            <label class="form-check-label col-form-label" for="mou_checkbox">手動設定</label>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label class="col-form-label">
                            <small class="text-danger">* </small>
                            此次委託單數
                        </label>
                    </div>
                    <div class="col-lg-8 text-center">
                        <div class="input-group">
                            <div class="input-group-prepend" id="mou_count_less">
                                <button class="btn btn-danger" type="button"> - </button>
                            </div>
                            <input type="number" class="form-control text-center" placeholder="" value="1" id="mou_count" min="0">
                            <div class="input-group-append" id="mou_count_add">
                                <button class="btn btn-success" type="button"> + </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label class="col-form-label">
                            <small class="text-danger">* </small>
                            每單需繳交
                        </label>
                    </div>
                    <div class="col-lg-8 text-center">
                        <input type="text" class="form-control" id="mou_pay">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label class="col-form-label">
                            每月總計繳交
                        </label>
                    </div>
                    <div class="col-lg-8 text-center">
                        <label class="col-form-label" id="mou_total_pay"></label>
                        <small class="text-danger">* 委託單數 X 每月費用</small>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" id="submit_edit_mou_btn">修改</button>
            </div>
        </div>
    </div>
</div>
<!-- ./修改勞務MOU Modal -->

<div class="modal fade" id="success_modal" tabindex="-1" role="dialog" aria-labelledby="success_modal" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">送件完成</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row mb-3">
                <div class="col-lg-12">
                    <label id="success_text"></label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal" id="click_and_refresh">確定並重新整理</button>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_info_modal" tabindex="-1" role="dialog" aria-labelledby="edit_info_modal" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">修改紀錄</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row mb-3">
                <div class="col-lg-4">
                    <label class="col-form-label">
                        會員編號
                    </label>
                </div>
                <div class="col-lg-8 text-center">
                    <label class="col-form-label member_id"></label>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-lg-4">
                    <label class="col-form-label">
                        會員姓名
                    </label>
                </div>
                <div class="col-lg-8 text-center">
                    <label class="col-form-label member_name"></label>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-lg-12 text-left">
                    <label class="col-form-label change_text"></label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">確定</button>
        </div>
        </div>
    </div>
</div>

@endsection

@section('include_js')

<script src="/assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="/assets/js/jquery-ui.min.js"></script>
<script src="/assets/js/jquery-ui-timepicker-addon.js"></script>
<script src="/assets/js/jquery-ui-timepicker-zh_tw.js"></script>

<script>
    var member_table = $('#member_table').DataTable({
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
    
    $('#mou_start_time').datepicker({
        dateFormat: 'yy-mm-dd',
        showSecond: true,
        // timeFormat: 'HH:mm:ss',
        controlType:"select",
        showOn: 'button',
        buttonText: '<button class="btn btn-default" type="button"><i class="icon-calendar3" style="color: #c62828;"></i></button>',
        disabled: true
    });

    $('#mou_end_time').datepicker({
        dateFormat: 'yy-mm-dd',
        showSecond: true,
        // timeFormat: 'HH:mm:ss',
        controlType:"select",
        showOn: 'button',
        buttonText: '<button class="btn btn-default" type="button"><i class="icon-calendar3" style="color: #c62828;"></i></button>',
        disabled: true
    });

    $('#mou_start_time').on('change', function(e) {
        $('#mou_end_time').datepicker("option", "minDate", $('#mou_start_time').val());
        if ($('#mou_start_time').val() > $('#mou_end_time').val()) {
            $('#mou_end_time').val($('#mou_start_time').val());
        }
    });

    var system_start_time = '';
    var system_end_time = '';

    var default_start = '';
    var default_end = '';
    var default_order = '';
    var default_pay = '';
    $(document).ready(function(){
        // 編輯mou
        $(document).delegate(".edit_btn", "click", function(){
            let member_id = $(this).data("memberid")
            let mou_id = $(this).data("mouid")
            $.ajax({
                method: "POST",
                url: '/Accounting/edit_mou/json',
                data: {
                    "_token": '{{ csrf_token() }}', 
                    "method": "get_target_mou",
                    "member_id": member_id,
                    "mou_id": mou_id
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
                        let mou_info = json.mou_info[0];
                        system_start_time = mou_info.start_time;
                        system_end_time = mou_info.end_time;
                        default_start = mou_info.start_time;
                        default_end = mou_info.end_time;
                        default_order = mou_info.order_number;
                        default_pay = mou_info.amount_per_order;

                        $("#edit_mou_modal #mou_member_id").html(mou_info.member_id);
                        $("#edit_mou_modal #mou_member_name").html(mou_info.member_name);
                        $("#edit_mou_modal #mou_insert_time").html(mou_info.insert_time);
                        $("#edit_mou_modal #mou_start_time").val(mou_info.start_time);
                        $("#edit_mou_modal #mou_end_time").val(mou_info.end_time);
                        $("#edit_mou_modal #mou_count").val(mou_info.order_number);
                        $("#edit_mou_modal #mou_pay").val(mou_info.amount_per_order);
                        $("#mou_id").html("單號: " + mou_info.mou_id);
                        $("#edit_mou_member_id").val(mou_info.member_id);
                        $("#edit_mou_id").val(mou_info.mou_id);
                        $("#mou_pay").focusout();
                        $("#edit_mou_modal").modal("show")
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

        // 送出編輯
        $("#submit_edit_mou_btn").on("click", function(){
            let member_id = $("#edit_mou_member_id").val();
            let mou_id = $("#edit_mou_id").val();
            let start_time = $("#mou_start_time").val();
            let end_time = $("#mou_end_time").val();
            let mou_count = $("#mou_count").val();
            let mou_pay = $("#mou_pay").val();
            var question_text = "";

            if (default_start != start_time || default_end != end_time) {
                question_text += "原本期限: " + default_start + "~" + default_end + "<br>更改為: <span class=\"text-danger\">" + start_time + "~" + end_time + "</span><br>";
            }
            if (default_order != mou_count) {
                question_text += "原入單數: " + default_order + "<br>修改後入單數: <span class=\"text-danger\">" + mou_count + "</span><br>";
            }
            if (default_pay != mou_pay) {
                question_text += "原每單繳交: $" + default_pay + "<br>修改後每單繳交: <span class=\"text-danger\">$" + mou_pay + "</span><br>";
            }

            if (question_text == "") {
                question_text += "無任何修改的部分，確定送出?";
            }
            console.log(question_text)

            Swal.fire({
                title: '修改確認',
                html: question_text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '是',
                cancelButtonText: '否'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        method: "POST",
                        url: '/Accounting/edit_mou/json',
                        data: {
                            "_token": '{{ csrf_token() }}', 
                            "method": "update_mou",
                            "member_id": member_id,
                            "mou_id": mou_id,
                            "start_time": start_time,
                            "end_time": end_time,
                            "mou_count": mou_count,
                            "mou_pay": mou_pay,
                            "cheange_text": question_text
                        },
                        dataType: 'json',
                        beforeSend: function() {
                            $("#edit_mou_modal").modal("hide");
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
                                $("#success_text").html(question_text);
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
    
        // 數字格式化
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        $("#mou_checkbox").on("click", function(){
            var checked = $("#mou_checkbox").prop("checked");
            if (checked) {
                $('#mou_start_time, #mou_end_time').datepicker("option", "disabled", false);
            } else {
                console.log(system_start_time, system_end_time)
                $("#mou_start_time").datepicker( "setDate", system_start_time ); // 恢復系統預設
                $("#mou_end_time").datepicker( "setDate", system_end_time ); // 恢復系統預設
                $('#mou_start_time, #mou_end_time').datepicker("option", "disabled", true);
            }
        });

        // 修改紀錄
        $(document).delegate(".edit_info_btn", "click", function(){
            let member_id = $(this).data("memberid");
            let mou_id = $(this).data("mouid");
            $.ajax({
                method: "POST",
                url: '/Accounting/edit_mou/json',
                data: {
                    "_token": '{{ csrf_token() }}', 
                    "method": "get_target_mou",
                    "member_id": member_id,
                    "mou_id": mou_id
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
                        let mou_info = json.mou_info[0];
                        console.log(mou_info)
                        $("#edit_info_modal .member_id").text(mou_info.member_id)
                        $("#edit_info_modal .member_name").text(mou_info.member_name)
                        $("#edit_info_modal .change_text").html(mou_info.change_text)
                        $("#edit_info_modal").modal("show")
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

        // 資料更新btn
        $("#data_refresh_btn, #click_and_refresh").on("click", function(){
            location.reload();
        });
    });
</script>
@endsection