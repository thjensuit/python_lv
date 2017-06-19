<?php
namespace Wsroot;
use \Response;
use \View;
use \Request;
use \Auth;
use \Upload;

class Controller_Admin extends Controller_Base{

   /* public $error_noti = array(
            'is_required' => ': 入力してください。',
            'choose_required' => ': 選択してください。',
            'wrong_format' => ': 正しく入力してください。',
            'zenkaku' => ': 全角ひらがなで入力してください。',
            'furigana' => ': 全角ひらがなで入力してください。',
            'email_format' => ': メールアドレス形式で入力してください。',
            'hankaku' => ': 半角で入力してください。',
            'hankakueisu' => ': 半角英数記で入力してください。',
            'hankakusuji' => ': 半角数字で入力してください。',
            'furigana' => ': 全角ひらがなで入力してください。',
            'katakana' => ': 全角カタカナで入力してください。',
            'phone_number' => '電話番号形式で入力してください。',
            'existed' => ': すでに存在しています。',
            'max_digit' => ': 文字未満で入力してください。',
            );*/

    public function before()
    {
        parent::before();
        if (Request::active()->controller !== 'Wsroot\Controller_Index' or ! in_array(Request::active()->action, array('login', 'logout')))
        {
            if (!Auth::check())
            {
                Response::redirect('wsroot/index/login');
            }
        }
    }

    public function isHankaku($string)
    {
        return preg_match('/\A[!-~]*\z/' . 'u', $string);
    }

    public function isHankakuEisu($string){
        return preg_match('/\A[a-zA-Z0-9]*\z/' . 'u', $string);
    }

    public function isNum($string)
    {
        return preg_match('/\A[0-9]*\z/' . 'u', $string);
    }

    public function isMap($string)
    {
        return preg_match('/\A[0-9]*[.]?[0-9]*\z/' . 'u', $string);
    }

    public function isURL($string) {
        /*if (!filter_var($string, FILTER_VALIDATE_URL) === false)http://xxx.xxx.xxx
            return true;
        return false;*/
        return preg_match('/\Ahttp:|https:|ftp:\/\/[0-9-a-zA-Z]*.[0-9-a-zA-Z]*.[0-9a-zA-Z]*\z/' . 'u', $string);
    }

    public function isPhone($phone)
    {
        $pattern = "/^([0-9]{3}-[0-9]{4}-[0-9]{3,4}|[0-9]{10,11}|[0-9]{2}-[0-9]{4}-[0-9]{3,4}|[0-9]{2}-[0-9]{4}-[0-9]{4,5}|[0-9]{3}-[0-9]{3}-[0-9]{4,5}|[0-9]{4}-[0-9]{2}-[0-9]{4})$/";
        return preg_match($pattern, $phone);
    }

    public function isHiragana($string)
    {
        return preg_match('/\A[ぁ-ゞ]*\z/' . 'u', $string);
    }

    public function isZenkakuKatakana($string)
    {
        return preg_match('/\A[ァ-ヶー]*\z/' . 'u', $string);
    }

    public function isZip($string)
    {
        return preg_match('/^[0-9]{3}-[0-9]{4}$/' . 'u', $string);
    }

    public function isEmail($string){
        if(function_exists('filter_var') && defined('FILTER_VALIDATE_EMAIL'))
            return filter_var($string, FILTER_VALIDATE_EMAIL);
        else {
            // Email Address Regular Expression That 99.99% Works from http://emailregex.com
            $pattern = "/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iu";
            return preg_match($pattern, $string);
        }
    }

    public function create_form($table, $data = null, $update_url, $insert_url, $title, $form_data=null) {
        if (!empty($form_data)){
            $this->data['form_data'] = $form_data;
        }
        if(!empty($data)) {
            $this->data['data'] = $data;
            $this->data['action'] = $update_url;
            $title = $title.'編集';
        } else {
            $this->data['action'] = $insert_url;
            $title = $title.'登録';
        }
        $this->data['title']=$title;
        return new Response(View::forge($table.'/form', $this->data));
    }


    public function confirm_delete($table, $data, $title) {
        $this->data['title'] = $title;
        $this->data['data'] = $data;
        return new Response(View::forge($table.'/confdel',$this->data));
    }

