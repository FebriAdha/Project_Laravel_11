@extends('layouts.user.main')
@section('content')
<!-- Start Banner Area -->
<section class="banner-area">
    <div class="container">
        <div class="row fullscreen align-items-center justify-content-start">
            <div class="col-lg-12">
                <div class="">
                    <!-- Single Slide -->
                    <div class="row">
                        <div class="col-lg-5 col-md-6">
                            <div class="banner-content">
                                <h1>Nike New <br>Collection!</h1>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.</p>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="banner-img">
                                <img class="img-fluid" src="{{ asset('assets/templates/user/img/banner/banner-img.png') }}" alt="Nike New Collection">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Banner Area -->

<!-- Start Product Area -->
<section class="section_gap">
    <div class="container">
        <!-- Section Title -->
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="section-title">
                    <h1>Latest Products</h1>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                </div>
            </div>
        </div>
        
        <!-- Product Grid -->
        <div class="row">
            <!-- Single Product -->
            <div class="col-lg-3 col-md-6">
                <div class="single-product">
                    <img class="img-fluid" src="{{ asset('assets/templates/user/img/product/p1.jpg') }}" alt="Adidas New Hammer Sole">
                    <div class="product-details">
                        <h6>Addidas New Hammer Sole for Sports Person</h6>
                        <div class="price">
                            <h6>$150.00</h6>
                            <h6 class="l-through">$210.00</h6>
                        </div>
                        <div class="prd-bottom">
                            <a href="#" class="social-info">
                                <span class="ti-bag"></span>
                                <p class="hover-text">Add to bag</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add more product cards here -->
        </div>
    </div>
</section>
<!-- End Product Area -->
@endsection
