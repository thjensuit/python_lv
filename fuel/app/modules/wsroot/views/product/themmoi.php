<?php $linkHost = "http://".$_SERVER['SERVER_NAME'];?>
<script type="text/javascript" src="/assets/ckfinder/ckfinder.js"></script>
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
        <div class="panel panel-default"><!-- 
            <iframe src="http://docs.google.com/gview?url=https://pdfs.semanticscholar.org/2a01/e1f14172a91215931ed787d97dee1301fe7d.pdf&embedded=true" style="width:718px; height:700px;" frameborder="0"></iframe> -->
            <div class="panel-heading"><?=$title?></div>
            <div class="panel-body">
                <?php if(isset($message)){?>
                <div class="alert bg-success" role="alert">
                    <svg class="glyph stroked checkmark"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#stroked-checkmark"></use></svg> <?php echo isset($message)?$message:""?> <a href="#" class="pull-right"><span class="glyphicon glyphicon-remove"></span></a>
                </div>
                <?php } ?>
                <form role="form" method="post" enctype="multipart/form-data">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tên Sản Phẩm</label>
                            <input type="text" name="name" av id="productName" class="form-control" placeholder="Placeholder" value="<?=isset($product['name'])?$product['name']:''?>">
                        </div>
                        <div class="form-group">
                            <label>Link SEO</label>
                            <input type="text" name="linkseo" id="linkseo" value="<?=isset($product['link_seo'])?$product['link_seo']:''?>" class="form-control" placeholder="Placeholder">
                        </div>
                        <div class="form-group">
                            <label><input type="checkbox" name="status" <?=(isset($product['status'])&&$product['status']==1)?'checked':''?> value="1"> 
                                Hiển thị
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Ảnh sản phẩm bìa:</label>
                            <input type="file" name="banner" >
                            <hr>
                            <?=isset($product['img'])?'<img src="/userfiles/'.$product['img'].'" width="156px" />':''?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Thương Hiệu:</label>
                            <select name="brand">
                                <option value="0">Chưa có thương hiệu</option>
                                <?php 
                                $brandID = isset($product['brandID'])?$product['brandID']:0;
                                foreach ($brand as $key => $value): 
                                    $selected="";
                                    if($value['id'] == $brandID) $selected="selected";
                                ?>
                                    <option <?=$selected?> value="<?=$value['id']?>"><?=$value['value']?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Dòng máy:</label>
                            <?php 
                            $arrCate = isset($product)?explode(",",$product['cateID']):array();
                            foreach ($cate as $key => $value): 
                                $checked="";
                                if(in_array($value['id'], $arrCate)) $checked="checked";
                                ?>
                                <input type="checkbox" <?=$checked?> name="cate[]" value="<?=$value['id']?>"> <?=$value['value']?>
                            <?php endforeach ?>
                            
                        </div>
                        <div class="form-group">
                            <label>Giá</label>
                            <input type="number" name="price" value="<?=isset($product['price'])?$product['price']:''?>" class="form-control" placeholder="0">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Mô tả ngắn</label>
                            <textarea id="shortdetail" name="shortdetail" class="form-control" rows="3"><?=isset($product['shortdetail'])?$product['shortdetail']:''?></textarea>
                        </div>  
                        <hr>
                        Upload PDF: <input type="file" name="fileToUpload" id="fileToUpload">
                        <a href="/userfiles/<?php if(isset($product['pdf'])) echo $product['pdf']?>"><?php if(isset($product['pdf'])) echo $product['pdf']?></a>

                        <?php if(isset($product['pdf']) && $product['pdf']){ ?>
                        <iframe src="http://docs.google.com/gview?url=<?=$linkHost?>/userfiles/<?=$product['pdf']?>&embedded=true" style="width:100%; height:300px;" frameborder="0"></iframe>
                        <?php }?>
                        <hr>
                        Hoặc
                        
                        <div class="form-group">
                            <label>Chi Tiết</label>
                            <textarea id="detail" name="detail" class="form-control" rows="3"><?=isset($product['detail'])?$product['detail']:''?></textarea>
                        </div>  
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Submit Button</button>
                        <button type="reset" class="btn btn-default">Reset Button</button>
                    </div>
                </div>
            </form>
        </div>
    </div><!-- /.col-->
</div><!-- /.row -->

<script src="//cdn.ckeditor.com/4.5.6/standard-all/ckeditor.js"></script>
<script type="text/javascript">
</script>
<script>
    // Note: in this sample we use CKEditor with two extra plugins:
    // - uploadimage to support pasting and dragging images,
    // - image2 (instead of image) to provide images with captions.
    // Additionally, the CSS style for the editing area has been slightly modified to provide responsive images during editing.
    // All these modifications are not required by CKFinder, they just provide better user experience.
    if ( typeof CKEDITOR !== 'undefined' ) {
        CKEDITOR.addCss( 'img {max-width:100%; height: auto;}' );
        var editor = CKEDITOR.replace( 'shortdetail', {
            extraPlugins: 'uploadimage,image2',
            removePlugins: 'image',
            height:350
        } );
        var detail = CKEDITOR.replace( 'detail', {
            extraPlugins: 'uploadimage,image2',
            removePlugins: 'image',
            height:350
        } );

        // Just call CKFinder.setupCKEditor and pass the CKEditor instance as the first argument.
        // The second parameter (optional), is the path for the CKFinder installation (default = "/ckfinder/").
        CKFinder.setupCKEditor( editor, '../' ) ;
        CKFinder.setupCKEditor( detail, '../' ) ;

        // It is also possible to pass an object with selected CKFinder properties as a second argument.
        // CKFinder.setupCKEditor( editor, { basePath : '../', skin : 'v1' } ) ;
    } else {
        document.getElementById( 'description' ).innerHTML = '<div class="tip-a tip-a-alert">This sample requires working Internet connection to load CKEditor from CDN.</div>'
    }

    $("#productName").keyup(function() {
        curName = $(this).val();
        console.log(curName);
        removeDau = bodauTiengViet(curName);
        seolink = removeDau.replace(/ /g , "-");
        seolink = seolink.replace(/,/g , "");
        seolink = seolink.replace(/\./g , "-");
        seolink = seolink.replace(/\%/g , "");
        $("#linkseo").val(seolink);
    });
    function bodauTiengViet(str) {
        str = str.toLowerCase();
        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
        str = str.replace(/đ/g, "d");
        return str;
    }
</script>