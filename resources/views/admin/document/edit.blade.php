@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('document.index') }}" class="text-info">Quản lý tài liệu</a></li>
                            <li class="breadcrumb-item active">Cập nhật tài liệu</li>
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
                            <form method="post" action="{{route("document.update", $edit->id)}}" id="quickForm">
                                @csrf
                                <div class="card-body">                           
                                    <div class="row">
                                        <div class="col-md-4 d-flex justify-content-center align-items-center">
                                            <div class="form-group text-center mt-2">
                                                <img id="holder" src="" style="width:200px; height:250px; object-fit:cover;" class="mx-auto d-block mb-4" />
                                                <span class="input-group-btn mr-2">
                                                    <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-info">
                                                        <i class="fa-solid fa-image"></i> Chọn file
                                                    </a>
                                                </span>
                                                <input id="thumbnail" class="form-control" type="hidden" name="file_path" value="{{ old('file_path', $edit->file_path) }}">                                                                             
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Tiêu đề tài liệu</label>
                                                        <input type="text" name="title" class="form-control" placeholder="VD: Ông Trùm Cuối Cùng" value="{{old('title', $edit->title)}}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Phân loại</label>
                                                        <select name="category_id" class="form-control select2bs4" style="width: 100%">
                                                            @foreach($categories as $item)
                                                                <option value="{{$item->id}}" {{ old('category_id', $edit->category_id) == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>      
                                            <div class="row">                                              
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Nhà xuất bản</label>
                                                        <select name="publisher_id" class="form-control select2bs4" style="width: 100%">
                                                            @foreach($publishers as $item)
                                                                <option value="{{$item->id}}" {{ old('publisher_id', $edit->publisher_id) == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Năm xuất bản</label>
                                                        <input type="number" name="publication_year" class="form-control" placeholder="VD: 2023" value="{{old('publication_year', $edit->publication_year)}}" {{ $edit->publisher_id > 1 ? '' : 'readonly' }}>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Tác giả</label>
                                                        <select name="authors[]" class="form-control select2bs4" multiple>
                                                            @foreach($authors as $item)
                                                                <option value="{{ $item->id }}" data-name="{{ $item->name }}" {{ in_array($item->id, old('authors', $edit->authors->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $item->name }} - {{ $item->phone }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>            
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Hình thức</label>
                                                        <select name="is_free" class="form-control select2bs4" style="width: 100%">
                                                           <option value="0" {{ old('is_free', $edit->is_free) == 0 ? 'selected' : '' }}>Miễn phí</option>
                                                           <option value="1" {{ old('is_free', $edit->is_free) == 1 ? 'selected' : '' }}>Mất phí</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Phí tải về</label>
                                                        <input type="number" name="price" class="form-control" placeholder="VD: 100000" value="{{old('price', $edit->price)}}" {{ $edit->is_free == 0 ? 'readonly' : '' }}>
                                                        <div class="form-text text-muted">Nhập số tiền (VNĐ) nếu tài liệu không miễn phí.</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Mô tả thêm</label>
                                                <textarea class="form-control mb-3" name="description" placeholder="Nhập mô tả (nếu cần)" style="height: 100px">{{ old('description', $edit->description) }}</textarea>
                                            </div>                                        
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <a href="{{route('document.index')}}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
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
                    title: {
                        required: true,
                        minlength: 5,
                        maxlength: 50
                    },
                    'authors[]': {
                        required: true
                    },     
                },
                messages: {
                    title: {
                        required: "Tiêu đề tài liệu không được để trống!",
                        minlength: "Tiêu đề tài liệu phải có ít nhất {0} ký tự!",
                        maxlength: "Tiêu đề tài liệu tối đa {0} ký tự!"
                    },
                    'authors[]': {
                        required: "Vui lòng chọn ít nhất một tác giả!"
                    },    
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
                if (isFree == 0) {
                    $('input[name="price"]').val('');
                    $('input[name="price"]').prop('readonly', true);
                } else {
                    $('input[name="price"]').prop('readonly', false);
                }
            }

            function togglePublicationYear() {
                let publisherId = parseInt($('select[name="publisher_id"]').val());
                let yearInput = $('input[name="publication_year"]');

                if (publisherId > 1) {
                    yearInput.prop('readonly', false);
                } else {
                    yearInput.val('');
                    yearInput.prop('readonly', true);
                }
            }

            togglePriceField();
            togglePublicationYear();

            $('select[name="is_free"]').change(function () {
                togglePriceField();
            });

            $('select[name="publisher_id"]').change(function () {
                togglePublicationYear();
            });

            $('input[name="publication_year"]').on('change blur', function () {
                togglePublicationYear();
            });
        });
    </script>

@endsection
