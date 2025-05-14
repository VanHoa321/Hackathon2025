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
                                <li><a class="active" href="{{ route('frontend.uploads') }}"><i class="far fa-upload"></i> Tải lên tài liệu</a></li>
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
                                            <h4 class="user-card-title">Thông tin tài liệu đăng tải</h4>
                                            <div class="user-card-header-right">
                                                <a href="#" class="theme-btn"><span class="fas fa-arrow-left"></span>Quay lại</a>
                                            </div>
                                        </div>
                                        <div class="user-form">
                                            <form action="{{ route('frontend.post-upload') }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">    
                                                    <div class="col-md-3">
                                                        <div class="d-flex justify-content-center align-items-center">
                                                            <div class="form-group text-center mt-2">
                                                                <img id="holder2" src="" style="width:150px; height:200px; object-fit:cover;" class="mx-auto d-block mb-4" />
                                                                <span class="input-group-btn mr-2">
                                                                    <a id="lfm2" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                                                        <i class="far fa-image"></i> Chọn ảnh bìa
                                                                    </a>
                                                                </span>
                                                                <input id="thumbnail2" class="form-control" type="hidden" name="cover_image" value="{{ old('cover_image') }}">                                                                             
                                                            </div>
                                                        </div>
                                                    </div>      
                                                    <div class="col-md-9">
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
                                                                    <select name="category_id" class="form-control select2bs4" style="width: 100%">
                                                                        @foreach($categories as $item)
                                                                            <option value="{{$item->id}}" {{ old('category_id') == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Số Điện Thoại</label>
                                                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}" placeholder="Số Điện Thoại">
                                                                    @error('phone')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Địa Chỉ</label>
                                                                    <input type="text" name="address" class="form-control" value="{{ old('address', auth()->user()->address) }}" placeholder="Địa Chỉ">
                                                                    @error('address')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label>Mô Tả</label>
                                                                    <textarea name="description" class="form-control" placeholder="Mô Tả">{{ old('description', auth()->user()->description) }}</textarea>
                                                                    @error('description')
                                                                        <span class="text-danger">{{ $message }}</span>
                                                                    @enderror
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