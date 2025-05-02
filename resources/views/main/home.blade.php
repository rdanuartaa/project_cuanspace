@extends('layouts.main')
@section('content')
<div class="page-heading">
    <div class="banner-heading">
        <img src="img/headerbg_2.jpg" alt="" class="img-reponsive">
        <div class="heading-content text-center">
            <div class="container container-42">
                <h1 class="page-title white">Shop</h1>
                <ul class="breadcrumb white">
                    <li><a href="">home</a></li>
                    <li><a href="">Shop All Products</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="nav nav-tabs nav-justified nav-filter white">
        <ul class="owl-carousel owl-theme js-owl-category">
            <li class="active"><a data-toggle="pill" href="#all">All</a></li>
            <li><a data-toggle="pill" href="#desain">Desain & Grafis</a></li>
            <li><a data-toggle="pill" href="#template">Template UI/UX</a></li>
            <li><a data-toggle="pill" href="#musik">Audio & Musik</a></li>
            <li><a data-toggle="pill" href="#animasi">Animasi</a></li>
            <li><a data-toggle="pill" href="#dev">Developer Assets</a></li>
            <li><a data-toggle="pill" href="#fotografi">Fotografi</a></li>
            <li><a data-toggle="pill" href="#ebook">E-Book</a></li>
        </ul>
    </div>
</div>
<div class="wrap-filter">
    <div class="wrap-filter-box wrap-filter-number">
        <ul class="pagination">
            <li class="active"><a href="">4</a></li>
            <li><a href="">5</a></li>
            <li><a href="">6</a></li>
        </ul>
        <span class="total-count">Showing 1-12 of 30 products</span>
    </div>
    <div class="wrap-filter-box text-center view-mode">
        <a class="col" href="#" onClick="return false;"><span class="icon-grid-img"></span></a>
    </div>
    <div class="wrap-filter-box text-center js-filter"><a href="#" class="filter-title"><i class="icon-equalizer"></i></a>
        <form action="#" method="get" class="form-filter-product js-filter-open">
            <span class="close-left js-close"><i class="icon-close f-20"></i></span>
            <div class="product-filter-wrapper">
                <div class="product-filter-inner text-left">
                    <div class="product-filter">
                        <div class="form-group">
                            <span class="title-filter">Category</span>
                            <button class="dropdown-toggle form-control" type="button" data-toggle="dropdown">Select a category
                            </button>
                            <ul class="dropdown-menu">
                                <li>Select a category</li>
                                <li>Backpacks</li>
                                <li>Decoration</li>
                                <li>Essentials</li>
                                <li>Interior</li>
                            </ul>
                        </div>
                    </div>
                    <div class="product-filter">
                        <div class="form-group">
                            <span class="title-filter">Price</span>
                            <div class="filter-content">
                                <div class="price-range-holder">
                                    <input type="text" class="price-slider" value="">
                                </div>
                                <span class="min-max">
                                Price: Rp 10k — 1000k
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="product-filter-button-group clearfix">
                    <div class="product-filter-button">
                        <a href="" class="btn-submit">Fillter </a>
                    </div>
                    <div class="product-filter-button">
                        <a href="" class="btn-submit">Clear </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="wrap-filter-box text-center view-mode">
        <a class="list" href="#" onClick="return false;"><span class="icon-list-img"></span></a>
    </div>
    <div class="wrap-filter-box wrap-filter-sorting">
        <button class="dropdown-toggle" type="button" data-toggle="dropdown" id="menu2">Sort by newness
        </button>
        <ul class="dropdown-menu" role="menu" aria-labelledby="menu2">
            <li><a href="#" title="">Sort by newness</a></li>
            <li><a href="#" title="">Best Selling</a></li>
            <li><a href="#" title="">Best Selling</a></li>
        </ul>
    </div>
    <div class="clearfix"></div>
