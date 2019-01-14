}catch(err){
    var hiweb_theme_rest_already_loaded;
    ///If jQuery is not defined, load default WP jQuery Library
    if(err.message.indexOf('jQuery is not defined') === 0){
        let script = document.createElement('script');
        script.type = "text/javascript";
        script.src = "{HIWEB_THEME_VENDORS_URL}/jquery3/jquery-3.3.1.min.js";
        script.defer = 'defer';
        script.onload = function () {
            if(!hiweb_theme_rest_already_loaded) {
                let scripts= document.getElementsByTagName('script');
                let script = document.createElement('script');
                script.type = "text/javascript";
                script.src = scripts[scripts.length-1].src;
                script.defer = 'defer';
                document.getElementsByTagName('head')[0].appendChild(script);
                hiweb_theme_rest_already_loaded = true;
            }
        };
        document.getElementsByTagName('head')[0].appendChild(script);
    }

}