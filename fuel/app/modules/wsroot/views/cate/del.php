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
                    <div class="alert bg-danger" role="alert">
                        <svg class="glyph stroked cancel"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#stroked-cancel"></use></svg>  Xóa là toàn bộ sản phẩm thuộc dòng này sẽ được chuyển về không <a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            Tên Thương hiệu: <label><?=$cate['value']?></label>
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