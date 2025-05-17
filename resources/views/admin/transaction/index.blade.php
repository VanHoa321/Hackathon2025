@extends('layout/admin_layout')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="#" class="text-info">Giao dịch người dùng</a></li>
                            <li class="breadcrumb-item text-secondary">Danh sách giao dịch</li>
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
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="example-table-2" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Người giao dịch</th>
                                            <th>Loại giao dịch</th>
                                            <th>Tài liệu</th>
                                            <th>Số tiền</th>
                                            <th>Nội dung</th>
                                            <th>Ngày giao dịch</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $counter = 1;
                                        @endphp
                                        @foreach ($items as $transaction)
                                            <tr id="transaction-{{ $transaction->id }}">
                                                <td>{{ $counter++ }}</td>
                                                <td>{{ $transaction->user->name }}, {{ $transaction->user->phone }}</td>
                                                <td>
                                                    @switch($transaction->type)
                                                        @case(1)
                                                            <span class="badge bg-success">Nạp tiền tài khoản</span>
                                                            @break
                                                        @case(2)
                                                            <span class="badge bg-primary">Mua tài liệu</span>
                                                            @break
                                                        @case(3)
                                                            <span class="badge bg-info">Đăng tải tài liệu</span>
                                                            @break
                                                        @case(4)
                                                            <span class="badge bg-warning">Tải xuống tài liệu</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-secondary">Không xác định</span>
                                                        @endswitch
                                                    </td>
                                                    <td>
                                                        @if ($transaction->document)
                                                            <a href="{{ route('frontend.document.details', $transaction->document->id) }}">
                                                                {{ $transaction->document->title }}
                                                            </a>
                                                        @else
                                                            Không có tài liệu
                                                        @endif
                                                    </td>
                                                    <td>{{ $transaction->amount == 0 ? 'Không có' : number_format($transaction->amount, 0, ',', '.') . ' điểm' }}</td>
                                                    <td>{{ $transaction->note }}</td>
                                                    <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
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