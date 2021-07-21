<!DOCTYPE html>
<html lang="zh_tw">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ $title }}</title>

    <!-- Custom fonts for this template-->
    <link href="/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/icons/icomoon/styles.css">

    <!-- Custom styles for this template-->
    <link href="/assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/jquery-ui.min.css">
    <link rel="stylesheet" href="/assets/css/jquery-ui.theme.min.css">
    <link rel="stylesheet" href="/assets/css/jquery-ui-timepicker-addon.css">

    <style>
        .load_mask {
            position: fixed;
            width: 100%; 
            height: 105%;
            z-index: 1051;
            background-color: rgba(0, 0, 0, 0.63);
            display: none;
            text-align: center;
            top: -20px;
            color: #fff;
        }

        .load_mask_show {
            display: table;
        }

        .spinner {
            display: inline-block;
            -webkit-animation: rotation 1s linear infinite;
            -o-animation: rotation 1s linear infinite;
            animation: rotation 1s linear infinite;
        }

        .input-group-btn>button {
            border-color: #d1d3e2;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
    </style>
    @yield('include_css')
</head>

<body id="page-top">

    <div class="load_mask">
        <div style="display: table-cell; vertical-align: middle; width: 100%;">
            <i class="icon-spinner2 spinner" style="color:#FFF; font-size: 50px;"></i>
            <div style=" font-size: 30px; padding-top: 10px;">Loading...</div>
        </div>
    </div>
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/index">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">VIP會員後台</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            
            @foreach ($listbar as $key => $value)
                @if (!empty($value['floor_2nd']))
                    <li class="nav-item {{ $active_1 == $value['name'] ? 'active' : "" }}">
                        <a class="nav-link {{ $active_1 == $value['name'] ? '' : "collapsed" }}" href="#" data-toggle="collapse" data-target="#account_list{{$key}}" aria-expanded="true" aria-controls="account_list{{$key}}">
                            <i class="{{ $value['icon'] }}"></i>
                            <span>{{ $value['ch_name'] }}</span>
                        </a>
                        <div id="account_list{{$key}}" class="collapse {{ $active_1 == $value['name'] ? 'show' : "" }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar"  {{ $active_1 == $value['name'] ? 'style=display: none;' : "" }}>
                            <div class="bg-white py-2 collapse-inner rounded">
                                @foreach ($value['floor_2nd'] as $Value_2nd)
                                    <a class="collapse-item {{ $active_2 == $Value_2nd['name'] ? 'active' : ""}}" href="{{ ($Value_2nd['url']) }}">{{ $Value_2nd['ch_name'] }}</a>
                                @endforeach
                            </div>
                        </div>
                    </li>
                @else
                    <li class="nav-item {{ $active_1 == $value['name'] ? 'active' : "" }}">
                        <a class="nav-link" href="{{  ($value['url']) }}">
                            <i class="{{ $value['icon'] }}"></i>
                            <span>{{ $value['ch_name'] }}</span>
                        </a>
                    </li>
                @endif
            @endforeach

            <!-- Nav Item - Dashboard -->
            {{-- <li class="nav-item" id="index">
                <a class="nav-link" href="/index">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>會員資料</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#account_list"
                    aria-expanded="true" aria-controls="account_list">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>會計系統</span>
                </a>
                <div id="account_list" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="#">會員資料修改</a>
                        <a class="collapse-item" href="#">修改入單MOU</a>
                        <a class="collapse-item" href="#">入會費查詢</a>
                        <a class="collapse-item" href="#">勞務費查詢</a>
                    </div>
                </div>
            </li>

            <li class="nav-item" id="">
                <a class="nav-link" href="#">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>後台管理</span>
                </a>
            </li> --}}

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">哈囉 {{ Auth::user()->name }}</span>
                                <img class="img-profile rounded-circle" src="/assets/images/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    登出
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- 返回頂部btn -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- 登出 Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">登出</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    確定要登出？
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">取消</button>
                    <a class="btn btn-primary" href="/logout">登出</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="/assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/assets/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="/assets/vendor/chart.js/Chart.min.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        
        
        // 數字格式化
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        // 判斷flash session
        var session_data = '{{ Session::get("msg") }}';
            if (session_data == "success") {
                Swal.fire({
                    icon: 'success',
                    title: '成功',
                    text: '更新完成'
                });
            }
            if (session_data == "error") {
                Swal.fire({
                    icon: 'error',
                    title: '失敗',
                    text: '更新失敗'
                });
            }
            if (session_data == "nothing change") {
                Swal.fire({
                    icon: 'success',
                    title: '',
                    text: '沒有異動'
                });
            }
    </script>

    @yield('include_js')
</body>

</html>