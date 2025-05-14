@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#" class="text-info">Quản lý tài liệu</a></li>
                            <li class="breadcrumb-item text-secondary">Danh sách chờ phê duyệt</li>
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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div>   
                                    <a type="button" class="btn btn-success" href="{{route('document.create')}}">
                                        <i class="fa-solid fa-plus" title="Tải lên tài liệu"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="example-table" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Ảnh bìa</th>
                                            <th>Tiêu đề</th>
                                            <th>Hình thức</th>
                                            <th>Lượt tải</th>
                                            <th>Lượt xem</th>
                                            <th>Ngày đăng tải</th>
                                            <th>Trạng thái</th>
                                            <th>Chức năng</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $counter = 1;
                                        @endphp
                                        @foreach ($items as $item)
                                            <tr id="document-{{ $item->id }}">
                                                <td>{{ $counter++ }}</td>
                                                <td><img src="{{ $item->cover_image }}" alt="" style="width: 60px; height: 80px"></td>
                                                <td>{{ $item->title }}</td>
                                                <td>
                                                    @if ($item->is_free == 1)
                                                        <span class="badge badge-success px-3 py-2">Miễn phí</span>
                                                    @else
                                                        <span class="badge badge-danger px-3 py-2">Mất phí</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->download_count }}</td>
                                                <td>{{ $item->view_count }}</td>
                                                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                                                <td>
                                                    <span class="badge badge-info p-2">Chờ phê duyệt</span>
                                                </td>
                                                <td>
                                                    <a href="#" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#pdfPreviewModal" 
                                                        class="btn btn-info btn-sm btn-preview" 
                                                        data-pdf-url="{{ asset('storage/' . $item->file_path) }}"
                                                        data-title="{{ $item->title }}"
                                                        title="Xem tài liệu">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>

                                                    <a href="#" class="btn btn-success btn-sm btn-approve" data-id="{{ $item->id }}" title="Phê duyệt tài liệu">
                                                        <i class="fa-solid fa-thumbs-up"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-danger btn-sm btn-refuse" data-id="{{ $item->id }}" title="Không phê duyệt">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    <link rel="stylesheet" href="https://unpkg.com/pdfjs-dist@3.11.174/web/pdf_viewer.css">
    <script src="https://unpkg.com/pdfjs-dist@3.11.174/build/pdf.min.js"></script>
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
                                $('#document-' + id).remove();
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
                                $('#document-' + id).remove();
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