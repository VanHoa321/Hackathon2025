@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('document.index') }}" class="text-info">Quản lý tài liệu</a></li>
                            <li class="breadcrumb-item active">Thêm mới tài liệu</li>
                        </ol>               
                    </div>
                </div>
            </div>
            @if ($errors->any())
                <div style="position: fixed; top: 70px; right: 16px; width: auto; z-index: 999" id="myAlert">
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger" role="alert">
                            <i class="bi bi-check2 text-danger"></i> {{ $error }}
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Điền các trường dữ liệu</h3>                               
                            </div>
                            <form method="post" action="{{route("document.store")}}" id="quickForm">
                                @csrf
                                <div class="card-body">                           
                                    <div class="row">
                                        <div class="col-md-4 d-flex justify-content-center align-items-center">
                                            <div class="form-group text-center mt-2">
                                                <img id="holder" src="" style="width:250px; height:250px; border-radius:50%; object-fit:cover;" class="mx-auto d-block mb-4" />
                                                <span class="input-group-btn mr-2">
                                                    <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-info">
                                                        <i class="fa-solid fa-image"></i> Chọn file
                                                    </a>
                                                </span>
                                                <input id="thumbnail" class="form-control" type="hidden" name="avatar" value="{{ old('avatar') }}">                                                                             
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Họ tên</label>
                                                        <input type="text" name="name" class="form-control" placeholder="VD: Võ Văn Hòa" value="{{old('name')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Thư điện tử</label>
                                                        <input type="email" name="email" class="form-control" placeholder="VD: vanhoa12092003@gmail.com" value="{{old('email')}}">
                                                    </div>
                                                </div>
                                            </div>      
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Số điện thoại</label>
                                                        <input type="number" name="phone" class="form-control" placeholder="VD: 0349191354" value="{{old('phone')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Ngày sinh</label>
                                                        <input type="date" name="birth_date" class="form-control" value="{{old('birth_date')}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Mô tả thêm</label>
                                                <textarea class="form-control mb-3" name="description" placeholder="Nhập mô tả (nếu cần)" style="height: 100px">{{ old('description') }}</textarea>
                                            </div>                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{route('author.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
                                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk" title="Lưu"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
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
                    name: {
                        required: true,
                        minlength: 3,
                        maxlength: 50
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 100
                    },
                    phone: {
                        required: true,
                        pattern: /^0\d{9,10}$/
                    }
                },
                messages: {
                    name: {
                        required: "Họ tên không được để trống!",
                        minlength: "Họ tên phải có ít nhất {0} ký tự!",
                        maxlength: "Họ tên tối đa {0} ký tự!"
                    },
                    email: {
                        required: "Email không được để trống!",
                        email: "Email không hợp lệ!",
                        maxlength: "Email tối đa {0} ký tự!"
                    },
                    phone: {
                        required: "Số điện thoại không được để trống!",
                        pattern: "Số điện thoại không hợp lệ! Phải bắt đầu bằng số 0 và đủ 10–11 số."
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
@endsection
