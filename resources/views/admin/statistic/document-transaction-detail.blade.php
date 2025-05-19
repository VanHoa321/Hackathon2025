@extends('layout/admin_layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="#" class="text-info">Thống kê</a></li>
                        <li class="breadcrumb-item text-secondary">Thống kê giao dịch của tài liệu: {{ $document->title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Transaction Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Danh sách giao dịch</h3>
                        </div>
                        <div class="card-body">
                            <table id="example-table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tên người dùng</th>
                                        <th>Loại giao dịch</th>
                                        <th>Số điểm</th>
                                        <th>Nội dung</th>
                                        <th>Ngày giao dịch</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactions as $index => $transaction)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $transaction->user->name ?? 'N/A' }}</td>
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
                                                    @case(5)
                                                        <span class="badge bg-success">Được phê duyệt tài liệu</span>
                                                        @break
                                                    @case(6)
                                                        <span class="badge bg-info">Cộng điểm tài liệu được tải</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">Không xác định</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $transaction->amount }}</td>
                                            <td>{{ $transaction->note }}</td>
                                            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Purchase Points Chart -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Biểu đồ thống kê lượng điểm giao dịch (mua tài liệu) theo tháng</h3>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="purchaseChartTabs" role="tablist">
                                @foreach ($transactionStats as $month => $data)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                           id="{{ str_replace(' ', '-', $month) }}-purchase-tab" 
                                           data-toggle="tab" 
                                           href="#{{ str_replace(' ', '-', $month) }}-purchase" 
                                           role="tab">{{ $month }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content" id="purchaseChartTabsContent">
                                @foreach ($transactionStats as $month => $data)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                         id="{{ str_replace(' ', '-', $month) }}-purchase" 
                                         role="tabpanel">
                                        <canvas id="purchase-chart-{{ str_replace(' ', '-', $month) }}" height="100"></canvas>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Download Count Chart -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Biểu đồ thống kê số lượt tải về theo tháng</h3>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="downloadChartTabs" role="tablist">
                                @foreach ($transactionStats as $month => $data)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                           id="{{ str_replace(' ', '-', $month) }}-download-tab" 
                                           data-toggle="tab" 
                                           href="#{{ str_replace(' ', '-', $month) }}-download" 
                                           role="tab">{{ $month }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content" id="downloadChartTabsContent">
                                @foreach ($transactionStats as $month => $data)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                         id="{{ str_replace(' ', '-', $month) }}-download" 
                                         role="tabpanel">
                                        <canvas id="download-chart-{{ str_replace(' ', '-', $month) }}" height="100"></canvas>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hidden inputs to pass data to JavaScript -->
    <input type="hidden" id="transactionStats" value="{{ json_encode($transactionStats) }}">
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    $(document).ready(function() {

        const transactionStats = JSON.parse($('#transactionStats').val());

        // Initialize purchase points charts
        Object.keys(transactionStats).forEach(month => {
            const canvasId = `purchase-chart-${month.replace(' ', '-')}`;
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: transactionStats[month].labels,
                    datasets: [{
                        label: 'Lượng điểm (Mua tài liệu)',
                        data: transactionStats[month].purchase_data,
                        borderColor: 'rgba(255, 99, 132, 1)', // Red
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Lượng điểm'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Ngày trong tháng'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: `Thống kê lượng điểm giao dịch - ${month}`
                        }
                    }
                }
            });
        });

        // Initialize download count charts
        Object.keys(transactionStats).forEach(month => {
            const canvasId = `download-chart-${month.replace(' ', '-')}`;
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: transactionStats[month].labels,
                    datasets: [{
                        label: 'Số lượt tải về',
                        data: transactionStats[month].download_data,
                        borderColor: 'rgba(54, 162, 235, 1)', // Blue
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Số lượt tải về'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Ngày trong tháng'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: `Thống kê lượt tải về - ${month}`
                        }
                    }
                }
            });
        });
    });
</script>
@endsection