<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 25.11.2021
 * Time: 16:35
 */
require('../../inc/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="../example/assets/bootstrap.min.css">
    <link rel="stylesheet" href="assets/styles.css">
	<!--<script src="assets/bootstrap.min.js"></script>-->
    <script src="assets/autobahn.js"></script>
    <title>FSC WebManager</title>
</head>
<body>
    <div class="container" id="app">
        <div class="row">
            <div class="chats_column col-3">
                <div id="chats_list" class="list-group" aria-orientation="vertical"></div>
            </div>
            <div class="messager_column col-9">
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
        const ws_addr = 'ws://<?=BROKER_URL?>';
        const chat_channel_prefix = '<?=CHAT_CHANNEL_PREFIX?>';
    </script>
    <script src="assets/manager.js"> </script>
</body>
</html>
