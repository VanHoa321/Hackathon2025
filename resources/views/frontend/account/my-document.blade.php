@extends('layout/web_layout')
@section('content')
    <main class="main">
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Hồ sơ của tôi</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                        <li class="active">Hồ sơ của tôi</li>
                    </ul>
                </div>
            </div>
        </div>
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {!! session('error') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="user-area bg py-100">
            <div class="container">
                @if (session('messenge2'))
                    <div class="alert alert-{{ session('messenge.style') }}">
                        {{ session('messenge.msg') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-lg-3">
                        <div class="sidebar">
                            <div class="sidebar-top">
                                <div class="sidebar-profile-img">
                                    <img id="holder" src="">
                                </div>
                                <h5>{{ auth()->user()->name }}</h5>
                                <p><a href="mailto:{{ auth()->user()->email }}">{{ auth()->user()->email }}</a></p>
                            </div>
                            <ul class="sidebar-list">
                                <li><a href="{{ route('frontend.profile') }}"><i class="far fa-user"></i> Hồ sơ của tôi</a></li>
                                <li><a href="{{ route('frontend.edit-password') }}"><i class="far fa-lock"></i> Đổi Mật Khẩu</a></li>
                                <li><a href="{{ route('frontend.my-favourite') }}"><i class="far fa-heart"></i> Danh sách yêu thích</a></li>
                                <li><a class="active" href="{{ route('frontend.mydocument') }}"><i class="far fa-upload"></i> Danh sách tài liệu</a></li>
                                <li><a href="{{ route('frontend.tran-history') }}"><i class="far fa-money-bill-transfer"></i> Lịch sử giao dịch</a></li>
                                <li><a href="{{ route('frontend.point') }}"><i class="far fa-coins"></i> Nạp coin</a></li>
                                <li><a href="{{ route('logout') }}"><i class="far fa-sign-out"></i> Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="user-wrapper">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="user-card">
                                        <div class="user-card-header">
                                            <h4 class="user-card-title">Tài liệu cá nhân</h4>
                                            <div class="user-card-header-right">
                                                <a href="{{ route("frontend.uploads") }}" class="theme-btn"><i class="far fa-cloud-arrow-up me-2"></i>Tải lên tài liệu</a>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-borderless text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>Tiêu đề</th>
                                                        <th>Tài liệu</th>
                                                        <th>Lượt tải</th>
                                                        <th>Trạng thái</th>
                                                        <th>Chức năng</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($items as $item)
                                                        <tr id="document-{{ $item->id }}">
                                                            <td><span class="table-list-code">{{ $item->title }}</span></td>
                                                            <td><a href="{{ route("frontend.document.download", $item->id) }}">{{ $item->file_path }}</a></td>
                                                            <td>{{ $item->download_count }} lượt</td>
                                                            <td>
                                                                @if ($item->approve == 1)
                                                                    <span class="badge badge-success">Đã phê duyệt</span>
                                                                @elseif ($item->approve == 0) 
                                                                    <span class="badge badge-info">Chờ phê duyệt</span>
                                                                @else
                                                                    <span class="badge badge-danger">Đã từ chối</span>
                                                                @endif                                                       
                                                            </td>
                                                            <td>
                                                                <a href="#" data-id="{{ $item->id }}" class="btn btn-outline-danger btn-sm btn-delete rounded-2" data-tooltip="tooltip" title="Xóa tài liệu"><i class="far fa-trash-can"></i></a>
                                                            </td>
                                                        </tr>    
                                                    @endforeach                                                                                    
                                                </tbody>
                                            </table>
                                            <div class="col-md-12">
                                                <input type="hidden" name="avatar" id="thumbnail" value="{{ old('avatar', auth()->user()->avatar) }}">
                                            </div>
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
    <script>
        $(document).ready(function() {

            $('body').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: "Xác nhận xóa tài liệu này?",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Xác nhận",
                    cancelButtonText: "Hủy"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/account/destroy/" + id,
                            type: "DELETE",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                toastr.success(response.message);
                                $('#document-' + id).remove();
                            },
                            error: function(xhr) {
                                toastr.error('Có lỗi khi xóa tài liệu này.');
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