</div>
<div class="product-standard product-grid">
    <div class="container container-42">
        <div class="tab-content">
            <div id="all" class="tab-pane fade in active">
                <div class="row">
                    <div class="col-md-15 col-sm-3 col-xs-6 product-item">
                        <div class="product-images">
                            <a href="#" class="hover-images effect"><img src="img/products/shortshirt.jpg" alt="photo" class="img-reponsive"></a>
                            <a href="#" class="btn-add-wishlist ver2"><i class="icon-heart"></i></a>
                            <a href="#" class="btn-quickview">QUICK VIEW</a>
                        </div>
                        <div class="product-info-ver2">
                            <h3 class="product-title"><a href="#">The Turtleneck</a></h3>
                            <div class="product-after-switch">
                                <div class="product-price">$295.00</div>
                                <div class="product-after-button">
                                    <a href="#" class="addcart">Checkout Now</a>
                                </div>
                            </div>
                            <div class="rating-star">
                                <span class="star star-5"></span>
                                <span class="star star-4"></span>
                                <span class="star star-3"></span>
                                <span class="star star-2"></span>
                                <span class="star star-1"></span>
                            </div>
                            <p class="product-desc">Compellingly brand enterprise value after functional manufactured products. Synergistically morph process-centric intellectual capital rather than extensible catalysts for change. Credibly aggregate progressive initiatives and long-term.</p>
                            <div class="product-price">$292.00</div>
                            <div class="button-group">
                                <a href="#" class="button add-to-cart">Checkout Now</a>
                                <a href="#" class="button add-to-wishlist">Add to wishlist</a>
                                <a href="#" class="button add-view">Quick view</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-15 col-sm-3 col-xs-6 product-item">
                        <div class="product-images">
                            <a href="#" class="hover-images effect"><img src="img/products/hat_2.jpg" alt="photo" class="img-reponsive">
                            </a>
                            <div class="ribbon-sale ver2"><span>sale</span></div>
                            <a href="#" class="btn-add-wishlist ver2"><i class="icon-heart"></i></a>
                            <a href="#" class="btn-quickview">QUICK VIEW</a>
                        </div>
                        <div class="product-info-ver2">
                            <h3 class="product-title"><a href="#">The Turtleneck</a></h3>
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
                            <p class="product-desc">Compellingly brand enterprise value after functional manufactured products. Synergistically morph process-centric intellectual capital rather than extensible catalysts for change. Credibly aggregate progressive initiatives and long-term.</p>
                            <div class="product-price">$292.00</div>
                            <div class="button-group">
                                <a href="#" class="button add-to-cart">Add to cart</a>
                                <a href="#" class="button add-to-wishlist">Add to wishlist</a>
                                <a href="#" class="button add-view">Quick view</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-15 col-sm-3 col-xs-6 product-item">
                        <div class="product-images">
                            <a href="#" class="hover-images effect"><img src="img/products/bag_1.jpg" alt="photo" class="img-reponsive"></a>
                            <a href="#" class="btn-add-wishlist ver2"><i class="icon-heart"></i></a>
                            <a href="#" class="btn-quickview">QUICK VIEW</a>
                        </div>
                        <div class="product-info-ver2">
                            <h3 class="product-title"><a href="#">The Turtleneck</a></h3>
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
                            <p class="product-desc">Compellingly brand enterprise value after functional manufactured products. Synergistically morph process-centric intellectual capital rather than extensible catalysts for change. Credibly aggregate progressive initiatives and long-term.</p>
                            <div class="product-price">$292.00</div>
                            <div class="button-group">
                                <a href="#" class="button add-to-cart">Add to cart</a>
                                <a href="#" class="button add-to-wishlist">Add to wishlist</a>
                                <a href="#" class="button add-view">Quick view</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-15 col-sm-3 col-xs-6 product-item">
                        <div class="product-images">
                            <a href="#" class="hover-images effect"><img src="img/products/long.jpg" alt="photo" class="img-reponsive"></a>
                            <a href="#" class="btn-add-wishlist ver2"><i class="icon-heart"></i></a>
                            <a href="#" class="btn-quickview">QUICK VIEW</a>
                        </div>
                        <div class="product-info-ver2">
                            <h3 class="product-title"><a href="#">The Turtleneck</a></h3>
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
                            <p class="product-desc">Compellingly brand enterprise value after functional manufactured products. Synergistically morph process-centric intellectual capital rather than extensible catalysts for change. Credibly aggregate progressive initiatives and long-term.</p>
                            <div class="product-price">$292.00</div>
                            <div class="button-group">
                                <a href="#" class="button add-to-cart">Add to cart</a>
                                <a href="#" class="button add-to-wishlist">Add to wishlist</a>
                                <a href="#" class="button add-view">Quick view</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-15 col-sm-3 col-xs-6 product-item">
                        <div class="product-images">
                            <a href="#" class="hover-images effect"><img src="img/products/sunglasses_1.jpg" alt="photo" class="img-reponsive"></a>
                            <a href="#" class="btn-add-wishlist ver2"><i class="icon-heart"></i></a>
                            <a href="#" class="btn-quickview">QUICK VIEW</a>
                        </div>
                        <div class="product-info-ver2">
                            <h3 class="product-title"><a href="#">The Turtleneck</a></h3>
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
                            <p class="product-desc">Compellingly brand enterprise value after functional manufactured products. Synergistically morph process-centric intellectual capital rather than extensible catalysts for change. Credibly aggregate progressive initiatives and long-term.</p>
                            <div class="product-price">$292.00</div>
                            <div class="button-group">
                                <a href="#" class="button add-to-cart">Add to cart</a>
                                <a href="#" class="button add-to-wishlist">Add to wishlist</a>
                                <a href="#" class="button add-view">Quick view</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-15 col-sm-3 col-xs-6 product-item">
                        <div class="product-images">
                            <a href="#" class="hover-images effect"><img src="img/products/mixhoodie.jpg" alt="photo" class="img-reponsive"></a>
                            <a href="#" class="btn-add-wishlist ver2"><i class="icon-heart"></i></a>
                            <a href="#" class="btn-quickview">QUICK VIEW</a>
                        </div>
                        <div class="product-info-ver2">
                            <h3 class="product-title"><a href="#">The Turtleneck</a></h3>
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
                            <p class="product-desc">Compellingly brand enterprise value after functional manufactured products. Synergistically morph process-centric intellectual capital rather than extensible catalysts for change. Credibly aggregate progressive initiatives and long-term.</p>
                            <div class="product-price">$292.00</div>
                            <div class="button-group">
                                <a href="#" class="button add-to-cart">Add to cart</a>
                                <a href="#" class="button add-to-wishlist">Add to wishlist</a>
                                <a href="#" class="button add-view">Quick view</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-15 col-sm-3 col-xs-6 product-item">
                        <div class="product-images">
                            <a href="#" class="hover-images effect"><img src="img/products/bag_2.jpg" alt="photo" class="img-reponsive"></a>
                            <a href="#" class="btn-add-wishlist ver2"><i class="icon-heart"></i></a>
                            <a href="#" class="btn-quickview">QUICK VIEW</a>
                        </div>
                        <div class="product-info-ver2">
                            <h3 class="product-title"><a href="#">The Turtleneck</a></h3>
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
                            <p class="product-desc">Compellingly brand enterprise value after functional manufactured products. Synergistically morph process-centric intellectual capital rather than extensible catalysts for change. Credibly aggregate progressive initiatives and long-term.</p>
                            <div class="product-price">$292.00</div>
                            <div class="button-group">
                                <a href="#" class="button add-to-cart">Add to cart</a>
                                <a href="#" class="button add-to-wishlist">Add to wishlist</a>
                                <a href="#" class="button add-view">Quick view</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-15 col-sm-3 col-xs-6 product-item">
                        <div class="product-images">
                            <a href="#" class="hover-images effect"><img src="img/products/hoodie_w.jpg" alt="photo" class="img-reponsive"></a>
                            <a href="#" class="btn-add-wishlist ver2"><i class="icon-heart"></i></a>
                            <a href="#" class="btn-quickview">QUICK VIEW</a>
                        </div>
                        <div class="product-info-ver2">
                            <h3 class="product-title"><a href="#">The Turtleneck</a></h3>
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
                            <p class="product-desc">Compellingly brand enterprise value after functional manufactured products. Synergistically morph process-centric intellectual capital rather than extensible catalysts for change. Credibly aggregate progressive initiatives and long-term.</p>
                            <div class="product-price">$292.00</div>
                            <div class="button-group">
                                <a href="#" class="button add-to-cart">Add to cart</a>
                                <a href="#" class="button add-to-wishlist">Add to wishlist</a>
                                <a href="#" class="button add-view">Quick view</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-15 col-sm-3 col-xs-6 product-item">
                        <div class="product-images">
                            <a href="#" class="hover-images effect"><img src="img/products/sunglasses_2.jpg" alt="photo" class="img-reponsive"></a>
                            <a href="#" class="btn-add-wishlist ver2"><i class="icon-heart"></i></a>
                            <a href="#" class="btn-quickview">QUICK VIEW</a>
                        </div>
                        <div class="product-info-ver2">
                            <h3 class="product-title"><a href="#">The Turtleneck</a></h3>
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
                            <p class="product-desc">Compellingly brand enterprise value after functional manufactured products. Synergistically morph process-centric intellectual capital rather than extensible catalysts for change. Credibly aggregate progressive initiatives and long-term.</p>
                            <div class="product-price">$292.00</div>
                            <div class="button-group">
                                <a href="#" class="button add-to-cart">Add to cart</a>
                                <a href="#" class="button add-to-wishlist">Add to wishlist</a>
                                <a href="#" class="button add-view">Quick view</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-15 col-sm-3 col-xs-6 product-item">
                        <div class="product-images">
                            <a href="#" class="hover-images effect"><img src="img/products/sweater_w.jpg" alt="photo" class="img-reponsive"></a>
                            <a href="#" class="btn-add-wishlist ver2"><i class="icon-heart"></i></a>
                            <a href="#" class="btn-quickview">QUICK VIEW</a>
                        </div>
                        <div class="product-info-ver2">
                            <h3 class="product-title"><a href="#">The Turtleneck</a></h3>
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
                            <p class="product-desc">Compellingly brand enterprise value after functional manufactured products. Synergistically morph process-centric intellectual capital rather than extensible catalysts for change. Credibly aggregate progressive initiatives and long-term.</p>
                            <div class="product-price">$292.00</div>
                            <div class="button-group">
                                <a href="#" class="button add-to-cart">Add to cart</a>
                                <a href="#" class="button add-to-wishlist">Add to wishlist</a>
                                <a href="#" class="button add-view">Quick view</a>
                            </div>
                        </div>
                    </div>
                <div class="pagination-container pagination-blog button-v text-center">
                    <nav>
                        <ul class="pagination">
                            <li><a class="active" href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li>
                                <a href="#" aria-label="Previous">
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                            </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Login Prompt (Sembunyi secara default) -->
<div id="loginPromptModal" style="display:none;">
    <div class="modal-content">
        <h3>Login Required</h3>
        <p>You must be logged in to view this product. Please log in to proceed.</p>
        <button onclick="window.location.href='{{ route('login') }}'">Login</button>
        <button onclick="closeModal()">Cancel</button>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Fungsi untuk menampilkan modal login
    function showLoginPrompt() {
        console.log("Modal Show Function Called"); // Debug log
        document.getElementById("loginPromptModal").style.display = "flex";
    }

    // Fungsi untuk menutup modal login
    function closeModal() {
        document.getElementById("loginPromptModal").style.display = "none";
    }
</script>
@endsection
