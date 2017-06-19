<?php
namespace Frontend;
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


    }
    public function paginate_data($url, $count,$uri_segment, $per_page, $num_links, $query,$currentpage){
        $pagination = \Pagination::forge('pagination', array(
            'pagination_url' => \Uri::base(false) . $url,
            'total_items' => $count,
            'per_page' => $per_page,
            'uri_segment' => $uri_segment,
            'num_links' => $num_links,
            'current_page' => $currentpage,
            'link_offset' => 0.5,
        ));
        $data['pagination']=$pagination;
        $data['data'] =  $query
        ->rows_offset($pagination->offset)
        ->rows_limit($pagination->per_page)
        ->get();
        return $data;
    }

    public function randomString($length = 5){
        $randstr = '';
        // srand((double) microtime(TRUE) * 1000000);
        $chars = array(
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'p',
            'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5',
            '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
            'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        for ($rand = 0; $rand < $length; $rand++) {
            $random = rand(0, count($chars) - 1);
            $randstr .= $chars[$random];
        }
        return $randstr;
    }

    //Khanhkid 20170315 set meta tag
    public function metaTag($note=null){
        $infoPage = \Request::active();
        $meta = array();
        $titleDefault = "DIADORA TEAM ORDER SYTEM:ディアドラ";
        $descriptionDefault = "ディアドラ チームオーダー" ;
        $keywordDefault = "DIADORA,ディアドラ,チーム,ユニフォーム,オリジナル,シミュレーション,マーキング加工";
        $og_site_nameDefault = "DIADORA TEAM ORDER SYSTEM";
        $og_titleDefault = "チームオーダー オリジナルウェア | DIADORA TEAM ORDER SYSTEM";
        $og_descriptionDefault = "世界に一つだけのオリジナルチームウェア。デザインサンプルを参考にシミュレーションしてみよう！";

        $http = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")?"https://":"http://";
        $linkurlDefault = $http.$_SERVER['SERVER_NAME']."/";
        $linkOgImage = $http.$_SERVER['SERVER_NAME']."/assets/img/diadora_ogp.jpg";

        switch ($infoPage->controller."|".$infoPage->action) {
            case 'Frontend\Controller_Index|index':
                // 20170315 Khanhkid prepare meta tag
                $meta = self::setMeta(
                            $titleDefault." チームオーダーシステム",
                            $descriptionDefault."システムは、簡単・スピーディーにオリジナルウェアを作ることができます。",
                            $keywordDefault.",サッカー,フットサル,テニス,陸上,ランニング,トレーニング",
                            $og_site_nameDefault,
                            $og_titleDefault,
                            $og_descriptionDefault,
                            $linkOgImage,
                            $linkurlDefault);
                break;
            case 'Frontend\Controller_Shoplist|index':
                $meta = self::setMeta(
                            "取扱店舗 | ".$titleDefault,
                            $descriptionDefault." ショップ一覧",
                            $keywordDefault,
                            $og_site_nameDefault,
                            $og_titleDefault,
                            $og_descriptionDefault,
                            $linkOgImage,
                            $linkurlDefault."shoplist/");
                break;
            case 'Frontend\Controller_Product|index':
                switch ($note) {
                    case 'FOOTBALL':
                        $meta = self::setMeta(
                            "サッカー・フットサル | ".$titleDefault,
                            $descriptionDefault." サッカー・フットサルウェア一覧",
                            $keywordDefault.",サッカー,フットサル",
                            $og_site_nameDefault,
                            $og_titleDefault,
                            $og_descriptionDefault,
                            $linkOgImage,
                            $linkurlDefault."product?category=".$note);
                        break;
                    case 'TENNIS':
                        $meta = self::setMeta(
                            "テニス | ".$titleDefault,
                            $descriptionDefault." テニスウェアのウェア一覧",
                            $keywordDefault.",テニス,レディース",
                            $og_site_nameDefault,
                            $og_titleDefault,
                            $og_descriptionDefault,
                            $linkOgImage,
                            $linkurlDefault."product?category=".$note);
                        break;
                    case 'TRACK_FIELD':
                        $meta = self::setMeta(
                            "陸上・ランニング | ".$titleDefault,
                            $descriptionDefault." 陸上・ランニングウェア一覧",
                            $keywordDefault.",陸上,ランニング",
                            $og_site_nameDefault,
                            $og_titleDefault,
                            $og_descriptionDefault,
                            $linkOgImage,
                            $linkurlDefault."product?category=".$note);
                        break;
                    case 'MULTI':
                        $meta = self::setMeta(
                            "トレーニング | ".$titleDefault,
                            $descriptionDefault." トレーニングウェア一覧",
                            $keywordDefault.",トレーニング,Tシャツ,ポロシャツ",
                            $og_site_nameDefault,
                            $og_titleDefault,
                            $og_descriptionDefault,
                            $linkOgImage,
                            $linkurlDefault."product?category=".$note);
                        break;
                }
                break;
            case 'Frontend\Controller_Product|detail':
                $meta = self::setMeta(
                            "デザイン | ".$titleDefault,
                            $descriptionDefault." デザイン",
                            $keywordDefault.",サッカー,フットサル,テニス,陸上,ランニング,トレーニング",
                            $og_site_nameDefault,
                            $og_titleDefault,
                            $og_descriptionDefault,
                            $linkOgImage,
                            $linkurlDefault);
                break;
            case 'Frontend\Controller_Product|save_design':
                $meta = self::setMeta(
                            "デザイン保存 | ".$titleDefault,
                            $descriptionDefault." デザイン保存",
                            $keywordDefault,
                            $og_site_nameDefault,
                            $og_titleDefault,
                            "オリジナルウェアのデザインを保存しました。",
                            $linkOgImage,
                            $linkurlDefault."product/detail/".$note."/"); // note --> product detail
                break;
            case 'Frontend\Controller_Order|index':
                $meta = self::setMeta(
                            "注文数入力 | ".$titleDefault,
                            $descriptionDefault." 注文数入力",
                            $keywordDefault,
                            $og_site_nameDefault,
                            $og_titleDefault,
                            $og_descriptionDefault,
                            $linkOgImage,
                            $linkurlDefault);
                break;
            case 'Frontend\Controller_Order|conf':
                $meta = self::setMeta(
                            "注文数確認 | DIADORA SPIRITOD:ディアドラ",
                            $descriptionDefault." 注文数確認",
                            $keywordDefault,
                            $og_site_nameDefault,
                            $og_titleDefault,
                            $og_descriptionDefault,
                            $linkOgImage,
                            $linkurlDefault);
                break;
            case 'Frontend\Controller_Order|completion':
                $meta = self::setMeta(
                            "オーダーシート印刷 | ".$titleDefault,
                            $descriptionDefault." 注文書印刷",
                            $keywordDefault,
                            $og_site_nameDefault,
                            $og_titleDefault,
                            "オーダーシートを作成しました。お近くのショップで正式注文してください。",
                            $linkOgImage,
                            $linkurlDefault."confirm_quantity/");
                break;
            case 'Frontend\Controller_Index|error':
                $meta = self::setMeta(
                            "エラー | ".$titleDefault,
                            $descriptionDefault." エラー",
                            "DIADORA,ディアドラ,チーム,ユニフォーム,オリジナル,マーキング加工",
                            $og_site_nameDefault,
                            $og_titleDefault,
                            $og_descriptionDefault,
                            $linkOgImage,
                            $linkurlDefault);
                break;
            case 'Frontend\Controller_Index|404':
                $meta = self::setMeta(
                            "ページが見つかりません | ".$titleDefault,
                            $descriptionDefault." HTTP404",
                            $keywordDefault,
                            $og_site_nameDefault,
                            $og_titleDefault,
                            "オーダーシートを作成しました。お近くのショップで正式注文してください。",
                            $linkOgImage,
                            $linkurlDefault);
                break;

            // for shop
            case 'Frontend\Controller_Index|login_forshop':
                $meta = self::setMeta(
                            "ショップログイン | ".$titleDefault,
                            $descriptionDefault." ショップログイン","","","","","");
                break;
            case 'Frontend\Controller_Forshop|design_history':
                $meta = self::setMeta(
                            "ショップデザイン履歴 | ".$titleDefault,
                            $descriptionDefault." ショップデザイン履歴","","","","","");
                break;
            case 'Frontend\Controller_Forshop|order_history':
                $meta = self::setMeta(
                            "ショップ注文履歴 | ".$titleDefault,
                            $descriptionDefault." ショップ注文履歴","","","","","");
                break;
            case 'Frontend\Controller_Forshop|order':
                $meta = self::setMeta(
                            "ショップ注文数入力 | ".$titleDefault,
                            $descriptionDefault." ショップ注文数入力","","","","","");
                break;
            case 'Frontend\Controller_Forshop|confirm_quantity':
                $meta = self::setMeta(
                            "ショップ注文数確認 | ".$titleDefault,
                            $descriptionDefault." ショップ注文数確認","","","","","");
                break;
            case 'Frontend\Controller_Forshop|confirm_order':
                $meta = self::setMeta(
                            "ショップ注文 | ".$titleDefault,
                            $descriptionDefault." ショップ注文","","","","","");
                break;
            case 'Frontend\Controller_Forshop|complete_order':
                $meta = self::setMeta(
                            "ショップ注文完了 | ".$titleDefault,
                            $descriptionDefault." ショップ注文完了","","","","","");
                break;
        }
        //echo '<pre>',var_dump($meta),'</pre>';die();
        return $meta;
    }
    public function setMeta($title,$description,$keywords,$og_site_name,$og_title,$og_description,$og_image,$og_url=""){
        return array(
                    "title" =>$title,
                    "description" =>$description,
                    "keywords" =>$keywords,
                    "og_site_name" =>$og_site_name,
                    "og_title" =>$og_title,
                    "og_description" =>$og_description,
                    "og_image" =>$og_image,
                    "og_url"=>$og_url);
    }

}