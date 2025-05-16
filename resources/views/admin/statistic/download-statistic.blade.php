@extends('layout/admin_layout')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-left">
                        <li class="breadcrumb-item"><a href="#" class="text-info">Thống kê</a></li>
                        <li class="breadcrumb-item text-secondary">Thống kê lượt tải về</li>
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
                            <h3 class="card-title">Danh sách tài liệu theo lượt tải về</h3>
                        </div>
                        <div class="card-body">
                            <table id="example-table" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ảnh bìa</th>
                                        <th>Tên tài liệu</th>
                                        <th>Danh mục</th>
                                        <th>Lượt tải về</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documents as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><img src="{{ $item->cover_image }}" alt="" style="width: 60px; height: 80px"></td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->category->name ?? 'N/A' }}</td>
                                            <td>{{ $item->download_count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top 10 Documents by Download Count Chart -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Top 10 tài liệu có lượt tải về cao nhất</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="top-download-documents-chart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top 10 Categories by Total Download Count Chart -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Top 10 danh mục có tổng lượt tải về cao nhất</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="top-download-categories-chart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hidden inputs to pass data to JavaScript -->
    <input type="hidden" id="topDownloadDocumentsData" value="{{ json_encode($topDownloadDocumentsData) }}">
    <input type="hidden" id="topDownloadCategoriesData" value="{{ json_encode($topDownloadCategoriesData) }}">
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    $(document).ready(function() {

        // Get data from hidden inputs
        const topDownloadDocumentsData = JSON.parse($('#topDownloadDocumentsData').val());
        const topDownloadCategoriesData = JSON.parse($('#topDownloadCategoriesData').val());

        // Initialize top 10 documents by download count chart
        const documentsCtx = document.getElementById('top-download-documents-chart').getContext('2d');
        new Chart(documentsCtx, {
            type: 'bar',
            data: {
                labels: topDownloadDocumentsData.labels,
                datasets: [{
                    label: 'Lượt tải về',
                    data: topDownloadDocumentsData.data,
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
                            text: 'Lượt tải về'
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
                        text: 'Top 10 tài liệu có lượt tải về cao nhất'
                    }
                }
            }
        });

        // Initialize top 10 categories by total download count chart
        const categoriesCtx = document.getElementById('top-download-categories-chart').getContext('2d');
        new Chart(categoriesCtx, {
            type: 'bar',
            data: {
                labels: topDownloadCategoriesData.labels,
                datasets: [{
                    label: 'Tổng lượt tải về',
                    data: topDownloadCategoriesData.data,
                    backgroundColor: 'rgba(153, 102, 255, 0.5)', // Purple color
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Tổng lượt tải về'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tên danh mục'
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
                        text: 'Top 10 danh mục có tổng lượt tải về cao nhất'
                    }
                }
            }
        });
    });
</script>
@endsection