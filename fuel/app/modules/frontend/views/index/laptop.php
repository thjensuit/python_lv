<div id="content-home">
  <?php require 'lazy/menu.phtml' ?>
  <h1>Laptop cũ giá rẻ uy tín chất lượng</h1>
  <div class="box">
      <!--<div class="box-heading">Mới nhất</div>-->
      <div class="box-content">
          <div class="box-product">

                <?php 
                echo '<pre>',var_dump(count($listProduct)),'</pre>';die();
                if(count($listProduct) == 0) echo "<strong>Hiện tại chưa có sản phẩm nào</strong>"; ?>
                <?php foreach ($listProduct as $key => $value) { 
                    ?>
                    <div class="boxover">
                        <div class="image"><a href="<?=$linkHost?>/detail/<?=$value['id']?>-<?=$value['link_seo']?>"><img src="/userfiles/<?=$value['img']?>" alt="<?=$value['name']?>" /></a></div>
                        <div class="name"><a href="<?=$linkHost?>/detail/<?=$value['id']?>-<?=$value['link_seo']?>"><?=$value['name']?></a></div>
                        <div class="price">
                            <?=number_format($value['price'])?> VND &nbsp;
                        </div>
                        <div class="description">
                            <?=$value['shortdetail']?>
                        </div>
                        <!-- them show len -->
                        <div class="boxover_info">
                          <span class="name"><?=$value['name']?></span><br/><br/><br/>
                          <span class="name1">Tính năng nổi bật:</span> <br/><br/>
                          <p>
                              <?=$value['shortdetail']?>
                          </p>
                          <div class="boxover_price">
                              <span class="price"><?=number_format($value['price'])?> VND</span>
                          </div>
                          </div>
                  </div>
                <?php } ?>
          </div>
      </div>
  </div>
</div>