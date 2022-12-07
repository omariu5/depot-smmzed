<!-- ======= Header ======= -->
<header id="header" class="fixed-top ">
    <div class="container d-flex align-items-center justify-content-between">
        <h1 class="logo">
            @if ($settings['logo'])
                <a href="/">
                    <img src="{{$settings['logo']}}" alt="{{$settings['site_title']}}">
                </a>

            @else
                <a href="/">{{$settings['site_title']}}</a>
            @endif
        </h1>
        <!-- Uncomment below if you prefer to use an image logo -->
        <nav id="navbar" class="navbar">
            <ul>
                @foreach($navlinks as $nv)
                    @if(count($nv->children))
                        <li class="dropdown"><a href="{{$nv->path}}"><span>{{$nv->name}}</span> <i class="bi bi-chevron-down"></i></a>
                            <ul>
                                @foreach($nv->children as $subnav)
                                    <li><a href="{{$nv->path}}">{{$nv->name}}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        @php
                            $path = $nv->path;
                            $icon = $nv->icon;
                            if (str_contains($icon,'scroll-to')) {
                                if (request()->path() != '/') {
                                    $path = $settings['site_url'].$path;
                                }
                                $icon = str_replace($icon,'',$icon);
                            }

                        @endphp
                    <li><a class="nav-link {{$nv->path =='/login' ? 'getstarted':''}} {{request()->path() == $nv->path ? 'active':''}} {{str_contains($nv->icon,'scroll-to') ? 'scrollto':''}}" href="{{$path}}">
                            @if ($icon) <i class="me-2 {{$icon}}"></i>  @endif <span class="d-inline-block">{{$nv->name}}</span></a></li>
                    @endif
                @endforeach
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav><!-- .navbar -->


    </div>
</header><!-- End Header -->
{{--@if ($page->slug == '/')--}}
{{--<section id="hero" class="d-flex align-items-center">--}}

{{--    <div class="container-fluid" data-aos="fade-up">--}}
{{--        <div class="row justify-content-center">--}}
{{--            <div class="col-xl-5 col-lg-6 pt-3 pt-lg-0 order-2 order-lg-1 d-flex flex-column justify-content-center">--}}
{{--                <h1>Bettter Digital Experience With Depot</h1>--}}
{{--                <h2>We are team of talented professionals. We create creative technical solutions</h2>--}}
{{--                <div><a href="#about" class="btn-get-started scrollto">Get Started</a></div>--}}
{{--            </div>--}}
{{--            <div class="col-xl-4 col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="150">--}}
{{--                <img src="/assets/themes/default/img/hero-img.png" class="img-fluid animated" alt="">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</section>--}}
{{--@else--}}
    <div class="container-fluid padding" style="height:150px "></div>
{{--@endif--}}