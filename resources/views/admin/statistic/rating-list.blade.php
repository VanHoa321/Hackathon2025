@extends('layout/admin_layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="#" class="text-info">Thống kê</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('statistic.rating') }}" class="text-info">Thống kê đánh giá</a></li>
                        <li class="breadcrumb-item text-secondary">Danh sách người đánh giá</li>
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
                            <h3 class="card-title">Người đánh giá tài liệu: {{ $document->title }}</h3>
                        </div>
                        <div class="card-body">
                            <table id="example-table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ảnh đại diện</th>
                                        <th>Tên người dùng</th>
                                        <th>Email</th>
                                        <th>Điểm đánh giá</th>
                                        <th>Ngày đánh giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ratings as $index => $rating)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <img src="{{ $rating->user->avatar ?? '/images/default-avatar.png' }}" 
                                                     alt="Avatar" 
                                                     style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover;">
                                            </td>
                                            <td>{{ $rating->user->name ?? 'N/A' }}</td>
                                            <td>{{ $rating->user->email ?? 'N/A' }}</td>
                                            <td>{{ number_format($rating->rating, 2) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($rating->created_at)->format('d/m/Y H:i') }}</td>
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