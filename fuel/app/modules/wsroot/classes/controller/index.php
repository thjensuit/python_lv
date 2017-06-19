<?php
namespace Wsroot;
use \View;
use \Response;
use \Request;
use \Auth;
use \Input;
class Controller_Index extends \Controller_Template{
    public $template = 'template';

    public function before(){
        parent::before();
        if (Request::active()->controller !== 'Wsroot\Controller_Index' or ! in_array(Request::active()->action, array('login', 'logout')))
        {
            if (!Auth::check())
            {
                Response::redirect('wsroot/index/login');
            }
        }
    }
    public function action_index(){
        $data = array();
        $this->template->title = "管理者専用メニュー";
        $this->template->content = View::forge('index/index',$data);
    }
    public function action_login(){
        Auth::check() and Response::redirect('wsroot');
        $val = \Validation::forge();

        if (\Input::method() == 'POST')
        {

            if (\Auth::instance()->login(Input::post('admin_id'), Input::post('admin_pass')))
            {
                Response::redirect('wsroot');
            }
            else
            {
                $data['message']='認証できませんでした。';
                return new Response(View::forge('login/index',$data));
            }
        } else {
                return new Response(View::forge('login/index'));
        }
    }


    public function action_logout(){
        Auth::logout();
        Response::redirect('wsroot/index');
    }
}




 ?>