@extends('layout/admin_layout')
@section('content')
    <style>
        .content-wrapper {
            min-height: 100%;
            height: auto;
        }
    </style>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('home.index') }}" class="text-info">Trang chủ</a></li>
                            <li class="breadcrumb-item active text-secondary">Thống kê</li>
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
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner text-white">
                                <h3>{{$customer_count}}</h3>
                                <p>Khách hàng quản lý</p>
                            </div>
                            <div class="icon">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <a href="{{route('customer.index')}}" class="small-box-footer"><span style="color:white">Chi tiết <i class="fas fa-arrow-circle-right"></i></span></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{$document_count}}</h3>
                                <p>Tài liệu hệ thống</p>
                            </div>
                            <div class="icon">
                                <i class="fa-solid fa-book"></i>
                            </div>
                            <a href="{{route('document.index')}}" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{$author_count}}</h3>
                                <p>Tác giả quản lý</p>
                            </div>
                            <div class="icon">
                                <i class="fa-solid fa-user-tie"></i>
                            </div>
                            <a href="{{route('author.index')}}" class="small-box-footer">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 class="text-white">{{$publisher_count}}</h3>
                                <p class="text-white">Nhà xuất bản liên kết</p>
                            </div>
                            <div class="icon">
                                <i class="fa-solid fa-house-user"></i>
                            </div>
                            <a href="{{route('publisher.index')}}" class="small-box-footer"><span class="text-white">Chi tiết <i class="fas fa-arrow-circle-right"></i></span></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Lọc thống kê</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="mb-1">
                                                <button type="button" id="filterDay" class="btn btn-info">Hôm nay</button>
                                            </div>
                                            <div class="mb-1">
                                                <button type="button" id="filterWeek" class="btn btn-info">Tuần này</button>
                                            </div>
                                            <div class="mb-1">
                                                <button type="button" id="filterMonth" class="btn btn-info">Tháng này</button>
                                            </div>
                                            <div class="mb-1">
                                                <button type="button" id="filterYear" class="btn btn-info">Năm nay</button>
                                            </div>
                                            <div class="mb-1">
                                                <button type="button" id="all" class="btn btn-info">Tất cả</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="row">
                                            <div class="col-md-11">
                                                <div class="d-flex" id="customRange">
                                                    <div class="col-md-6 mb-3 me-3">
                                                        <div class="d-flex align-items-center">
                                                            <label for="startDate" class="form-label mb-0 me-2 w-auto" style="min-width: 70px;">Từ ngày</label>
                                                            <input type="date" id="startDate" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <label for="endDate" class="form-label mb-0 me-2 w-auto" style="min-width: 70px;">Đến ngày</label>
                                                            <input type="date" id="endDate" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" id="filterButton" class="btn btn-info">
                                                    Xem
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Thống kê hóa đơn</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <table id="example-table" class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Khách hàng</th>
                                                            <th>Loại giao dịch</th>
                                                            <th>Tài liệu</th>
                                                            <th>Số tiền</th>
                                                            <th>Nội dung</th>
                                                            <th>Ngày giao dịch</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>              
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Doanh thu theo tháng</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                <div class="form-group">
                                    <select id="yearSe" class="form-control" style="width: 100px;">
                                    </select>
                                </div>
                                    <canvas id="areaChart" style="min-height: 250px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">                 
                    <div class="col-md-12">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Top khách hàng</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="barChartCustomer" style="min-height: 350px; height: 350px; max-height: 350px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    <script src="{{asset("assets/plugins/chart.js/Chart.min.js")}}"></script>
    <script>
        $(document).ready(function() {
            loadData('all');

            var yearSelect = $('#yearSe');
            function populateYearOptions() {
                const currentYear = new Date().getFullYear();
                for (let year = 2021; year <= 2050; year++) {
                    yearSelect.append(`<option value="${year}">${year}</option>`);
                }
                yearSelect.val(currentYear);
            }
            populateYearOptions();
            yearSelect.change(function () {
                var year = $(this).val();
                loadData('all', null, null, year);
            });

            var areaChartRevenue;
            var barChartCustomer;

            $('#filterDay, #filterWeek, #filterMonth, #filterYear, #all').click(function() {
                var timeType = $(this).attr('id');
                loadData(timeType);
            });

            $('#filterButton').click(function() {
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                if (!startDate || !endDate) {
                    toastr.error('Vui lòng chọn thời gian thống kê');
                    return;
                }

                var start = new Date(startDate);
                var end = new Date(endDate);
                if (start > end) {
                    toastr.error('Ngày bắt đầu không thể lớn hơn ngày kết thúc');
                    return;
                }
                loadData('custom', startDate, endDate);
            });

            function loadData(timeType, startDate = null, endDate = null, year = new Date().getFullYear()) {
                $.ajax({
                    url: '/admin/home/index',
                    method: 'GET',
                    data: {
                        timeType: timeType,
                        startDate: startDate,
                        endDate: endDate,
                        year: year
                    },
                    success: function(response) {
                        var transaction = response.transaction;
                        var monthlyRevenue = response.monthlyRevenue;
                        var topCustomer = response.topCustomer;

                        //Xử lý giao dịch
                        renderTransactionData(transaction);

                        var monthlyRevenueLabels = [];
                        var monthlyRevenueDatas = [];
                        for (var i = 1; i <= 12; i++) {
                            monthlyRevenueLabels.push("Tháng " + i);
                            var found = monthlyRevenue.find(item => item.month == i);
                            monthlyRevenueDatas.push(found ? parseFloat(found.total) : 0);
                        }
                        renderAreaChart(monthlyRevenueLabels, monthlyRevenueDatas);

                        var topCustomerLabels = [];
                        var topCustomerDatas = [];
                        topCustomer.forEach(function(item) {
                            topCustomerLabels.push(item.name + " - " + item.phone);
                            topCustomerDatas.push(item.total_amount);
                        });
                        renderBarChartCustomer(topCustomerLabels, topCustomerDatas);
                    },
                    error: function(xhr) {
                        console.error("Lỗi khi gọi AJAX:", xhr.responseText);
                    }
                });
            }

            function renderTransactionData(data) {
                var table = $('#example-table').DataTable();
                table.clear();
                if (data.length === 0) {
                    table.draw(false);
                    return;
                }
                data.forEach(function(item, index) {
                    let transactionType = '';
                    switch (item.type) {
                        case 1:
                            transactionType = 'Nạp tiền tài khoản';
                            badgeClass = 'badge bg-success';
                            break;
                        case 2:
                            transactionType = 'Mua tài liệu';
                            badgeClass = 'badge bg-primary';
                            break;
                        case 3:
                            transactionType = 'Đăng tải tài liệu';
                            badgeClass = 'badge bg-warning';
                            break;
                        case 4:
                            transactionType = 'Tải về tài liệu';
                            badgeClass = 'badge bg-info';
                            break;
                        default:
                            transactionType = 'Không xác định';
                            badgeClass = 'badge bg-secondary';
                    }
                    let transactionTypeHtml = `<span class="${badgeClass}">${transactionType}</span>`;
                    let documentTitle = item.document_title ? item.document_title : 'Không có';
                    table.row.add([
                        index + 1,
                        item.name + "," + item.phone,
                        transactionTypeHtml,
                        documentTitle,
                        item.amount.toLocaleString() + ' điểm',
                        item.note,
                        moment(item.transaction_date).format('DD-MM-YYYY HH:mm:ss'),
                    ]).draw(false);
                });
                table.columns(0).header().to$().css("width", "5%");
                table.columns(1).header().to$().css("width", "15%");
                table.columns(2).header().to$().css("width", "15%");
                table.columns(3).header().to$().css("width", "15%");
                table.columns(4).header().to$().css("width", "20%");
                table.columns(4).header().to$().css("width", "15%");
            }

            function renderAreaChart(labels, data) {
                var areaChartCanvas = $('#areaChart').get(0).getContext('2d');
                var areaChartData = {
                    labels: labels,
                    datasets: [{
                        label: 'Doanh thu theo tháng',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: false,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        data: data
                    }]
                };

                var areaChartOptions = {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: true,
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                display: true,
                            },
                            ticks: {
                                beginAtZero: true,
                                callback: function(value) {
                                    if (Number.isInteger(value)) {
                                        return value;
                                    }
                                }
                            }
                        }]
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function (tooltipItem, data) {
                                var label = data.datasets[tooltipItem.datasetIndex].label || '';
                                var value = tooltipItem.yLabel;
                                if (label) {
                                    label += ': ';
                                }
                                label += value.toLocaleString('vi-VN') + ' VNĐ';
                                return label;
                            }
                        }
                    }
                }
                if (areaChartRevenue) {
                    areaChartRevenue.destroy();
                }
                areaChartRevenue = new Chart(areaChartCanvas, {
                    type: 'line',
                    data: areaChartData,
                    options: areaChartOptions
                });
            }

            function renderBarChartCustomer(labels, data) {
                var barChartCanvas = $('#barChartCustomer').get(0).getContext('2d');
                var barChartData = {
                    labels: labels,
                    datasets: [{
                        label: 'Tổng tiền nạp',
                        backgroundColor: 'rgba(236, 66, 80, 0.9)',
                        borderColor: 'rgba(236, 66, 80, 0.9)',
                        borderWidth: 1,
                        data: data,
                    }]
                };

                if(barChartCustomer){
                    barChartCustomer.destroy();
                }

                var barChartOptions = {
                    maintainAspectRatio: false,
                    responsive: true,
                    legend: {
                        display: true
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: true,
                            },
                            ticks: {
                                beginAtZero: true
                            },
                            barPercentage: 0.15,
                        }],
                        yAxes: [{
                            gridLines: {
                                display: true,
                            },
                            ticks: {
                                beginAtZero: true,
                                callback: function(value) {
                                    if (Number.isInteger(value)) {
                                        return value;
                                    }
                                }
                            }
                        }]
                    }
                };

                barChartCustomer = new Chart(barChartCanvas, {
                    type: 'bar',
                    data: barChartData,
                    options: barChartOptions
                });
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $("#myAlert").fadeOut(500);
            }, 3500);
        })
    </script>
@endsection
