@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('document-category.index') }}" class="text-info">Quản lý danh mục tài liệu</a></li>
                            <li class="breadcrumb-item active">Chỉnh sửa danh mục</li>
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
                            <form method="post" action="{{route("document-category.update", $edit->id)}}" id="quickForm">
                                @csrf
                                <div class="card-body">                           
                                    <div class="form-group">
                                        <label>Tên danh mục tài liệu</label>
                                        <input type="text" name="name" value="{{ old('name', $edit->name) }}" class="form-control" placeholder="VD: Tài liệu hướng dẫn">
                                    </div>  
                                    <div class="form-group">
                                        <label>Điểm thưởng đăng tải</label>
                                        <input type="number" name="reward" min="0" value="{{ old('reward', $edit->reward) }}" class="form-control" placeholder="VD: 20">
                                        <em>Quy ước: 1 điểm = 1.000 VNĐ</em>
                                    </div>                                                               
                                    <div class="form-group">
                                        <label>Mô tả thêm</label>
                                        <textarea class="form-control mb-3" name="description" placeholder="Nhập mô tả thêm (nếu cần)" style="height: 100px">{{ old('description', $edit->description) }}</textarea>
                                    </div>                                                                                                                                
                                </div>
                                <div class="card-footer">
                                    <a href="{{route('document-category.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
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
                        maxlength: 50
                    }
                },
                messages: {
                    name: {
                        required: "Tên danh mục tài liệu không được để trống",
                        minlength: "Tên danh mục tài liệu phải có ít nhất {0} ký tự!",
                        maxlength: "Tên danh mục tài liệu tối đa {0} ký tự!"
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
