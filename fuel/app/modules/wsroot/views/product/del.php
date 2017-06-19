<?php $linkHost = "http://".$_SERVER['SERVER_NAME'];?>
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
        <li class="active">Icons</li>
    </ol>
</div><!--/.row-->

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header"><?=$title?></h1>
    </div>
</div><!--/.row-->
    
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading"><?=$title?></div>
            <div class="panel-body">
                <form role="form" action="#" method="post" enctype="multipart/form-data">
                    <div class="col-md-6">
                        <div class="form-group">
                            ID: <label><?=$product['id']?></label>
                        </div>
                        <div class="form-group">
                            Tên Sản Phẩm: <label><?=$product['name']?></label>
                        </div>
                        <div class="form-group">
                            Giá: <label><?=number_format($product['price'])?></label>
                        </div>
                        <div class="form-group">
                            Ảnh: <img src="/userfiles/<?=$product['img']?>">
                        </div>
                        <div class="form-group">
                            Mô tả ngắn: <?=html_entity_decode($product['shortdetail'])?>
                        </div>
                    </div>
                    <div class="col-md-6"></div>
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Xóa</button>
                        <button type="reset" class="btn btn-default">Reset Button</button>
                    </div>
                </div>
            </form>
        </div>
    </div><!-- /.col-->
</div><!-- /.row -->