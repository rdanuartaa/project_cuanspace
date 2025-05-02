@extends('layouts.main')
@section('content')
<div class="container">
    <div class="single-product-detail product-bundle product-aff">
        <div class="row">
            <div class="col-xs-12 col-sm-5 col-md-6">
                <div class="product-images">
                    <div class="main-img js-product-slider">
                        <a href="#" class="hover-images effect"><img src="img/bottle.jpg" alt="photo" class="img-reponsive"></a>
                        <a href="#" class="hover-images effect"><img src="img/bottle_3.jpg" alt="photo" class="img-reponsive"></a>
                        <a href="#" class="hover-images effect"><img src="img/bottle2.jpg" alt="photo" class="img-reponsive"></a>
                    </div>
                </div>
                <div class="multiple-img-list-ver2 js-click-product">
                    <div class="product-col">
                        <div class="img active">
                            <img src="img/bottle.jpg" alt="images" class="img-responsive">
                        </div>
                    </div>
                    <div class="product-col">
                        <div class="img">
                            <img src="img/bottle_3.jpg" alt="images" class="img-responsive">
                        </div>
                    </div>
                    <div class="product-col">
                        <div class="img">
                            <img src="img/bottle2.jpg" alt="images" class="img-responsive">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-7 col-md-6">
                <div class="single-product-info">
                    <div class="rating-star">
                        <div class="icon-rating"><span class="star star-5"></span>
                            <span class="star star-4"></span>
                            <span class="star star-3"></span>
                            <span class="star star-2"></span>
                            <span class="star star-1"></span>
                        </div>
                        <span class="review">(1 customer review)</span>
                    </div>
                    <h3 class="product-title"><a href="#">External-Affiliate Product</a></h3>
                    <div class="product-price">
                        <span class="old">$222.00</span>
                        <span>$330.00</span>
                    </div>
                    <p class="product-desc">Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. Mauris placerat eleifend leo.</p>
                    <div class="action v2">
                        <a href="#" class="link-ver1 add-cart">Purchase On Amazon</a>
                        <a href="#" class="link-ver1 wish"><i class="icon-heart f-15"></i></a>
                        <div class="clearfix"></div>
                    </div>
                    <div class="share-social">
                        <span>Share :</span>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                        <a href="#"><i class="fa fa-facebook"></i></a>
                        <a href="#"><i class="fa fa-google-plus"></i></a>
                        <a href="#"><i class="fa fa-pinterest-p"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--single-product-detail-->
    <div class="single-product-tab">
        <ul class="nav nav-tabs text-center">
            <li class="active"><a data-toggle="pill" href="#desc">Description</a></li>
            <li><a data-toggle="pill" href="#info">Additional Information</a></li>
            <li><a data-toggle="pill" href="#review">Reviews (1)</a></li>
        </ul>
        <div class="tab-content">
            <div id="desc" class="tab-pane fade in active">
                <p class="p-center">Constructed in cotton sweat fabric, this lovely piece, lacus eu mattis auctor, dolor lectus venenatis nulla, at tristique eros sem vel ante. Sed leo enim, iaculis ornare tristique non, vulputate sit amet ante. Mauris placerat eleifend leo.</p>
            </div>
            <div id="info" class="tab-pane fade in">
                <p class="p-center">Constructed in cotton sweat fabric, this lovely piece, lacus eu mattis auctor, dolor lectus venenatis nulla, at tristique eros sem vel ante. Sed leo enim, iaculis ornare tristique non, vulputate sit amet ante. Mauris placerat eleifend leo.</p>
            </div>
            <div id="review" class="tab-pane fade in ">
                <p class="p-center">Constructed in cotton sweat fabric, this lovely piece, lacus eu mattis auctor, dolor lectus venenatis nulla, at tristique eros sem vel ante. Sed leo enim, iaculis ornare tristique non, vulputate sit amet ante. Mauris placerat eleifend leo.</p>
            </div>
        </div>
    </div>
    <!--single-product-tab-->
