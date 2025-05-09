@extends('layout/web_layout')
@section('content')
    <section class="page-title">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-10">
                    <h1>
                        Sản phẩm giới thiệu
                    </h1>
                </div>
            </div>
        </div>
        <div class="page-title-wave">
            <img src="web-assets/images/page-bg.svg" alt="">
        </div>
    </section>

    <div class="page-content">
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 col-md-12 order-lg-1 ps-lg-5">
                        <!-- Product List -->
                        <div class="row text-center" id="product-container-grid"></div>

                        <!-- Pagination -->
                        <nav aria-label="Page navigation" class="mt-8 text-center">
                            <ul class="pagination" id="pagination-area"></ul>
                        </nav>
                    </div>
                    <div class="col-lg-3 col-md-12 mt-8 mt-lg-0">
                        <div class="themeht-sidebar">
                            <div class="widget">
                                <div class="widget-search">
                                    <div class="widget-searchbox">
                                        <input type="text" placeholder="Nhập tên sản phẩm cần tìm..." id="search-name" class="form-control">
                                        <button type="button" class="search-btn">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="widget widget-categories">
                                <h4 class="widget-title">Phân loại</h4>
                                <ul class="widget-categories list-unstyled">
                                    <li id="category-all" value="all"> <a href="#"> Tất cả</a></li>
                                    @foreach ($categories as $category)
                                        <li id="category-{{ $category->id }}" value="{{ $category->id }}"> 
                                            <a href="#">{{ $category->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="widget widget-price">
                                <h4 class="widget-title">Mức giá</h4>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input price-filter" id="priceCheck1" value="under_100k" checked>
                                    <label class="form-check-label" for="priceCheck1">Dưới 100.000đ</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input price-filter" id="priceCheck2" value="100k_to_500k" checked>
                                    <label class="form-check-label" for="priceCheck2">Từ 100.000đ - dưới 500.000đ</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input price-filter" id="priceCheck3" value="500k_to_1m" checked>
                                    <label class="form-check-label" for="priceCheck3">Từ 500.000đ - 1.000.000đ</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input price-filter" id="priceCheck4" value="above_1m" checked>
                                    <label class="form-check-label" for="priceCheck4">Trên 1.000.000đ</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {

            loadProducts();

            function loadProducts(page = 1) {
                var categoryId = $(".widget-categories li.active").attr("value");
                var selectedPriceRanges = [];
                var searchName = $("#search-name").val();

                $(".price-filter:checked").each(function () {
                    selectedPriceRanges.push($(this).val());
                });

                $.ajax({
                    url: "/product/getData?page=" + page,
                    method: "GET",
                    data: {
                        category_id: categoryId,
                        search_name: searchName,
                        price_ranges: selectedPriceRanges,
                    },
                    success: function (response) {
                        var productGrid = "";
                        $.each(response.products, function (index, product) {
                            let finalPrice = product.price - product.sale_price;
                            let priceHtml = "";

                            if (product.sale_price > 0) {
                                priceHtml = `<del>${product.price.toLocaleString("vi-VN")} đ</del> ${finalPrice.toLocaleString("vi-VN")} đ`;
                            } else {
                                priceHtml = `${product.price.toLocaleString("vi-VN")} đ`;
                            }

                            productGrid += `
                            <div class="col-lg-4 col-md-6 mt-5 mt-md-${index < 3 ? '0' : '5'}">
                                <div class="product-item">
                                    <div class="product-img" style="height: 300px; width: 300px; overflow: hidden;">
                                        <img class="img-fluid" src="${product.image}" style="height: 100%; width: 100%; object-fit: container" alt="${product.name}">
                                    </div>
                                    <div class="product-desc">
                                        <a href="#" class="product-name text-limit">${product.name}</a>
                                        <span class="product-price">
                                            ${priceHtml}
                                        </span>
                                        <div class="product-link mt-3">
                                            <a class="add-cart add-cart-product" href="#" data-id="${product.id}">
                                                <i class="bi bi-eye me-2"></i>Chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        });

                        if (productGrid === "") {
                            productGrid = "<div class='col-12 text-center mt-100'><p>Không tìm thấy sản phẩm nào!</p></div>";
                        }

                        $("#product-container-grid").html(productGrid);
                        renderPagination(response);
                    },
                    error: function () {
                        toastr.error("Lỗi khi tải dữ liệu!");
                    },
                });
            }

            function renderPagination(data) {
                var paginationHTML = "";
                if (data.last_page > 1) {
                    paginationHTML = `
                    <ul class="pagination">
                        <li class="page-item ${data.current_page <= 1 ? 'disabled' : ''}">
                            <a class="page-link" href="#" data-page="${Math.max(1, data.current_page - 1)}">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                        </li>`;
                    for (let i = 1; i <= data.last_page; i++) {
                        paginationHTML += `
                        <li class="page-item ${data.current_page === i ? 'active' : ''}">
                            <a class="page-link" href="#" data-page="${i}">${i}</a>
                        </li>`;
                    }
                    paginationHTML += `
                        <li class="page-item ${data.current_page >= data.last_page ? 'disabled' : ''}">
                            <a class="page-link" href="#" data-page="${Math.min(data.last_page, data.current_page + 1)}">
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </li>
                    </ul>`;
                }
                $("#pagination-area").html(paginationHTML);
            }

            $("#search-name").on("input", function () {
                loadProducts(1);
            });

            $(".widget-categories li a").click(function (e) {
                e.preventDefault();
                $(".widget-categories li").removeClass("active");
                $(this).parent().addClass("active");
                loadProducts(1);
            });

            $(".price-filter").change(function () {
                loadProducts(1);
            });

            $(document).on("click", ".pagination a", function (e) {
                e.preventDefault();
                var page = $(this).data("page");
                if (page) loadProducts(page);
            });
        });
    </script>
@endsection