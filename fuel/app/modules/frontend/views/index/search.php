<div id="content-home">
  <?php require 'lazy/menu.phtml' ?>
  <h1>Tìm kiếm sản phẩm</h1>
  <div class="box">
        <form action="#" method="GET">
          <p>Tên sản phẩm: <input type="text"  class="inputBox" name="name" value="<?= isset($_GET['name'])?$_GET['name']:'' ?>"></p>
          <p>Dung lượng Ram: <input type="text" class="inputBox" name="ram" value="<?= isset($_GET['ram'])?$_GET['ram']:'' ?>"></p>
          <p>Dung lượng HDD: <input type="text" class="inputBox" name="harddisk" value="<?= isset($_GET['harddisk'])?$_GET['harddisk']:'' ?>"></p>
          <input type="submit" value="Tìm Kiếm">
        </form>
        <h1>Kết quả</h1>
      <div class="box-content">
          <input type="hidden" id="brandID" value="<?=isset($brandID)?$brandID:0?>">
          <div class="box-product" id="listProduct">
                
          </div>
      </div>
  </div>
</div>