<?php
namespace Wsroot;
use \Response;
use \View;

class Controller_Base extends \Controller_Template {

    protected $_isJsonResponse = false;
    protected $_jsonData = array();
    public $template = 'template';
    protected $data = array();
    protected $view;

    public function before()
    {
        parent::before();
        $this->current_user = null;
        // foreach (\Auth::verified() as $driver)
        // {
        //     if (($id = $driver->get_user_id()) !== false)
        //     {
        //         $this->current_user = \Model\Auth_User::find($id[1]);
        //     }
        //     break;
        // }
        // \View::set_global('current_user', $this->current_user);
    }

    public function closeform() {
        echo "<script> parent.location.reload(); parent.$.colorbox.close();</script>";
        exit;
    }

    public function errorback( $arr = null, $path = null ) {
        if (empty($arr) || !is_array($arr)) return false;
        $err = implode('\n', $arr);
        $back = ($path) ? "location.href = '$path';" : 'history.back();';
        $html = '<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">';
        $html .= "<script language='JavaScript'> alert('".$err."'); ".$back."</script>";
        echo $html; exit;
    }

}