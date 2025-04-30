@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('product.index') }}" class="text-info">Danh sách sản phẩm</a></li>
                            <li class="breadcrumb-item active">Thêm mới sản phẩm</li>
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
                            <form method="post" action="{{route("product.store")}}" id="quickForm">
                                @csrf
                                <div class="card-body">                           
                                    <div class="row">
                                        <div class="col-md-4 d-flex justify-content-center align-items-center">
                                            <div class="form-group text-center mt-2">
                                                <img id="holder" src="" style="width:250px; height:250px; border-radius:50%; object-fit:cover;" class="mx-auto d-block mb-4" />
                                                <span class="input-group-btn mr-2">
                                                    <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-info">
                                                        <i class="fa-solid fa-image"></i> Chọn ảnh
                                                    </a>
                                                </span>
                                                <input id="thumbnail" class="form-control" type="hidden" name="image" value="{{ old('image') }}">                                                                             
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Tên sản phẩm</label>
                                                <input type="text" name="name" class="form-control" placeholder="Nhập tên sản phẩm" value="{{old('name')}}">
                                            </div>
                                            <div class="row">                                        
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Phân loại</label>
                                                        <select name="category_id" class="form-control select2bs4" style="width: 100%">
                                                            @foreach($categories as $cate);
                                                                <option value="{{$cate->id}}" {{ old('category_id') == $cate->id ? 'selected' : '' }}>{{$cate->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Giá bán</label>
                                                        <input type="number" name="price" class="form-control" placeholder="Nhập giá bán sản phẩm" value="{{old('price')}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Giảm giá</label>
                                                        <input type="number" name="sale_price" class="form-control" placeholder="Nhập giảm giá sản phẩm" value="{{old('sale_price')}}">
                                                    </div>
                                                </div>
                                            </div>   
                                            <div class="form-group">
                                                <label>Mô tả ngắn</label>
                                                <textarea class="form-control mb-3" name="abstract" placeholder="Nhập mô tả thêm" style="height: 60px">{{ old('abstract') }}</textarea>
                                            </div>                                                                          
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Nội dung</label>
                                                <textarea id="summernote" class="form-control" name="content" placeholder="Nhập nội dung sản phẩm">{{ old('content') }}</textarea>
                                            </div>        
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{route('product.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
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
                        minlength: 5,
                        maxlength: 50
                    },
                    category_id: {
                        min: 1
                    },
                    price: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    sale_price: {
                        required: true,
                        number: true,
                        min: 0
                    }
                },
                messages: {
                    name: {
                        required: "Tên mặt hàng không được để trống!",
                        minlength: "Tên mặt hàng phải có ít nhất {0} ký tự!",
                        maxlength: "Tên mặt hàng tối đa {0} ký tự!"
                    },
                    category_id: {
                        min: "Vui lòng chọn danh mục sản phẩm!"
                    },
                    price: {
                        required: "Giá vốn không được để trống!",
                        number: "Giá vốn phải là số!",
                        min: "Giá vốn không được nhỏ hơn 0!"
                    },
                    sale_price: {
                        required: "Giá bán không được để trống!",
                        number: "Giá bán phải là số!",
                        min: "Giá bán không được nhỏ hơn 0!"
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
