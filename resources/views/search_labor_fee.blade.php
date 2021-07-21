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
                    <table class="table table-bordered" id="labor_table" style="width: 100%;">
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
                                    <nobr>手機電話</nobr>
                                </th>
                                <th>
                                    <nobr>總執行單數</nobr>
                                </th>
                                <th>
                                    <nobr>應繳勞務費</nobr>
                                </th>
                                <th>
                                    <nobr>狀態</nobr>
                                </th>
                                <th>
                                    <nobr>月份</nobr>
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
                            @foreach ($labor_fee_list as $key => $val)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $val->member_id }}</td>
                                    <td>{{ $val->member_name }}</td>
                                    <td>{{ $val->mobile_phone }}</td>
                                    <td>{{ $val->order_number }}</td>
                                    <td>${{ number_format($val->order_number * $val->amount_per_order) }}</td>
                                    <td>{!! ($val->pay_status == 0 ? "<span class='text-success'>已繳費</span>" : "<span class='text-danger'>未繳費</span>") !!}</td>
                                    <td>{{ $val->pay_month }}</td>
                                    <td>{{ $val->pay_date }}</td>
                                    <td>
                                        @if ($val->pay_status != 0)
                                            <button class="btn btn-info pay_btn" data-memberid="{{ $val->member_id }}" data-monthid="{{ $val->id }}">繳費</button>
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

<div class="modal fade" id="success_modal" tabindex="-1" role="dialog" aria-labelledby="success_modal" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">成功</h5>
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
            <button type="button" class="btn btn-default" data-dismiss="modal">確認</button>
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

    var labor_table = $('#labor_table').DataTable({
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

        $(document).delegate(".pay_btn", "click", function(){
            let monthid = $(this).data("monthid");
            
            Swal.fire({
                title: '繳費',
                text: "確認繳費?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '是',
                cancelButtonText: '否'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        method: "POST",
                        url: '/Accounting/search_labor_fee/json',
                        data: {
                            "_token": '{{ csrf_token() }}', 
                            "method": "pay_labor_fee",
                            "monthid": monthid,
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

        $("#submit_search").on("click", function(){
            let search_input = $("#search_input").val();
            let pay_way = $("input[name='inlineRadioOptions']:checked").val();
            $.ajax({
                method: "POST",
                url: '/Accounting/search_labor_fee/json',
                data: {
                    "_token": '{{ csrf_token() }}', 
                    "method": "search_labor_by_input",
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
                        labor_table.clear().draw();
                        labor_table.rows.add(json.labor_table_arr).draw();
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