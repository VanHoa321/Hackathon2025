@extends('layout/web_layout')

@section('content')
<div class="site-breadcrumb">
    <div class="site-breadcrumb-bg" style="background: url(/web-assets/img/breadcrumb/01.jpg)"></div>
    <div class="container">
        <div class="site-breadcrumb-wrap">
            <h4 class="breadcrumb-title">Những Tác giả nổi tiếng bạn có thể biết</h4>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('frontend.home.index') }}"><i class="far fa-home"></i> Trang chủ</a></li>
                <li class="active">Tác giả</li>
            </ul>
        </div>
    </div>
</div>

<div class="page-content">
    <section class="themeht-authors">
        <div class="team-area pt-100 pb-60">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline">Tác giả</span>
                            <h2 class="site-title">Gặp gỡ những tác giả <span> nổi tiếng</span></h2>
                            <div class="heading-divider"></div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    @foreach ($authors as $author)
                        <div class="col-md-6 col-lg-3">
                            <div class="team-item wow fadeInUp" data-wow-delay=".25s">
                                <div class="team-img">
                                    <img src="{{ asset($author->avatar) }}" alt="{{ $author->name }}" style="width: 200px; height: 200px; object-fit: cover;">
                                </div>
                                <div class="team-content">
                                    <div class="team-bio">
                                        <h5><a href="{{ route('frontend.author.details', $author->id) }}">{{ $author->name }}</a></h5>
                                        <span>{{ $author->birth_date }}</span>
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
                    @endforeach
                </div>

                <div class="row mt-5">
                    <div class="col-12">
                        <div class="pagination-area mb-lg-0">
                            <div aria-label="Page navigation example">
                                <ul class="pagination justify-content-start">
                                    @if ($authors->onFirstPage())
                                        <li class="page-item disabled">
                                            <a class="page-link ms-0" href="#" aria-label="Previous">
                                                <span aria-hidden="true"><i class="far fa-arrow-left"></i></span>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link ms-0" href="{{ $authors->previousPageUrl() }}" aria-label="Previous">
                                                <span aria-hidden="true"><i class="far fa-arrow-left"></i></span>
                                            </a>
                                        </li>
                                    @endif

                                    @for ($i = 1; $i <= $authors->lastPage(); $i++)
                                        <li class="page-item {{ $authors->currentPage() == $i ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $authors->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    @if ($authors->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $authors->nextPageUrl() }}" aria-label="Next">
                                                <span aria-hidden="true"><i class="far fa-arrow-right"></i></span>
                                            </a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" aria-label="Next">
                                                <span aria-hidden="true"><i class="far fa-arrow-right"></i></span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
