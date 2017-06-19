<?php
namespace Wsroot;
use \View;
use \Input;
use \Session;
use \Response;
use \Model_Product;
use \Model_Brand;
use \Model_Cate;

class Controller_Cate extends Controller_Admin{

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
            $cate = Model_Cate::forge()->set($data);
            $cate->save();
            $data['message'] = "Tạo mới thành công";   
        }
        $this->template->title = $data['title'] = "Thêm dòng mới";
        $this->template->content = View::forge('cate/themmoi',$data);
    }
    public function action_del($cateID)
    {
        $cate = Model_Cate::find($cateID);
        if (\Input::method() == 'POST')
        {
            $listProduct = Model_Product::find("all",array('where' => array(array('cateID','LIKE', "%".$cateID."%"))));
            foreach ($listProduct as $key => $value) {
                $value['cateID'] = str_replace($cateID.",","",$value['cateID']);
                $value->save();
            }
            $cate->delete();
            Response::redirect('/wsroot/cate/');
        }
        $data['cate'] = $cate;
        $this->template->title = $data['title'] = "Xóa dòng";
        $this->template->content = View::forge('cate/del',$data);
    }
    public function action_chinhsua($cateID)
    {
        $cate = Model_Cate::find($cateID);
        if(!$cate) die("400");
        if (\Input::method() == 'POST' && \Security::check_token())
        {
            $cate->value = Input::post('name');
            $cate->status = isset($_POST['status'])?1:0;
            $cate->save();
            $data['message'] = "Sửa sản phẩm thành công";   
        }

        $data['cate'] = $cate;
        $this->template->title = $data['title'] = "Chỉnh sửa dòng";
        $this->template->content = View::forge('cate/themmoi',$data);
    }
    public function action_index()
    {
        $data = array();
        $data['listCate'] = Model_Cate::find('all');
        $this->template->title = "Danh sách dòng";
        $this->template->content = View::forge('cate/index',$data);
    }
}
?>
