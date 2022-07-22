@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="clearfix blog-page">
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
                    </ul>
                </div>
            </div>
            <div class="main-content fl-right">
                <div class="section" id="list-blog-wp">
                    <div class="section-head clearfix">
                        <h3 class="section-title">Bài viết</h3>
                    </div>
                    <div class="section-detail">
                        <ul class="list-item">
                            @foreach ($posts as $post)
                                <li class="clearfix">
                                    <a href="{{ url("bai-viet/{$post->slug}.html") }}" title="" class="thumb fl-left">
                                        <img src="{{ asset("uploads/post/$post->thumbnail") }}" alt=""
                                            style="height:190px; width:263.25px">
                                    </a>
                                    <div class="info fl-right">
                                        <a href="{{ url("bai-viet/{$post->slug}.html") }}" title=""
                                            class="title">{{ $post->title }}</a>
                                        <span class="create-date">{{ $post->created_at->format('H:i:s d-m-Y') }} -
                                            {{ $post->PostCat->name }}</span>
                                        <p class="desc">{{ $post->description }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="mt-3"> {{ $posts->links() }}</div>
            </div>
            <div class="sidebar fl-left">
                @include('layouts.client_selling')
                @include('layouts.client_banner')
            </div>
        </div>

    </div>
    @include('sweetalert::alert')
@endsection
