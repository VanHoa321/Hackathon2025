<header id="site-header" class="header">
    <div id="header-wrap">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <!-- Navbar -->
                    <nav class="navbar navbar-expand-lg">
                        <a class="d-flex align-items-center" href="{{ route('frontend.home.index') }}">
                            <h3 class="mb-0" style="color: rgb(65, 99, 236)">WHK<span style="color: #FF6004">18</span></h3>
                        </a>
                        <button class="navbar-toggler ht-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <svg width="100" height="100" viewBox="0 0 100 100">
                                <path class="line line1" d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058"></path>
                                <path class="line line2" d="M 20,50 H 80"></path>
                                <path class="line line3" d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942"></path>
                            </svg>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="nav navbar-nav mx-auto">
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{ route("frontend.home.index") }}">Trang chủ</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route("frontend.product.index") }}">Sản phẩm</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route("frontend.post.index") }}">Bài viết</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route("frontend.home.about-us") }}">Về chúng tôi</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Liên hệ</a>
                                </li>
                                <!-- <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Pages</a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="about-us.html">About Us</a>
                                        </li>
                                        <li>
                                            <a href="team.html">Team</a>
                                        </li>
                                        <li>
                                            <a href="team-single.html">Team Single</a>
                                        </li>
                                        <li>
                                            <a href="price-table.html">Price Table</a>
                                        </li>
                                        <li>
                                            <a href="faq.html">Faq</a>
                                        </li>
                                        <li>
                                            <a href="login.html">Login</a>
                                        </li>
                                        <li>
                                            <a href="coming-soon.html">Coming Soon</a>
                                        </li>
                                        <li>
                                            <a href="error-404.html">Error 404</a>
                                        </li>
                                    </ul>
                                </li> -->
                            </ul>
                        </div>
                        <div class="header-right d-flex align-items-center">
                            <a class="header-btn" href="{{ route("login") }}">Đăng nhập <i class="bi bi-arrow-right"></i>
                            </a>
                            <a href="#" class="ht-nav-toggle">
                                <i class="bi bi-grid-fill"></i>
                            </a>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>