<?php
namespace Wsroot;
use \View;
use \Input;
use \Session;
use \Response;
use \Model_User;
class Controller_Users extends Controller_Admin{

    public $template = 'template';

    public function action_index()
    {
        $sort = Input::get("Sort");
        $sortN = Input::get("SortN");
        if(!$sort) $sort = "pk";
        $query = Model_User::query(array('order_by' => array($sort => $sortN)));
        $count = $query->count();
        $data = $this->paginate_data('wsroot/users',$count,3,20,5,$query);
        $data['title'] = "アカウント管理　一覧";
        $offset = $data['pagination']->offset;
        $last_show = ($offset+$data['pagination']->per_page)>$count?$count:($offset+$data['pagination']->per_page);
        $data['no_string'] = $count.' 件中 '.($offset+1).'-'.$last_show.' 件目';
        $data['sort'] = $sort;
        $data['sortN'] = $sortN;
        // var_dump($data['pagination']);die;

        $this->template->title = "アカウント管理　一覧";
        $this->template->content = View::forge('users/index',$data);
    }

    public function action_form($pk=null){
        return $this->create_form('users',isset($pk)?Model_User::find($pk):'','/wsroot/users/edit/'.$pk,'/wsroot/users/create','管理者アカウント');
    }

    public function handle_input($action=null, $pk=null){
        $message = \Config::get('ERROR_NOTI');
        $admin_id = trim(Input::post('admin_id'));
        $admin_pass = trim(Input::post('admin_pass'));
        $admin_name = trim(Input::post('admin_name'));
        $errors = array();
        if (empty($admin_id)) $errors['admin_id'] = 'ID'.$message['is_required'];
        else {
            if (!$this->isHankakuEisu($admin_id)) $errors['admin_id'] = 'ID'.$message['hankakueisu']; else {
                $same_users = empty($pk)?Model_User::query(array('where'=>array(array('admin_id',$admin_id)))):
                Model_User::query(array('where'=>array(array('admin_id',$admin_id), array('pk','!=',$pk))));
                if ($same_users->count()>0){
                    $errors['admin_id']='同じID'.$message['existed'];
                }
            }
        }

        if (empty($admin_pass) && $action==null) $errors['admin_pass'] = 'PASS'.$message['is_required'];
        else {
            if (!$this->isHankakuEisu($admin_pass)) $errors['admin_pass'] = 'PASS'.$message['hankakueisu'];
        }
        return $errors;
    }

    public function action_edit($pk = null)
    {
        $user = Model_User::find($pk);
        if ($user!=null){
            // $val = Model_User::validate('edit');
            // if ($val->run(array('admin_pass'=>"********")))
            $errors = $this->handle_input('edit',$pk);
            if (empty($errors))
            {
                $user->admin_id = trim(Input::post('admin_id'));
                $user->admin_name = trim(Input::post('admin_name'));
                $user->up_datetime = date('Y-m-d H:i:s');
                if (trim(Input::post('admin_pass'))!=""){
                    $user->admin_pass = base64_encode(hash_pbkdf2('sha256', trim(Input::post('admin_pass')), \Config::get('auth.salt'), \Config::get('auth.iterations', 10000), 32, true));
                }
                if ($user->save())
                {
                   $this->closeform();
                }
                else
                {
                    $this->errorback(array("Cannot update this user!"));
                    Session::set_flash('error', e('Could not update user #' . $pk));
                }
            } else {
                // $this->errorback($val->error());
                $this->errorback($errors);
            }
        } else {
            $this->errorback(array("Could not found information if this user!"));
        }
    }

    public function action_create()
    {
        // $val = Model_User::validate('create');
        // if ($val->run())
        $errors = $this->handle_input();
        if (empty($errors))
        {
            $result = \Auth::instance()->create_user(Input::post('admin_id'),Input::post('admin_pass'),Input::post('admin_name'));
            if ($result)
            {
                $this->closeform();
            }
            else
            {
                Session::set_flash('error', e('Could not create user.'));
                $this->errorback(array('Admin ID already existed.'));
            }
        }
        else
        {
            // $this->errorback($val->error());
            $this->errorback($errors);
        }
    }


    public function action_confdel($pk=null){
        return $this->confirm_delete('users',Model_User::find($pk),'管理者アカウント　削除確認');
    }

    public function action_delete(){
        if ($user = Model_User::find($_POST['pk']))
        {
            $user->delete();
            $this->closeform();
        }
        else
        {
            Session::set_flash('error', e('Could not delete user'));
            $this->errorback(array("Cannot found information of this user!"));
        }
    }
}
?>
