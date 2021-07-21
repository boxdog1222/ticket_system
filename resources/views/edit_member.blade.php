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
                                    <nobr>期限</nobr>
                                </th>
                                <th>
                                    <nobr>姓名</nobr>
                                </th>
                                <th>
                                    <nobr>身分證</nobr>
                                </th>
                                <th>
                                    <nobr>電話</nobr>
                                </th>
                                <th>
                                    <nobr>地址</nobr>
                                </th>
                                <th>
                                    <nobr>備註</nobr>
                                </th>
                                <th>
                                    <nobr>操作</nobr>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($member_table as $key => $val)
                                <tr class="text-center">
                                    <td><nobr>{{ $val->id }}</nobr></td>
                                    <td><nobr>{{ $val->member_id }}</nobr></td>
                                    <td><nobr>{!! $val->status !!}</nobr></td>
                                    <td><nobr>{!! $val->start_time . '<br>~<br>' . $val->end_time !!}</nobr></td>
                                    <td><nobr>{{ $val->member_name }}</nobr></td>
                                    <td><nobr>{{ $val->identity_card }}</nobr></td>
                                    <td><nobr>{{ $val->mobile_phone }}</nobr></td>
                                    <td>{{ $val->address }}</td>
                                    <td>{{ $val->note }}</td>
                                    <td><nobr>{!! $val->btn !!}</nobr></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 編輯會員 Modal -->
<div class="modal fade" id="edit_member_modal" tabindex="-1" role="dialog" aria-labelledby="edit_member_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">編輯會員</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="col-form-label">
                        <small class="text-danger">* </small>
                        會員期限
                    </label>
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <input type="text" name="time" id="start_time" class="form-control required_input" placeholder="年-月-日 時:分:秒" maxlength="19" value="" style="z-index: 1050;">
                    </div>
                </div>
                <div class="col-lg-1 text-center">
                    <label class="col-form-label"> ~ </label>
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <input type="text" name="time" id="end_time" class="form-control required_input" placeholder="年-月-日 時:分:秒" maxlength="19" value="" style="z-index: 1050;">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-lg-2">
                    <label for=""></label>
                </div>
                <div class="col-lg-4">
                    <div class="form-check form-check-inline">
                        <input type="checkbox" class="form-check-input" id="checkbox1">
                        <label class="form-check-label col-form-label" for="checkbox1">手動設定</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="col-form-label">
                        <small class="text-danger">* </small>
                        入會費
                    </label>
                </div>
                <div class="col-lg-4">
                    <input type="text" class="form-control required_input" placeholder="1,000" id="member_fee">
                </div>
                <div class="col-lg-6">
                    <label class="col-form-label">
                        <small class="text-danger">* </small>
                        繳納方式
                    </label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="0" checked>
                        <label class="form-check-label col-form-label" for="inlineRadio1">當場繳納</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="1">
                        <label class="form-check-label col-form-label" for="inlineRadio2">事後繳交</label>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="col-form-label">
                        會員編號
                    </label>
                </div>
                <div class="col-lg-10 text-center">
                    <input type="hidden" id="member_id">
                    <label class="col-form-label text-danger" id="member_id_text"> --- 送件後產生 --- </label>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="col-form-label">
                        會員姓名
                    </label>
                </div>
                <div class="col-lg-10 text-center">
                    <input type="text" class="form-control" placeholder="請輸入會員姓名..." id="member_name">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="col-form-label">
                        會員身分證
                    </label>
                </div>
                <div class="col-lg-10 text-center">
                    <input type="text" class="form-control" placeholder="請輸入會員身分證..." id="identity_card">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="col-form-label">
                        市內電話
                    </label>
                </div>
                <div class="col-lg-10 text-center">
                    <input type="text" class="form-control" placeholder="請輸入市內電話..." id="local_phone">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="col-form-label">
                        <small class="text-danger">* </small>
                        行動電話
                    </label>
                </div>
                <div class="col-lg-10 text-center">
                    <input type="text" class="form-control required_input" placeholder="請輸入行動電話..." id="mobile_phone">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="col-form-label">
                        其他聯絡方式
                    </label>
                </div>
                <div class="col-lg-10 text-center">
                    <input type="text" class="form-control" placeholder="請輸入其他聯絡方式..." id="other_phone">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="col-form-label">
                        地址
                    </label>
                </div>
                <div class="col-lg-10 text-center">
                    <input type="text" class="form-control" placeholder="請輸入地址..." id="address">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-lg-2">
                    <label class="col-form-label">
                        備註
                    </label>
                </div>
                <div class="col-lg-10 text-center">
                    <textarea class="form-control" rows="10" placeholder="請輸入備註..." id="note"></textarea>
                </div>
            </div>


        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
            <button type="button" class="btn btn-warning" id="submit_add_btn">修改</button>
        </div>
        </div>
    </div>
</div>
<!-- ./編輯會員 Modal -->

