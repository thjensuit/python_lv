<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Dashboard</h1>
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
      data.addColumn('number', 'predict');
      <?php 
        if(isset($arrReal)) echo "data.addColumn('number', 'real');"
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
          title: 'Hien Thong Du Bao',
          subtitle: 'Line Chart',
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
                <input type="radio" id="systemFile" name="sysType" value="1" checked> Su dung du lieu he thong 
                <input type="radio" id="clientFile" name="sysType" value="2" <?=(isset($_POST['sysType']) && $_POST['sysType']== 2)?'checked':''?>> Su dung du lieu nguoi dung
            </div>
            <div class="form-group" id="clientPart" <?=(isset($_POST['sysType']) && $_POST['sysType']== 2)?'':'style="display: none"'?>>
                <div id="clientFile_option"><div class="form-group">Training file: <input type="file" name="training" id="training"></div></div>
            </div>
            <div class="form-group" id="systemPart" <?=(isset($_POST['sysType']) && $_POST['sysType']!= 1)?'style="display: none"':''?>>
                <div id="clientFile_option"> 
                    <div class="form-group">
                        <span> Thang: </span>
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
                <div class="form-group" id="attriGroup">
                    <div id="RelativeHumidity"><input class="btn_attri" type="checkbox" name="attri[]" checked value="2"/> [RelativeHumidity]<br></div>
                    <div id="MaxTemperature"><input class="btn_attri" type="checkbox" name="attri[]" checked value="3"/> [MaxTemperature]<br></div>
                    <div id="Solar"><input class="btn_attri" type="checkbox" name="attri[]" checked value="4"/> [Solar]<br></div>
                    <div id="MinTemperature"><input class="btn_attri" type="checkbox" name="attri[]" checked value="5"/> [MinTemperature]<br></div>
                    <div id="Wind"><input class="btn_attri" type="checkbox" name="attri[]" checked value="6"/> [Wind]</div>
                </div>
                <div class="form-group" >
                    <strong><span style="color: red;" id="noteSystem">Bat buoc su dung format csv 'Du Lieu Nguon Du Bao': [RelativeHumidity],[MaxTemperature],[Solar],[MinTemperature],[Wind]</span></strong>
                </div>
            </div>

            <div class="form-group">
                <span> Du bao: </span>
                <select name="partName" id="partName">
                    <?php 
                        foreach ($arrAttr as $key => $value) {
                            $checked = "";
                            if(isset($_POST['monthpartName']) &&  $_POST['partName'] == $i)
                                $checked = (isset($_POST['partName']) && $_POST['partName']== 1)?'selected':'';
                            echo '<option '.$checked.' value="'.$value.'">'.$key.'</option>'; 
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">Du Lieu Nguon Du Bao :<input type="file" name="predict" id="predict"></div>
            <div class="form-group">Real Data (option) :<input type="file" name="real" id="real"></div>
            <input type="submit" value="Run Process" name="submit">
        </form>
    </div>
</div>

<hr>
<p>Time execute: <?=isset($time_excu)?$time_excu:"";?></p>
<?php 
    if (isset($indexAlogithm))
        echo "<p>Indexs of Alogithm:".$indexAlogithm." </p>";
?>
<p>Download Prediction: <?=isset($fileDownload)?'<a href="/userfiles/'.$fileDownload.'" target="_blank">Download</a>':"";?></p>
<div id="linechart_material" style="width: 900px; height: 500px"></div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $("#partName").click(function(event) {
            updateNote($(this).val());
        });
        $("#fr_submit").submit(function(event) {
            sysType = $('input[name=sysType]:checked', '#fr_submit').val();
            message = "";
            if(sysType == 2){
                if($("#training").val()==""){
                    message += "Chon file train\n";
                }
            }
            if($("#predict").val()==""){
                message += "Chon file predict\n";
            }
            if(message != ""){
                alert(message);
                return false;
            }
        });
    });
    $("#systemFile").click(function(event) {
        $("#systemPart").show();
        $("#clientPart").hide();
    });
    $("#clientFile").click(function(event) {
        $("#clientPart").show();
        $("#systemPart").hide();
    });

    function updateNote(partName) {
        switch(partName) {
            case "1":
                showAllAttri();
                $("#Precipitation").remove();
                $("#noteSystem").html("Bat buoc su dung format csv 'Du Lieu Nguon Du Bao': [RelativeHumidity],[MaxTemperature],[Solar],[MinTemperature],[Wind]");
                break;
            case "2":
                showAllAttri();
                $("#MaxTemperature").remove();
                $("#noteSystem").html("Bat buoc su dung format csv 'Du Lieu Nguon Du Bao': [Precipitation],[RelativeHumidity],[Solar],[MinTemperature],[Wind]");
                break;
        }
    }
    function showAllAttri() {
        $("#attriGroup").html('<div id="Precipitation"><input class="btn_attri" type="checkbox" name="attri[]" checked value="1"/> [Precipitation]<br></div>\
                <div id="RelativeHumidity"><input class="btn_attri" type="checkbox" name="attri[]" checked value="2"/> [RelativeHumidity]<br></div>\
                <div id="MaxTemperature"><input class="btn_attri" type="checkbox" name="attri[]" checked value="3"/> [MaxTemperature]<br></div>\
                <div id="Solar"><input class="btn_attri" type="checkbox" name="attri[]" checked value="4"/> [Solar]<br></div>\
                <div id="MinTemperature"><input class="btn_attri" type="checkbox" name="attri[]" checked value="5"/> [MinTemperature]<br></div>\
                <div id="Wind"><input class="btn_attri" type="checkbox" name="attri[]" checked value="6"/> [Wind]</div>');
    }
</script>
