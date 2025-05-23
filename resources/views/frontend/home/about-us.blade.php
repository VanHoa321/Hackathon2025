@extends('layout/web_layout')
@section('content')
    <div class="site-breadcrumb">
        <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
        <div class="container">
            <div class="site-breadcrumb-wrap">
                <h4 class="breadcrumb-title">Giới thiệu tính năng website</h4>
                <ul class="breadcrumb-menu">
                    <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                    <li class="active">Giới thiệu</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="help-area py-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="help-search">
                        <h3>Các tính năng của website</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="help-item">
                        <div class="help-icon">
                            <i class="fal fa-search"></i>
                        </div>
                        <div class="help-content">
                            <h4>Tìm kiếm tài liệu</h4>
                            <p>Dễ dàng tìm kiếm tài liệu theo tên, từ khóa, môn học hoặc lĩnh vực bạn quan tâm.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="help-item">
                        <div class="help-icon">
                            <i class="fal fa-upload"></i>
                        </div>
                        <div class="help-content">
                            <h4>Đăng tải tài liệu</h4>
                            <p>Chia sẻ tài liệu của bạn với cộng đồng, hỗ trợ người khác học tập và nghiên cứu.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="help-item">
                        <div class="help-icon">
                            <i class="fal fa-download"></i>
                        </div>
                        <div class="help-content">
                            <h4>Tải về tài liệu</h4>
                            <p>Tải tài liệu về thiết bị cá nhân để đọc offline bất cứ khi nào bạn cần.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="help-item">
                        <div class="help-icon">
                            <i class="fal fa-eye"></i>
                        </div>
                        <div class="help-content">
                            <h4>Xem trước nội dung</h4>
                            <p>Xem nhanh trước nội dung tài liệu để quyết định có phù hợp với nhu cầu của bạn hay không.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="help-item">
                        <div class="help-icon">
                            <i class="fal fa-comments-question"></i>
                        </div>
                        <div class="help-content">
                            <h4>Hỏi đáp AI</h4>
                            <p>Đặt câu hỏi liên quan đến nội dung tài liệu và nhận câu trả lời tức thì từ AI hỗ trợ.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="help-item">
                        <div class="help-icon">
                            <i class="fal fa-scroll"></i>
                        </div>
                        <div class="help-content">
                            <h4>Tóm tắt văn bản bằng AI</h4>
                            <p>AI giúp bạn tóm tắt nhanh nội dung tài liệu dài, tiết kiệm thời gian đọc hiểu.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="help-item">
                        <div class="help-icon">
                            <i class="fal fa-headphones-alt"></i>
                        </div>
                        <div class="help-content">
                            <h4>Nghe tài liệu</h4>
                            <p>Chuyển đổi văn bản thành giọng nói để bạn có thể nghe tài liệu mọi lúc, mọi nơi.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="help-item">
                        <div class="help-icon">
                            <i class="fal fa-user-cog"></i>
                        </div>
                        <div class="help-content">
                            <h4>Quản lý thông tin cá nhân</h4>
                            <p>Chỉnh sửa hồ sơ, theo dõi tài liệu đã đăng, đã tải và tương tác với hệ thống.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="help-item">
                        <div class="help-icon">
                            <i class="fal fa-star-half-alt"></i>
                        </div>
                        <div class="help-content">
                            <h4>Đánh giá & bình luận</h4>
                            <p>Đọc nhận xét từ người dùng khác và để lại đánh giá của bạn cho từng tài liệu.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-lg-6 mx-auto">
                    <div class="help-search">
                        <h3>Thành viên nhóm</h3>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6 col-lg-3">
                    <div class="team-item wow fadeInUp" data-wow-delay=".25s" style="display: flex; flex-direction: column; align-items: center;">
                        <div class="team-img" style="width:250px; height:250px">
                            <img src="/web-assets/img/team/03.jpg" alt="thumb" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="team-content">
                            <div class="team-bio">
                                <h5><a href="#">Võ Văn Hòa</a></h5>
                                <span>215748010310021</span>
                            </div>
                        </div>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-x-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="team-item wow fadeInUp" data-wow-delay=".50s" style="display: flex; flex-direction: column; align-items: center;">
                        <div class="team-img" style="width:250px; height:250px">
                            <img src="/web-assets/img/team/03.jpg" alt="thumb" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="team-content">
                            <div class="team-bio">
                                <h5><a href="#">Bùi Mạnh Phúc</a></h5>
                                <span>215748010310011</span>
                            </div>
                        </div>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-x-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="team-item wow fadeInUp" data-wow-delay=".75s" style="display: flex; flex-direction: column; align-items: center;">
                        <div class="team-img" style="width:250px; height:250px">
                            <img src="/storage/files/1/Avatar/DSC_2061.jpg" alt="thumb" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="team-content">
                            <div class="team-bio">
                                <h5><a href="#">Đặng Hồ Anh Quý</a></h5>
                                <span>215748010310009</span>
                            </div>
                        </div>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-x-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="team-item wow fadeInUp" data-wow-delay="1s" style="display: flex; flex-direction: column; align-items: center;">
                        <div class="team-img" style="width:250px; height:250px">
                            <img src="/storage/files/1/Avatar/dinhanh.jpg" alt="thumb" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="team-content">
                            <div class="team-bio">
                                <h5><a href="#">Lê Đình Anh</a></h5>
                                <span>215748010310013</span>
                            </div>
                        </div>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-x-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('layout.partial.chatbot')
@endsection