@extends('layout/admin_layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="#" class="text-info">Thống kê</a></li>
                        <li class="breadcrumb-item text-secondary">Thống kê yêu thích</li>
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
                            <h3 class="card-title">Danh sách tài liệu được yêu thích</h3>
                        </div>
                        <div class="card-body">
                            <table id="example-table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ảnh bìa</th>
                                        <th>Tên tài liệu</th>
                                        <th>Danh mục</th>
                                        <th>Số lượt yêu thích</th>
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
                                            <td>{{ $item->favourite_count }}</td>
                                            <td>
                                                <a href="{{ route('admin.favourites.list', $item->id) }}" 
                                                   class="btn btn-info btn-sm" 
                                                   title="Xem danh sách người yêu thích">
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

            <!-- Overall Favourites Chart -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Top 10 tài liệu được yêu thích nhất</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="overall-favourite-chart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Favourites Chart -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Biểu đồ thống kê yêu thích theo tháng</h3>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="monthlyFavouriteTabs" role="tablist">
                                @foreach ($monthlyFavouriteStats as $month => $data)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                           id="{{ str_replace(' ', '-', $month) }}-favourite-tab" 
                                           data-toggle="tab" 
                                           href="#{{ str_replace(' ', '-', $month) }}-favourite" 
                                           role="tab">{{ $month }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content" id="monthlyFavouriteTabsContent">
                                @foreach ($monthlyFavouriteStats as $month => $data)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                         id="{{ str_replace(' ', '-', $month) }}-favourite" 
                                         role="tabpanel">
                                        <canvas id="favourite-chart-{{ str_replace(' ', '-', $month) }}" height="100"></canvas>
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
    <input type="hidden" id="overallFavouriteData" value="{{ json_encode($overallfavouriteData) }}">
    <input type="hidden" id="monthlyFavouriteStats" value="{{ json_encode($monthlyFavouriteStats) }}">
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    $(document).ready(function() {
        // Get data from hidden inputs
        const overallFavouriteData = JSON.parse($('#overallFavouriteData').val());
        const monthlyFavouriteStats = JSON.parse($('#monthlyFavouriteStats').val());

        // Initialize overall favourites chart
        const overallCtx = document.getElementById('overall-favourite-chart').getContext('2d');
        new Chart(overallCtx, {
            type: 'bar',
            data: {
                labels: overallFavouriteData.labels,
                datasets: [{
                    label: 'Số lượt yêu thích',
                    data: overallFavouriteData.data,
                    backgroundColor: 'rgba(75, 192, 192, 0.5)', // Teal color
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số lượt yêu thích'
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
                        text: 'Top 10 tài liệu được yêu thích nhất'
                    }
                }
            }
        });

        // Initialize monthly favourites charts
        Object.keys(monthlyFavouriteStats).forEach(month => {
            const canvasId = `favourite-chart-${month.replace(' ', '-')}`;
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthlyFavouriteStats[month].labels,
                    datasets: [{
                        label: 'Số lượt yêu thích',
                        data: monthlyFavouriteStats[month].data,
                        backgroundColor: 'rgba(255, 159, 64, 0.5)', // Orange color
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Số lượt yêu thích'
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
                            text: `Thống kê yêu thích - ${month}`
                        }
                    }
                }
            });
        });
    });
</script>
@endsection