    public function confirm_send($table, $data, $title) {
        $this->data['title'] = $title;
        $this->data['data'] = $data;
        return new Response(View::forge($table.'/confsend',$this->data));
    }

    public function paginate_data($url, $count,$uri_segment, $per_page, $num_links, $query, $typeQuery = null){
        $pagination = \Pagination::forge('pagination', array(
            'pagination_url' => \Uri::base(false) . $url,
            'total_items' => $count,
            'per_page' => $per_page,
            'uri_segment' => $uri_segment,
            'num_links' => $num_links,
            'link_offset' => 0.5,
        ));
        $data['pagination']=$pagination;
        if($typeQuery === 'db_select'){
            $data['data'] =  $query
            ->offset($pagination->offset)
            ->limit($pagination->per_page)
            ->execute();
        }
        else{
            $data['data'] =  $query
            ->rows_offset($pagination->offset)
            ->rows_limit($pagination->per_page)
            ->get();
        }

        return $data;
    }


    /*
    @Athour: KhanhKid
    @Date: 20170125 15:16
    @Description: Function to upload image
    */
    public function UploadImage($type=0)
    {
        $destination = array('0'=> "",'1' => "/main_img/");
        Upload::process(array(
            'path'        => APPPATH_USERFILE.$destination[$type],
            'normalize'   => true,
            'change_case' => 'lower',
            'randomize'   => true,
            'ext_whitelist' => array('jpeg', 'jpg', 'gif', 'png'),
            #'max_size'    => 1024 * 1024,
            'auto_rename'    => true,
        ));

        if (Upload::is_valid()) {
            Upload::save();
            switch ($type) {
                case 1:
                    if (!file_exists(APPPATH_USERFILE.'/main_img/')) {
                        mkdir(APPPATH_USERFILE.'/main_img/', 0777, true);
                    }
                    return "/userfiles/main_img/".Upload::get_files()[0]['saved_as'];
                    break;
                default:
                    return Upload::get_files();
                    break;
            }

        }
        return $this->response(Upload::get_errors());
    }

    public function change_key($arr,$olds,$news){
        $new_arr = array();
        $index = 0;
        foreach($olds as $old){
            $new_arr[$news[$index]] = $arr[$old];
            $index++;
        }
        return $new_arr;
    }

    public function export($data, $header, $module, $type = 0){
        /*
            type =
            1: order
            0: shop
        */
        $stream="";
        $dir = ($type == 0)?"userfiles/doc/shop/":"userfiles/doc/order/";
        try {
            $stream = \Format::forge($data)->to_csv(null,null,null,$header);
            $stream = mb_convert_encoding( $stream, 'CP932', 'ASCII,JIS,UTF-8,eucJP-win,SJIS-win' );
            $filename = $module.'_'.date('Ymd').'.csv';
            \File::create(DOCROOT .$dir, $filename, $stream);
            $file_path =  '/'.$dir.$filename;
            header('Content-Encoding: UTF-8');
            header('Content-Type:text/csv;charset=Shift-JIS');
            header('Content-Disposition: attachment; filename="'.$filename.'"');
            return \Format::forge(array(
                    'status'=>true,
                    'message'=>'Download OK...',
                    'url'=> $file_path
            ))->to_json();
        } catch (Exception $e) {
            return \Format::forge(array(
                    'status'=>false,
                    'message'=>$e->getMessage()
            ))->to_json();
        }
    }

    protected function generate_factory_order_no($factory_order_no, &$latestFactoryNoList){
        if (empty($latestFactoryNoList[$factory_order_no]))
            $latestFactoryNoList[$factory_order_no] = '0000';
        $latestFactoryNo = $latestFactoryNoList[$factory_order_no];
        $latestFactoryNo = $this->getNewNumber($latestFactoryNo,strlen($latestFactoryNo));
        $latestFactoryNoList[$factory_order_no] = $latestFactoryNo;
        return $latestFactoryNo.$factory_order_no;
    }
    protected function getNewNumber($num,$numLen){
        $num++;
        $newStrLen = strlen($num);
        for ($i=0;$i<($numLen-$newStrLen);$i++){
            $num='0'.$num;
        }
        return $num;
    }
}