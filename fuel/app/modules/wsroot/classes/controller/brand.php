<?php
namespace Wsroot;
use \View;
use \Input;
use \Session;
use \Response;
use \Model_Product;
use \Model_Brand;
use \Model_Cate;

class Controller_Brand extends Controller_Admin{

    public $template = 'template';

    public function action_themmoi()
    {
        $data = array();
        if (\Input::method() == 'POST' && \Security::check_token())
        {
            $data = array(
                    'value' => Input::post('name'),
                    'status' => isset($_POST['status'])?1:0,
                );
            $brand = Model_Brand::forge()->set($data);
            $brand->save();
            $data['message'] = "Tạo mới thành công";   
        }
        $this->template->title = $data['title'] = "Thêm thương hiệu mới";
        $this->template->content = View::forge('brand/themmoi',$data);
    }
    public function action_del($brandID)
    {
        $brand = Model_Brand::find($brandID);
        if (\Input::method() == 'POST')
        {
            $listProduct = Model_Product::find("all",array('where' => array(array('brandID', $brandID))));
            foreach ($listProduct as $key => $value) {
                $value['brandID'] = 0;
                $value->save();
            }
            $brand->delete();
            Response::redirect('/wsroot/brand/');
        }
        $data['brand'] = $brand;
        $this->template->title = $data['title'] = "Xóa thương hiệu";
        $this->template->content = View::forge('brand/del',$data);
    }
    public function action_chinhsua($brandID)
    {
        $brand = Model_Brand::find($brandID);
        if(!$brand) die("400");
        if (\Input::method() == 'POST' && \Security::check_token())
        {
            $brand->value = Input::post('name');
            $brand->status = isset($_POST['status'])?1:0;
            $brand->save();
            $data['message'] = "Sửa sản phẩm thành công";   
        }

        $data['brand'] = $brand;
        $this->template->title = $data['title'] = "Chỉnh sửa sản phẩm";
        $this->template->content = View::forge('brand/themmoi',$data);
    }
    public function action_index()
    {
        $data = array();
        $data['listBrand'] = Model_Brand::find('all');
        $this->template->title = "Danh sách Thương hiệu";
        $this->template->content = View::forge('brand/index',$data);
    }
}
?>
