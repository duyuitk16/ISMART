@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="clearfix detail-blog-page">
        <div class="wp-inner">
            <div class="secion" id="breadcrumb-wp">
                <div class="secion-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="{{ url('trang-chu') }}" title="">Trang chủ</a>
                        </li>
                        <li>
                            <a href="{{ url('bai-viet') }}" title="">Bài viết</a>
                        </li>
                        <li>
                            <a href="{{ url("bai-viet/$this_post->slug") }}" title="">{{ $this_post->title }}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="main-content fl-right">
                <div class="section" id="detail-blog-wp">
                    <div class="section-head clearfix">
                        <h3 class="section-title">{{ $this_post->title }}
                        </h3>
                    </div>
                    <div class="section-detail">
                        <span class="create-date">{{ $this_post->created_at->format('H:i:s d-m-Y') }} -
                            {{ $this_post->PostCat->name }}</span>
                        <div class="detail">
                            {!! $this_post->content !!}
                        </div>
                    </div>
                </div>
                <div class="section" id="social-wp">
                    <div class="section-detail">
                        <div class="fb-like" data-href="" data-layout="button_count" data-action="like" data-size="small"
                            data-show-faces="true" data-share="true"></div>
                        <div class="g-plusone-wp">
                            <div class="g-plusone" data-size="medium"></div>
                        </div>
                        <div class="fb-comments" id="fb-comment" data-href="" data-numposts="5"></div>
                    </div>
                </div>
            </div>
            <div class="sidebar fl-left">
                @include('layouts.client_selling', [
                    'selling_products' => $selling_products,
                ])
                @include('layouts.client_banner')
            </div>
        </div>
    </div>
    @include('sweetalert::alert')
@endsection
