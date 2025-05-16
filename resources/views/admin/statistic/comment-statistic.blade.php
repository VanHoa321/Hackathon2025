@extends('layout/admin_layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="#" class="text-info">Thống kê</a></li>
                        <li class="breadcrumb-item text-secondary">Thống kê bình luận</li>
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
                            <h3 class="card-title">Danh sách tài liệu theo lượt bình luận</h3>
                        </div>
                        <div class="card-body">
                            <table id="example-table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ảnh bìa</th>
                                        <th>Tên tài liệu</th>
                                        <th>Danh mục</th>
                                        <th>Số lượt bình luận</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documents as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><img src="{{ $item->cover_image }}" alt="" style="width: 60px; height: 80px"></td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->category->name ?? 'N/A' }}</td>
                                            <td>{{ $item->comment_count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top 10 Documents by Comment Count Chart -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Top 10 tài liệu có lượt bình luận cao nhất</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="top-comment-documents-chart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weekly Comment Charts -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Biểu đồ thống kê bình luận theo tuần</h3>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="weeklyCommentTabs" role="tablist">
                                @foreach ($weeklyCommentStats as $week => $data)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                           id="{{ str_replace([' ', '/'], '-', $week) }}-tab" 
                                           data-toggle="tab" 
                                           href="#{{ str_replace([' ', '/'], '-', $week) }}" 
                                           role="tab">{{ $week }}</a>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="tab-content" id="weeklyCommentTabsContent">
                                @foreach ($weeklyCommentStats as $week => $data)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                         id="{{ str_replace([' ', '/'], '-', $week) }}" 
                                         role="tabpanel">
                                        <canvas id="comment-chart-{{ str_replace([' ', '/'], '-', $week) }}" height="100"></canvas>
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
    <input type="hidden" id="topCommentDocumentsData" value="{{ json_encode($topCommentDocumentsData) }}">
    <input type="hidden" id="weeklyCommentStats" value="{{ json_encode($weeklyCommentStats) }}">
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    $(document).ready(function() {

        // Get data from hidden inputs
        const topCommentDocumentsData = JSON.parse($('#topCommentDocumentsData').val());
        const weeklyCommentStats = JSON.parse($('#weeklyCommentStats').val());

        // Initialize top 10 documents by comment count chart
        const documentsCtx = document.getElementById('top-comment-documents-chart').getContext('2d');
        new Chart(documentsCtx, {
            type: 'bar',
            data: {
                labels: topCommentDocumentsData.labels,
                datasets: [{
                    label: 'Số lượt bình luận',
                    data: topCommentDocumentsData.data,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)', // Blue color
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số lượt bình luận'
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
                        text: 'Top 10 tài liệu có lượt bình luận cao nhất'
                    }
                }
            }
        });

        // Initialize weekly comment charts
        Object.keys(weeklyCommentStats).forEach(week => {
            const canvasId = `comment-chart-${week.replace(/[\s\/]/g, '-')}`;
            const ctx = document.getElementById(canvasId).getContext('2d');
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: weeklyCommentStats[week].labels,
                    datasets: [{
                        label: 'Số lượt bình luận',
                        data: weeklyCommentStats[week].data,
                        backgroundColor: 'rgba(255, 206, 86, 0.5)', // Yellow color
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Số lượt bình luận'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Ngày trong tuần'
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
                            text: `Thống kê bình luận - ${week}`
                        }
                    }
                }
            });
        });
    });
</script>
@endsection