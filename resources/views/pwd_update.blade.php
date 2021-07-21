@extends('common.header')

@section('content')

<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">更換密碼</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 mb-2">
                        <label for="password1">修改密碼</label>
                        <input type="text" class="form-control" id="password1" value="" autofocus>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <label for="password2">再次輸入</label>
                        <input type="text" class="form-control" id="password2" value="">
                    </div>
                    <div class="col-lg-12">
                        <button class="btn btn-primary" id="update_btn">更新</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('include_js')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {
            
            $("#update_btn").on("click", function(){
                var password1 = $("#password1").val();
                var password2 = $("#password2").val();
                if (password1 == "" || password2 == "") {
                    Swal.fire({
                        icon: 'error',
                        title: '必填未填',
                        text: '密碼未填寫完畢'
                    });
                    return false;
                } else if (password1 != password2) {
                    Swal.fire({
                        icon: 'error',
                        title: '錯誤',
                        text: '密碼不一致，請重新輸入'
                    });
                    return false;
                }

                $.ajax({
                    method: "POST",
                    url: '/backstage_management/json',
                    data: {
                        "_token": '{{ csrf_token() }}',
                        "method": "reset_self_pwd", 
                        "password": password1
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
                                text: '更新失敗，請聯繫工程師'
                            });
                        } else if (json.msg == "success") {
                            location.href("logout");
                        }
                        $('.load_mask_show').removeClass('load_mask_show');
                    },
                    error: function(xhr) {
                        $('.load_mask_show').removeClass('load_mask_show');
                        Swal.fire({
                            icon: 'error',
                            title: '錯誤',
                            text: '程式錯誤，請聯繫工程師'
                        });
                    }
                });
            });
        });
    </script>
@endsection