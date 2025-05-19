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
                                        <h4 class="user-card-title">Nạp điểm vào tài khoản</h4>
                                        <div class="user-form">
                                            <form action="{{ route('frontend.deposit') }}" method="POST">
                                                @csrf
                                                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; width: 100%;">
                                                    <div style="width: 100%; display: flex; align-items: center; justify-content: center; row-gap: 2;">
                                                        <input class="input-coin" style="flex: 1; margin-right: 10px" type="text" placeholder="Nhập số tiền ..." id="display-input" />
                                                        <input name="point" type="hidden" id="real-value" />
                                                        <button class="button-coin"
                                                            style="transition: all 0.3s ease-in-out; outline: none; border: none; padding: 10px 30px; border-radius: 8px; color: white; background-color: #11b76b;">
                                                            <i class="fa fa-wallet"></i>
                                                            Nạp tiền
                                                        </button>
                                                    </div>
                                                    <div
                                                        style="display: flex; align-items: center; margin-top: 20px; flex-wrap: wrap; gap: 15px;">
                                                        <div value="20000" class="btn-coin">100.000 đ</div>
                                                        <div class="btn-coin">200.000 đ</div>
                                                        <div class="btn-coin">300.000 đ</div>
                                                        <div class="btn-coin">400.000 đ</div>
                                                        <div class="btn-coin">500.000 đ</div>
                                                        <div class="btn-coin">600.000 đ</div>
                                                        <div class="btn-coin">700.000 đ</div>
                                                        <div class="btn-coin">800.000 đ</div>
                                                        <div class="btn-coin">1.000.000đ</div>
                                                        <div class="btn-coin">2.000.000đ</div>
                                                        <div class="btn-coin">3.000.000đ</div>
                                                        <div class="btn-coin">4.000.000đ</div>
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="col-md-12">
                                                <input type="hidden" id="thumbnail" value="{{ old('avatar', auth()->user()->avatar) }}">
                                            </div>
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
@section('styles')
    <style>
        .input-coin {
            padding: 10px 20px;
            width: 100%;
            height: 45px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            transition: all 0.3s ease;
            color: #7e7d7d;
        }

        .input-coin:focus {
            border-color: #11b76b;
        }

        .button-coin:hover {
            background-color: #2ed98c !important;
        }

        .btn-coin {
            padding: 20px 40px;
            width: 220px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }

        .btn-coin:hover {
            border-color: #11b76b;
        }
    </style>
@endsection
@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const displayInput = document.getElementById("display-input");
            const realValueInput = document.getElementById("real-value");
            const btnCoins = document.querySelectorAll(".btn-coin");

            function formatVND(value) {
                return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            btnCoins.forEach((btn) => {
                btn.addEventListener("click", function() {
                    const raw = this.textContent.replace(/[^\d]/g, "");
                    const formatted = formatVND(raw);
                    displayInput.value = formatted;
                    realValueInput.value = raw;

                    console.log("Giá trị thực tế:", realValueInput.value);
                });
            });
        });
    </script>
@endsection