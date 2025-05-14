@extends('layout/web_layout')
@section('content')
    <main class="main">
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Danh sách tài liệu</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                        <li class="active">Danh sách tài liệu</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="shop-area py-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="shop-sidebar">
                            <div class="shop-widget">
                                <div class="shop-search-form">
                                    <h4 class="shop-widget-title">Tìm kiếm tài liệu</h4>
                                    <form action="#">
                                        <div class="form-group"> 
                                            <input id="search_name" type="text" class="form-control" placeholder="Nhập tiêu đề cần tìm...">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="shop-widget">
                                <h4 class="shop-widget-title">Phân loại tài liệu</h4>
                                <ul class="shop-checkbox-list">
                                    @foreach ($categories as $item)
                                        <li>
                                            <div class="form-check">
                                                <input class="form-check-input category-filter" type="checkbox" id="category-{{ $item->id }}" name="category_ids[]" value="{{ $item->id }}" checked>
                                                <label class="form-check-label" for="category-{{ $item->id }}">{{ $item->name }}<span>({{ $item->documents_count }})</span></label>
                                            </div>
                                        </li>  
                                    @endforeach                           
                                </ul>
                            </div>
                            <div class="shop-widget">
                                <h4 class="shop-widget-title">Nhà xuất bản</h4>
                                <ul class="shop-checkbox-list">
                                    @foreach ($publishers as $item)
                                        <li>
                                            <div class="form-check">
                                                <input class="form-check-input publisher-filter" type="checkbox" id="publisher-{{ $item->id }}" name="publisher_ids[]" value="{{ $item->id }}" checked>
                                                <label class="form-check-label" for="publisher-{{ $item->id }}">{{ $item->name }}<span>({{ $item->documents_count }})</span></label>
                                            </div>
                                        </li>  
                                    @endforeach    
                                </ul>
                            </div>
                            <div class="shop-widget">
                                <h4 class="shop-widget-title">Hình thức</h4>
                                <ul class="shop-checkbox-list">
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input is-free-filter" type="checkbox" name="frees[]" value="1" id="free-1" checked>
                                            <label class="form-check-label" for="size1">Miễn phí</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input is-free-filter" type="checkbox" name="frees[]" value="0" id="free-2" checked>
                                            <label class="form-check-label" for="size2">Mất phí</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="shop-item-wrapper">
                            <div class="row" id="document_grid"></div>
                        </div>
                        <div class="pagination-area mb-0"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {

            loadDocuments();

            function loadDocuments(page = 1) {
                var search_name = $("#search_name").val();

                var category_ids = $(".category-filter:checked").map(function () {
                    return $(this).val();
                }).get();

                var publisher_ids = $(".publisher-filter:checked").map(function () {
                    return $(this).val();
                }).get();

                var free_ids = $(".is-free-filter:checked").map(function () {
                    return $(this).val();
                }).get();

                if (category_ids.length === 0 || publisher_ids.length === 0 || free_ids.length === 0) {
                    $("#document_grid").html("<div class='col-12 text-center mt-100'><p>Không tìm thấy tài liệu phù hợp!</p></div>");
                    return;
                }

                $.ajax({
                    url: "/document/getData?page=" + page,
                    method: "GET",
                    data: {
                        search_name: search_name,
                        category_ids: category_ids,
                        publisher_ids: publisher_ids,
                        free_ids: free_ids,
                    },
                    success: function (response) {
                        var documentsGrid = "";
                        $.each(response.documents, function (index, item) {
                            let isFreeLabel = item.is_free
                                ? `<span class="type hot">Miễn phí</span>`
                                : `<span class="type discount">Trả phí</span>`;

                            let priceHtml = item.price && !item.is_free
                                ? `<span><i class="fa-solid fa-coins"></i> ${Number(item.price).toLocaleString('vi-VN')} đ</span>`
                                : `<span><i class="fa-solid fa-hand-holding-heart"></i> Miễn phí</span>`;

                            let favouriteClass = item.favourited_by_user ? 'favourited' : '';
                            let favouriteTitle = item.favourited_by_user ? 'Bỏ yêu thích' : 'Yêu thích';
                            let requiresLoginAttr = !item.is_logged_in ? 'data-requires-login="true"' : '';

                            documentsGrid += `
                                <div class="col-md-4 col-lg-3">
                                    <div class="product-item">
                                        <div class="product-img">
                                            ${isFreeLabel}
                                            <a href="/document-details/${item.id}">
                                                <img src="${item.cover_image}" alt="${item.title}">
                                            </a>
                                            <div class="product-action-wrap">
                                                <div class="product-action ms-3">
                                                    <a class="mb-2" href="/document-details/${item.id}" data-tooltip="tooltip" title="Xem chi tiết">
                                                        <i class="far fa-eye"></i>
                                                    </a>
                                                    <a href="#" data-tooltip="tooltip" title="${favouriteTitle}"
                                                        class="favourite-btn ${favouriteClass}"
                                                        data-document-id="${item.id}"
                                                        data-is-favourited="${item.favourited_by_user ? 'true' : 'false'}"
                                                        ${requiresLoginAttr}>
                                                        <i class="far fa-heart"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <h3 class="product-title"><a href="/document-details/${item.id}">${item.title}</a></h3>
                                            <div class="product-bottom">
                                                <div class="product-price">
                                                    ${priceHtml}
                                                </div>
                                            </div>
                                            <div class="product-bottom">
                                                <div class="product-price">
                                                    <span><i class="fa-solid fa-star"></i> 9.0/10.0 (10 đánh giá)</span>
                                                </div>
                                            </div>
                                            <div class="product-bottom">
                                                <div class="product-price">
                                                    <span><i class="fa-solid fa-eye"></i> ${item.view_count} lượt xem</span>
                                                </div>
                                            </div>
                                            <div class="product-bottom">
                                                <div class="product-price">
                                                    <span><i class="fa-solid fa-download"></i> ${item.download_count} lượt tải</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });

                        if (documentsGrid === "") {
                            documentsGrid = "<div class='col-12 text-center mt-100'><p>Không tìm thấy sản phẩm nào!</p></div>";
                        }

                        $("#document_grid").html(documentsGrid);
                        renderPagination(response.current_page, response.last_page);
                    },
                    error: function () {
                        toastr.error("Lỗi khi tải dữ liệu!");
                    },
                });
            }

            $("#search_name").on("input", function () {
                loadDocuments(1);
            });

            $(".category-filter, .publisher-filter, .is-free-filter").on("change", function () {
                loadDocuments(1);
            });

            $(document).on("click", ".page-nav", function (e) {
                e.preventDefault();
                
                if ($(this).closest(".page-item").hasClass("disabled") || $(this).closest(".page-item").hasClass("active")) {
                    return;
                }

                let page = $(this).data("page");
                if (page !== undefined && page !== null) {
                    loadDocuments(page);
                }
            });

            function renderPagination(currentPage, lastPage) {
                const maxPagesToShow = 4;
                let paginationHtml = `<ul class="pagination">`;

                if (currentPage > 1) {
                    paginationHtml += `
                        <li class="page-item">
                            <a class="page-link page-nav" href="#" data-page="${currentPage - 1}" aria-label="Previous">
                                <span aria-hidden="true"><i class="far fa-arrow-left"></i></span>
                            </a>
                        </li>
                    `;
                }

                let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
                let endPage = startPage + maxPagesToShow - 1;

                if (endPage > lastPage) {
                    endPage = lastPage;
                    startPage = Math.max(1, endPage - maxPagesToShow + 1);
                }

                if (startPage > 1) {
                    paginationHtml += `<li class="page-item"><a class="page-link page-nav" href="#" data-page="1">1</a></li>`;
                    if (startPage > 2) {
                        paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                }

                for (let i = startPage; i <= endPage; i++) {
                    paginationHtml += `
                        <li class="page-item ${i === currentPage ? 'active' : ''}">
                            <a class="page-link page-nav" href="#" data-page="${i}">${i}</a>
                        </li>
                    `;
                }

                if (endPage < lastPage) {
                    if (endPage < lastPage - 1) {
                        paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                    }
                    paginationHtml += `<li class="page-item"><a class="page-link page-nav" href="#" data-page="${lastPage}">${lastPage}</a></li>`;
                }
                
                if (currentPage < lastPage) {
                    paginationHtml += `
                        <li class="page-item">
                            <a class="page-link page-nav" href="#" data-page="${currentPage + 1}" aria-label="Next">
                                <span aria-hidden="true"><i class="far fa-arrow-right"></i></span>
                            </a>
                        </li>
                    `;
                }

                paginationHtml += `</ul>`;

                $(".pagination-area").html(`<div aria-label="Page navigation example">${paginationHtml}</div>`);
            }

        });

        document.addEventListener('click', function (e) {
            if (e.target.closest('.favourite-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.favourite-btn');
                const documentId = btn.getAttribute('data-document-id');
                const isFavourited = btn.getAttribute('data-is-favourited') === 'true';
                const url = isFavourited
                    ? '{{ url("account/favourite") }}/' + documentId
                    : '{{ route("frontend.add-favourite", ":id") }}'.replace(':id', documentId);
                const method = isFavourited ? 'DELETE' : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success(data.message);
                            btn.classList.toggle('favourited');
                            btn.setAttribute('data-is-favourited', isFavourited ? 'false' : 'true');
                            btn.setAttribute('title', isFavourited ? 'Yêu thích' : 'Bỏ yêu thích');
                        } else {
                            toastr.error(data.message);
                        }
                    })
                    .catch(error => {
                        toastr.error('Bạn cần phải đăng nhập để thực hiện chức năng này');
                        console.error(error);
                    });
            }
        });
    </script>
@endsection