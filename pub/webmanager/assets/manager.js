
let con;

let old_chat = null;
let selected_chat = null;

function renderMessage(msg){

    let msg_dir = 'inbox';
    if(msg.manager === 1) msg_dir = 'sent';

    return '<div class="chat_msg '+msg_dir +'">'+msg.text+'</div>';
}
function renderChat(chat){
    return '<a href="#" class="chat_item list-group-item list-group-item-action" data-id="'+chat._id+'">Чат '+chat._id+' ('+chat.totalMessages+')'+'</a>';
}

function switchChatChannel(from,to){
    let from_name = chat_channel_prefix+'_'+from;
    let to_name = chat_channel_prefix+'_'+to;

    if(from) {
        con.unsubscribe(from_name);
    }

    con.subscribe(to_name, function(topic, data) {
        //console.log(data);
        window.chat_history.innerHTML += renderMessage(data);

    });

}

function selectChat(id){

    //save previous chat id
    old_chat = selected_chat;

    //write new chat id
    selected_chat = id;

    //clean history
    window.chat_history.innerHTML = '';

    //load history
    fetch(pub_prefix+'/manager/chat/'+id).then((r) => r.json()).then((d)=> {

        //draw history
        if(d.data){
            d.data.forEach((msg) => {
                window.chat_history.innerHTML += renderMessage(msg);
            });
        }

        //switch chat update channel
        if(!con) {
            //first run - initialize connection
            con = new ab.Session(ws_addr,
                function () {
                    switchChatChannel(old_chat,selected_chat);
                },
                function () {
                    console.warn('WebSocket connection closed');
                },
                {'skipSubprotocolCheck': true}
            );
        }else{
            switchChatChannel(old_chat,selected_chat);
        }

        //set `active` class
        for(let elem of document.getElementsByClassName('chat_item')){
            let cur = elem.dataset.id;
            if(selected_chat === cur){
                if(!elem.classList.contains('active')) elem.classList.add('active');
            }else{
                if(elem.classList.contains('active')) elem.classList.remove('active');
            }
        }

        window.chat_history.scrollTop = window.chat_history.scrollHeight;

    });

}

window.chat_send.addEventListener("click",function(){

    fetch(pub_prefix+'/manager/'+'chat/'+selected_chat,{
        method:'POST',
        body: window.chat_message.value,
    }).then((r) => r.json()).then((data) => {
        //console.log(data);
        window.chat_message.value = '';
        window.chat_history.scrollTop = window.chat_history.scrollHeight;
    });

    return false;
});

document.addEventListener("DOMContentLoaded",function(){
    fetch(pub_prefix+'/manager/chat/list').then((r) => r.json()).then((d)=>{
        if(d.data){
            d.data.forEach((chat) => {
                window.chats_list.innerHTML += renderChat(chat);
            });

        }

        let chat_elements = document.getElementsByClassName('chat_item');
        for(let elem of chat_elements){
            elem.addEventListener('click',function(e){
                let sid = this.dataset.id;
                selectChat(sid);

                e.preventDefault();
                return false;
            });
        }
        if(d.data){
            selectChat(d.data[0]._id);
        }

    });
});