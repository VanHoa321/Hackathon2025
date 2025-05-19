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
                            <div class="shop-single-rating">
                                @php
                                    $fullStars = floor($item->average_rating);
                                    $hasHalfStar = ($item->average_rating - $fullStars) >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                @endphp
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                                @if ($hasHalfStar)
                                    <i class="fas fa-star-half-alt"></i>
                                @endif
                                @for ($i = 0; $i < $emptyStars; $i++)
                                    <i class="far fa-star"></i>
                                @endfor
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

    @include('layout.partial.chatbot')
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
                            <div class="shop-single-rating">
                                @php
                                    $fullStars = floor($item->average_rating);
                                    $hasHalfStar = ($item->average_rating - $fullStars) >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                @endphp
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                                @if ($hasHalfStar)
                                    <i class="fas fa-star-half-alt"></i>
                                @endif
                                @for ($i = 0; $i < $emptyStars; $i++)
                                    <i class="far fa-star"></i>
                                @endfor
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
                            <div class="shop-single-rating">
                                @php
                                    $fullStars = floor($item->average_rating);
                                    $hasHalfStar = ($item->average_rating - $fullStars) >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                @endphp
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                                @if ($hasHalfStar)
                                    <i class="fas fa-star-half-alt"></i>
                                @endif
                                @for ($i = 0; $i < $emptyStars; $i++)
                                    <i class="far fa-star"></i>
                                @endfor
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
                        <h2 class="site-title">Bài viết<span> mới nhất</span></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($posts as $post)
                    <div class="col-md-6 col-lg-4">
                        <div class="blog-item wow fadeInUp" data-wow-delay=".25s">
                            <div class="blog-item-img">
                                <img src="{{ $post->image }}" alt="Thumb" style="width:100%; height:350px">
                            </div>
                            <div class="blog-item-info">
                                <div class="blog-item-meta">
                                    <ul>
                                        <li><a href="{{ route('frontend.posts.show', $post->id ) }}"><i class="far fa-user-circle"></i> {{ $post->user->name }}</a></li>
                                                <li><a href="{{ route('frontend.posts.show', $post->id ) }}"><i class="far fa-calendar-alt"></i> {{ $post->created_at->format('F d, Y') }}</a></li>
                                    </ul>
                                </div>
                                <h4 class="blog-title">
                                    <a href="#">{{ $post->title }}</a>
                                </h4>
                                <p>{{ Str::limit($post->abstract, 100) }}</p>
                                <a class="theme-btn" href="{{ route('frontend.posts.show', $post->id) }}">Chi tiết<i class="fas fa-arrow-right-long"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</main>
@endsection
@section('styles')
@endsection