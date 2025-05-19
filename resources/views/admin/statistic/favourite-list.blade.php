@extends('layout/admin_layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="#" class="text-info">Thống kê</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('statistic.favourite') }}" class="text-info">Thống kê yêu thích</a></li>
                        <li class="breadcrumb-item text-secondary">Danh sách người yêu thích</li>
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
                            <h3 class="card-title">Người yêu thích tài liệu: {{ $document->title }}</h3>
                        </div>
                        <div class="card-body">
                            <table id="example-table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ảnh đại diện</th>
                                        <th>Tên người dùng</th>
                                        <th>Email</th>
                                        <th>Ngày yêu thích</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($favourites as $index => $favourite)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <img src="{{ $favourite->user->avatar ?? '/images/default-avatar.png' }}" 
                                                     alt="Avatar" 
                                                     style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover;">
                                            </td>
                                            <td>{{ $favourite->user->name ?? 'N/A' }}</td>
                                            <td>{{ $favourite->user->email ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($favourite->created_at)->format('d/m/Y H:i') }}</td>
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