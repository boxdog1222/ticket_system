<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, user-scalable=0, initial-scale=1, maximum-scale=1"><!-- rwd要用的 -->
    <title>後台登入</title>

    <link rel="shortcut icon" href="{{ url('assets/images/_favicon.ico') }}"/>
    <link rel="bookmark" href="{{ url('assets/images/_favicon.ico') }}"/>
    <link rel="bookmark" href="{{ url('assets/images/logo_lod.png') }}"/>
    <script src="{{ url('assets/js/app.js') }}"></script>
    <link href = "{{ url('assets/css/login.css') }}?t={{ time() }}" rel = "stylesheet">
</head>
<body>
    <div id="app">
        <div class="col-12 card-box">
            <div class="card card-attr text-center">
                <div class="row justify-content-center">
                    <div class="card-img-top">
                        {{-- <img class="card-img" src="{{ url('assets/images/_favicon.ico') }}" alt="Card image cap"> --}}
                        <span v-if="error_msg != ''" v-text="error_msg" class="text-danger"></span>
                    </div>
                    <form id="form-block" class="card-body" onsubmit="return submit_click();">
                        <h5 class="form-group card-text" v-text="'請輸入您的身份'"></h5>
                        <div class="input-box d-flex align-items-center mb-3">
                            <b-icon icon="person-fill" class="input-icon"></b-icon>
                            <input type="text" name="name" class="form-control" v-bind:placeholder="'帳號'">
                        </div>
                        <div class="input-box d-flex align-items-center mb-3">
                            <b-icon icon="unlock-fill" class="input-icon"></b-icon>
                            <input type="password" name="password" class="form-control" v-bind:placeholder="'密碼'">
                            
                        </div>
                        <div class="form-group">
                            <b-button type="submit" variant="primary" class="btn btn-primary btn-block">
                                <span v-text="'登入'"></span>
                                <b-icon icon="arrow-right-circle" class="input-icon"></b-icon>
                            </b-button>
                        </div>
                    </form>
                </div>
                    
            </div>
        </div>
            
        <div class="footer text-muted" style="text-align: center;">
            <span style="font-family: 'Roboto',Helvetica Neue,Helvetica,Arial,sans-serif;"></span>
            <div style="font-style: italic;">  </div>

        </div>
    </div>
        
</body>
<script type="text/javascript">
    
    var app = new Vue({
        el:'#app',
        data: {
            error_msg: '',
        }
    });
    
    function submit_click() {
        var formData = new FormData(document.getElementById("form-block"));
        axios({
            method: 'post',
            url: "{{ url('/login_check') }}",
            data: formData,
            headers: {'Content-Type': 'multipart/form-data' },
        }).then(function(response) {
            if(response.data.error) {
                app.$data.error_msg = response.data.error;
            } else {
                window.location.href = "{{ url('/index') }}";
            }
        }).catch(function (error) { // 請求失敗
            console.log(error);
        });
        return false;
    }
    
</script>
</html>