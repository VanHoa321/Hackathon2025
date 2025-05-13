<!-- header middle -->
<div class="header-middle">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-5 col-lg-3 col-xl-3">
                <div class="header-middle-logo">
                    <a class="navbar-brand" href="{{ route('frontend.home.index') }}">
                        <h2>Sense<span style="color:#11B76B">Lib</span></h2>
                    </a>
                </div>
            </div>
            <div class="d-none d-lg-block col-lg-6 col-xl-5">
                <div class="header-middle-search">
                    <form action="#">
                        <div class="search-content">
                            <input type="text" class="form-control" placeholder="Search Here...">
                            <button type="submit" class="search-btn"><i class="far fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-7 col-lg-3 col-xl-4">
                <div class="header-middle-right">
                    <ul class="header-middle-list">
                        @auth
                        <li>
                            <div class="header-middle-account">
                                <div class="dropdown">
                                    <div data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="{{ auth()->user()->avatar ? auth()->user()->avatar : asset('web-assets/img/account/user.jpg') }}" alt="{{ auth()->user()->name }}">
                                    </div>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <div class="dropdown-user">
                                                <h5>Welcome! {{ auth()->user()->name }}</h5>
                                                <p>Your Balance: $200.00</p>
                                            </div>
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('frontend.profile') }}"><i class="far fa-user"></i> Hồ sơ của tôi</a></li>
                                        <li><a class="dropdown-item" href="{{ route('frontend.edit-password') }}"><i class="far fa-lock"></i> Đổi mật khẩu</a></li>
                                        <li><a class="dropdown-item" href="{{ route('frontend.settings') }}"><i class="far fa-gear"></i> Cài đặt</a></li>
                                        <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="far fa-sign-out"></i> Logout</a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        @endauth
                        <li><a href="#" class="list-item"><i class="far fa-arrows-rotate"></i><span>0</span></a></li>
                        <li><a href="#" class="list-item"><i class="far fa-heart"></i><span>0</span></a></li>
                        <li class="dropdown-cart">
                            <a href="#" class="shop-cart list-item"><i class="far fa-shopping-bag"></i>
                                <span>5</span></a>
                            <div class="dropdown-cart-menu">
                                <div class="dropdown-cart-header">
                                    <span>03 Items</span>
                                    <a href="#">View Cart</a>
                                </div>
                                <ul class="dropdown-cart-list">
                                    <li>
                                        <div class="dropdown-cart-item">
                                            <div class="cart-img">
                                                <a href="#"><img src="/web-assets/img/product/p47.png" alt="#"></a>
                                            </div>
                                            <div class="cart-info">
                                                <h4><a href="#">Xamaha R15 Red</a></h4>
                                                <p class="cart-qty">1x - <span class="cart-amount">$200.00</span></p>
                                            </div>
                                            <a href="#" class="cart-remove" title="Remove this item"><i class="far fa-times-circle"></i></a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-cart-item">
                                            <div class="cart-img">
                                                <a href="#"><img src="/web-assets/img/product/p12.png" alt="#"></a>
                                            </div>
                                            <div class="cart-info">
                                                <h4><a href="#">Apple Blue Watch</a></h4>
                                                <p class="cart-qty">1x - <span class="cart-amount">$120.00</span></p>
                                            </div>
                                            <a href="#" class="cart-remove" title="Remove this item"><i class="far fa-times-circle"></i></a>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-cart-item">
                                            <div class="cart-img">
                                                <a href="#"><img src="/web-assets/img/product/p32.png" alt="#"></a>
                                            </div>
                                            <div class="cart-info">
                                                <h4><a href="#">Orange Sweater</a></h4>
                                                <p class="cart-qty">1x - <span class="cart-amount">$330.00</span></p>
                                            </div>
                                            <a href="#" class="cart-remove" title="Remove this item"><i class="far fa-times-circle"></i></a>
                                        </div>
                                    </li>
                                </ul>
                                <div class="dropdown-cart-bottom">
                                    <div class="dropdown-cart-total">
                                        <span>Total</span>
                                        <span class="total-amount">$650.00</span>
                                    </div>
                                    <a href="#" class="theme-btn">Checkout</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- header main navigation -->
<div class="main-navigation">
    <nav class="navbar navbar-expand-lg">
        <div class="container position-relative">
            <a class="navbar-brand" href="{{ route('frontend.home.index') }}">
                <h2 class="logo-scrolled">Sense<span style="color:#11B76B">Lib</span></h2>
            </a>
            <div class="mobile-menu-right">
                <div class="search-btn">
                    <button type="button" class="nav-right-link"><i class="far fa-search"></i></button>
                    <div class="mobile-search-form">
                        <form action="#">
                            <input type="text" class="form-control" placeholder="Tìm kiếm...">
                            <button type="submit"><i class="far fa-search"></i></button>
                        </form>
                    </div>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-mobile-icon"><i class="far fa-bars"></i></span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="main_nav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Tài liệu</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Giới thiệu</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Liên lạc</a></li>
                </ul>
                @guest
                <div class="nav-right">
                    <div class="nav-right-btn">
                        <a href="{{ route('login') }}" class="theme-btn">Đăng nhập</a>
                    </div>
                </div>
                @endguest
            </div>
        </div>
    </nav>
</div>
</header>
<!-- header area end -->