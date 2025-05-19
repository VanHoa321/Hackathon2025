@extends('layout/admin_layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="#" class="text-info">Thống kê</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('statistic.comment') }}" class="text-info">Thống kê bình luận</a></li>
                        <li class="breadcrumb-item text-secondary">Danh sách bình luận</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    @if (session('error'))
        <div class="alert alert-danger" role="alert" style="position: fixed; top: 70px; right: 16px; width: auto; z-index: 999">
            {{ session('error') }}
        </div>
    @endif

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Bình luận cho tài liệu: {{ $document->title }}</h3>
                        </div>
                        <div class="card-body">
                            <table id="example-table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Người bình luận</th>
                                        <th>Nội dung</th>
                                        <th>Ngày bình luận</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($comments as $index => $comment)
                                        <tr id="comment-{{ $comment->id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $comment->user->name ?? 'N/A' }}, {{ $comment->user->phone ?? 'Chưa cập nhật' }}</td>
                                            <td>{{ $comment->content }}</td>
                                            <td>{{ \Carbon\Carbon::parse($comment->created_at)->format('d/m/Y H:i') }}</td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection