@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#" class="text-info">Quản lý tài liệu</a></li>
                            <li class="breadcrumb-item text-secondary">Danh sách tài liệu</li>
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
                                            <th>Hình ảnh</th>
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
                                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                        <input type="checkbox" class="custom-control-input IsActive" id="customSwitch{{ $item->id }}" {{ $item->status ? 'checked' : '' }} value="{{ $item->id }}">
                                                        <label class="custom-control-label" for="customSwitch{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($item->uploaded_by == Auth::user()->id)
                                                        <a href="{{ route("document.edit", $item->id) }}" class="btn btn-info btn-sm" title="Sửa thông tin tài liệu">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                    @endif
                                                    <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="{{ $item->id }}" title="Xóa tài liệu">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
        $(document).ready(function() {

            $('body').on('change', '.IsActive', function(e) {
                e.preventDefault();
                var check = $(this);
                const id = check.val();
                $.ajax({
                    url: "/admin/document/change/" + id,
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Có lỗi xảy ra khi đổi trạng thái');
                    }
                });
            })

            $('body').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: "Xác nhận xóa tài liệu?",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Xóa",
                    cancelButtonText: "Hủy"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/admin/document/destroy/" + id,
                            type: "DELETE",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                toastr.success(response.message);
                                $('#document-' + id).remove();
                            },
                            error: function(xhr) {
                                toastr.error('Có lỗi khi xóa tài liệu');
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
@endsection