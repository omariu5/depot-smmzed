<footer id="footer">
    <div class="footer-top border-top border-white">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 footer-contact">
                    <h3>@if ($settings['logo'])
                            <a href="/">
                                <img src="{{$settings['logo']}}" class="w-100" alt="{{$settings['site_title']}}">
                            </a>

                        @else
                            <a class="text-white" href="/">{{$settings['site_title']}}</a>
                        @endif</h3>
                    <p>
                        @if ($settings['sitephone'])
                            <strong>@lang('Phone')</strong> {{$settings['sitephone']}}<br>
                        @endif
                        @if ($settings['siteemail'])
                            <strong>@lang('E-mail')</strong> {{$settings['siteemail']}}<br>
                        @endif
                    </p>
                </div>

                <div class="col-lg-2 col-md-6 footer-links">
                    <h4></h4>
                    <ul>
                        <li><i class="bx bx-chevron-right"></i> <a href="/">Home</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="/dashboard">Dashboard</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="/terms-and-conditions">Terms & Conditions</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="/privacy-policy">Privacy policy</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 footer-links">
                    <h4></h4>
                    <ul>
                        <li><i class="bx bx-chevron-right"></i> <a href="/services">Services</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="/api-docs">API Documentation</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="/login">Login</a></li>
                        <li><i class="bx bx-chevron-right"></i> <a href="/register">Sign up</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6 footer-newsletter">
                    <h4>About us</h4>
                    <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna</p>
                    @if (!empty($settings['site_email']))
                        <p><i class="fa fa-envelope"></i> {{$settings['site_email']}} </p>
                    @endif
                    @if (!empty($settings['site_phone']))
                        <p><i class="fa fa-phone-alt"></i> {{$settings['site_phone']}} </p>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <div class="container">

        <div class="copyright-wrap d-md-flex py-4">
            <div class="me-md-auto text-center text-md-start">
                <div class="copyright">
                    &copy; Copyright <strong><span>{{$settings['site_title']}}</span></strong>. All Rights Reserved
                </div>
            </div>
            <div class="social-links text-center text-md-right pt-3 pt-md-0">
                @if ($settings['twitter'])
                <a target="_blank" href="{{$settings['twitter']}}" class="twitter"><i class="bx bxl-twitter"></i></a>
                @endif
                @if ($settings['facebook'])
                <a target="_blank" href="{{$settings['facebook']}}" href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                @endif
                @if ($settings['instagram'])
                <a target="_blank" href="{{$settings['instagram']}}" class="instagram"><i class="bx bxl-instagram"></i></a>
                @endif
                @if ($settings['youtube'])
                <a target="_blank" href="{{$settings['youtube']}}" class="youtube"><i class="bx bxl-youtube"></i></a>
                @endif
                @if ($settings['linkedin'])
                <a target="_blank" href="{{$settings['linkedin']}}" class="linkedin"><i class="bx bxl-linkedin"></i></a>
                @endif
            </div>
        </div>

    </div>
</footer><!-- End Footer -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<div id="preloader"></div>

<!-- Vendor JS Files -->
<script src="/dist/js/func.js"></script>
<script src="/assets/themes/default/vendor/purecounter/purecounter.js"></script>
<script src="/assets/themes/default/vendor/aos/aos.js"></script>
<script src="/assets/themes/default/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/themes/default/vendor/glightbox/js/glightbox.min.js"></script>
<script src="/assets/themes/default/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="/assets/themes/default/vendor/swiper/swiper-bundle.min.js"></script>
<script src="/assets/themes/default/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="/assets/themes/default/js/main.js"></script>
