<?php
namespace Wsroot;
use \View;
use \Input;
use \Session;
use \Response;
use \Fieldset;
use \Config;
class Controller_Handtools extends Controller_Admin{

    public $template = 'template';

    public function action_index(){
        $data = array();
        $data['title'] = "デザインサンプル一覧";
        $this->template->title = "デザインサンプル一覧";
        $this->template->content = View::forge('handtools/index',$data);
    }

    public function action_edit($tmp_code = null){
        $designSample = Model_DesignSampleList::find($tmp_code);

        $koukai = (int)Input::post('koukai');
        $category = Input::post('category');
        $lead_time_pk = (int)Input::post('lead_time_category');
        $design_no = Input::post('designNo');
        $rank = (int)Input::post('rank');

        $data = array(
                    'koukai' => $koukai,
                    'category' => $category,
                    'm_lead_time_pk' => $lead_time_pk,
                    'design_no' => $design_no,
                    'rank' => $rank,
                    'up_datetime' => date("Y-m-d H:i:s")
                );
        $errors = $this->handle_input($data);
        if (empty($errors)){
            $designSample->set($data);
            if ($designSample->save())
            {
               $this->closeform();
            }
            else
            {
                $this->errorback(array("Cannot update this product!"));
                Session::set_flash('error', e('Could not update product #' . $tmp_code));
            }
        }else{
            $this->errorback($errors);
        }
    }
    public function action_create(){
        $errors = array();
        $koukai = (int)Input::post('koukai');
        $category = Input::post('category');
        $lead_time_pk = (int)Input::post('lead_time_category');
        $design_no = Input::post('designNo');
        $rank = Input::post('rank');
        $data = array(
                    'koukai' => $koukai,
                    'category' => $category,
                    'm_lead_time_pk' => $lead_time_pk,
                    'design_no' => $design_no,
                    'rank' => $rank,
                    'up_datetime' => date("Y-m-d H:i:s")
                );
        $errors = $this->handle_input($data);

        if (empty($errors))
        {
            $shop = Model_DesignSampleList::forge($data);
            if ($shop->save()){
                $this->closeform();
            }
            else
            {
                Session::set_flash('error', e('Could not create shop.'));
                $this->errorback(array("Could not create shop"));
            }
        }
        else
        {
            $this->errorback($errors);
        }
    }
    public function action_ajaxcheckValidate()
    {
        $errors = array();
        $koukai = (int)Input::post('koukai');
        $category = Input::post('category');
        $lead_time_pk = (int)Input::post('lead_time_category');
        $design_no = Input::post('designNo');
        $rank = Input::post('rank');
        $data = array(
                    'koukai' => $koukai,
                    'category' => $category,
                    'm_lead_time_pk' => $lead_time_pk,
                    'design_no' => $design_no,
                    'rank' => $rank,
                    'up_datetime' => date("Y-m-d H:i:s")
                );
        $errors = $this->handle_input($data);
        return json_encode($errors);
    }
    public function handle_input($data){
        $errors = array();
        $koukai = $data['koukai'];
        $category = $data['category'];
        $lead_time_pk = $data['m_lead_time_pk'];
        $design_no = $data['design_no'];
        $rank = $data['rank'];

        if(!in_array($koukai, array(0,1))){
            $errors["koukai"] = "公開: 選択してください。";
        }


        $categoryList = Config::get("CATEGORY");
        if(!isset($categoryList[$category])){
            $errors["category"] = "カテゴリー: 選択してください。";
        }

        $leadTimeCheck = \Model_Leadtime::find('all', array('where'=>array(array('category', $category),array('no',$lead_time_pk))));
        if(!$leadTimeCheck){
            $errors["lead_time_pk"] = "名称: 選択してください。";
        }
        if($design_no){
            $designNoCheck = \Model_Designhistory::find('all', array('where'=>array(array('design_no', $design_no))));
            if(!$designNoCheck){
                $errors["design_no"] = "デザインNo: データが存在しません";
            }
        }else{
            $errors["design_no"] = "デザインNo: 入力してください。";
        }
        if($rank == ""){
            $errors["rank"] = "表示順: 入力してください";
        }
        return $errors;
    }
    public function action_confdel($pk=null){
        return $this->confirm_delete('designsamplelist',Model_DesignSampleList::find($pk),'デザインサンプル 削除');
    }
    public function action_koukai()
    {
        $tmp_code = trim(Input::post('tmp_code'));
        if($tmp_code){
            $product = Model_Product::find($tmp_code);
            $product->koukai = ($product->koukai == 0)?1:0;
            $product->save();
            return $product->koukai;
        }
        return 0;
    }
    public function action_form($pk=null){
        $data = isset($pk)?Model_DesignSampleList::find(array('where' => array('pk' => $pk))):'';
        return $this->create_form('designsamplelist',$data,'/wsroot/designsamplelist/edit/'.$pk,'/wsroot/designsamplelist/create','デザインサンプル登録');
    }
    public function action_ajaxchangestatus(){
        $id = (int)Input::post('id');
        $result = Model_DesignSampleList::find($id);
        $result->koukai = ($result->koukai==1)?0:1;
        $result->save();
        return $result->koukai;
    }
    public function action_delete(){
        try {
            if ($shop = Model_DesignSampleList::find((int)$_POST['pk']))
            {
                $shop->delete();
                $this->closeform();
            }
            else
            {
                Session::set_flash('error', e('Could not delete user'));
                $this->errorback(array("Cannot found information of this user!"));
            }
        } catch(\Exception $e){
            $this->closeform();
        }

    }
}
?>
