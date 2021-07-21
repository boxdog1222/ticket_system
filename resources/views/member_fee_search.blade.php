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

<div class="row mb-3">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-1">
                        <label class="col-form-label">
                            查詢條件
                        </label>
                    </div>
                    <div class="col-lg-2 text-center">
                        <input type="text" class="form-control" placeholder="輸入會員編號/姓名/電話..." id="search_input">
                    </div>
                    <div class="col-lg-1">
                        <label class="col-form-label">
                            繳費狀況
                        </label>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="0">
                            <label class="form-check-label col-form-label" for="inlineRadio1">已繳費</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="1">
                            <label class="form-check-label col-form-label" for="inlineRadio2">未繳費</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 text-right">
                        <button class="btn btn-primary" id="submit_search">搜尋</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered" id="member_table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    <nobr>會員編號</nobr>
                                </th>
                                <th>
                                    <nobr>會員姓名</nobr>
                                </th>
                                <th>
                                    <nobr>入會日期</nobr>
                                </th>
                                <th>
                                    <nobr>狀態</nobr>
                                </th>
                                <th>
                                    <nobr>應繳費用</nobr>
                                </th>
                                <th>
                                    <nobr>繳費日期</nobr>
                                </th>
                                <th>
                                    <nobr>操作</nobr>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($member_list as $key => $val)
                                <tr>
                                    <td>{{ $key +1 }}</td>
                                    <td>{{ $val->member_id }}</td>
                                    <td>{{ $val->member_name }}</td>
                                    <td>{{ $val->join_time }}</td>
                                    <td>{!! ($val->pay_way == 0 ? "<span class='text-success'>已繳交</span>" : "<span class='text-danger'>未繳交</span>") !!}</td>
                                    <td>${{ number_format($val->member_fee) }}</td>
                                    <td>{{ $val->member_fee_give_time }}</td>
                                    <td>
                                        <button class="btn btn-info m-1 member_log" data-memberid="{{ $val->member_id }}">會員紀錄</button>
                                        @if ($val->pay_way == 1)
                                            <button class="btn btn-primary m-1 pay_btn" data-memberid="{{ $val->member_id }}">繳交入會費</button>
                                        @endif
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

<!-- 會員紀錄 Modal -->
<div class="modal fade" id="member_mou_info_modal" tabindex="-1" role="dialog" aria-labelledby="member_mou_info_modal" aria-hidden="true">
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
                <!-- member info -->
                <div class="col-lg-5">
                    <div class="row">
                        <div class="col-lg-4">
                            <label class="col-form-label">入會費</label>
                        </div>
                        <div class="col-lg-8">
                            <label class="col-form-label info_member_fee">入會費TEXT</label>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-4">
                            <label class="col-form-label">繳納狀況</label>
                        </div>
                        <div class="col-lg-8">
                            <label class="col-form-label info_pay_way">繳納狀況TEXT</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <label class="col-form-label">會員編號</label>
                        </div>
                        <div class="col-lg-8">
                            <label class="col-form-label info_member_id">會員編號TEXT</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <label class="col-form-label">會員期限</label>
                        </div>
                        <div class="col-lg-8">
                            <label class="col-form-label info_fresh_time">會員期限TEXT</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <label class="col-form-label">會員姓名</label>
                        </div>
                        <div class="col-lg-8">
                            <label class="col-form-label info_member_name">會員姓名TEXT</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <label class="col-form-label">會員ID</label>
                        </div>
                        <div class="col-lg-8">
                            <label class="col-form-label info_identity_card">會員IDTEXT</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <label class="col-form-label">市內電話</label>
                        </div>
                        <div class="col-lg-8">
                            <label class="col-form-label info_local_phone">市內電話TEXT</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <label class="col-form-label">行動電話</label>
                        </div>
                        <div class="col-lg-8">
                            <label class="col-form-label info_mobile_phone">行動電話TEXT</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <label class="col-form-label">其他聯絡方式</label>
                        </div>
                        <div class="col-lg-8">
                            <label class="col-form-label info_other_phone">其他聯絡方式TEXT</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <label class="col-form-label">地址</label>
                        </div>
                        <div class="col-lg-8">
                            <label class="col-form-label info_address">地址TEXT</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <label class="col-form-label">備註</label>
                        </div>
                        <div class="col-lg-8">
                            <label class="col-form-label info_note">備註TEXT</label>
                        </div>
                    </div>
                </div>
                <!-- ./member info -->

                <!-- mou info -->
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="mou_execution_status_table" style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td colspan="12">勞務MOU執行狀態</td>
                                        </tr>
                                        <tr>
                                            <td>總執行單數</td>
                                            <td class="total_mou_order_count">6單</td>
                                        </tr>
                                        <tr>
                                            <td>基本數值</td>
                                            <td class="basic_val">1,000,000</td>
                                        </tr>
                                        <tr>
                                            <td>達成率</td>
                                            <td class="completion_rate">0%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="member_mou_info_table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>單號</th>
                                            <th>MOU入單數</th>
                                            <th>MOU期限</th>
                                            <th>狀態</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>A000001</td>
                                            <td>2單</td>
                                            <td>20201/06/22<br>20201/07/22</td>
                                            <td class="text-success">執行中</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ./mou info -->
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">確認</button>
        </div>
        </div>
    </div>