</div>
<div class="information">
    <ul>
        <li class="info-center text-center"><span>SKU :</span>
            <a href="">004004</a>
        </li>
        <li class="info-center bd-rl text-center"><span>Categories :</span>
            <a href="">Hoodies</a>,
            <a href="">Accessories</a>
        </li>
        <li class="info-center text-center"><span>Tags :</span>
            <a href="">Designer</a>,
            <a href="">Tech</a>
        </li>
    </ul>
</div>
<div class="product-related">
    <div class="container container-42">
        <h3 class="title text-center">Related Products</h3>
        <div class="owl-carousel owl-theme js-owl-product">
            <div class="product-item">
                <div class="product-images">
                    <a href="#" class="hover-images effect"><img src="img/products/highheels.jpg" alt="products" class="img-reponsive"></a>
                    <a href="#" class="btn-add-wishlist ver2"><i class="icon-heart"></i></a>
                    <a href="#" class="btn-quickview">QUICK VIEW</a>
                </div>
                <div class="product-info-ver2">
                    <h3 class="product-title"><a href="#">Tia Slides in Brandy</a></h3>
                    <div class="product-after-switch">
                        <div class="product-price">$295.00</div>
                        <div class="product-after-button">
                            <a href="#" class="addcart">ADD TO CART</a>
                        </div>
                    </div>
                    <div class="rating-star">
                        <span class="star star-5"></span>
                        <span class="star star-4"></span>
                        <span class="star star-3"></span>
                        <span class="star star-2"></span>
                        <span class="star star-1"></span>
                    </div>
                    <div class="product-price">$292.00</div>
                </div>
            </div>
            <div class="product-item">
                <div class="product-images">
                    <a href="#" class="hover-images effect"><img src="img/products/hoodie.jpg" alt="photo" class="img-reponsive">
            <div class="ribbon-sale ver2"><span>sale</span></div>
            </a>
                    <a href="#" class="btn-add-wishlist ver2"><i class="icon-heart"></i></a>
                    <a href="#" class="btn-quickview">QUICK VIEW</a>
                </div>
                <div class="product-info-ver2">
                    <h3 class="product-title"><a href="#">Alabama Tee</a></h3>
                    <div class="product-after-switch">
                        <div class="product-price">$295.00</div>
                        <div class="product-after-button">
                            <a href="#" class="addcart">ADD TO CART</a>
                        </div>
                    </div>
                    <div class="rating-star">
                        <span class="star star-5"></span>
                        <span class="star star-4"></span>
                        <span class="star star-3"></span>
                        <span class="star star-2"></span>
                        <span class="star star-1"></span>
                    </div>
                    <div class="product-price">$292.00</div>
                </div>
            </div>
            <div class="product-item">
                <div class="product-images">
                    <a href="#" class="hover-images effect"><img src="img/products/bag_1.jpg" alt="photo" class="img-reponsive">
            <div class="ribbon-new ver2"><span>new</span></div>
            </a>
                    <a href="#" class="btn-add-wishlist ver2"><i class="icon-heart"></i></a>
                    <a href="#" class="btn-quickview">QUICK VIEW</a>
                </div>
                <div class="product-info-ver2">
                    <h3 class="product-title"><a href="#">Denali Blanket Scarf</a></h3>
                    <div class="product-after-switch">
                        <div class="product-price">$295.00</div>
                        <div class="product-after-button">
                            <a href="#" class="addcart">ADD TO CART</a>
                        </div>
                    </div>
                    <div class="rating-star">
                        <span class="star star-5"></span>
                        <span class="star star-4"></span>
                        <span class="star star-3"></span>
                        <span class="star star-2"></span>
                        <span class="star star-1"></span>
                    </div>
                    <div class="product-price">$292.00</div>
                </div>
            </div>
            <div class="product-item">
                <div class="product-images">
                    <a href="#" class="hover-images effect"><img src="img/products/long.jpg" alt="photo" class="img-reponsive">
            <div class="ribbon-sale ver2"><span>sale</span></div>
            </a>
                    <a href="#" class="btn-add-wishlist ver2"><i class="icon-heart"></i></a>
                    <a href="#" class="btn-quickview">QUICK VIEW</a>
                </div>
                <div class="product-info-ver2">
                    <h3 class="product-title"><a href="#">Flannel Button Under</a></h3>
                    <div class="product-after-switch">
                        <div class="product-price">$295.00</div>
                        <div class="product-after-button">
                            <a href="#" class="addcart">ADD TO CART</a>
                        </div>
                    </div>
                    <div class="rating-star">
                        <span class="star star-5"></span>
                        <span class="star star-4"></span>
                        <span class="star star-3"></span>
                        <span class="star star-2"></span>
                        <span class="star star-1"></span>
                    </div>
                    <div class="product-price">$292.00</div>
                </div>
            </div>
            <div class="product-item">
                <div class="product-images">
                    <a href="#" class="hover-images effect"><img src="img/products/sunglasses_1.jpg" alt="photo" class="img-reponsive"></a>
                    <a href="#" class="btn-add-wishlist ver2"><i class="icon-heart"></i></a>
                    <a href="#" class="btn-quickview">QUICK VIEW</a>
                </div>
                <div class="product-info-ver2">
                    <h3 class="product-title"><a href="#">Large Cube Planter</a></h3>
                    <div class="product-after-switch">
                        <div class="product-price">$295.00</div>
                        <div class="product-after-button">
                            <a href="#" class="addcart">ADD TO CART</a>
                        </div>
                    </div>
                    <div class="rating-star">
                        <span class="star star-5"></span>
                        <span class="star star-4"></span>
                        <span class="star star-3"></span>
                        <span class="star star-2"></span>
                        <span class="star star-1"></span>
                    </div>
                    <div class="product-price">$292.00</div>
                </div>
            </div>
            <div class="product-item">
                <div class="product-images">
                    <a href="#" class="hover-images effect"><img src="img/products/sunglasses_1.jpg" alt="photo" class="img-reponsive"></a>
                    <a href="#" class="btn-add-wishlist ver2"><i class="icon-heart"></i></a>
                    <a href="#" class="btn-quickview">QUICK VIEW</a>
                </div>
                <div class="product-info-ver2">
                    <h3 class="product-title"><a href="#">Large Cube Planter</a></h3>
                    <div class="product-after-switch">
                        <div class="product-price">$295.00</div>
                        <div class="product-after-button">
                            <a href="#" class="addcart">ADD TO CART</a>
                        </div>
                    </div>
                    <div class="rating-star">
                        <span class="star star-5"></span>
                        <span class="star star-4"></span>
                        <span class="star star-3"></span>
                        <span class="star star-2"></span>
                        <span class="star star-1"></span>
                    </div>
                    <div class="product-price">$292.00</div>
                </div>
            </div>
            <div class="product-item">
                <div class="product-images">
                    <a href="#" class="hover-images effect"><img src="img/products/sunglasses_1.jpg" alt="photo" class="img-reponsive"></a>
                    <a href="#" class="btn-add-wishlist ver2"><i class="icon-heart"></i></a>
                    <a href="#" class="btn-quickview">QUICK VIEW</a>
                </div>
                <div class="product-info-ver2">
                    <h3 class="product-title"><a href="#">Large Cube Planter</a></h3>
                    <div class="product-after-switch">
                        <div class="product-price">$295.00</div>
                        <div class="product-after-button">
                            <a href="#" class="addcart">ADD TO CART</a>
                        </div>
                    </div>
                    <div class="rating-star">
                        <span class="star star-5"></span>
                        <span class="star star-4"></span>
                        <span class="star star-3"></span>
                        <span class="star star-2"></span>
                        <span class="star star-1"></span>
                    </div>
                    <div class="product-price">$292.00</div>
                </div>
            </div>
        </div>
    </div>
</div
@endsection