<div class="modal fade" id="success_modal" tabindex="-1" role="dialog" aria-labelledby="success_modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">修改完成</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-4">
                    <label class="col-form-label">
                        會員編號
                    </label>
                </div>
                <div class="col-lg-8">
                    <label class="col-form-label member_id"></label>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <label class="col-form-label">
                        會員姓名
                    </label>
                </div>
                <div class="col-lg-8">
                    <label class="col-form-label member_name"></label>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <label class="col-form-label">
                        行動電話
                    </label>
                </div>
                <div class="col-lg-8">
                    <label class="col-form-label mobile_phone"></label>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <label class="col-form-label">
                        會員期限
                    </label>
                </div>
                <div class="col-lg-8">
                    <label class="col-form-label time_limit"></label>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <label class="col-form-label">
                        繳費狀況
                    </label>
                </div>
                <div class="col-lg-8">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="pay_status" id="inlineRadio1" value="0" disabled>
                        <label class="form-check-label col-form-label" for="inlineRadio1">已繳費</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="pay_status" id="inlineRadio2" value="1" disabled>
                        <label class="form-check-label col-form-label" for="inlineRadio2">事後繳費</label>
                    </div>
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

    $("#checkbox1").on("click", function(){
        var checked = $("#checkbox1").prop("checked");
        if (checked) {
            $('#start_time, #end_time').datepicker("option", "disabled", false);
        } else {
            $('#start_time, #end_time').datepicker("option", "disabled", true);
        }
    });

    $(document).ready(function() {
        // 資料更新btn
        $("#data_refresh_btn").on("click", function(){
            location.reload();
        });

        // 編輯會員btn click
        $(document).delegate(".edit_member", "click", function(){
            let member_id = $(this).data("memberid")
            // console.log(member_id)
            $.ajax({
                method: "POST",
                url: '/Accounting/edit_member/json',
                data: {
                    "_token": '{{ csrf_token() }}', 
                    "method": "member_info",
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
                        let member_info = json.member_info[0];

                        if (member_info.check_pass == 0) {
                            $("#checkbox1").attr("disabled", true);
                        } else {
                            $("#checkbox1").removeAttr("disabled")
                        }
                        $("#start_time").datepicker( "setDate", member_info.start_time );
                        $("#end_time").datepicker( "setDate", member_info.end_time );
                        $("#member_fee").val(member_info.member_fee);

                        if (member_info.pay_way == 0) {
                            $("input[name='inlineRadioOptions'][value='0']").attr("checked", true)
                        } else {
                            $("input[name='inlineRadioOptions'][value='1']").attr("checked", true)
                        }
                        
                        $("#member_id_text").html(member_info.member_id)
                        $("#member_id").val(member_info.member_id)
                        $("#member_name").val(member_info.member_name)
                        $("#identity_card").val(member_info.identity_card)
                        $("#local_phone").val(member_info.local_phone)
                        $("#mobile_phone").val(member_info.mobile_phone)
                        $("#other_phone").val(member_info.other_phone)
                        $("#address").val(member_info.address)
                        $("#note").val(member_info.note)

                        $("#edit_member_modal").modal("show")
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

        // 送出編輯btn 
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
            let member_id = $("#member_id").val();
            let member_name = $("#member_name").val();
            let identity_card = $("#identity_card").val();
            let local_phone = $("#local_phone").val();
            let mobile_phone = $("#mobile_phone").val();
            let other_phone = $("#other_phone").val();
            let address = $("#address").val();
            let note = $("#note").val();
            
            Swal.fire({
                title: '修改確認',
                text: '是否確定修改?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '是',
                cancelButtonText: '否'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        method: "POST",
                        url: '/Accounting/edit_member/json',
                        data: {
                            "_token": '{{ csrf_token() }}', 
                            "method": "update_member",
                            "start_time": start_time,
                            "end_time": end_time,
                            "member_fee": member_fee,
                            "pay_way": pay_way,
                            "member_id": member_id,
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
                            $("#edit_member_modal").modal("hide");
                            $('.load_mask').addClass('load_mask_show');
                        },
                        success: function(json) {
                            if(json.msg == "error") {
                                Swal.fire({
                                    icon: 'error',
                                    title: '錯誤',
                                    text: '修改失敗，請稍後再試或連繫工程師'
                                });
                            } else if (json.msg == "success") {
                                console.log(json)
                                $("#success_modal .member_id").text(json.member_info[0].member_id);
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
                                text: '修改失敗，請稍後再試或連繫工程師'
                            });
                        }
                    });
                }
            })
        });

        // 刪除會員btn click
        $(document).delegate(".del_btn", "click", function(){
            let member_id = $(this).data("memberid");
            Swal.fire({
                title: '刪除確認',
                text: '是否確定刪除會員資料?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '是',
                cancelButtonText: '否'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        method: "POST",
                        url: '/Accounting/edit_member/json',
                        data: {
                            "_token": '{{ csrf_token() }}', 
                            "method": "del_member",
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
                                    text: '刪除失敗，請稍後再試或連繫工程師'
                                });
                            } else if (json.msg == "success") {
                                Swal.fire({
                                    icon: 'success',
                                    title: '成功',
                                    text: '會員資料已刪除，請重新整理頁面'
                                });
                            }

                            $('.load_mask_show').removeClass('load_mask_show');
                        },
                        error: function(xhr) {
                            $('.load_mask_show').removeClass('load_mask_show');
                            Swal.fire({
                                icon: 'error',
                                title: '錯誤',
                                text: '刪除失敗，請稍後再試或連繫工程師'
                            });
                        }
                    });
                }
            })

        });
    });

</script>
@endsection