</div>
<!-- ./會員紀錄 Modal -->

<div class="modal fade" id="success_modal" tabindex="-1" role="dialog" aria-labelledby="success_modal" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">繳費成功</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row mb-3">
                <div class="col-lg-12">
                    <label id="success_text">繳費成功</label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal" id="click_and_refresh">確定並重新整理</button>
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

    var member_mou_info_table = $('#member_mou_info_table').DataTable({
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

    $(document).ready(function(){
        // 資料更新btn
        $("#data_refresh_btn, #click_and_refresh").on("click", function(){
            location.reload();
        });

        // 使modal內table表頭正常顯示
        $('#member_mou_info_modal').on('shown.bs.modal', function (e) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        });

        // 會員紀錄
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

        // 繳交入會費btn
        $(document).delegate(".pay_btn", "click", function(){
            let member_id = $(this).data("memberid");
            Swal.fire({
                title: '繳費確認',
                text: '是否確定進行繳費?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '是',
                cancelButtonText: '否'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        method: "POST",
                        url: '/Accounting/search_member_fee/json',
                        data: {
                            "_token": '{{ csrf_token() }}', 
                            "method": "pay_member_fee",
                            "member_id": member_id,
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
                                    text: '繳費失敗，請稍後再試或連繫工程師'
                                });
                            } else if (json.msg == "success") {
                                $("#success_modal").modal("show");
                            }
                            $('.load_mask_show').removeClass('load_mask_show');
                        },
                        error: function(xhr) {
                            $('.load_mask_show').removeClass('load_mask_show');
                            Swal.fire({
                                icon: 'error',
                                title: '錯誤',
                                text: '繳費失敗，請稍後再試或連繫工程師'
                            });
                        }
                    });
                }
            })
        });

        $("#submit_search").on("click", function(){
            let search_input = $("#search_input").val();
            let pay_way = $("input[name='inlineRadioOptions']:checked").val();
            $.ajax({
                method: "POST",
                url: '/Accounting/search_member_fee/json',
                data: {
                    "_token": '{{ csrf_token() }}', 
                    "method": "search_member_fee_by_input",
                    "search_input": search_input,
                    "pay_way": pay_way,
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
                            text: '新增失敗，請稍後再試或連繫工程師'
                        });
                    } else if (json.msg == "success") {
                        member_table.clear().draw();
                        member_table.rows.add(json.member_table_arr).draw();
                        Swal.fire({
                            icon: 'success',
                            title: '成功',
                        });
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
        });
    });
</script>
@endsection