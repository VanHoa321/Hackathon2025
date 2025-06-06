@extends('layout/web_layout')
@section('content')
    <main class="main">
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Tải lên tài liệu</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                        <li class="active">Tải lên tài liệu</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="user-area bg py-100">
            <div class="container">
                @if (session('messenge'))
                    <div class="alert alert-{{ session('messenge.style') }} alert-dismissible fade show" role="alert">
                        {{ session('messenge.msg') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
                                            <h4 class="user-card-title">Thông tin tài liệu đăng tải</h4>
                                            <div class="user-card-header-right">
                                                <a href="{{ route("frontend.mydocument") }}" class="theme-btn"><span class="fas fa-arrow-left"></span>Quay lại</a>
                                            </div>
                                        </div>
                                        <div class="user-form">
                                            <form id="quickForm" action="{{ route('frontend.post-upload') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">    
                                                    <div class="col-md-3">
                                                        <div class="d-flex justify-content-center align-items-center">
                                                            <div class="form-group text-center mt-2">
                                                                <img id="holder3" src="" style="width:150px; height:200px; object-fit:cover;" class="mx-auto d-block mb-4" />
                                                                <span class="input-group-btn mr-2">
                                                                    <a id="lfm3" data-input="thumbnail3" data-preview="holder3" class="btn btn-primary">
                                                                        <i class="far fa-image"></i> Chọn ảnh bìa
                                                                    </a>
                                                                </span>
                                                                <input id="thumbnail3" class="form-control" type="hidden" name="cover_image" value="{{ old('cover_image') }}">                                                                             
                                                            </div>
                                                        </div>
                                                    </div>      
                                                    <div class="col-md-9">
                                                        <div class="row">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Tiêu đề tài liệu</label>
                                                                    <input type="text" name="title" class="form-control" placeholder="VD: Ngày mà trời sáng" value="{{old('title')}}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Phân loại</label>
                                                                    <select name="category_id" class="select">
                                                                        @foreach($categories as $item)
                                                                            <option value="{{$item->id}}" {{ old('category_id') == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                            
                                                        <div class="row">
                                                            <div class="col-md-12 mb-2">
                                                                <label>Tải lên tài liệu</label>
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <a id="lfm2" data-input="thumbnail2" class="btn btn-success p-2 mr-2" style="width: 250px">
                                                                        <i class="fa-solid fa-file-arrow-up"></i> Chọn file tài liệu
                                                                    </a>
                                                                    <input id="thumbnail2" class="form-control d-none" type="text" name="file_path" value="{{ old('file_path') }}">
                                                                    <span id="file_name_display" class="form-control bg-light" style="border: 1px solid #ced4da; padding: 10px; display: inline-block; min-height: 44px;"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Hình thức</label>
                                                                        <select name="is_free" class="select">
                                                                            <option value="1" {{ old('is_free') == 1 ? 'selected' : '' }}>Miễn phí</option>
                                                                            <option value="0" {{ old('is_free') == 0 ? 'selected' : '' }}>Mất phí</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Phí tải về</label>
                                                                        <input type="number" name="price" class="form-control" placeholder="VD: 100" value="{{old('price')}}" style="width: 100%;">
                                                                        <em>Quy ước: 1đ = 1.000 VNĐ</em>
                                                                    </div>
                                                                </div>
                                                            </div>                                                                                                                                                                              
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Mô tả</label>
                                                                        <textarea class="form-control mb-3" name="description" placeholder="Nhập mô tả tài liệu" style="height: 100px">{{ old('description') }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <input type="hidden" name="avatar" id="thumbnail" value="{{ old('avatar', auth()->user()->avatar) }}">
                                                            </div>
                                                        </div>
                                                    </div>                                  
                                                </div>
                                                <button type="submit" class="theme-btn"><span class="far fa-save"></span> Lưu Thay Đổi</button>
                                            </form>
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
    <script src="{{asset("assets/plugins/jquery-validation/jquery.validate.min.js")}}"></script>
    <script src="{{asset("assets/plugins/jquery-validation/additional-methods.min.js")}}"></script>
    <script src="{{asset("assets/plugins/select2/js/select2.full.min.js")}}"></script>
    <script src="{{asset("assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js")}}"></script>

    <script>
        $(function () {
            $('#quickForm').validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 5,
                        maxlength: 50
                    },
                    description: {
                        required: true,
                    }
                },
                messages: {
                    title: {
                        required: "Tiêu đề tài liệu không được để trống!",
                        minlength: "Tiêu đề tài liệu phải có ít nhất {0} ký tự!",
                        maxlength: "Tiêu đề tài liệu tối đa {0} ký tự!"
                    },
                    description: {
                        required: "Mô tả tài liệu không được để trống!",
                    }                 
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            $('.select2').select2()
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            },3500);
        });
    </script>

    <script>
        $(document).ready(function () {
            function togglePriceField() {
                let isFree = $('select[name="is_free"]').val();
                if (isFree == 1) {
                    $('input[name="price"]').val('');
                    $('input[name="price"]').prop('readonly', true);
                    $('input[name="price"]').css('background-color', '#e0e0e0');
                } else {
                    $('input[name="price"]').prop('readonly', false);
                    $('input[name="price"]').css('background-color', 'white');
                }
            }

            togglePriceField();

            $('select[name="is_free"]').change(function () {
                togglePriceField();
            });
        });
    </script>
@endsection