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
        <h1 class="page-header">Các dòng máy</h1>
    </div>
</div><!--/.row-->
        

<div class="row list-product">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">Toàn bộ dòng máy</div>

            <div class="panel-body">
                <a href="/wsroot/cate/themmoi" class="btn btn-primary">+ Thêm mới</a>
                <hr>
                <table id="listbrand" class="display" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Tool</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Tool</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php 
                            foreach ($listCate as $key => $value) {
                            ?>
                            <tr>
                            <td><?=$value['id']?></td>
                            <td><?=$value['value']?></td>
                            <td><?=$value['status']?></td>
                            <td>
                                <a href="/wsroot/cate/del/<?=$value['id']?>"><svg class="glyph stroked trash"><use xlink:href="#stroked-trash"/></svg></a>
                                |
                                <a href="/wsroot/cate/<?=$value['id']?>"><svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"/></svg></a>
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
        $('#listbrand').DataTable({
            "columns": [
                { "width": "5%" },
                null,
                null,
                null,
                null,
                null,
                null
              ]
        });
    } );
</script>
