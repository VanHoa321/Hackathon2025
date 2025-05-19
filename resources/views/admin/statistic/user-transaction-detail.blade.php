@extends('layout/admin_layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="{{ route('statistic.user-transaction.overview') }}" class="text-info">Thống kê</a></li>
                        <li class="breadcrumb-item text-secondary">Thống kê giao dịch của {{ $user->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Deposit Statistics Charts -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Biểu đồ thống kê nạp tiền theo tháng</h3>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="depositChartTabs" role="tablist">
                                @foreach ($transactionStats as $month => $data)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                           id="{{ str_replace(' ', '-', $month) }}-deposit-tab" 
                                           data-toggle="tab" 
                                           href="#{{ str_replace(' ', '-', $month) }}-deposit" 
                                           role="tab">{{ $month }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content" id="depositChartTabsContent">
                                @foreach ($transactionStats as $month => $data)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                         id="{{ str_replace(' ', '-', $month) }}-deposit" 
                                         role="tabpanel">
                                        <canvas id="deposit-chart-{{ str_replace(' ', '-', $month) }}" height="100"></canvas>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Spend Statistics Charts -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Biểu đồ thống kê chi tiêu theo tháng</h3>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="spendChartTabs" role="tablist">
                                @foreach ($transactionStats as $month => $data)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                           id="{{ str_replace(' ', '-', $month) }}-spend-tab" 
                                           data-toggle="tab" 
                                           href="#{{ str_replace(' ', '-', $month) }}-spend" 
                                           role="tab">{{ $month }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content" id="spendChartTabsContent">
                                @foreach ($transactionStats as $month => $data)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                         id="{{ str_replace(' ', '-', $month) }}-spend" 
                                         role="tabpanel">
                                        <canvas id="spend-chart-{{ str_replace(' ', '-', $month) }}" height="100"></canvas>
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

        // Initialize deposit charts
        Object.keys(transactionStats).forEach(month => {
            const canvasId = `deposit-chart-${month.replace(' ', '-')}`;
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: transactionStats[month].labels,
                    datasets: [
                        {
                            label: 'Nạp tiền',
                            data: transactionStats[month].deposit_data[0],
                            borderColor: 'rgba(75, 192, 192, 1)', // Teal
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Tài liệu được duyệt',
                            data: transactionStats[month].deposit_data[1],
                            borderColor: 'rgba(54, 162, 235, 1)', // Blue
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Tài liệu tải lên được mua',
                            data: transactionStats[month].deposit_data[2],
                            borderColor: 'rgba(153, 102, 255, 1)', // Purple
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Tổng nạp',
                            data: transactionStats[month].deposit_data[3],
                            borderColor: 'rgba(255, 159, 64, 1)', // Orange
                            backgroundColor: 'rgba(255, 159, 64, 0.2)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Số điểm'
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
                            text: `Thống kê nạp tiền - ${month}`
                        }
                    }
                }
            });
        });

        // Initialize spend charts
        Object.keys(transactionStats).forEach(month => {
            const canvasId = `spend-chart-${month.replace(' ', '-')}`;
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: transactionStats[month].labels,
                    datasets: [
                        {
                            label: 'Mua tài liệu',
                            data: transactionStats[month].spend_data[0],
                            borderColor: 'rgba(255, 99, 132, 1)', // Red
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Tổng chi tiêu',
                            data: transactionStats[month].spend_data[1],
                            borderColor: 'rgba(153, 102, 255, 1)', // Purple
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Số điểm'
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
                            text: `Thống kê chi tiêu - ${month}`
                        }
                    }
                }
            });
        });
    });
</script>
@endsection