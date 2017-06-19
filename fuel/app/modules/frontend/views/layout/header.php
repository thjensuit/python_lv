<!-- header start -->
<header>
    <div id="header" class="cb">
        <p id="logo"><a href="/"><img src="/assets/frontend/img/logo.gif" alt="DIADORA"></a><span id="spritoid_logo"><img src="/assets/frontend/img/spritoid_logo.gif" alt="SPRITO ID"></span></p>
        <ul id="header_side">
            <!-- <li id="header_guide"><a href="#">ご利用ガイド <b class="caret"></b></a></li> -->
            <li id="header_dealer"><a href="/shoplist/">取扱店一覧 <b class="caret"></b></a></li>
            <li id="header_serch">
                <form method="POST" action="/search/">
                    <input type="text" id="h_sarch_box" name="keyword" value="">
                    <input type="submit" id="h_sarch_btn" value="GO">
                </form>
            </li>
        </ul>
    </div>
</header>
<!-- header end -->
<!-- navi start -->
<nav>
    <div id="navi">
        <ul class="cb">
            <li><a href="/product?category=<?=urlencode('FOOTBALL')?>">FOOTBALL <b class="caret"></b></a></li>
            <li><a href="/product?category=<?=urlencode('TENNIS')?>">TENNIS <b class="caret"></b></a></li>
            <li><a href="/product?category=<?=urlencode('TRACK&FIELD')?>">TRACK &amp; FIELD <b class="caret"></b></a></li>
            <li><a href="/product?category=<?=urlencode('MULTI')?>">MULTI <b class="caret"></b></a></li>
        </ul>
    </div>
</nav>
<!-- navi end -->