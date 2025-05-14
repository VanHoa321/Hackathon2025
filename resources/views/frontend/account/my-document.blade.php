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
                                <li><a href="{{ route('frontend.settings') }}"><i class="far fa-gear"></i> Cài đặt</a></li>
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
                                                        <tr>
                                                            <td><span class="table-list-code">{{ $item->title }}</span></td>
                                                            <td><a href="{{ route("frontend.document.download", $item->id) }}">{{ $item->file_path }}</a></td>
                                                            <td>{{ $item->download_count }} lượt</td>
                                                            <td>
                                                                @if ($item->status == 1)
                                                                    <span class="badge badge-success">Đã tải lên</span>
                                                                @elseif ($item->status == 0) 
                                                                    <span class="badge badge-info">Chờ phê duyệt</span>
                                                                @else
                                                                    <span class="badge badge-danger">Đã từ chối</span>
                                                                @endif                                                       
                                                            </td>
                                                            <td>
                                                                @if ($item->approve == 0)
                                                                    <a href="#" class="btn btn-outline-secondary btn-sm rounded-2" data-tooltip="tooltip" title="Cập nhật tài liệu">
                                                                        <i class="far fa-pen"></i>
                                                                    </a>
                                                                @endif
                                                                <a href="#" class="btn btn-outline-danger btn-sm rounded-2" data-tooltip="tooltip" title="Xóa tài liệu"><i class="far fa-trash-can"></i></a>
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
        document.addEventListener('DOMContentLoaded', function() {
            const removeFavouriteButtons = document.querySelectorAll('.remove-favourite');

            removeFavouriteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const documentId = this.getAttribute('data-id');
                    const url = `/account/favourite/${documentId}`;

                    // Hiển thị SweetAlert để xác nhận
                    Swal.fire({
                        title: 'Bạn có chắc chắn?',
                        text: "Bạn muốn bỏ yêu thích tài liệu này?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Có, bỏ yêu thích!',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Gửi yêu cầu DELETE nếu người dùng xác nhận
                            fetch(url, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json',
                                    },
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire(
                                            'Thành công!',
                                            data.message,
                                            'success'
                                        ).then(() => {
                                            location.reload(); // Reload the page to update the list
                                        });
                                    } else {
                                        Swal.fire(
                                            'Lỗi!',
                                            data.message,
                                            'error'
                                        );
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    Swal.fire(
                                        'Lỗi!',
                                        'Đã xảy ra lỗi, vui lòng thử lại!',
                                        'error'
                                    );
                                });
                        }
                    });
                });
            });
        });
    </script>
@endsection