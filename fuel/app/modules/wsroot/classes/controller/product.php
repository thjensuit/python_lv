<?php
namespace Wsroot;
use \View;
use \Input;
use \Session;
use \Response;
use \Model_Product;
use \Model_Brand;
use \Model_Cate;

class Controller_Product extends Controller_Admin{

    public $template = 'template';

    public function action_themmoi()
    {
        $data = array();
        if (\Input::method() == 'POST')
        {
            $linkbanner ="";
            $linkpdf ="";
            if(is_array($_FILES)) {             
                foreach ($_FILES as $key => $value) {
                    if(is_uploaded_file($value['tmp_name'])) {
                        $sourcePath = $value['tmp_name'];
                        $targetPath = APPPATH_USERFILE."product/".str_replace(".",date("Ymdhis").".",$value['name']);
                        if($key == "banner")
                            $linkbanner = "product/".str_replace(".",date("Ymdhis").".",$value['name']);
                        else
                            $linkpdf = "product/".str_replace(".",date("Ymdhis").".",$value['name']);
                        move_uploaded_file($sourcePath,$targetPath);
                        if($key == "banner"){
                            \Image::load($targetPath)
                                ->config('bgcolor', '#fff')
                                ->resize(156, 156, true, true)->save($targetPath);
                        }
                    }
                }
                
            }
            $data = array(
                    'name' => Input::post('name'),
                    'status' => isset($_POST['status'])?1   :0,
                    'link_seo' => Input::post('linkseo'),
                    'price' => Input::post('price'),
                    'shortdetail' => Input::post('shortdetail'),
                    'detail' => Input::post('detail'),
                    'brandID' => Input::post('brand'),
                    'cateID' => isset($_POST['cate'])?",".implode(",",$_POST['cate']).",":"",
                    'pdf' => $linkpdf,
                    'img' => $linkbanner,
                    'timestamp' => date("Y-m-d H:i:s"),
                );
            $product = Model_Product::forge()->set($data);
            $product->save();

            $data['message'] = "Đăng thành công";   
        }
        $data['brand'] = \Model_Brand::getAllItem();
        $data['cate'] = \Model_Cate::getAllItem();
        $this->template->title = $data['title'] = "Thêm mới sản phẩm";
        $this->template->content = View::forge('product/themmoi',$data);
    }

    public function action_chinhsua($proID)
    {
        $product = Model_Product::getDetailbyID($proID);
        if(!$product) die("400");
        if (\Input::method() == 'POST')
        {
            $linkbanner ="";
            $linkpdf ="";
            if(is_array($_FILES)) {             
                foreach ($_FILES as $key => $value) {
                    if(is_uploaded_file($value['tmp_name'])) {
                        $sourcePath = $value['tmp_name'];
                        $targetPath = APPPATH_USERFILE."product/".str_replace(".",date("Ymdhis").".",$value['name']);
                        if($key == "banner"){
                            @unlink(APPPATH_USERFILE.$product->img); 
                            $linkbanner = "product/".str_replace(".",date("Ymdhis").".",$value['name']);
                        }else{
                            $product->pdf = $linkpdf;
                            $linkpdf = "product/".str_replace(".",date("Ymdhis").".",$value['name']);
                        }
                        move_uploaded_file($sourcePath,$targetPath);
                        if($key == "banner"){
                            \Image::load($targetPath)
                                ->config('bgcolor', '#fff')
                                ->resize(156, 156, true, true)->save($targetPath);
                        }
                    }
                }   
            }
            $product->name = Input::post('name');
            $product->status = isset($_POST['status'])?1:0;
            $product->link_seo = Input::post('linkseo');
            $product->price = Input::post('price');
            $product->shortdetail = Input::post('shortdetail');
            $product->detail = Input::post('detail');
            $product->brandID = Input::post('brand');
            $product->cateID = isset($_POST['cate'])?",".implode(",",$_POST['cate']).",":"";
            if($linkbanner != "") $product->img = $linkbanner;
            if($linkpdf != "") $product->pdf = $linkpdf;
            
            $product->save();
            $data['message'] = "Sửa sản phẩm thành công";   
        }

        $data['product'] = $product;
        $data['brand'] = \Model_Brand::getAllItem();
        $data['cate'] = \Model_Cate::getAllItem();
        $this->template->title = $data['title'] = "Chỉnh sửa sản phẩm";
        $this->template->content = View::forge('product/themmoi',$data);
    }
    public function action_index()
    {
        $data = array();
        $data['listProduct'] = Model_Product::getAllItem();
        $data['listBrand'] = Model_Brand::getListBrand();
        $data['listCate'] = Model_Cate::getListCate();
        $this->template->title = "Danh sách sản phẩm";
        $this->template->content = View::forge('product/index',$data);
    }
    public function action_del($proID)
    {
        $product = Model_Product::find($proID);
        if (\Input::method() == 'POST')
        {
            $product->delete();
            Response::redirect('/wsroot/product/');
        }
        $data['product'] = $product;
        $this->template->title = $data['title'] = "Xóa sản phẩm";
        $this->template->content = View::forge('product/del',$data);
    }
}
?>
