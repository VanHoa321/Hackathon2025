@extends('layout/web_layout')
@section('content')
    <main class="main">
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/web-/web-assets/img/breadcrumb/01.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">{{ $item->title }}</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="{{ route('frontend.document.index') }}"><i class="far fa-home"></i> Danh sách tài liệu</a></li>
                        <li class="active">Chi tiết tài liệu</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="shop-single py-100">
            <div class="container">
                <div class="row">
                    <div class="col-md-9 col-lg-6 col-xxl-5">
                        <div class="shop-single-gallery">
                            <div class="flexslider">
                                <ul class="slides">
                                    <li data-thumb="{{ $item->cover_image }}">
                                        <img src="{{ $item->cover_image }}" alt="#">
                                    </li>                                  
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 col-xxl-6">
                        <div class="shop-single-info">
                            <h4 class="shop-single-title">{{ $item->title }}</h4>
                            <div class="shop-single-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                                <i class="far fa-star"></i>
                                <span class="rating-count"> (4 Customer Reviews)</span>
                            </div>
                            <div class="shop-single-price">
                                <del>$690</del>
                                <span class="amount">$650</span>
                                <span class="discount-percentage">30% Off</span>
                            </div>
                            <p class="mb-3">
                                {{ $item->description }}
                            </p>
                            <div class="shop-single-sortinfo">
                                <ul>
                                    <li>Tác giả: 
                                        <span>
                                            {{ $item->authors->pluck('name')->implode(', ') }}
                                        </span>
                                    </li>
                                    <li>Phân loại: <span>{{ $item->category->name }}</span></li>
                                    <li>Lượt xem: <span>{{ $item->view_count }}</span></li>
                                    <li>Lượt tải: <span>{{ $item->download_count }}</span></li>
                                </ul>
                            </div>
                            <div class="shop-single-action">
                                <div class="row align-items-center">
                                    <div class="col-md-12 col-lg-12 col-xl-12">
                                        <div class="shop-single-btn">
                                            <a href="#" class="theme-btn"><i class="fa-solid fa-magnifying-glass me-1"></i>Xem trước</a>
                                            <a href="#" class="theme-btn"><i class="fa-solid fa-cloud-arrow-down me-1"></i>Tải về</a>
                                            <a href="#" class="theme-btn theme-btn2" data-tooltip="tooltip" title="Add To Wishlist"><span class="far fa-heart"></span></a>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-lg-12 col-xl-12 mt-4">
                                        <div class="shop-single-share">
                                            <span>Chia sẻ:</span>
                                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                                            <a href="#"><i class="fab fa-x-twitter"></i></a>
                                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                                            <a href="#"><i class="fab fa-pinterest-p"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- shop single details -->
                <div class="shop-single-details">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-tab1" data-bs-toggle="tab" data-bs-target="#tab1"
                                type="button" role="tab" aria-controls="tab1" aria-selected="true">Nội dung tóm tắt</button>
                            <button class="nav-link" id="nav-tab3" data-bs-toggle="tab" data-bs-target="#tab3"
                                type="button" role="tab" aria-controls="tab3" aria-selected="false">Bình luận (5)</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="nav-tab1">
                            <div class="shop-single-desc">
                                <p>
                                    There are many variations of passages of Lorem Ipsum available, but the majority
                                    have suffered alteration in some form, by injected humour, or randomised words which
                                    don't look even slightly believable. If you are going to use a passage of Lorem
                                    Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of
                                    text. All the Lorem Ipsum generators on the Internet tend to repeat predefined
                                    chunks as necessary, making this the first true generator on the Internet.
                                </p>                                
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="nav-tab3">
                            <div class="shop-single-review">
                                <div class="blog-comments">
                                    <h5>Reviews (05)</h5>
                                    <div class="blog-comments-wrapper">
                                        <div class="blog-comments-single">
                                            <img src="/web-assets/img/blog/com-3.jpg" alt="thumb">
                                            <div class="blog-comments-content">
                                                <h5>Kenneth Evans</h5>
                                                <span><i class="far fa-clock"></i> 31 January, 2025</span>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries but also the leap electronic typesetting, remaining essentially unchanged. It was popularised in the with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                                <a href="#"><i class="far fa-reply"></i> Reply</a>
                                                <div class="review-rating">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                    <i class="far fa-star"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="blog-comments-wrapper">
                                        <div class="blog-comments-single">
                                            <img src="/web-assets/img/blog/com-3.jpg" alt="thumb">
                                            <div class="blog-comments-content">
                                                <h5>Kenneth Evans</h5>
                                                <span><i class="far fa-clock"></i> 31 January, 2025</span>
                                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries but also the leap electronic typesetting, remaining essentially unchanged. It was popularised in the with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                                <a href="#"><i class="far fa-reply"></i> Reply</a>
                                                <div class="review-rating">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                    <i class="far fa-star"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="blog-comments-form">
                                        <h4 class="mb-4">Bình luận tài liệu</h4>
                                        <form action="#">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <select class="form-control form-select">
                                                            <option value="5">5 sao</option>
                                                            <option value="4">4 sao</option>
                                                            <option value="3">3 sao</option>
                                                            <option value="2">2 sao</option>
                                                            <option value="1">1 sao</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <textarea class="form-control" rows="5" placeholder="Nhập nội dung bình luận"></textarea>
                                                    </div>
                                                    <button type="submit" class="theme-btn"><span class="far fa-paper-plane"></span> Bình luận</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="product-area pt-50 related-item">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="site-heading-inline">
                                    <h2 class="site-title">Tài liệu liên quan</h2>
                                    <a href="{{ route("frontend.document.index") }}">Tất cả <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-3">
                                <div class="product-item">
                                    <div class="product-img">
                                        <span class="type new">New</span>
                                        <a href="shop-single.html"><img src="/web-assets/img/product/p7.png" alt=""></a>
                                        <div class="product-action-wrap">
                                            <div class="product-action">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#quickview" data-tooltip="tooltip" title="Quick View"><i class="far fa-eye"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="shop-single.html">Bluetooth Earphones</a></h3>
                                        <div class="product-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-bottom">
                                            <div class="product-price">
                                                <span>$100.00</span>
                                            </div>
                                            <button type="button" class="product-cart-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart">
                                                <i class="far fa-shopping-bag"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="product-item">
                                    <div class="product-img">
                                        <span class="type hot">Hot</span>
                                        <a href="shop-single.html"><img src="/web-assets/img/product/p8.png" alt=""></a>
                                        <div class="product-action-wrap">
                                            <div class="product-action">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#quickview" data-tooltip="tooltip" title="Quick View"><i class="far fa-eye"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="shop-single.html">Bluetooth Earphones</a></h3>
                                        <div class="product-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-bottom">
                                            <div class="product-price">
                                                <span>$100.00</span>
                                            </div>
                                            <button type="button" class="product-cart-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart">
                                                <i class="far fa-shopping-bag"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="product-item">
                                    <div class="product-img">
                                        <span class="type oos">Out Of Stock</span>
                                        <a href="shop-single.html"><img src="/web-assets/img/product/p12.png" alt=""></a>
                                        <div class="product-action-wrap">
                                            <div class="product-action">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#quickview" data-tooltip="tooltip" title="Quick View"><i class="far fa-eye"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="shop-single.html">Bluetooth Earphones</a></h3>
                                        <div class="product-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-bottom">
                                            <div class="product-price">
                                                <span>$100.00</span>
                                            </div>
                                            <button type="button" class="product-cart-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart">
                                                <i class="far fa-shopping-bag"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <div class="product-item">
                                    <div class="product-img">
                                        <span class="type discount">10% Off</span>
                                        <a href="shop-single.html"><img src="/web-assets/img/product/p14.png" alt=""></a>
                                        <div class="product-action-wrap">
                                            <div class="product-action">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#quickview" data-tooltip="tooltip" title="Quick View"><i class="far fa-eye"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                <a href="#" data-tooltip="tooltip" title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="shop-single.html">Bluetooth Earphones</a></h3>
                                        <div class="product-rate">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="product-bottom">
                                            <div class="product-price">
                                                <del>$120.00</del>
                                                <span>$100.00</span>
                                            </div>
                                            <button type="button" class="product-cart-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart">
                                                <i class="far fa-shopping-bag"></i>
                                            </button>
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
@section('scripts')
    
@endsection