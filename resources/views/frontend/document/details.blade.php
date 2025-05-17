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

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 1050;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 1050;">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="shop-single py-100">
            <div class="container">
                <input type="hidden" value="{{ $item->id }}" id="documentId">
                <div class="row">
                    <div class="col-md-9 col-lg-4">
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
                    <div class="col-md-12 col-lg-8">
                        <div class="shop-single-info">
                            <h4 class="shop-single-title">{{ $item->title }}</h4>
                            <div class="shop-single-rating">
                                {{ number_format( $averageRating, 1) }}/10 điểm đánh giá
                                <span class="rating-count"> ({{ $ratingCount }} đánh giá)</span>
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
                                            @if ($item->authors && $item->authors->count() > 0)
                                                {{ $item->authors->pluck('name')->implode(', ') }}
                                            @else
                                                {{ $item->user->name }}
                                            @endif
                                        </span>
                                    </li>
                                    <li>Phân loại: <span>{{ $item->category->name }}</span></li>
                                    <li>Lượt xem: <span>{{ $item->view_count }} lượt</span></li>
                                    <li>Lượt tải: <span>{{ $item->download_count }} lượt</span></li>
                                    <li>Định dạng tệp: 
                                        <span>{{ $item->file_format }}</span>
                                        @if (!empty($item->file_path_pdf) && ($item->file_format != "pdf"))
                                            , pdf
                                        @endif
                                    </li>
                                </ul>
                            </div>
                            <div class="shop-single-action">
                                <div class="row align-items-center">
                                    <div class="col-md-12 col-lg-12 col-xl-12">
                                        <div class="shop-single-btn">
                                            <a href="#" class="theme-btn" data-bs-toggle="modal" data-bs-target="#pdfPreviewModal"><i class="fa-solid fa-magnifying-glass me-1"></i>Xem trước</a>
                                            <a id="summaryButton" href="#" class="theme-btn" data-bs-toggle="modal" data-bs-target="#summaryModal"><i class="fa-solid fa-list me-1"></i>Tóm tắt</a>                                        
                                            @if ($hasAccess)
                                                @auth
                                                    <a id="tts" href="#" class="theme-btn" data-bs-toggle="modal" data-bs-target="#ttsModal"><i class="fa-solid fa-play me-1"></i></a>
                                                    <a href="{{ route('frontend.document.download', $item->id) }}" class="theme-btn"><i class="fa-solid fa-cloud-arrow-down me-1"></i>({{ $item->file_format }})</a>
                                                    @if ($item->file_path_pdf)
                                                        <a href="{{ route('frontend.document.downloadPDF', $item->id) }}" class="theme-btn"><i class="fa-solid fa-cloud-arrow-down me-1"></i>(pdf)</a>
                                                    @endif
                                                @endauth
                                            @else
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#purchaseModal" class="theme-btn"><i class="fa-solid fa-cart-shopping me-1"></i>Mua tài liệu ({{ number_format($item->price, 0, ',', '.') }}đ)</a>
                                            @endif
                                            @auth
                                                <a href="#" class="theme-btn rate-btn" data-bs-toggle="{{ Auth::check() ? 'modal' : '' }}" data-bs-target="{{ Auth::check() ? '#ratingModal' : '' }}"
                                                    data-requires-login="{{ Auth::check() ? 'false' : 'true' }}"
                                                    data-has-rated="{{ $userRating ? 'true' : 'false' }}"
                                                    data-rating="{{ $userRating ? $userRating->rating : '' }}">
                                                    <i class="fa-solid fa-star me-1"></i>{{ $userRating ? 'Sửa đánh giá' : 'Đánh giá' }}
                                                </a>
                                            @endauth
                                            <a href="#" data-tooltip="tooltip" title="{{ Auth::check() && $item->favourited_by_user ? 'Bỏ yêu thích' : 'Yêu thích' }}"
                                                class="favourite-btn theme-btn theme-btn2 {{ Auth::check() && $item->favourited_by_user ? 'favourited' : '' }}"
                                                data-document-id="{{ $item->id }}"
                                                data-is-favourited="{{ Auth::check() && $item->favourited_by_user ? 'true' : 'false' }}"
                                                @if (!Auth::check()) data-requires-login="true" @endif>
                                                <i class="far fa-heart" style="margin-left:0"></i>
                                            </a>
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
                <div class="modal fade" id="pdfPreviewModal" tabindex="-1" aria-labelledby="pdfPreviewModalLabel" aria-hidden="true" style="max-height: 650px; overflow-y: auto;">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="pdfPreviewModalLabel">Xem trước: {{ $item->title }}</h5>
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

                <div class="modal quickview fade" id="summaryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="summaryModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="far fa-xmark"></i></button>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="fs-4">Tóm tắt nội dung với AI</label>
                                                <div class="mt-2">
                                                    <p id="summaryDocument" style="max-height: 400px; overflow-y: auto;"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @auth
                    <div class="modal fade" id="purchaseModal" tabindex="-1" aria-labelledby="purchaseModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="purchaseModalLabel">Xác nhận mua tài liệu</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    Bạn có muốn mua tài liệu <strong>{{ $item->title }}</strong> với giá <strong>{{ number_format($item->price, 0, ',', '.') }}</strong> điểm? <br>
                                    Số dư hiện tại: <strong>{{ number_format(Auth::user()->point, 0, ',', '.') }}</strong> điểm.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <a href="{{ route('frontend.purchase', $item->id) }}" class="btn btn-primary">Xác nhận</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endauth

                <div class="modal quickview fade" id="ttsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ttsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <button type="button" class="btn-close" id="stop-audio" data-bs-dismiss="modal" aria-label="Close"><i class="far fa-xmark"></i></button>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="fs-4">Nghe tài liệu</label>
                                                <div class="mt-2">
                                                    <p id="ttsDocument" style="max-height: 400px; overflow-y: auto;"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal quickview fade" id="ratingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="far fa-xmark"></i></button>
                            <div class="modal-body">
                                <div class="row">
                                    <form id="ratingForm">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Điểm đánh giá (từ 0.0 đến 10.0)</label>
                                                    <input type="number" step="0.1" min="0" max="10" id="ratingInput" name="rating" value="{{ $userRating->rating ?? '' }}" class="form-control" placeholder="0.0" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="m-2 col-md-4 col-lg-4 col-sm-6 justify-content-center">
                                                <button type="submit" class="theme-btn"><span class="far fa-user"></span> Lưu</button>
                                            </div>
                                            @if($userRating)
                                            <div class="m-2 col-md-4 col-lg-4 col-sm-6 justify-content-center">
                                                <button type="button" id="deleteRatingBtn" class="theme-btn bg-danger"><span class="far fa-user"></span> Xóa đánh giá</button>
                                            </div>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($hasAccess)
                    @include('layout.partial.chatbot-document')
                @endif

                <div class="shop-single-details">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-tab3" data-bs-toggle="tab" data-bs-target="#tab3" type="button"
                                role="tab" aria-controls="tab3" aria-selected="false">Bình luận
                                ({{ $comments->count() }})</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="tab3" role="tabpanel" aria-labelledby="nav-tab3">
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
                            @foreach($recommendations as $recommendation)
                                <div class="col-md-6 col-lg-3">
                                    <div class="product-item">
                                        <div class="product-img">
                                            @if ($recommendation->is_free)
                                            <span class="type hot">Miễn phí</span>
                                            @elseif (!$recommendation->is_new)
                                            <span class="type discount">Trả phí</span>
                                            @endif
                                            <a href="{{route('frontend.document.details',  $recommendation->id )}}"><img src="{{ asset($recommendation->cover_image) }}" alt="{{ $recommendation->title }}"></a>
                                            <div class="product-action-wrap">
                                                <div class="product-action ms-3">
                                                    <a class="mb-2" href="{{route('frontend.document.details',  $recommendation->id )}}" data-tooltip="tooltip" title="Xem chi tiết"><i class="far fa-eye"></i></a>
                                                    <a href="#" data-tooltip="tooltip" title="{{ Auth::check() && $recommendation->favourited_by_user ? 'Bỏ yêu thích' : 'Yêu thích' }}"
                                                        class="favourite-btn {{ Auth::check() && $recommendation->favourited_by_user ? 'favourited' : '' }}"
                                                        data-document-id="{{ $recommendation->id }}"
                                                        data-is-favourited="{{ Auth::check() && $recommendation->favourited_by_user ? 'true' : 'false' }}"
                                                        @if (!Auth::check()) data-requires-login="true" @endif>
                                                        <i class="far fa-heart"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <h3 class="product-title"><a href="{{route('frontend.document.details',  $recommendation->id )}}">{{ $recommendation->title }}</a></h3>
                                            <div class="product-bottom">
                                                <div class="product-price">
                                                    @if($recommendation->price) 
                                                    <span><i class="fa-solid fa-coins"></i> {{ number_format($recommendation->price, 0, ',', '.') }} đ</span>
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
                                                    <span><i class="fa-solid fa-eye"></i> {{ $recommendation->view_count }} lượt xem</span>
                                                </div>
                                            </div>
                                            <div class="product-bottom">
                                                <div class="product-price">
                                                    <span><i class="fa-solid fa-download"></i> {{ $recommendation->download_count }} lượt tải</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
        $(document).ready(function() {
            $('#submit-comment').click(function() {
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
                    success: function(response) {
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
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            toastr.warning('Vui lòng đăng nhập để bình luận!');
                        } else {
                            toastr.error('Đã xảy ra lỗi khi gửi bình luận.');
                        }
                    }
                });
            });

            $('#summaryButton').click(function() {
                let documentId = $('#documentId').val();
                $('#summaryDocument').html('');
                $('#summaryDocument').html('<div class="text-center text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Đang tóm tắt, vui lòng chờ...</div>');
                $.ajax({
                    url: '/api/summary',
                    method: 'POST',
                    data: {
                        id: documentId
                    },
                    success: function(response) {
                        let answer = response.answer;
                        answer = answer.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                        answer = answer.replace(/\*(.*?)\*/g, '<em>$1</em>');
                        $('#summaryDocument').html(answer);
                    },
                    error: function(xhr) {
                        toastr.error('Có lỗi khi xem bản tóm tắt');
                    }
                });
            });

            let audioElement = null;

            $('#tts').click(function (e) {
                e.preventDefault();
                let documentId = $('#documentId').val();
                $('#ttsDocument').html('');
                $('#ttsDocument').html('<div class="text-center text-muted fs-3"><i class="fas fa-spinner fa-spin me-2" style="font-size: 2rem;"></i>Đang xử lý audio, vui lòng chờ...</div>');
                $.ajax({
                    url: '/api/tts',
                    method: 'POST',
                    data: {
                        id: documentId
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (audioBlob) {
                        const audioUrl = URL.createObjectURL(audioBlob);

                        if (audioElement) {
                            audioElement.pause();
                            audioElement.src = "";
                            audioElement.load();
                            audioElement = null;
                        }

                        audioElement = document.createElement('audio');
                        audioElement.src = audioUrl;
                        audioElement.controls = true;
                        audioElement.autoplay = true;
                        audioElement.style.width = '100%';

                        const container = $('#ttsDocument');
                        container.html('');
                        container.append(audioElement);
                        container.append(stopButton);
                        const audio = new Audio(audioUrl);
                    },
                    error: function (xhr) {
                        toastr.error('Có lỗi khi tải giọng đọc');
                    }
                });
            });

            $('#stop-audio').on('click', function() {
                if (ajaxRequest) {
                    ajaxRequest.abort();
                    console.log('Request AJAX đã bị hủy.');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Lưu đánh giá
            $('#ratingForm').on('submit', function(e) {
                e.preventDefault();
                const rating = $('#ratingInput').val();
                $.ajax({
                    url: '{{ route("frontend.document.rate", $item->id) }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        rating: rating
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        location.reload();
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message || 'Đã xảy ra lỗi.');
                    }
                });
            });

            // Xóa đánh giá
            $('#deleteRatingBtn').on('click', function() {
                $.ajax({
                    url: '{{ route("frontend.document.unrate", $item->id) }}',
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        location.reload();
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON.message || 'Đã xảy ra lỗi.');
                    }
                });
            });

            $("#sendMessageChatbotDocument").click(function() {
                sendChatbotMessage();
            });

            $("#chatBotDocumentInput").keypress(function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    sendChatbotMessage();
                }
            });

            function sendChatbotMessage() {
                let userMessage = $("#chatBotDocumentInput").val();
                if (userMessage === "") {
                    toastr.error("Vui lòng nhập câu hỏi");
                    return;
                }
                $("#chatbotIntro").remove();
                let userBubble =
                    `<div class="d-flex justify-content-end mb-1">
                        <div class="bg-primary text-white rounded-3 px-3 py-2 shadow-sm" style="max-width: 75%">${userMessage}</div>
                    </div>`;
                $("#chatbotDocumentContent").append(userBubble);
                $("#chatbotDocumentContent").animate({
                    scrollTop: $('#chatbotDocumentContent')[0].scrollHeight
                }, 500);

                $("#chatBotDocumentInput").val("");
                let documentId = $('#documentId').val();
                $.ajax({
                    url: "/api/ask-document-ai",
                    type: "POST",
                    data: {
                        id: documentId,
                        question: userMessage
                    },
                    beforeSend: function() {
                        let loadingBubble = `
                            <div id="loadingMessage" class="d-flex align-items-start mb-3">
                                <img src="/storage/files/1/Avatar/ai-avatar.jpg" alt="Bot icon" class="rounded-circle me-2" width="32" height="32" />
                                <div class="bg-white rounded-3 px-3 py-2 shadow-sm" style="max-width: 75%"><i class="fas fa-spinner fa-spin"></i></div>
                            </div>`;
                        $("#chatbotDocumentContent").append(loadingBubble);
                        $("#chatbotDocumentContent").animate({
                            scrollTop: $('#chatbotDocumentContent')[0].scrollHeight
                        }, 500);
                    },
                    success: function(response) {
                        $("#loadingMessage").remove();
                        let botMessage = response.answer;
                        botMessage = botMessage.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                        botMessage = botMessage.replace(/\*(.*?)\*/g, '<em>$1</em>');
                        let botBubble =
                            `<div class="d-flex align-items-start mb-3">
                                <img src="/storage/files/1/Avatar/ai-avatar.jpg" alt="Bot icon" class="rounded-circle me-2" width="32" height="32" />
                                <div class="bg-white rounded-3 px-3 py-2 shadow-sm" style="max-width: 75%">${botMessage}</div>
                            </div>`;
                        $("#chatbotDocumentContent").append(botBubble);
                    },
                    error: function() {
                        $("#loadingMessage").remove();
                        toastr.error("Có lỗi xảy ra, vui lòng thử lại");
                    }
                });
            }
        });
    </script>

    <script>
        const toggleBtn = document.getElementById("toggleModal");
        const modal = document.getElementById("modalContainer");
        const minimizeBtn = document.getElementById("minimizeModal");
        const zoomBtn = document.getElementById("zoomModal");
        const chatIcon = toggleBtn.querySelector(".fa-comments");
        const closeIcon = toggleBtn.querySelector(".fa-times");

        function openModal() {
            modal.style.visibility = "visible";
            modal.style.opacity = "1";
            modal.style.transform = "scale(1)";
            chatIcon.style.display = "none";
            closeIcon.style.display = "inline-block";
        }

        function closeModal() {
            modal.style.opacity = "0";
            modal.style.transform = "scale(0.95)";
            setTimeout(() => {
                modal.style.visibility = "hidden";
            }, 300);
            chatIcon.style.display = "inline-block";
            closeIcon.style.display = "none";
        }

        function toggleFullscreen() {
            modal.classList.toggle("fullscreen");
            zoomBtn.querySelector("i").classList.toggle("fa-compress");
            zoomBtn.querySelector("i").classList.toggle("fa-expand");
        }

        toggleBtn.addEventListener("click", () => {
            if (modal.style.visibility === "visible") closeModal();
            else openModal();
        });
        minimizeBtn.addEventListener("click", closeModal);
        zoomBtn.addEventListener("click", toggleFullscreen);

        closeModal();
    </script>

    @php
        $pdfUrl = asset('storage/' . ($item->file_path_pdf ?? $item->file_path));
    @endphp
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://unpkg.com/pdfjs-dist@3.11.174/build/pdf.worker.min.js';
        document.getElementById('pdfPreviewModal').addEventListener('shown.bs.modal', function() {
            const pdfUrl = "{{ $pdfUrl }}";
            const maxPages = "{{ $doc_preview }}";
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
                        const viewport = page.getViewport({
                            scale: zoomScale
                        });
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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