<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Trang chủ</h1>
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
                <input type="radio" id="systemFile" name="sysType" value="1" checked> Sử dụng dữ liệu hệ thống 
                <input type="radio" id="clientFile" name="sysType" value="2" <?=(isset($_POST['sysType']) && $_POST['sysType']== 2)?'checked':''?>> Sử dụng dữ liệu cá nhân
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
                <div class="form-group" >
                    <strong><span style="color: red;" id="noteSystem">Các yếu tố hỗ trợ dự báo:</span></strong>
                </div>
                <div class="form-group" id="attriGroup">
                    <div id="RelativeHumidity"><input class="btn_attri" type="checkbox" name="attri[]" checked value="2"/> [Độ ẩm]<br></div>
                    <div id="MinTemperature"><input class="btn_attri" type="checkbox" name="attri[]" checked value="5"/> [Nhiệt độ nhỏ nhất]<br></div>
                    <div id="Solar"><input class="btn_attri" type="checkbox" name="attri[]" checked value="4"/> [Năng lượng mặt trời]<br></div>
                    <div id="MaxTemperature"><input class="btn_attri" type="checkbox" name="attri[]" checked value="3"/> [Nhiệt độ cao nhất]<br></div>
                    <div id="Wind"><input class="btn_attri" type="checkbox" name="attri[]" checked value="6"/> [Năng lượng gió]</div>
                </div>
                <div class="form-group" >
                    <strong><span style="color: red;" id="noteSystem">Thứ tự vị trí các cột trong file csv: [Độ ẩm],[Nhiệt độ cao nhất],[Năng lượng mặt trời],[Nhiệt độ thấp nhất],[Năng lượng gió]</span></strong>
                </div>
            </div>

            <div class="form-group">
                <span> Dự báo: </span>
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
            <div class="form-group">Dữ liệu dự báo :<input type="file" name="predict" id="predict"></div>
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
<p>Download kết quả dự báp: <?=isset($fileDownload)?'<a href="/userfiles/'.$fileDownload.'" target="_blank">Download</a>':"";?></p>
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
        showAllAttri();
        switch(partName) {
            case "1":
            	showAllAttri([2,5,4,3,6])
                $("#noteSystem").html("Thứ tự vị trí các cột trong file csv dữ liệu dự báo: [Relative Humidity,Min Temperature,Solar,Max Temperature,Wind]");
                break;
            case "2":
            	showAllAttri([4,6,3,5,1])
                $("#noteSystem").html("Thứ tự vị trí các cột trong file csv dữ liệu dự báo:  [Solar,Wind,Max Temperature,Min Temperature,Precipitation]");
            case "3":
            	showAllAttri([4,5,2,6,1])
                $("#noteSystem").html("Thứ tự vị trí các cột trong file csv dữ liệu dự báo: [Solar, Min Temperature, Relative Humidity, Wind, Precipitation]");
                break;
            case "4":
            	showAllAttri([3,2,1,5,6])
                $("#noteSystem").html("Thứ tự vị trí các cột trong file csv dữ liệu dự báo: [Max Temperature,Relative Humidity,Precipitation,Min Temperature,Wind]");
                break;
            case "5":
            	showAllAttri([4,3,1,2,6])
                $("#noteSystem").html("Thứ tự vị trí các cột trong file csv dữ liệu dự báo: [Solar,Max Temperature,Precipitation,Relative Humidity,Wind]");
                break;
            case "6":
            	showAllAttri([4,3,1,6,2])
                $("#noteSystem").html("Thứ tự vị trí các cột trong file csv dữ liệu dự báo: [Solar,Max Temperature,Precipitation,Min Temperature,Relative Humidity]");
                break;
        }
    }
    function showAllAttri(arrRelated) {
    	var strInput = "";
    	for (var i = 0; i < arrRelated.length; i++) {
    		switch(arrRelated[i]) {
			    case 1:
			        strInput += '<div id="Precipitation"><input class="btn_attri" type="checkbox" name="attri[]" checked value="1"/> [Lượng mưa]<br></div>';
			        break;
			    case 2:
			        strInput += '<div id="RelativeHumidity"><input class="btn_attri" type="checkbox" name="attri[]" checked value="2"/> [Độ ẩm]<br></div>';
			    case 3:
			        strInput += '<div id="MaxTemperature"><input class="btn_attri" type="checkbox" name="attri[]" checked value="3"/> [Nhiệt độ cao nhất]<br></div>';
			    case 4:
			        strInput += '<div id="Solar"><input class="btn_attri" type="checkbox" name="attri[]" checked value="4"/> [Năng lượng mặt trời]<br></div>';
			    case 5:
			        strInput += '<div id="MinTemperature"><input class="btn_attri" type="checkbox" name="attri[]" checked value="5"/> [Nhiệt độ nhỏ nhất]<br></div>';
			    case 6:
			        strInput += '<div id="Wind"><input class="btn_attri" type="checkbox" name="attri[]" checked value="6"/> [Năng lượng gió]</div>';
			        break;
			}
    	}
        $("#attriGroup").html(strInput);
    }
</script>
