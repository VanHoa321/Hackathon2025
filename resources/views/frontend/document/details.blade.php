@extends('layout/web_layout')
@section('content')
    <main class="main">
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
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

        @if (session('error'))
            <script>
                toastr.error("{{ session('error') }}");
            </script>
        @endif

        <div class="shop-single py-100">
            <div class="container">
                <input type="hidden" value="{{ $item->id }}" id="documentId">
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
                                9.5/10 điểm 
                                <span class="rating-count"> (320 đánh giá)</span>
                            </div>
                            <div class="shop-single-price">
                                Hình thức: 
                                @if($item->is_free)
                                    Miễn phí
                                @else
                                    Mất phí - {{ number_format($item->price, 0, ',', '.') }}đ
                                @endif
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
                                            <a href="#" class="theme-btn" data-bs-toggle="modal" data-bs-target="#pdfPreviewModal"><i class="fa-solid fa-magnifying-glass me-1"></i>Xem trước</a>
                                            <a href="{{ route("frontend.document.download", $item->id) }}" class="theme-btn"><i class="fa-solid fa-cloud-arrow-down me-1"></i>Tải về</a>
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

                <!-- PDF Preview Modal -->
                <div class="modal fade" id="pdfPreviewModal" tabindex="-1" aria-labelledby="pdfPreviewModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="pdfPreviewModalLabel">Xem trước: {{ $item->title }} (5 trang đầu)</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="pdfViewer" class="pdf-viewer"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
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
                            <button class="nav-link" id="nav-tab3" data-bs-toggle="tab" data-bs-target="#tab3" type="button"
                                role="tab" aria-controls="tab3" aria-selected="false">Bình luận
                                ({{ $comments->count() }})</button>
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
                                    <div class="blog-comments-wrapper" style="max-height: 400px; overflow-y: auto;"
                                        id="comment-list">
                                        @foreach ($comments as $cmt)
                                            <div class="blog-comments-single mt-0" style="margin-bottom: 20px;">
                                                <img src="{{ asset($cmt->user->avatar) }}" alt="avatar" class="rounded-circle"
                                                    width="50">
                                                <div class="blog-comments-content">
                                                    <h5>{{ $cmt->user->name }}</h5>
                                                    <span><i class="far fa-clock"></i>{{ $cmt->created_at }}</span>
                                                    <p>{{ $cmt->content }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="blog-comments-form">
                                        <h4 class="mb-4">Bình luận tài liệu</h4>
                                        <form action="#">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <textarea id="comment-content" class="form-control" rows="5" placeholder="Nhập nội dung bình luận"></textarea>
                                                    </div>
                                                    <button type="button" id="submit-comment" class="theme-btn"><span class="far fa-paper-plane"></span> Bình luận</button>
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
    <link rel="stylesheet" href="https://unpkg.com/pdfjs-dist@3.11.174/web/pdf_viewer.css">
    <script src="https://unpkg.com/pdfjs-dist@3.11.174/build/pdf.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#submit-comment').click(function () {
                let content = $('#comment-content').val();
                let documentId = $('#documentId').val();

                $.ajax({
                    url: '{{ route("frontend.document.comment") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        content: content,
                        document_id: documentId
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            toastr.success(response.message);
                            $('#comment-list').prepend(`
                                <div class="blog-comments-single mt-0" style="margin-bottom: 20px;">
                                    <img src="${response.comment.avatar}" alt="avatar" class="rounded-circle" width="50">
                                    <div class="blog-comments-content">
                                        <h5>${response.comment.user_name}</h5>
                                        <span><i class="far fa-clock"></i>${response.comment.created_at.toLocaleString('vi-VN')}</span>
                                        <p>${response.comment.content}</p>
                                    </div>
                                </div>
                            `);
                            $('#comment-content').val('');
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 401) {
                            toastr.warning('Vui lòng đăng nhập để bình luận!');
                        } else {
                            toastr.error('Đã xảy ra lỗi khi gửi bình luận.');
                        }
                    }
                });
            });
        });
    </script>

    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://unpkg.com/pdfjs-dist@3.11.174/build/pdf.worker.min.js';
        document.getElementById('pdfPreviewModal').addEventListener('shown.bs.modal', function () {
            const pdfUrl = "{{ asset('storage/' . $item->file_path) }}";
            const maxPages = 5;
            const zoomScale = 1.0;

            const viewerContainer = document.getElementById('pdfViewer');
            viewerContainer.innerHTML = '';

            const loadingTask = pdfjsLib.getDocument(pdfUrl);
            loadingTask.promise.then(pdf => {
                for (let pageNum = 1; pageNum <= Math.min(pdf.numPages, maxPages); pageNum++) {
                    pdf.getPage(pageNum).then(page => {
                        const canvas = document.createElement('canvas');
                        canvas.className = 'pdf-page';
                        viewerContainer.appendChild(canvas);
                        const context = canvas.getContext('2d');
                        const viewport = page.getViewport({ scale: zoomScale });
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        page.render({
                            canvasContext: context,
                            viewport: viewport
                        });
                    });
                }
            }).catch(error => {
                console.error('Error loading PDF:', error);
                viewerContainer.innerHTML = '<p>Lỗi khi tải tài liệu PDF. Vui lòng thử lại.</p>';
            });
        });
    </script>

    <style>
        .modal-dialog {
            max-width: 800px;
            margin: 1.75rem auto;
        }
        .modal-body {
            padding: 0;
            background: #f8f9fa;
        }
        .pdf-viewer {
            width: 100%;
            height: 600px;
            overflow-y: auto;
            text-align: center;
        }
        .pdf-page {
            margin: 10px auto;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: block;
        }
        @media (max-width: 576px) {
            .modal-dialog {
                max-width: 95vw;
            }
            .pdf-viewer {
                height: 400px;
            }
            .pdf-page {
                width: 100%;
            }
        }
        #toolbarContainer {
            display: none;
        }
    </style>
@endsection