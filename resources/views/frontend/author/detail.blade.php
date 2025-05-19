@extends('layout/web_layout')

@section('content')
<style>
    .user-display {
        display: flex;
        gap: 24px;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .user-img {
        width: 150px;
        height: 150px;
        border: 3px solid #11b76b;
        border-radius: 50%;
        overflow: hidden;
        background-color: #f0fdf7;
        box-shadow: 0 2px 8px rgba(17, 183, 107, 0.2);
        flex-shrink: 0;
    }

    .user-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        border-radius: 50%;
    }

    .user-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
        font-size: 15px;
        color: #444;
        padding-top: 4px;
    }

    .user-info h4 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #11b76b;
    }

    .user-info p {
        margin: 0;
        line-height: 1.6;
        color: #555;
        font-weight: 400;
    }

    .user-info p::before {
        content: "• ";
        color: #11b76b;
        font-weight: bold;
        margin-right: 6px;
    }

    .user-info p:first-of-type::before {
        content: "📧 ";
    }

    .user-info p:nth-of-type(2)::before {
        content: "📞 ";
    }

    .user-info p:nth-of-type(3)::before {
        content: "📅 ";
    }

    .user-info p:last-of-type::before {
        content: "📝 ";
    }

    .user-documents {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .user-documents .user-card-title {
        margin: 0 0 15px 0;
        font-size: 20px;
        font-weight: 600;
        color: #11b76b;
    }

    .user-documents .card {
        border: 3px solid #11b76b;
        border-radius: 8px;
        overflow: hidden;
        background-color: #f0fdf7;
        box-shadow: 0 2px 8px rgba(17, 183, 107, 0.2);
        transition: transform 0.3s ease;
    }

    .user-documents .card:hover {
        transform: translateY(-5px); 
    }

    .user-documents .card-body {
        padding: 15px;
        color: #444;
    }

    .user-documents .card-title {
        font-size: 18px;
        font-weight: 600;
        color: #11b76b;
        margin-bottom: 10px;
    }

    .user-documents .card-text {
        margin: 0 0 10px 0;
        line-height: 1.6;
        color: #555;
        font-weight: 400;
    }

    .user-documents .card-text::before {
        content: "• ";
        color: #11b76b;
        font-weight: bold;
        margin-right: 6px;
    }

    .user-documents .btn-primary {
        background-color: #11b76b;
        border-color: #11b76b;
        font-size: 14px;
    }

    .user-documents .btn-primary:hover {
        background-color: #0e8a50;
        border-color: #0e8a50;
    }

    .user-documents.text-center p {
        font-size: 16px;
        color: #555;
        padding: 20px;
        background-color: #f0fdf7;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(17, 183, 107, 0.2);
    }
</style>

<div class="site-breadcrumb">
    <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
    <div class="container">
        <div class="site-breadcrumb-wrap">
            <h4 class="breadcrumb-title">Chi tiết Tác giả</h4>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                <li><a href="{{ route('frontend.author.index') }}">Tác giả</a></li>
                <li class="active">{{ $author->name }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="themeht-blogs-detail">
        <div class="user-card">
            <h4 class="user-card-title">Thông tin tác giả</h4>
            <div class="user-form">
                <div class="user-display">
                    <div class="user-img">
                        <img src="{{ asset($author->avatar) }}"
                            alt="{{ $author->name }}" class="img" />
                    </div>
                    <div class="user-info">
                        <h4>{{ $author->name }}</h4>
                        <p>{{ $author->email ?? 'Không có thông tin' }}</p>
                        <p>{{ $author->phone ?? 'Không có thông tin' }}</p>
                        <p>{{ $author->birth_date ?? 'Không có thông tin' }}</p>
                        <p>{{ $author->bio ?? 'Không có thông tin' }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($documents->count() > 0)
            <div class="user-documents mt-5">
                <h4 class="user-card-title">Danh sách tài liệu</h4>
                <div class="row">
                    @foreach($documents as $document)
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $document->title }}</h5>
                                    <p class="card-text">Lượt xem: {{ $document->view_count ?? 0 }}</p>
                                    <p class="card-text">Lượt tải: {{ $document->download_count ?? 0 }}</p>
                                    <a href="{{ route('frontend.document.details', $document->id) }}" class="btn btn-primary btn-sm">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="user-documents mt-5 text-center">
                <p>Hiện tại chưa có tác phẩm nào.</p>
            </div>
        @endif
    </section>
</div>
@endsection
