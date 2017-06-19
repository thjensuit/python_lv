<?php require 'lazy/menu.phtml'; ?>
<div class="breadcrumb">
    <a href="/">Trang chủ</a>
    &raquo; <a href="/"><?=$product['name']?></a>
</div>
<div id="content">
    <h1><?=$product['name']?></h1>
    <div class="product-info">
        <div class="left">
            <div class="image"><a href="/userfiles/<?=$product['img']?>" title="<?=$product['name']?>" class="colorbox" rel="colorbox"><img src="/userfiles/<?=$product['img']?>" title="<?=$product['name']?>" alt="<?=$product['name']?>" id="image" /></a></div>
        </div>
        <div class="right">
            <div class="description">
                <span>Tình trạng:</span> Còn hàng
            </div>
            <div class="price">
                Giá: <?=number_format($product['price'])?> VND <br />
            </div>
            <div class="review">
                <div class="share">
                    <div id="fb-root"></div>
                    <script>(function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s); js.id = id;
                        js.src = "http://connect.facebook.net/vi_VN/all.js#xfbml=1";
                        fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));
                    </script>
                    <div class="fb-share-button" data-href="" data-type="button"></div>
                    <div style="margin-left: 150px;margin-top: -20px;">
                        <div class="g-plus" data-action="share" data-height="20"></div>
                    </div>
                    <script type="text/javascript">
                        (function() {
                        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                        po.src = 'https://apis.google.com/js/platform.js';
                        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                        })();
                    </script>
                    <div style="margin-left: 80px;margin-top: -20px;"><a href="https://twitter.com/share" class="twitter-share-button" data-dnt="true" data-count="none" data-via="twitterapi">Tweet</a></div>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                </div>
            </div>
        </div>
    </div>
    <div id="tabs" class="htabs">
    </div>
    <div id="tab-description" class="tab-content">
        <?=html_entity_decode($product['shortdetail'])?>
        <?=html_entity_decode($product['detail'])?>
        
        <?php if($product['pdf']){ ?>
        <iframe src="http://docs.google.com/gview?url=<?=$linkHost?>/userfiles/<?=$product['pdf']?>&embedded=true" style="width:100%; height:700px;" frameborder="0"></iframe>
        <?php }?>
    </div>
    <div id="fb-root"></div>
    <script>(function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id= id;
        js.src = "http://connect.facebook.net/vi_VN/all.js#xfbml=1";
        fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <div class="fb-comments" data-href="<?=$linkHost?>/<?=$product['link_seo']?>" data-width="700" data-num-posts="10"></div>
</div>
