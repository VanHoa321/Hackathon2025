@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('publisher.index') }}" class="text-info">Quản lý nhà xuất bản </a></li>
                            <li class="breadcrumb-item active">Thêm mới nhà xuất bản</li>
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
                        <div class="card card-primary">
                            <form method="post" action="{{route("publisher.store")}}" id="quickForm">
                                @csrf
                                <div class="card-body">                                
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Tên nhà xuất bản</label>
                                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="VD: Nhà xuất bản Kim Đồng">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Thư điện tử</label>
                                                <input type="text" name="email" value="{{ old('email') }}" class="form-control" placeholder="VD: nxbkimdong123@gmail.com">
                                            </div>
                                        </div>                                             
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Số điện thoại liên hệ</label>
                                                <input type="number" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="VD: 0349191354">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Địa chỉ</label>
                                                <input type="text" name="address" value="{{ old('address') }}" class="form-control" placeholder="VD: Số 1, Nguyễn Thái Học, Đội Cấn, Ba Đình, Hà Nội">
                                            </div>
                                        </div>                                             
                                    </div>   
                                    <div class="form-group">
                                        <label>Địa chỉ website (nếu có)</label>
                                        <input type="text" name="website" value="{{ old('website') }}" class="form-control" placeholder="nxbkimdong.com.vn">
                                    </div>
                                    <div class="form-group">
                                        <label>Mô tả thêm</label>
                                        <textarea class="form-control mb-3" name="description" placeholder="Nhập mô tả thêm (nếu cần)" style="height: 100px">{{ old('description') }}</textarea>
                                    </div>   
                                </div>                                                                                                  
                                <div class="card-footer">
                                    <a href="{{route('publisher.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
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
                    minlength: 2,
                    maxlength: 255
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 255
                },
                phone: {
                    required: true,
                    minlength: 10,
                    maxlength: 15,
                    digits: true
                },
                address: {
                    minlength: 10,
                    maxlength: 255
                },
                website: {
                    required: false,
                    url: true,
                    maxlength: 255
                }
            },
            messages: {
                name: {
                    required: "Tên nhà xuất bản không được để trống!",
                    minlength: "Tên nhà xuất bản phải có ít nhất 2 ký tự!",
                    maxlength: "Tên nhà xuất bản không được quá 255 ký tự!"
                },
                email: {
                    required: "Email không được để trống!",
                    email: "Email không hợp lệ!",
                    maxlength: "Email không được quá 255 ký tự!"
                },
                phone: {
                    required: "Số điện thoại không được để trống!",
                    minlength: "Số điện thoại phải có ít nhất 10 ký tự!",
                    maxlength: "Số điện thoại không được quá 12 ký tự!",
                    digits: "Số điện thoại phải là dạng số!"
                },
                address: {
                    minlength: "Địa chỉ phải có ít nhất 10 ký tự!",
                    maxlength: "Địa chỉ không được quá 255 ký tự!"
                },
                website: {
                    url: "Website phải là một URL hợp lệ!",
                    maxlength: "Website không được quá 255 ký tự!"
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
        });
    </script>
@endsection
