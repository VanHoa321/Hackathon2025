@extends('layout/admin_layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('user.index') }}" class="text-info">Quản trị viên</a></li>
                        <li class="breadcrumb-item active">Thông tin tài liệu được đăng tải</li>
                    </ol>
                </div>
            </div>
        </div>
        @if(Session::has('messenge') && is_array(Session::get('messenge')))
            @php
                $messenge = Session::get('messenge');
            @endphp
        @if(isset($messenge['style']) && isset($messenge['msg']))
            <div class="alert alert-{{ $messenge['style'] }}" role="alert" style="position: fixed; top: 70px; right: 16px; width: auto; z-index: 999" id="myAlert">
                <i class="bi bi-check2 text-{{ $messenge['style'] }}"></i>{{ $messenge['msg'] }}
            </div>
                @php
                    Session::forget('messenge');
                @endphp
            @endif
        @endif
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-info card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid"
                                    src="{{ $item->cover_image }}"
                                    alt="User profile picture"
                                    style="width:150px; height:150px; object-fit:cover;">
                            </div>
                            <h3 class="profile-username text-center mt-2">{{ $item->title }}</h3>
                            <p class="text-muted text-center">Định dạng file: {{ $item->file_format }}</p>
                            <p class="text-muted text-center">Đăng tải bởi:
                                <a href="{{ route('user.show', $item->upload_by->id) }}" title="Xem thông tin tài khoản">
                                    {{ $item->upload_by->name }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card card-info">
                        <div class="card-header p-3 text-center">
                            <h4>Thông tin tài liệu</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><strong>Tiêu đề</strong></div>
                                <div class="col-lg-9 col-md-8">{{ $item->title }}</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><strong>Danh mục</strong></div>
                                <div class="col-lg-9 col-md-8">{{ $item->category->name }}</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><strong>Hình thức</strong></div>
                                <div class="col-lg-9 col-md-8">
                                    {{ $item->is_free ? 'Miễn phí' : 'Mất phí' }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><strong>Phí tải</strong></div>
                                <div class="col-lg-9 col-md-8">
                                    {{ $item->price }}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><strong>Mô tả</strong></div>
                                <div class="col-lg-9 col-md-8">{{ $item->description ? $item->description : "Chưa cập nhật" }}</div>
                            </div>
                            <hr>
                            <div class="text-center p-1">
                                <a href="{{ route('document.approve') }}" class="btn btn-info btn-sm mx-2" title="Quay lại">
                                    <i class="fa-solid fa-rotate-left"></i> Quay lại
                                </a>
                                <a href="#" class="btn btn-primary btn-sm mx-2" data-toggle="modal" data-target="#pdfPreviewModal">
                                    <i class="fa-solid fa-file"></i> Xem tài liệu
                                </a>
                                <a href="#" class="btn btn-success btn-sm btn-approve mx-2" data-id="{{ $item->id }}" title="Phê duyệt tài liệu">
                                    <i class="fa-solid fa-thumbs-up"></i> Duyệt tài liệu
                                </a>
                                <a href="#" class="btn btn-danger btn-sm btn-refuse mx-2" data-id="{{ $item->id }}" title="Không phê duyệt">
                                    <i class="fa-solid fa-xmark"></i> Không duyệt
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="pdfPreviewModal" tabindex="-1" aria-labelledby="pdfPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xem trước tài liệu</h5>
                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Đóng"> Đóng</button>
            </div>
            <div class="modal-body" style="height: 80vh;">
                <iframe
                    src="{{ asset('storage/' . ($item->file_path_pdf ?? $item->file_path)) }}#toolbar=0"
                    width="100%"
                    height="100%"
                    style="border: none;">
                </iframe>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<!-- PDF.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>

@php
$pdfUrl = asset('storage/' . ($item->file_path_pdf ?? $item->file_path));
@endphp
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('pdfPreviewModal');
        const pdfContainer = document.getElementById('pdf-container');

        const pdfUrl = "{{ $pdfUrl }}";

        modal.addEventListener('shown.bs.modal', function() {
            // Clear container if modal reopens
            pdfContainer.innerHTML = '';

            // Load PDF
            const loadingTask = pdfjsLib.getDocument(pdfUrl);
            loadingTask.promise.then(pdf => {
                const totalPages = Math.min(pdf.numPages, 5); // Giới hạn 5 trang

                for (let pageNum = 1; pageNum <= totalPages; pageNum++) {
                    pdf.getPage(pageNum).then(page => {
                        const scale = 1.3;
                        const viewport = page.getViewport({
                            scale
                        });

                        const canvas = document.createElement("canvas");
                        canvas.style.boxShadow = '0 0 10px rgba(0,0,0,0.1)';
                        const context = canvas.getContext("2d");
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext);
                        pdfContainer.appendChild(canvas);
                    });
                }
            }).catch(error => {
                console.error("Lỗi khi tải PDF:", error);
                pdfContainer.innerHTML = '<p class="text-danger">Không thể hiển thị tài liệu.</p>';
            });
        });
    });
</script>

<script>
    $(document).ready(function() {

        $('body').on('click', '.btn-refuse', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            Swal.fire({
                title: "Xác nhận từ chối phê duyệt này?",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Xác nhận",
                cancelButtonText: "Hủy"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/admin/document/refuse/" + id,
                        type: "POST",
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            window.location.href = "{{ route('document.approve') }}";
                        },
                        error: function(xhr) {
                            toastr.error('Có lỗi khi từ chối phê duyệt tài liệu');
                        }
                    });
                }
            });
        })

        $('body').on('click', '.btn-approve', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            Swal.fire({
                title: "Xác nhận phê duyệt tài liệu này?",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Xác nhận",
                cancelButtonText: "Hủy"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/admin/document/approve/" + id,
                        type: "POST",
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            toastr.success(response.message);
                            window.location.href = "{{ route('document.approve') }}"
                        },
                        error: function(xhr) {
                            console.log(xhr)
                            toastr.error('Có lỗi khi phê duyệt tài liệu');
                        }
                    });
                }
            });
        })

        setTimeout(function() {
            $("#myAlert").fadeOut(500);
        }, 3500);
    })
</script>

<style>
    .modal-dialog.modal-xl {
        max-width: 90vw;
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
        background: #fff;
    }

    .pdf-page {
        margin: 10px auto;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        display: block;
    }

    #pdfViewerToolbar {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 10px;
    }

    @media (max-width: 576px) {
        .modal-dialog.modal-xl {
            max-width: 95vw;
        }

        .pdf-viewer {
            height: 400px;
        }

        .pdf-page {
            width: 100%;
        }

        #pdfViewerToolbar .btn {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
    }
</style>
@endsection