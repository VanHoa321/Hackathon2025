@extends('layout/web_layout')
@section('content')
<main class="main">

    <!-- hero slider -->
    <div class="hero-section3 hs3-2">
        <div class="container">
            <div class="hero-slider owl-carousel owl-theme">
                @foreach ($slides as $slide)
                <div class="hero-single">
                    <div class="hero-single-bg" style="background-image: url('{{ $slide->image }}')"></div>
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-md-12 col-lg-6">
                                <div class="hero-content">
                                    <h1 class="hero-title" data-animation="fadeInRight" data-delay=".50s">
                                        {{ $slide->title }}
                                    </h1>
                                    <p data-animation="fadeInLeft" data-delay=".75s">
                                        {{ $slide->sub_title }}
                                    </p>
                                    <div class="hero-btn" data-animation="fadeInUp" data-delay="1s">
                                        <a href="{{ $slide->alias ? $slide->alias : route('frontend.home.index') }}" class="theme-btn">Xem ngay<i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
    <!-- hero slider end -->

    <!-- category area -->
    <div class="category-area4 pt-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="site-heading text-center wow fadeInDown" data-wow-delay=".25s">
                        <span class="site-title-tagline">Our Category</span>
                        <h2 class="site-title">Our Popular <span>Category</span></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="category-item wow fadeInLeft" data-wow-delay=".25s">
                        <a href="#">
                            <div class="category-img-box">
                                <div class="category-img">
                                    <img src="/web-assets/img/category/bc6.jpg" alt="">
                                    <div class="category-img-info">
                                        <h4>Living Room</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="category-item wow fadeInUp" data-wow-delay=".25s">
                        <a href="#">
                            <div class="category-img-box">
                                <div class="category-img">
                                    <img src="/web-assets/img/category/bc7.jpg" alt="">
                                    <div class="category-img-info">
                                        <h4>Tables & Chairs</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="category-item wow fadeInDown" data-wow-delay=".25s">
                        <a href="#">
                            <div class="category-img-box">
                                <div class="category-img">
                                    <img src="/web-assets/img/category/bc8.jpg" alt="">
                                    <div class="category-img-info">
                                        <h4>Dining Furniture</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="category-item wow fadeInRight" data-wow-delay=".25s">
                        <a href="#">
                            <div class="category-img-box">
                                <div class="category-img">
                                    <img src="/web-assets/img/category/bc9.jpg" alt="">
                                    <div class="category-img-info">
                                        <h4>Office Furniture</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="category-item wow fadeInUp" data-wow-delay=".25s">
                        <a href="#">
                            <div class="category-img-box">
                                <div class="category-img">
                                    <img src="/web-assets/img/category/bc10.jpg" alt="">
                                    <div class="category-img-info">
                                        <h4>Kids Furniture</h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- category area end-->

    <div class="product-area pt-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="site-heading text-center wow fadeInDown" data-wow-delay=".25s">
                        <h2 class="site-title">Những tài liệu <span>Mới nhất</span></h2>
                    </div>
                </div>
            </div>
            <div class="product-slider owl-carousel owl-theme wow fadeInUp" data-wow-delay=".25s">
                @foreach ($latestDocuments as $item)
                <div class="product-item">
                    <div class="product-img">
                        @if ($item->is_free)
                        <span class="type hot">Miễn phí</span>
                        @elseif (!$item->is_new)
                        <span class="type discount">Trả phí</span>
                        @endif
                        <a href="/document-details/{{ $item->id }}"><img src="{{ asset($item->cover_image) }}" alt="{{ $item->title }}"></a>
                        <div class="product-action-wrap">
                            <div class="product-action ms-3">
                                <a class="mb-2" href="/document-details/{{ $item->id }}" data-tooltip="tooltip" title="Xem chi tiết"><i class="far fa-eye"></i></a>
                                <a href="#" data-tooltip="tooltip" title="{{ Auth::check() && $item->favourited_by_user ? 'Bỏ yêu thích' : 'Yêu thích' }}"
                                    class="favourite-btn {{ Auth::check() && $item->favourited_by_user ? 'favourited' : '' }}"
                                    data-document-id="{{ $item->id }}"
                                    data-is-favourited="{{ Auth::check() && $item->favourited_by_user ? 'true' : 'false' }}"
                                    @if (!Auth::check()) data-requires-login="true" @endif>
                                    <i class="far fa-heart"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="product-content">
                        <h3 class="product-title"><a href="#">{{ $item->title }}</a></h3>
                        <div class="product-bottom">
                            <div class="product-price">
                                @if($item->price)
                                    <span><i class="fa-solid fa-coins"></i> {{ number_format($item->price, 0, ',', '.') }} đ</span>
                                @else
                                    <span><i class="fa-solid fa-hand-holding-heart"></i> Miễn phí</span>
                                @endif
                            </div>
                        </div>
                        <div class="product-bottom">
                            <div class="product-price">
                                <span><i class="fa-solid fa-star"></i> 9.0/10.0 (10 lượt đánh giá)</span>
                            </div>
                        </div>
                        <div class="product-bottom">
                            <div class="product-price">
                                <span><i class="fa-solid fa-eye"></i> {{ $item->view_count }} lượt xem</span>
                            </div>
                        </div>
                        <div class="product-bottom">
                            <div class="product-price">
                                <span><i class="fa-solid fa-download"></i> {{ $item->download_count }} lượt tải</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- product area end -->

    <!-- deal area -->
    <div class="deal-area2 pt-80">
        <div class="container">
            <div class="deal-wrap wow fadeInUp" data-wow-delay=".25s">
                <div class="row g-0">
                    <div class="col-12 col-lg-6">
                        <div class="deal-img">
                            <img src="/web-assets/img/deal/03.jpg" alt="#">
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 align-self-center">
                        <div class="deal-content">
                            <span class="deal-sub-title">Todays Deal 20% Off</span>
                            <h3 class="deal-title">Summer Beach Bag With Elegant Women's Accessories.</h3>
                            <p class="deal-text">There are many variations of passages available but the majority
                                have suffered alteration in some form words look even slightly believable.</p>
                            <h3 class="deal-price"><span>$1020</span><del>$1200</del></h3>
                            <div class="col-lg-12 col-xl-8 mx-auto">
                                <div class="deal-countdown">
                                    <div class="countdown" data-countdown="2030/12/30"></div>
                                </div>
                            </div>
                            <div class="deal-btn">
                                <a href="#" class="theme-btn">Buy Now<i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- deal area end  -->


    <!-- product area -->
    <div class="product-area pt-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="site-heading text-center wow fadeInDown" data-wow-delay=".25s">
                        <h2 class="site-title">Những tài liệu <span>được tải nhiều nhất</span></h2>
                    </div>
                </div>
            </div>
            <div class="product-slider owl-carousel owl-theme wow fadeInUp" data-wow-delay=".25s">
                @foreach ($mostDownloadedDocuments as $item)
                <div class="product-item">
                    <div class="product-img">
                        @if ($item->is_free)
                            <span class="type hot">Miễn phí</span>
                        @elseif (!$item->is_new)
                            <span class="type discount">Trả phí</span>
                        @endif
                        <a href="/document-details/{{ $item->id }}"><img src="{{ asset($item->cover_image) }}" alt="{{ $item->title }}"></a>
                        <div class="product-action-wrap">
                            <div class="product-action ms-3">
                                <a class="mb-2" href="/document-details/{{ $item->id }}" data-tooltip="tooltip" title="Xem chi tiết"><i class="far fa-eye"></i></a>
                                <a href="#" data-tooltip="tooltip" title="{{ Auth::check() && $item->favourited_by_user ? 'Bỏ yêu thích' : 'Yêu thích' }}"
                                    class="favourite-btn {{ Auth::check() && $item->favourited_by_user ? 'favourited' : '' }}"
                                    data-document-id="{{ $item->id }}"
                                    data-is-favourited="{{ Auth::check() && $item->favourited_by_user ? 'true' : 'false' }}"
                                    @if (!Auth::check()) data-requires-login="true" @endif>
                                    <i class="far fa-heart"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="product-content">
                        <h3 class="product-title"><a href="#">{{ $item->title }}</a></h3>
                        <div class="product-bottom">
                            <div class="product-price">
                                @if($item->price)
                                    <span><i class="fa-solid fa-coins"></i> {{ number_format($item->price, 0, ',', '.') }} đ</span>
                                @else
                                    <span><i class="fa-solid fa-hand-holding-heart"></i> Miễn phí</span>
                                @endif
                            </div>
                        </div>
                        <div class="product-bottom">
                            <div class="product-price">
                                <span><i class="fa-solid fa-star"></i> 9.0/10.0 (10 lượt đánh giá)</span>
                            </div>
                        </div>
                        <div class="product-bottom">
                            <div class="product-price">
                                <span><i class="fa-solid fa-eye"></i> {{ $item->view_count }} lượt xem</span>
                            </div>
                        </div>
                        <div class="product-bottom">
                            <div class="product-price">
                                <span><i class="fa-solid fa-download"></i> {{ $item->download_count }} lượt tải</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- product area end -->

    <div class="product-area pt-80">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="site-heading text-center wow fadeInDown" data-wow-delay=".25s">
                        <h2 class="site-title">Những tài liệu <span>được xem nhiều nhất</span></h2>
                    </div>
                </div>
            </div>
            <div class="product-slider owl-carousel owl-theme wow fadeInUp" data-wow-delay=".25s">
                @foreach ($mostViewedDocuments as $item)
                    <div class="product-item">
                        <div class="product-img">
                            @if ($item->is_free)
                                <span class="type hot">Miễn phí</span>
                            @elseif (!$item->is_new)
                                <span class="type discount">Trả phí</span>
                            @endif
                            <a href="/document-details/{{ $item->id }}"><img src="{{ asset($item->cover_image) }}" alt="{{ $item->title }}"></a>
                            <div class="product-action-wrap">
                                <div class="product-action ms-3">
                                    <a class="mb-2" href="/document-details/{{ $item->id }}" data-tooltip="tooltip" title="Xem chi tiết"><i class="far fa-eye"></i></a>
                                    <a href="#" data-tooltip="tooltip" title="{{ Auth::check() && $item->favourited_by_user ? 'Bỏ yêu thích' : 'Yêu thích' }}"
                                        class="favourite-btn {{ Auth::check() && $item->favourited_by_user ? 'favourited' : '' }}"
                                        data-document-id="{{ $item->id }}"
                                        data-is-favourited="{{ Auth::check() && $item->favourited_by_user ? 'true' : 'false' }}"
                                        @if (!Auth::check()) data-requires-login="true" @endif>
                                        <i class="far fa-heart"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="product-content">
                            <h3 class="product-title"><a href="#">{{ $item->title }}</a></h3>
                            <div class="product-bottom">
                                <div class="product-price">
                                    @if($item->price)
                                        <span><i class="fa-solid fa-coins"></i> {{ number_format($item->price, 0, ',', '.') }} đ</span>
                                    @else
                                        <span><i class="fa-solid fa-hand-holding-heart"></i> Miễn phí</span>
                                    @endif
                                </div>
                            </div>
                            <div class="product-bottom">
                                <div class="product-price">
                                    <span><i class="fa-solid fa-star"></i> 9.0/10.0 (10 lượt đánh giá)</span>
                                </div>
                            </div>
                            <div class="product-bottom">
                                <div class="product-price">
                                    <span><i class="fa-solid fa-eye"></i> {{ $item->view_count }} lượt xem</span>
                                </div>
                            </div>
                            <div class="product-bottom">
                                <div class="product-price">
                                    <span><i class="fa-solid fa-download"></i> {{ $item->download_count }} lượt tải</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    <!-- blog area -->
    <div class="blog-area py-90">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="site-heading text-center wow fadeInDown" data-wow-delay=".25s">
                        <span class="site-title-tagline">Our Blog</span>
                        <h2 class="site-title">Latest News & <span>Blog</span></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-lg-4">
                    <div class="blog-item wow fadeInUp" data-wow-delay=".25s">
                        <div class="blog-item-img">
                            <img src="/web-assets/img/blog/01.jpg" alt="Thumb">
                        </div>
                        <div class="blog-item-info">
                            <div class="blog-item-meta">
                                <ul>
                                    <li><a href="#"><i class="far fa-user-circle"></i> By Alicia Davis</a></li>
                                    <li><a href="#"><i class="far fa-calendar-alt"></i> January 29, 2025</a></li>
                                </ul>
                            </div>
                            <h4 class="blog-title">
                                <a href="#">There are many variations of passage available majority suffered.</a>
                            </h4>
                            <p>There are many variations available the majority have suffered alteration randomised
                                words.</p>
                            <a class="theme-btn" href="#">Read More<i class="fas fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="blog-item wow fadeInDown" data-wow-delay=".25s">
                        <div class="blog-item-img">
                            <img src="/web-assets/img/blog/02.jpg" alt="Thumb">
                        </div>
                        <div class="blog-item-info">
                            <div class="blog-item-meta">
                                <ul>
                                    <li><a href="#"><i class="far fa-user-circle"></i> By Alicia Davis</a></li>
                                    <li><a href="#"><i class="far fa-calendar-alt"></i> January 29, 2025</a></li>
                                </ul>
                            </div>
                            <h4 class="blog-title">
                                <a href="#">There are many variations of passage available majority suffered.</a>
                            </h4>
                            <p>There are many variations available the majority have suffered alteration randomised
                                words.</p>
                            <a class="theme-btn" href="#">Read More<i class="fas fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="blog-item wow fadeInUp" data-wow-delay=".25s">
                        <div class="blog-item-img">
                            <img src="/web-assets/img/blog/03.jpg" alt="Thumb">
                        </div>
                        <div class="blog-item-info">
                            <div class="blog-item-meta">
                                <ul>
                                    <li><a href="#"><i class="far fa-user-circle"></i> By Alicia Davis</a></li>
                                    <li><a href="#"><i class="far fa-calendar-alt"></i> January 29, 2025</a></li>
                                </ul>
                            </div>
                            <h4 class="blog-title">
                                <a href="#">There are many variations of passage available majority suffered.</a>
                            </h4>
                            <p>There are many variations available the majority have suffered alteration randomised
                                words.</p>
                            <a class="theme-btn" href="#">Read More<i class="fas fa-arrow-right-long"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@section('styles')
@endsection