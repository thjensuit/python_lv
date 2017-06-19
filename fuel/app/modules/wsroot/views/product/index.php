<script type="text/javascript" src="/assets/datatable/jquery.dataTables.min.js"></script>
<link href="/assets/datatable/jquery.dataTables.min.css" rel="stylesheet">
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
        <li class="active">Icons</li>
    </ol>
</div><!--/.row-->

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Các sản phẩm</h1>
    </div>
</div><!--/.row-->
        

<div class="row list-product">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Toàn bộ sản phầm</div>

            <div class="panel-body">
                <a href="/wsroot/product/themmoi" class="btn btn-primary">+ Thêm mới</a>
                <hr>
                <table id="listproduct" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Loại</th><!-- 
                            <th>Mô Tả Ngắn</th> -->
                            <th>Ngày Tạo</th>
                            <th>Tool</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Loại</th><!-- 
                            <th>Mô Tả Ngắn</th> -->
                            <th>Ngày Tạo</th>
                            <th>Tool</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php 
                            foreach ($listProduct as $key => $value) {?>
                            <tr>
                            <td><?=$value['id']?></td>
                            <td><?=$value['name']?></td>
                            <td><img width="150px" src="/userfiles/<?=$value['img']?>"></td>
                            <td>Loại: <?=isset($listBrand[$value['brandID']])?$listBrand[$value['brandID']]:"Chưa có thương hiệu";?> <br> 
                                Dòng: <?php 
                                $cate = explode(",",$value['cateID']);
                                foreach ($cate as $k_cate => $k_cateValue): 
                                    if(isset($listCate[$k_cateValue])){
                                        echo $listCate[$k_cateValue].",";
                                    }
                                endforeach ?>
                            </td><!-- 
                            <td><?php echo preg_replace('#\<(.*?)\>#', '\n', $value['shortdetail'])?></td> -->
                            <td><?=$value['timecreate']?></td>
                            <td>
                                <a href="/wsroot/product/del/<?=$value['id']?>"><svg class="glyph stroked trash"><use xlink:href="#stroked-trash"/></svg></a>
                                |
                                <a href="/wsroot/product/<?=$value['id']?>"><svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"/></svg></a>
                            </td>
                            </tr>
                            <?php
                            }
                        ?>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><!--/.row-->  
 
<script type="text/javascript">
    $(document).ready(function() {
        $('#listproduct').DataTable({
            "searching": true,
            "columns": [
                { "width": "5%" },
                null,
                null,
                null,
                null,
                null
              ]
        });
    } );
</script>
