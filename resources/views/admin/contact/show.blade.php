@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('admin.contact.index') }}" class="text-info">Quản lý liên hệ</a></li>
                            <li class="breadcrumb-item active">Chi tiết liên hệ</li>
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
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Chi tiết liên hệ #{{ $contact->id }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>ID</label>
                                            <input type="text" class="form-control" value="{{ $contact->id }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Người gửi</label>
                                            <input type="text" class="form-control" value="{{ $contact->user->name ?? 'Khách' }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" class="form-control" value="{{ $contact->user->email ?? 'N/A' }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Ngày gửi</label>
                                            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($contact->created_at)->format('d/m/Y H:i') }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Trạng thái</label>
                                            <input type="text" class="form-control status-input" value="{{ $contact->is_read ? 'Đã đọc' : 'Chưa đọc' }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nội dung</label>
                                            <textarea class="form-control" style="height: 200px" readonly>{{ $contact->message }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('admin.contact.index') }}" class="btn btn-warning">
                                    <i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i>
                                </a>
                                <a href="#" class="btn btn-{{ $contact->is_read ? 'secondary' : 'primary' }} btn-mark-read" data-id="{{ $contact->id }}" title="{{ $contact->is_read ? 'Đánh dấu chưa đọc' : 'Đánh dấu đã đọc' }}">
                                    <i class="fa-solid {{ $contact->is_read ? 'fa-envelope' : 'fa-envelope-open' }} mark-icon"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-delete" data-id="{{ $contact->id }}" title="Xóa liên hệ">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
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
            $('body').on('click', '.btn-mark-read', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const isRead = $(this).hasClass('btn-primary');
                const url = isRead
                    ? "{{ route('admin.contact.mark-read', ['id' => ':id']) }}".replace(':id', id)
                    : "{{ route('admin.contact.mark-unread', ['id' => ':id']) }}".replace(':id', id);
                const $button = $(this);
                const $statusInput = $('.status-input');
                const $markIcon = $button.find('.mark-icon');

                console.log('Nút đánh dấu được nhấn, ID:', id, 'isRead:', isRead, 'URL:', url);
                console.log('Status Input found:', $statusInput.length, 'Mark Icon found:', $markIcon.length);

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('AJAX thành công:', response);
                        if (response.success) {
                            toastr.success(response.message);

                            if (isRead) {
                                $statusInput.val('Đã đọc');
                                $button.removeClass('btn-primary').addClass('btn-secondary');
                                $markIcon.removeClass('fa-envelope-open').addClass('fa-envelope');
                                $button.attr('title', 'Đánh dấu chưa đọc');
                            } else {
                                $statusInput.val('Chưa đọc');
                                $button.removeClass('btn-secondary').addClass('btn-primary');
                                $markIcon.removeClass('fa-envelope').addClass('fa-envelope-open');
                                $button.attr('title', 'Đánh dấu đã đọc');
                            }
                        } else {
                            console.log('Response không thành công:', response);
                            toastr.error('Không thể cập nhật trạng thái');
                        }
                    },
                    error: function(xhr) {
                        console.log('AJAX lỗi:', xhr.status, xhr.responseText);
                        toastr.error('Có lỗi xảy ra khi thay đổi trạng thái');
                    }
                });
            });

            $('body').on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                Swal.fire({
                    title: "Xác nhận xóa liên hệ?",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Xóa",
                    cancelButtonText: "Hủy"
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log('Gửi yêu cầu xóa, ID:', id);
                        $.ajax({
                            url: "{{ route('admin.contact.destroy', ':id') }}".replace(':id', id),
                            type: "DELETE",
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                console.log('Xóa thành công:', response);
                                toastr.success(response.message);
                                window.location.href = "{{ route('admin.contact.index') }}";
                            },
                            error: function(xhr) {
                                console.log('Lỗi khi xóa:', xhr.status, xhr.responseText);
                                toastr.error('Có lỗi khi xóa liên hệ');
                            }
                        });
                    }
                });
            });

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            }, 3500);
        });
    </script>
@endsection
