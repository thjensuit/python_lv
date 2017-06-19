<div id="dhtmlgoodies_xpPane">
    <div class="dhtmlgoodies_panel">
        <div class="menu">
            <a href="/wsroot/order"><i class="fa fa-file-text-o" aria-hidden="true"></i> 受注データ一覧</a>
        </div>
    </div>
    <div class="dhtmlgoodies_panel">
        <div class="menu">
            <a href="/wsroot/product"><i class="fa fa-file-text-o" aria-hidden="true"></i>商品データ一覧</a>
        </div>
    </div>
    <div class="dhtmlgoodies_panel">
        <div class="menu">
            <a href="/wsroot/manageimage"><i class="fa fa-file-text-o" aria-hidden="true"></i> メイン画像一覧</a>
            <a href="/wsroot/designsamplelist"><i class="fa fa-file-text-o" aria-hidden="true"></i> デザインサンプル一覧</a>
        </div>
    </div>
    <div class="dhtmlgoodies_panel">
        <div class="menu">
            <a href="/wsroot/shops"><i class="fa fa-file-text-o" aria-hidden="true"></i> 得意先マスター</a>
            <a href="/wsroot/leadtime"><i class="fa fa-file-text-o" aria-hidden="true"></i> 納期マスター</a>
        </div>
    </div>
    <div class="dhtmlgoodies_panel" id="setuser">
        <div class="menu">
            <a href="/wsroot/users"><i class="fa fa-user" aria-hidden="true"></i> 管理者アカウント</a>
        </div>
    </div>
    <a href="../" target="_blank" class="btn_prv"><i class="fa fa-desktop" aria-hidden="true"></i><span>公開サイトを確認</span></a>
    <a href="/wsroot/logout" class="btn_logout"><i class="fa fa-sign-out" aria-hidden="true"></i><span>ログアウト</span></a>
    <script type="text/javascript">
        //<![CDATA[
        window.onload = function() {initDhtmlgoodies_xpPane(
          Array(
            '<i class="fa fa-folder-open-o" aria-hidden="true"></i> 受注管理',
            '<i class="fa fa-folder-open-o" aria-hidden="true"></i> 商品管理',
            '<i class="fa fa-folder-open-o" aria-hidden="true"></i> ページ管理',
            '<i class="fa fa-folder-open-o" aria-hidden="true"></i> マスター管理',
            ' アカウント管理'
          ),Array(
            true,
            true,
            true,
            true,
            true
          ),Array(
            'pane0',
            'pane1',
            'pane2',
            'pane3',
            'pane_img'
          )
        );
        }
        //]]>
    </script>
</div>