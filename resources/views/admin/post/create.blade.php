@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">         
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('admin-post.index') }}" class="text-info">Danh sách bài viết</a></li>
                            <li class="breadcrumb-item active">Thêm mới bài viết</li>
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
        <div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; color: white; text-align: center; padding-top: 20%;">
            <i class="fa-solid fa-robot fa-6x mb-3" style="color: white;"></i>
            <h2>Đang tạo nội dung bài viết</h2>
            <p>Vui lòng chờ...</p>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Điền các trường dữ liệu</h3>                               
                            </div>
                            <form method="post" action="{{ route('admin-post.store') }}" id="quickForm">
                                @csrf
                                <div class="card-body">                                                               
                                    <div class="row">
                                        <div class="col-md-4 d-flex justify-content-center align-items-center">
                                            <div class="form-group text-center mt-2">
                                                <img id="holder" src="{{ old('image') }}" style="width:320px; height:220px;" class="mx-auto d-block mb-4" />
                                                <span class="input-group-btn">
                                                    <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-info">
                                                        <i class="fa-solid fa-image"></i> Chọn ảnh
                                                    </a>
                                                    <a class="btn btn-success ml-1" id="btnGenContentAI">Tạo nội dung với AI</a>
                                                </span>
                                                <input id="thumbnail" class="form-control" type="hidden" name="image" value="{{ old('image') }}">                                                                             
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Tiêu đề</label>
                                                        <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="Nhập tiêu đề">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Phân loại bài viết</label>
                                                        <select name="tags[]" class="form-control select2bs4" multiple>
                                                            @foreach($tags as $tag)
                                                                <option value="{{ $tag->id }}" data-name="{{ $tag->name }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>{{ $tag->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>                         
                                            </div>
                                            <div class="form-group">
                                                <label>Mô tả ngắn</label>
                                                <textarea class="form-control mb-3" name="abstract" placeholder="Nhập mô tả ngắn" style="height: 109px">{{ old('abstract') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="contentConfigForm" style="display: none;" class="border p-3 mb-3 rounded mt-2">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Mục tiêu bài viết</label>
                                                    <select class="form-control select2bs4" id="purposeOption">
                                                        <option>Giới thiệu</option>
                                                        <option>Quảng bá</option>
                                                        <option>Thông báo</option>
                                                        <option>Chia sẻ kinh nghiệm / kiến thức</option>
                                                        <option>Kêu gọi hành động</option>
                                                        <option>Hướng dẫn học tập</option>
                                                        <option>Phân tích nội dung học thuật</option>
                                                        <option>Định hướng nghề nghiệp</option>
                                                        <option>Cung cấp thông tin sức khỏe</option>
                                                        <option>Chia sẻ kiến thức y khoa</option>
                                                        <option>Hướng dẫn phòng tránh bệnh</option>
                                                        <option>Giới thiệu sản phẩm công nghệ</option>
                                                        <option>Hướng dẫn sử dụng phần mềm</option>
                                                        <option>Phân tích xu hướng công nghệ</option>
                                                        <option>Xây dựng thương hiệu</option>
                                                        <option>Chiến dịch truyền thông</option>
                                                        <option>Tăng tương tác người dùng</option>
                                                        <option>Nâng cao nhận thức cộng đồng</option>
                                                        <option>Tư vấn pháp lý</option>
                                                        <option>Truyền thông chính sách</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Đối tượng mục tiêu</label>
                                                    <select class="form-control select2bs4" id="targetAudience">
                                                    <option>Trẻ em</option>
                                                    <option>Thanh thiếu niên</option>
                                                    <option>Sinh viên</option>
                                                    <option>Người đi làm</option>
                                                    <option>Dân văn phòng</option>
                                                    <option>Người làm việc tự do</option>
                                                    <option>Người cao tuổi</option>
                                                    <option>Người nội trợ</option>
                                                    <option>Người có thu nhập cao</option>
                                                    <option>Người có thu nhập trung bình/thấp</option>
                                                    <option>Người nước ngoài sống tại Việt Nam</option>
                                                    <option>Người ăn kiêng</option>
                                                    <option>Người tập gym / thể hình</option>
                                                    <option>Người mắc bệnh mãn tính</option>
                                                    <option>Người quan tâm đến sức khỏe</option>
                                                    <option>Người yêu thích công nghệ</option>
                                                    <option>Người quan tâm đến giáo dục</option>
                                                    <option>Người yêu thích du lịch</option>
                                                    <option>Phụ nữ mang thai</option>
                                                    <option>Bậc cha mẹ</option>
                                                    <option>Người khởi nghiệp</option>
                                                    <option>Người làm nghệ thuật / sáng tạo</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Độ dài bài viết</label>
                                                    <select class="form-control select2bs4" id="lengthOption">
                                                        <option>Ngắn (200 - 300 từ)</option>
                                                        <option>Vừa (300 - 500 từ)</option>
                                                        <option>Dài (500 - 1000 từ)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Phong cách viết</label>
                                                    <select class="form-control select2bs4" id="toneOption">
                                                    <option>Thân thiện</option>
                                                    <option>Chuyên nghiệp</option>
                                                    <option>Hài hước</option>
                                                    <option>Kể chuyện</option>
                                                    <option>Truyền cảm hứng</option>
                                                    <option>Sáng tạo</option>
                                                    <option>Giản dị, gần gũi</option>
                                                    <option>Trang trọng</option>
                                                    <option>Thuyết phục</option>
                                                    <option>Sâu sắc</option>
                                                    <option>Trung lập</option>
                                                    <option>Phân tích</option>
                                                    <option>Mạnh mẽ, quyết đoán</option>
                                                    <option>Tâm lý</option>
                                                    <option>Thực tế</option>
                                                    <option>Lạc quan</option>
                                                    <option>Kêu gọi hành động</option>
                                                    <option>Giáo dục</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <a class="btn btn-info" id="btnGenerateContent">Tạo nội dung</a>
                                        </div>                                
                                    </div>
                                    <div class="form-group">
                                        <label>Nội dung</label>
                                        <textarea id="summernote" class="form-control" name="content" placeholder="Nhập nội dung bài viết">{{ old('content') }}</textarea>
                                    </div>                                                                                                                                                                                                                 
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('admin-post.index') }}" class="btn btn-warning"><i class="fa-solid fa-rotate-left" style="color:white" title="Quay lại"></i></a>
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
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js') }}"></script>
    <script src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
    <script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>

    <script>
        $(function () {
            $('#quickForm').validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 5,
                        maxlength: 255
                    },
                    image: {
                        required: true
                    },
                    content: {
                        required: true
                    },
                    'tags[]': {
                        required: true
                    },
                    abstract: {
                        maxlength: 255
                    }
                },
                messages: {
                    title: {
                        required: "Tiêu đề bài viết không được để trống",
                        minlength: "Tiêu đề phải có ít nhất {0} ký tự!",
                        maxlength: "Tiêu đề tối đa {0} ký tự!"
                    },
                    image: {
                        required: "Vui lòng chọn hình ảnh cho bài viết"
                    },
                    content: {
                        required: "Nội dung bài viết không được để trống"
                    },
                    'tags[]': {
                        required: "Vui lòng chọn ít nhất một phân loại"
                    },
                    abstract: {
                        maxlength: "Mô tả ngắn tối đa {0} ký tự!"
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

            $('.select2').select2();
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            $('#summernote').summernote({
                height: 300
            });

            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            }, 3500);
        });

        document.getElementById('btnGenContentAI').addEventListener('click', function () {
            const form = document.getElementById('contentConfigForm');
            const btn = this;
            const summernoteSection = document.getElementById('summernote').closest('.form-group');

            const isVisible = form.style.display === 'block';
            form.style.display = isVisible ? 'none' : 'block';
            summernoteSection.style.display = isVisible ? 'block' : 'none';
            if (isVisible) {
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-success');
                btn.textContent = 'Tạo nội dung với AI';
            } else {
                btn.classList.remove('btn-success');
                btn.classList.add('btn-danger');
                btn.textContent = 'Hủy';
            }
        });

        $('#btnGenerateContent').click(function () {
            let tagNames = $('select[name="tags[]"] option:selected').map(function() {
                return $(this).data('name');
            }).get();
            let data = {
                title: $('input[name="title"]').val(),
                tags: tagNames,
                abstract: $('textarea[name="abstract"]').val(),
                purpose: $('#purposeOption').val(),
                target: $('#targetAudience').val(),
                length: $('#lengthOption').val(),
                tone: $('#toneOption').val()
            };

            $.ajax({
                url: '/api/generate-post',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),   
                beforeSend: function () {
                    $('#loadingOverlay').show();
                },
                success: function (response) {
                    $('#contentConfigForm').hide();
                    var content = convertMarkdownToHtml(response.content);
                    $('#summernote').summernote('code', content);
                    $('#loadingOverlay').hide();
                    const summernoteSection = document.getElementById('summernote').closest('.form-group');
                    const btn = document.getElementById('btnGenContentAI');
                    summernoteSection.style.display = 'block';
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-success');
                    btn.textContent = 'Tạo nội dung với AI';
                },
                error: function (xhr, status, error) {
                    $('#loadingOverlay').hide();
                    console.error("Chi tiết lỗi:", xhr.responseText);
                }
            });
        });

        function convertMarkdownToHtml(content) {
            content = content.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            content = content.replace(/\*(.*?)\*/g, '<em>$1</em>');
            content = content.replace(/^---$/gm, '<hr>');

            return content;
        }

    </script>
@endsection