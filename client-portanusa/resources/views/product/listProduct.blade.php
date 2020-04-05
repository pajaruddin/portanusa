@if(count($products) != 0)
<div class="row">
    @php 
    $no_loop = 1
    @endphp
    @foreach($products as $product)
    <div class="col-lg-3 col-md-4 col-6">
        <?php
        $price = DisplayProductPrice::getPrice($product->id);
        $product_discount = DisplayProductPrice::getDiscount($product->id, $price);

        $price_after_discount = $product_discount['price'];

        $product_status = DisplayProductCart::getStatus($product->id, $price);

        $status_style = "";
        if($price != $price_after_discount){
            $status_style .= " discount";
        }
        if($product->status == "New"){
            $status_style .= " is_new";
        }
        ?>
        <div class="product_item{{ $status_style }} {{ ($no_loop % 4 == 0 ? "border-none" : "") }} mt-4 mb-4">
            <a href="/base/{{ $product->url }}" tabindex="0"><div class="product_image d-flex flex-column align-items-center justify-content-center"><img src="{{ $asset_domain."/".$image_path."/".$product->image }}" alt=""></div></a>
            <div class="product_content">
                @if($price != 0)
                    <div class="product_price">
                        @if($price != $price_after_discount)
                        Rp {{ number_format($price_after_discount,0,0,'.') }}
                        <br/>
                        <span>Rp {{ number_format($price,0,0,'.') }}</span>
                        @else
                        Rp {{ number_format($price,0,0,'.') }}
                        @endif
                    </div>
                @else
                <div class="product_price">{{ $product_status['label'] }}</div>
                @endif
                <div class="product_name"><div><a href="/base/{{ $product->url }}" tabindex="0">{{ str_limit($product->name, 20) }}</a></div></div>
            </div>
            @if(Auth::check())
            <div class="product_fav {{ (DisplayWishlist::checkProduct($product->id) ? 'active' : '') }}" data-productId="{{ $product->id }}"><i class="fas fa-heart"></i></div>
            @else
            <a href="javascript:;" onclick="showLoginModal()"><div class="product_fav" data-productId="{{ $product->id }}"><i class="fas fa-heart"></i></div></a>
            @endif
            <ul class="product_marks">
                <li class="product_mark product_discount">-{{ $product_discount['discount'] }}%</li>
                <li class="product_mark product_new">new</li>
            </ul>
        </div>
    </div>
    @php
    $no_loop++;   
    @endphp
    @endforeach
    <div class="col-sm-12 mt-4">
        @if(!empty($filter["search"]))
        {{ $products->appends(Input::except('page', 'currentUrl', 'price', 'orderBy'))->links() }}
        @else
        {{ $products->links() }}
        @endif
    </div>
</div>
@else
<h6 class="pt-4"><i>product not found</i></h6>
@endif