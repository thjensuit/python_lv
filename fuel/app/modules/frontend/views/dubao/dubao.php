<div class="row">
    <div class="col-lg-12">
        <img src="/assets/img/banner.png">
    </div>
    <!-- /.col-lg-12 -->
</div>

<?php
    if(isset($arrPredict)){
?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
       google.charts.load('current', {'packages':['line']});
      google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('string', 'No');
      data.addColumn('number', 'Dự báo');
      <?php 
        if(isset($arrReal)) echo "data.addColumn('number', 'Thực đo');"
      ?>

      data.addRows(
          [

      <?php
      foreach ($arrPredict as $key => $value) {
            if(isset($arrReal))
                echo "['" . $value[0] . "'," . $value[1] . "," . $arrReal[$key][1] . "],";
            else
                echo "['" . $value[0] . "'," . $value[1] . "],";
      } 
      ?>
      ]);
      var options = {
        chart: {
          title: 'Kết quả dự báo', 
          subtitle: 'Biểu đồ',
          "hAxis":{"title":"Date",showTextEvery:1},
        },
        width: 1000,
        height: 500
      };

      var chart = new google.charts.Line(document.getElementById('linechart_material'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }
    </script>
<?php }?>
<!-- /.row -->
<div class="row">
    <div class="col-lg-6 col-md-6">
        <form method="post" id="fr_submit" enctype="multipart/form-data" >
            <div class="form-group">
                <input type="radio" id="systemFile" name="sysType" value="1" checked> Sử dụng dữ liệu CFSR 
                <!-- <input type="radio" id="clientFile" name="sysType" value="2" <?=(isset($_POST['sysType']) && $_POST['sysType']== 2)?'checked':''?>> Sử dụng dữ liệu thực đo -->
            </div>
            <div class="form-group" id="clientPart" <?=(isset($_POST['sysType']) && $_POST['sysType']== 2)?'':'style="display: none"'?>>
                <div id="clientFile_option"><div class="form-group">File huấn luyện: <input type="file" name="training" id="training"></div></div>
            </div>
            <div class="form-group" id="systemPart" <?=(isset($_POST['sysType']) && $_POST['sysType']!= 1)?'style="display: none"':''?>>
                <div id="clientFile_option"> 
                    <div class="form-group">
                        <span> Dự báo trong tháng: </span>
                        <select name="month">
                            <?php 
                                for ($i=1; $i <13 ; $i++) { 
                                    $checked = "";
                                    if(isset($_POST['month']) &&  $_POST['month'] == $i)
                                        $checked = ((isset($_POST['month']) && $_POST['month']== $i))?'selected':'';
                                    echo '<option '.$checked.' value="'.$i.'">'.$i.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <span> Dự báo: </span>
                <select name="partName" id="partName">
                    <?php 
                        $arrKeyName = array(
                                        'Precipitation' => 'Lượng mưa', 
                                        'RelativeHumidity' => 'Độ ẩm', 
                                        'MaxTemperature' => 'Nhiệt độ cao nhất', 
                                        'Solar' => 'Năng lượng mặt trời', 
                                        'MinTemperature' => 'Nhiệt độ thấp nhất', 
                                        'Wind' => 'Gió', 
                                    );
                        foreach ($arrAttr as $key => $value) {
                            $checked = "";
                            $checked = (isset($_POST['partName']) && $_POST['partName']== $value)?'selected':'';
                            echo '<option '.$checked.' value="'.$value.'">'.$arrKeyName[$key].'</option>'; 
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">Số ngày dự báo : <input type="number" name="predict" id="predict" value="<?=(isset($_POST['predict']))?$_POST['predict']:'30'?>"></div>
            <div class="form-group">Dự liệu thực đo (nếu có) <i>* Dùng để so sánh với thực tế</i>:<input type="file" name="real" id="real"></div>
            <input type="submit" value="Tiến hành" name="submit">
        </form>
    </div>
</div>

<hr>
<p>Thời gian thực thi: <?=isset($time_excu)?$time_excu:"";?></p>
<?php 
    if (isset($indexAlogithm))
        echo "<p>Các chỉ số đánh giá thuật toán:".$indexAlogithm." </p>";
?>
<p>Download kết quả dự báo: <?=isset($fileDownload)?'<a href="/userfiles/'.$fileDownload.'" target="_blank">Download</a>':"";?></p>
<div id="linechart_material" style="width: 900px; height: 500px"></div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $("#fr_submit").submit(function(event) {
            sysType = $('input[name=sysType]:checked', '#fr_submit').val();
            message = "";
            if(sysType == 2){
                if($("#training").val()==""){
                    message += "Chọn file huấn luyện\n";
                }
            }
            if($("#predict").val()==""){
                message += "Chọn file dự báo\n";
            }
            if(message != ""){
                alert(message);
                return false;
            }
        });
        $("#systemFile").click(function(event) {
            $("#systemPart").show();
            $("#clientPart").hide();
        });
        $("#clientFile").click(function(event) {
            $("#clientPart").show();
            $("#systemPart").hide();
        });
    });
</script>
