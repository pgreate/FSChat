let con;

function renderMessage(msg){
    let msg_dir = 'inbox';
    if(msg.manager === 1) msg_dir = 'sent';

    return '<div class="chat_msg '+msg_dir +'">'+msg.text+'</div>';
}

document.addEventListener("DOMContentLoaded",function(){
    window.chat_send.addEventListener("click",function(){

        console.log("SEND");



        fetch(pub_prefix+'/api/'+'chat/'+sid,{
            method:'POST',
            body: window.chat_message.value,
        }).then((r) => r.json()).then((data) => {
            //console.log(data);
            window.chat_message.value = '';
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
                    //window.chat_message.value = '';
                });
            },
            function () {
                console.warn('WebSocket connection closed');
            },
            {'skipSubprotocolCheck': true}
        );

    });
});
