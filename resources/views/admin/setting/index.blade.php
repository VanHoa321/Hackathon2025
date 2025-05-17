@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('publisher.index') }}" class="text-info">Hệ thống </a></li>
                            <li class="breadcrumb-item active">Cấu hình hệ thống</li>
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
                            <form method="post" action="{{ route("setting.update") }}" id="quickForm">
                                @csrf
                                <div class="card-body">                                
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Số lần đăng nhập tối đa</label>
                                                <input type="number" name="max_login" min="1" step="1" oninput="this.value = Math.max(1, Math.floor(this.value))" value="{{ old('max_login', $setting->max_login) }}" class="form-control" placeholder="VD: 5">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Số tài liệu mới nhất ở trang chủ</label>
                                                <input type="number" name="home_doc_new" min="1" step="1" oninput="this.value = Math.max(1, Math.floor(this.value))" value="{{ old('home_doc_new', $setting->home_doc_new) }}" class="form-control" placeholder="VD: 5">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Số tài liệu tải nhiều ở trang chủ</label>
                                                <input type="number" name="home_doc_download" min="1" step="1" oninput="this.value = Math.max(1, Math.floor(this.value))" value="{{ old('home_doc_download', $setting->home_doc_download) }}" class="form-control" placeholder="VD: 5">
                                            </div>
                                        </div>   
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Số tài liệu xem nhiều ở trang chủ</label>
                                                <input type="number" name="home_doc_view" min="1" step="1" oninput="this.value = Math.max(1, Math.floor(this.value))" value="{{ old('home_doc_view', $setting->home_doc_view) }}" class="form-control" placeholder="VD: 5">
                                            </div>
                                        </div>     
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Số bài viết mới trên trang chủ</label>
                                                <input type="number" name="home_post" min="1" step="1" oninput="this.value = Math.max(1, Math.floor(this.value))" value="{{ old('home_post', $setting->home_post) }}" class="form-control" placeholder="VD: 5">
                                            </div>
                                        </div>  
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Số tài liệu trên 1 trang tài liệu</label>
                                                <input type="number" name="doc_page" min="1" step="1" oninput="this.value = Math.max(1, Math.floor(this.value))" value="{{ old('doc_page', $setting->doc_page) }}" class="form-control" placeholder="VD: 5">
                                            </div>
                                        </div>  
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Số trang tài liệu cho xem trước</label>
                                                <input type="number" name="doc_preview" min="1" step="1" oninput="this.value = Math.max(1, Math.floor(this.value))" value="{{ old('doc_preview', $setting->doc_preview) }}" class="form-control" placeholder="VD: 5">
                                            </div>
                                        </div>                                                        
                                    </div>    
                                </div>                                                                                                  
                                <div class="card-footer">
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
                    max_login: {
                        required: true,
                        min: 1,
                        max: 100,
                        digits: true
                    },
                    home_doc_new: {
                        required: true,
                        min: 1,
                        max: 50,
                        digits: true
                    },
                    home_doc_download: {
                        required: true,
                        min: 1,
                        max: 50,
                        digits: true
                    },
                    home_doc_view: {
                        required: true,
                        min: 1,
                        max: 50,
                        digits: true
                    },
                    home_post: {
                        required: true,
                        min: 1,
                        max: 20,
                        digits: true
                    },
                    doc_page: {
                        required: true,
                        min: 1,
                        max: 50,
                        digits: true
                    },
                    doc_preview: {
                        required: true,
                        min: 1,
                        max: 50,
                        digits: true
                    }
                },
                messages: {
                    max_login: {
                        required: "Số lần đăng nhập tối đa không được để trống!",
                        min: "Số lần đăng nhập tối đa phải lớn hơn hoặc bằng 1!",
                        max: "Số lần đăng nhập tối đa không được vượt quá 100!",
                        digits: "Số lần đăng nhập tối đa phải là số nguyên!"
                    },
                    home_doc_new: {
                        required: "Số tài liệu mới nhất không được để trống!",
                        min: "Số tài liệu mới nhất phải lớn hơn hoặc bằng 1!",
                        max: "Số tài liệu mới nhất không được vượt quá 50!",
                        digits: "Số tài liệu mới nhất phải là số nguyên!"
                    },
                    home_doc_download: {
                        required: "Số tài liệu tải nhiều không được để trống!",
                        min: "Số tài liệu tải nhiều phải lớn hơn hoặc bằng 1!",
                        max: "Số tài liệu tải nhiều không được vượt quá 50!",
                        digits: "Số tài liệu tải nhiều phải là số nguyên!"
                    },
                    home_doc_view: {
                        required: "Số tài liệu xem nhiều không được để trống!",
                        min: "Số tài liệu xem nhiều phải lớn hơn hoặc bằng 1!",
                        max: "Số tài liệu xem nhiều không được vượt quá 50!",
                        digits: "Số tài liệu xem nhiều phải là số nguyên!"
                    },
                    home_post: {
                        required: "Số bài viết mới không được để trống!",
                        min: "Số bài viết mới phải lớn hơn hoặc bằng 1!",
                        max: "Số bài viết mới không được vượt quá 20!",
                        digits: "Số bài viết mới phải là số nguyên!"
                    },
                    doc_page: {
                        required: "Số tài liệu trên 1 trang không được để trống!",
                        min: "Số tài liệu trên 1 trang phải lớn hơn hoặc bằng 1!",
                        max: "Số tài liệu trên 1 trang không được vượt quá 50!",
                        digits: "Số tài liệu trên 1 trang phải là số nguyên!"
                    },
                    doc_preview: {
                        required: "Số trang tài liệu xem trước không được để trống!",
                        min: "Số trang tài liệu xem trước phải lớn hơn hoặc bằng 1!",
                        max: "Số trang tài liệu xem trước không được vượt quá 50!",
                        digits: "Số trang tài liệu xem trước phải là số nguyên!"
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
            }, 3500);
        });
    </script>
@endsection
