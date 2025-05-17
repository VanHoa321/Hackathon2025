@extends('layout/web_layout')
@section('content')
    <main class="main">
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Hồ sơ của tôi</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                        <li class="active">Hồ sơ của tôi</li>
                    </ul>
                </div>
            </div>
        </div>
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {!! session('error') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
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
                                <li><a class="active" href="{{ route('frontend.tran-history') }}"><i class="far fa-money-bill-transfer"></i> Lịch sử giao dịch</a></li>
                                <li><a href="{{ route('frontend.point') }}"><i class="far fa-coins"></i> Nạp coin</a></li>
                                <li><a href="{{ route('logout') }}"><i class="far fa-sign-out"></i> Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="user-wrapper">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="user-card">
                                        <div class="user-card-header">
                                            <h4 class="user-card-title">Lịch sử giao dịch</h4>
                                        </div>
                                        @if ($transactions->isEmpty())
                                            <div class="alert alert-info" role="alert">
                                                Bạn chưa có giao dịch nào.
                                            </div>
                                        @else
                                            <div class="table-responsive">
                                                <table class="table table-borderless text-nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th>Loại giao dịch</th>
                                                            <th>Tài liệu</th>
                                                            <th>Số tiền</th>
                                                            <th>Nội dung</th>
                                                            <th>Ngày giao dịch</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($transactions as $transaction)
                                                            <tr id="transaction-{{ $transaction->id }}">
                                                                <td>
                                                                    @switch($transaction->type)
                                                                        @case(1)
                                                                            <span class="badge bg-success">Nạp tiền tài khoản</span>
                                                                            @break
                                                                        @case(2)
                                                                            <span class="badge bg-primary">Mua tài liệu</span>
                                                                            @break
                                                                        @case(3)
                                                                            <span class="badge bg-info">Đăng tải tài liệu</span>
                                                                            @break
                                                                        @case(4)
                                                                            <span class="badge bg-warning">Tải xuống tài liệu</span>
                                                                            @break
                                                                        @default
                                                                            <span class="badge bg-secondary">Không xác định</span>
                                                                    @endswitch
                                                                </td>
                                                                <td>
                                                                    @if ($transaction->document)
                                                                        <a href="{{ route('frontend.document.details', $transaction->document->id) }}">
                                                                            {{ $transaction->document->title }}
                                                                        </a>
                                                                    @else
                                                                        Không có tài liệu
                                                                    @endif
                                                                </td>
                                                                <td>{{ $transaction->amount == 0 ? 'Không có' : number_format($transaction->amount, 0, ',', '.') . ' điểm' }}</td>
                                                                <td>{{ $transaction->note }}</td>
                                                                <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif
                                        <div class="col-md-12">
                                            <input type="hidden" name="avatar" id="thumbnail" value="{{ old('avatar', auth()->user()->avatar) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection