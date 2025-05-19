@extends('layout/admin_layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="#" class="text-info">Thống kê</a></li>
                        <li class="breadcrumb-item text-secondary">Thống kê đánh giá</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!-- Documents Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Danh sách tài liệu được đánh giá</h3>
                        </div>
                        <div class="card-body">
                            <table id="example-table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ảnh bìa</th>
                                        <th>Tên tài liệu</th>
                                        <th>Danh mục</th>
                                        <th>Điểm trung bình</th>
                                        <th>Số lượt đánh giá</th>
                                        <th>Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documents as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><img src="{{ $item->cover_image }}" alt="" style="width: 60px; height: 80px"></td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->category->name ?? 'N/A' }}</td>
                                            <td>{{ number_format($item->average_rating, 2) }}</td>
                                            <td>{{ $item->rating_count }}</td>
                                            <td>
                                                <a href="{{ route('admin.ratings.list', $item->id) }}" 
                                                   class="btn btn-info btn-sm" 
                                                   title="Xem danh sách người đánh giá">
                                                    <i class="fa-solid fa-eye"></i> Xem
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Average Rating Charts -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Biểu đồ thống kê đánh giá trung bình theo tháng</h3>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="monthlyChartTabs" role="tablist">
                                @foreach ($monthlyStats as $month => $data)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                           id="{{ str_replace(' ', '-', $month) }}-tab" 
                                           data-toggle="tab" 
                                           href="#{{ str_replace(' ', '-', $month) }}" 
                                           role="tab">{{ $month }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content" id="monthlyChartTabsContent">
                                @foreach ($monthlyStats as $month => $data)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                         id="{{ str_replace(' ', '-', $month) }}" 
                                         role="tabpanel">
                                        <canvas id="chart-{{ str_replace(' ', '-', $month) }}" height="100"></canvas>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rating Count Charts -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Biểu đồ thống kê số lượt đánh giá theo tháng</h3>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="monthlyRatingCountTabs" role="tablist">
                                @foreach ($monthlyRatingCountStats as $month => $data)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                           id="{{ str_replace(' ', '-', $month) }}-count-tab" 
                                           data-toggle="tab" 
                                           href="#{{ str_replace(' ', '-', $month) }}-count" 
                                           role="tab">{{ $month }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content" id="monthlyRatingCountTabsContent">
                                @foreach ($monthlyRatingCountStats as $month => $data)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                         id="{{ str_replace(' ', '-', $month) }}-count" 
                                         role="tabpanel">
                                        <canvas id="count-chart-{{ str_replace(' ', '-', $month) }}" height="100"></canvas>
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
    <input type="hidden" id="monthlyStats" value="{{ json_encode($monthlyStats) }}">
    <input type="hidden" id="monthlyRatingCountStats" value="{{ json_encode($monthlyRatingCountStats) }}">
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    $(document).ready(function() {

        // Get monthly stats from hidden inputs
        const monthlyStats = JSON.parse($('#monthlyStats').val());
        const monthlyRatingCountStats = JSON.parse($('#monthlyRatingCountStats').val());

        // Initialize average rating charts
        Object.keys(monthlyStats).forEach(month => {
            const canvasId = `chart-${month.replace(' ', '-')}`;
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthlyStats[month].labels,
                    datasets: [{
                        label: 'Điểm đánh giá trung bình',
                        data: monthlyStats[month].data,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 10,
                            title: {
                                display: true,
                                text: 'Điểm trung bình'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Tên tài liệu'
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
                            text: `Thống kê đánh giá - ${month}`
                        }
                    }
                }
            });
        });

        // Initialize rating count charts
        Object.keys(monthlyRatingCountStats).forEach(month => {
            const canvasId = `count-chart-${month.replace(' ', '-')}`;
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthlyRatingCountStats[month].labels,
                    datasets: [{
                        label: 'Số lượt đánh giá',
                        data: monthlyRatingCountStats[month].data,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)', // Different color for distinction
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Số lượt đánh giá'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Tên tài liệu'
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
                            text: `Thống kê số lượt đánh giá - ${month}`
                        }
                    }
                }
            });
        });
    });
</script>
@endsection