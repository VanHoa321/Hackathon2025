@extends('layout/web_layout')

@section('content')
    <main class="main">
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Mua điểm</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                        <li class="active">Mua điểm</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="user-area bg py-100">
            <div class="container">
                @if (session('messenge2'))
                    <div class="alert alert-{{ session('messenge.style') }}">
                        {{ session('messenge.msg') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-lg-3">
                        <div class="sidebar">
                            <div class="sidebar-top">
                                <div class="sidebar-profile-img">
                                    <img id="holder" src="">
                                </div>
                                <h5>{{ auth()->user()->name }}</h5>
                                <p><a href="mailto:{{ auth()->user()->email }}">{{ auth()->user()->email }}</a></p>
                            </div>
                            <ul class="sidebar-list">
                                <li><a href="{{ route('frontend.profile') }}"><i class="far fa-user"></i> Hồ sơ của tôi</a></li>
                                <li><a href="{{ route('frontend.edit-password') }}"><i class="far fa-lock"></i> Đổi Mật Khẩu</a></li>
                                <li><a href="{{ route('frontend.my-favourite') }}"><i class="far fa-heart"></i> Danh sách yêu thích</a></li>
                                <li><a href="{{ route('frontend.mydocument') }}"><i class="far fa-upload"></i> Danh sách tài liệu</a></li>
                                <li><a href="{{ route('frontend.tran-history') }}"><i class="far fa-money-bill-transfer"></i> Lịch sử giao dịch</a></li>
                                <li><a class="active" href="{{ route('frontend.point') }}"><i class="far fa-coins"></i> Nạp coin</a></li>
                                <li><a href="{{ route('logout') }}"><i class="far fa-sign-out"></i> Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="user-wrapper">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="user-card">
                                        <h4 class="user-card-title">Nạp điểm thành công</h4>
                                        <div class="user-form">
                                            <div style="text-align: center;">
                                                <img src="http://127.0.0.1:8000/storage/files/1/Slider/8183434.jpg" alt="Đặt bàn thành công" style="max-width: 350px; margin: 0 auto;">
                                                <h3 class="mt-3 text-success"><em>Nạp tiền vào tài khoản thành công!</em></h3>
                                                <h5 class="text-success bold mt-2"><em>Số tiền: {{ number_format(session('amount'), 0, ',', '.') }} VNĐ</em></h5>
                                                <a href="{{ route('frontend.point') }}" class="theme-btn mt-4">Tạo giao dịch mới</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <input type="hidden" id="thumbnail" value="{{ old('avatar', auth()->user()->avatar) }}">
                </div>
            </div>
        </div>
    </main>
@endsection