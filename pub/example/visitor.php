<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 24.11.2021
 * Time: 16:10
 */
require('../../inc/config.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="assets/bootstrap.min.css">
        <link rel="stylesheet" href="assets/styles.css">
        <link rel="stylesheet" href="assets/widget.css">
        <script src="assets/js.cookie.min.js"></script>
        <script src="assets/autobahn.js"></script>
    </head>
    <body>
        <div class="container" id="app">
            <div class="row">
                <div class="messager_column col-12">
                    <div class="messager_container form-control">
                        <div id="chat_history"></div>
                    </div>
                    <div id="chat_inputbar" class="input-group">
                        <input type="text" id="chat_message" class="form-control"><button type="button" id="chat_send" class="btn btn-primary">Отправить</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            const pub_prefix = '/h/fsc/pub';
            const ws_addr = 'ws://'+'<?=BROKER_URL?>';
            const sid = Cookies.get('PHPSESSID');
        </script>
        <script src="assets/widget.js"></script>
    </body>
</html>
