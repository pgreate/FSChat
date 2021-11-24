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
    <link rel="stylesheet" href="assets/styles.css">
    <script src="assets/js.cookie.min.js"></script>
    <script src="assets/autobahn.js"></script>
</head>
<body>
    <div class="container">
        <textarea rows="50" id="chat_history"></textarea>
        <input type="text" id="chat_message"><button type="button" id="chat_send">Отправить</button>
    </div>
<script>
    const pub_prefix = '/h/fsc/pub';
    const ws_addr = 'ws://'+'<?=BROKER_URL?>';
    const sid = Cookies.get('PHPSESSID');

    let con;

    function renderMessage(msg){
        return msg.sid+":"+msg.text+"\n";
    }

    document.addEventListener("DOMContentLoaded",function(){
        window.chat_send.addEventListener("click",function(){

           console.log("SEND");



           fetch(pub_prefix+'/api/'+'chat/'+sid,{
               method:'POST',
               body: window.chat_message.value,
           }).then((r) => r.json()).then((data) => {
               console.log(data);
           });

           return false;
        });

        fetch(pub_prefix+'/api/'+'chat/'+sid).then((r) => r.json()).then((d) => {

            if(d.data){
                d.data.forEach((msg) => {
                    window.chat_history.innerHTML += renderMessage(msg);
                });
            }

            let chat_id = '<?=CHAT_CHANNEL_PREFIX?>'+'_'+sid;

            con = new ab.Session(ws_addr,
                function () {
                    con.subscribe(chat_id, function(topic, data) {
                        //console.log(data);
                        window.chat_history.innerHTML += renderMessage(data);
                        window.chat_message.value = '';
                    });
                },
                function () {
                    console.warn('WebSocket connection closed');
                },
                {'skipSubprotocolCheck': true}
            );

        });
    });
</script>
</body>
</html>
