<?php
namespace Frontend;
use \View;
use \Response;
use \Request;
use \Auth;
use \Input;
use \Session;

class Controller_Index extends Controller_Base{
    public $template = 'template';

    public function before(){
    	parent::before();
    }
    public function action_index(){
        $data = array();
        ini_set('display_errors', true);
        error_reporting(E_ALL);
        if(Input::method() == "POST"){
            $timestamp = date("Ymdhis");
            $start = microtime(true);
            $partName = Input::post('partName'); //
            $sysType = Input::post('sysType');

            $temp = explode(".", $_FILES["predict"]["name"]);
            $newfilename = 'predict'.$timestamp.'.' . end($temp);
            move_uploaded_file($_FILES["predict"]["tmp_name"], APPPATH_USERFILE . $newfilename);
            chmod (APPPATH_USERFILE . $newfilename, "0755");
            $predictFile = APPPATH_USERFILE . $newfilename;

            $realFile ="";
            if(isset($_FILES["real"]["name"]) && $_FILES["real"]["tmp_name"] !=""){
                $fh = fopen($_FILES["real"]["tmp_name"],'r');

                $temp = explode(".", $_FILES["real"]["name"]);
                $newfilename = 'real'.$timestamp.'.' . end($temp);
                move_uploaded_file($_FILES["real"]["tmp_name"], APPPATH_USERFILE . $newfilename);
                chmod (APPPATH_USERFILE . $newfilename, "0755");
                $realFile = " -r ".APPPATH_USERFILE . $newfilename;

                
                $i = 1;
                while ($line = fgets($fh)) {
                  $arrReal[] = array($i,floatval($line));
                  $i++;
                }
                fclose($fh);
                $data['arrReal'] = $arrReal;
            }

            if($sysType == 1){// using data our system
                $listAttri = Input::post('attri');
                $month = Input::post("month");
                $linkMonth = DOCROOT."data_standard/".$month.".csv";       
                $execComd = 'sudo -u khanhkid python '.PYTHONPATH.'source/Collect_alogrithm.py -m '.$linkMonth.' -o '.APPPATH_USERFILE.' -p '.$predictFile.' -c '.implode(",",$listAttri)." -t ".$partName.$realFile;
            }else{// using client database
                $temp = explode(".", $_FILES["training"]["name"]);
                $newfilename = 'train'.$timestamp.'.' . end($temp);
                move_uploaded_file($_FILES["training"]["tmp_name"], APPPATH_USERFILE . $newfilename);
                chmod (APPPATH_USERFILE . $newfilename, "0755");

                $trainingFile = APPPATH_USERFILE . $newfilename;
                $execComd = 'sudo -u khanhkid python '.PYTHONPATH.'source/Collect_alogrithm.py -i '.$trainingFile.' -o '.APPPATH_USERFILE.' -p '.$predictFile." -t ".$partName.$realFile;
            } 
            $linkResult = exec($execComd);
            if($linkResult != ""){
                $linkResult = json_decode($linkResult);
                $arrLink = explode("/", $linkResult[0]);
                $data['fileDownload'] = end($arrLink);
                $fh = fopen($linkResult[0],'r');
                $i = 1;
                while ($line = fgets($fh)) {
                  $arrPredict[] = array($i,floatval($line));
                  $i++;
                }
                fclose($fh);
                $data['arrPredict'] = $arrPredict;

                if(isset($linkResult[1])){
                    $fh = fopen($linkResult[1],'r');
                    while ($line = fgets($fh)) {
                      $indexAlogithm = $line;
                    }
                    fclose($fh);
                    $data['indexAlogithm'] = $indexAlogithm;
                }


                $time_elapsed_secs = microtime(true) - $start;
                $data['time_excu'] = $time_elapsed_secs;
            }            
        }
        $arrAttr = array(
                            'Precipitation' => 1, 
                            'RelativeHumidity' => 2, 
                            'MaxTemperature' => 3, 
                            'Solar' => 4, 
                            'MinTemperature' => 5, 
                            'Wind' => 6, 
                        );
        $data['arrAttr'] = $arrAttr;
        $this->template->meta = $this->metaTag();
        $this->template->content = View::forge('index/index',$data);
    } 
    public function action_dubao(){
        $data = array();
        ini_set('display_errors', true);
        error_reporting(E_ALL);
        if(Input::method() == "POST"){
            $timestamp = date("Ymdhis");
            $start = microtime(true);
            $partName = Input::post('partName'); //
            $sysType = Input::post('sysType');

            $temp = explode(".", $_FILES["predict"]["name"]);
            $newfilename = 'predict'.$timestamp.'.' . end($temp);
            move_uploaded_file($_FILES["predict"]["tmp_name"], APPPATH_USERFILE . $newfilename);
            chmod (APPPATH_USERFILE . $newfilename, "0755");
            $predictFile = APPPATH_USERFILE . $newfilename;

            $realFile ="";
            if(isset($_FILES["real"]["name"]) && $_FILES["real"]["tmp_name"] !=""){
                $fh = fopen($_FILES["real"]["tmp_name"],'r');

                $temp = explode(".", $_FILES["real"]["name"]);
                $newfilename = 'real'.$timestamp.'.' . end($temp);
                move_uploaded_file($_FILES["real"]["tmp_name"], APPPATH_USERFILE . $newfilename);
                chmod (APPPATH_USERFILE . $newfilename, "0755");
                $realFile = " -r ".APPPATH_USERFILE . $newfilename;

                
                $i = 1;
                while ($line = fgets($fh)) {
                  $arrReal[] = array($i,floatval($line));
                  $i++;
                }
                fclose($fh);
                $data['arrReal'] = $arrReal;
            }

            if($sysType == 1){// using data our system
                $listAttri = Input::post('attri');
                $month = Input::post("month");
                $linkMonth = DOCROOT."data_standard/".$month.".csv";       
                $execComd = 'sudo -u khanhkid python '.PYTHONPATH.'source/Collect_alogrithm.py -m '.$linkMonth.' -o '.APPPATH_USERFILE.' -p '.$predictFile.' -c '.implode(",",$listAttri)." -t ".$partName.$realFile;
            }else{// using client database
                $temp = explode(".", $_FILES["training"]["name"]);
                $newfilename = 'train'.$timestamp.'.' . end($temp);
                move_uploaded_file($_FILES["training"]["tmp_name"], APPPATH_USERFILE . $newfilename);
                chmod (APPPATH_USERFILE . $newfilename, "0755");

                $trainingFile = APPPATH_USERFILE . $newfilename;
                $execComd = 'sudo -u khanhkid python '.PYTHONPATH.'source/Collect_alogrithm.py -i '.$trainingFile.' -o '.APPPATH_USERFILE.' -p '.$predictFile." -t ".$partName.$realFile;
            } 
            $linkResult = exec($execComd);
            if($linkResult != ""){
                $linkResult = json_decode($linkResult);
                $arrLink = explode("/", $linkResult[0]);
                $data['fileDownload'] = end($arrLink);
                $fh = fopen($linkResult[0],'r');
                $i = 1;
                while ($line = fgets($fh)) {
                  $arrPredict[] = array($i,floatval($line));
                  $i++;
                }
                fclose($fh);
                $data['arrPredict'] = $arrPredict;

                if(isset($linkResult[1])){
                    $fh = fopen($linkResult[1],'r');
                    while ($line = fgets($fh)) {
                      $indexAlogithm = $line;
                    }
                    fclose($fh);
                    $data['indexAlogithm'] = $indexAlogithm;
                }


                $time_elapsed_secs = microtime(true) - $start;
                $data['time_excu'] = $time_elapsed_secs;
            }            
        }
        $arrAttr = array(
                            'Precipitation' => 1, 
                            'RelativeHumidity' => 2, 
                            'MaxTemperature' => 3, 
                            'Solar' => 4, 
                            'MinTemperature' => 5, 
                            'Wind' => 6, 
                        );
        $data['arrAttr'] = $arrAttr;
        $this->template->meta = $this->metaTag();
        $this->template->content = View::forge('index/dubao',$data);
    } 
    function urlExists($url=NULL)  
    {  
        if($url == NULL) return false;  
        $ch = curl_init($url);  
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);  
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        $data = curl_exec($ch);  
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
        curl_close($ch);  
        if($httpcode>=200 && $httpcode<300){  
            return true;  
        } else {  
            return false;  
        }  
    }  
}
?>