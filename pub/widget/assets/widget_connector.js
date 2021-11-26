function fsc_widget_connect(d,u){

        let widget_holder = d.createElement('div');
        widget_holder.classList.add('fsc_widget');

        let widget_head = d.createElement('div');
        widget_head.classList.add('fsc_widget_head');
        widget_head.innerText = 'Напишите нам';

        widget_head.addEventListener('click',function(){
            if(widget_holder.classList.contains('fsc_collapsed')){
                widget_holder.classList.remove('fsc_collapsed');
            }
        });

        let widget_body = d.createElement('div');
        widget_body.classList.add('fsc_widget_body');

        let widget_iframe = d.createElement('iframe');
        widget_iframe.src = u;
        widget_body.append(widget_iframe);



        let widget_close = d.createElement('div');
        widget_close.classList.add('fsc_widget_close');
        widget_close.innerText = 'X';
        widget_close.addEventListener('click',function(){
            widget_holder.classList.add('fsc_collapsed');
        });

        widget_holder.append(widget_close);
        widget_holder.append(widget_head);
        widget_holder.append(widget_body);

        d.body.append(widget_holder);

        let widget_style = d.createElement('link');
        widget_style.rel = "stylesheet";
        widget_style.href = u+'assets/widget_connector.css';
        d.head.append(widget_style);
}