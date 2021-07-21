@extends('common.header')

@section('include_css')
<link href="/assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

@endsection

@section('content')

<div class="row mb-3">
    <div class="col-lg-4 mb-3">
        <div class="card">
            <div class="card-header text-center">
                目前會員人數
            </div>
            <div class="card-body">
                <h5 class="card-title text-center">{{ (!empty($all_member_count) ? number_format($all_member_count) : 0) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="card">
            <div class="card-header text-center">
                會費已入帳總數
            </div>
            <div class="card-body">
                <h5 class="card-title text-center">{{ (!empty($already_pay_member_fee) ? number_format($already_pay_member_fee) : 0) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="card">
            <div class="card-header text-center">
                會費未入帳總數
            </div>
            <div class="card-body">
                <h5 class="card-title text-center">{{ (!empty($unready_pay_member_fee) ? number_format($unready_pay_member_fee) : 0) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="card">
            <div class="card-header text-center">
                總執行單數
            </div>
            <div class="card-body">
                <h5 class="card-title text-center">{{ (!empty($total_order) ? number_format($total_order) : 0) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="card">
            <div class="card-header text-center">
                基本數值
            </div>
            <div class="card-body">
                <h5 class="card-title text-center">{{ (!empty($total_order) ? number_format($total_order * 120000) : 0) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="card">
            <div class="card-header text-center">
                達成率
            </div>
            <div class="card-body">
                <h5 class="card-title text-center">{{ (!empty($total_order) ? number_format($total_order * 100) : 0) }}%</h5>
            </div>
        </div>
    </div>
</div>

@endsection

@section('include_js')

<script src="/assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script>
    
</script>

@endsection