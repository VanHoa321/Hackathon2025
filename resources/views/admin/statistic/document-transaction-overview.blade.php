@extends('layout/admin_layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="#" class="text-info">Thống kê</a></li>
                        <li class="breadcrumb-item text-secondary">Danh sách tài liệu</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Danh sách tài liệu</h3>
                        </div>
                        <div class="card-body">
                            <table id="example-table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tiêu đề</th>
                                        <th>Số lượt giao dịch</th>
                                        <th>Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documents as $index => $document)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $document->title }}</td>
                                            <td>{{ $document->transactions->count() }}</td>
                                            <td>
                                                <a href="{{ route('statistic.document-transaction-detail', $document->id) }}" class="btn btn-info btn-sm">Xem thống kê</a>
                                            </td>
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