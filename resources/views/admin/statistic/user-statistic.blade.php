@extends('layout/admin_layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="#" class="text-info">Thống kê</a></li>
                        <li class="breadcrumb-item text-secondary">Thống kê người dùng</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Registration Statistics Charts -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Biểu đồ thống kê số tài khoản được đăng ký theo tháng</h3>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="registrationChartTabs" role="tablist">
                                @foreach ($registrationStats as $month => $data)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                           id="{{ str_replace(' ', '-', $month) }}-registration-tab" 
                                           data-toggle="tab" 
                                           href="#{{ str_replace(' ', '-', $month) }}-registration" 
                                           role="tab">{{ $month }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content" id="registrationChartTabsContent">
                                @foreach ($registrationStats as $month => $data)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                         id="{{ str_replace(' ', '-', $month) }}-registration" 
                                         role="tabpanel">
                                        <canvas id="registration-chart-{{ str_replace(' ', '-', $month) }}" height="100"></canvas>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction Statistics Charts -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Biểu đồ thống kê số lượt giao dịch theo tháng</h3>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="transactionChartTabs" role="tablist">
                                @foreach ($transactionStats as $month => $data)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                           id="{{ str_replace(' ', '-', $month) }}-transaction-tab" 
                                           data-toggle="tab" 
                                           href="#{{ str_replace(' ', '-', $month) }}-transaction" 
                                           role="tab">{{ $month }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content" id="transactionChartTabsContent">
                                @foreach ($transactionStats as $month => $data)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                         id="{{ str_replace(' ', '-', $month) }}-transaction" 
                                         role="tabpanel">
                                        <canvas id="transaction-chart-{{ str_replace(' ', '-', $month) }}" height="100"></canvas>
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
    <input type="hidden" id="registrationStats" value="{{ json_encode($registrationStats) }}">
    <input type="hidden" id="transactionStats" value="{{ json_encode($transactionStats) }}">
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    $(document).ready(function() {
        // Get stats from hidden inputs
        const registrationStats = JSON.parse($('#registrationStats').val());
        const transactionStats = JSON.parse($('#transactionStats').val());

        // Initialize registration charts
        Object.keys(registrationStats).forEach(month => {
            const canvasId = `registration-chart-${month.replace(' ', '-')}`;
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: registrationStats[month].labels,
                    datasets: [{
                        label: 'Số tài khoản đăng ký',
                        data: registrationStats[month].data,
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
                                text: 'Số tài khoản'
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
                            text: `Thống kê đăng ký - ${month}`
                        }
                    }
                }
            });
        });

        // Initialize transaction charts
        Object.keys(transactionStats).forEach(month => {
            const canvasId = `transaction-chart-${month.replace(' ', '-')}`;
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: transactionStats[month].labels,
                    datasets: [
                        {
                            label: 'Nạp tiền',
                            data: transactionStats[month].data[0],
                            borderColor: 'rgba(75, 192, 192, 1)', // Teal
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Mua tài liệu',
                            data: transactionStats[month].data[1],
                            borderColor: 'rgba(255, 99, 132, 1)', // Red
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Tải lên tài liệu',
                            data: transactionStats[month].data[2],
                            borderColor: 'rgba(255, 206, 86, 1)', // Yellow
                            backgroundColor: 'rgba(255, 206, 86, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Tải xuống tài liệu',
                            data: transactionStats[month].data[3],
                            borderColor: 'rgba(153, 102, 255, 1)', // Purple
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Phê duyệt tài liệu',
                            data: transactionStats[month].data[4],
                            borderColor: 'rgb(29, 255, 29)', 
                            backgroundColor: 'rgba(255, 206, 86, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Tải liệu được tải',
                            data: transactionStats[month].data[5],
                            borderColor: 'rgb(255, 0, 221)', 
                            backgroundColor: 'rgba(255, 206, 86, 0.2)',
                            fill: true,
                            tension: 0.4
                        },
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Số lượt giao dịch'
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
                            text: `Thống kê giao dịch - ${month}`
                        }
                    }
                }
            });
        });
    });
</script>
@endsection