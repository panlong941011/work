$(function(w){



    var copy = function(obj){

        if(typeof obj != "object" || !obj.content) return false;

        var inp = document.createElement("textarea");

        var txt = "";

        var str = obj.content;

        if(typeof str == "string"){

            txt = str;

        }

        if(typeof str == "object"){

            txt = JSON.stringify(str);

        }

        if(typeof str == "function"){

            txt = str.toString();

        }


        inp.value = txt;

        inp.style.width = "1px";

        inp.style.height = "1px";

        document.body.appendChild(inp)

        inp.select();

        if(document.execCommand("Copy")){
            obj.success && typeof obj.success == "function" && obj.success(txt);
        }else{
            obj.error && typeof obj.error == "function" && obj.error();
        }


        document.body.removeChild(inp);

    }


    if(w.jQuery && typeof w.jQuery == "function" && typeof jQuery.prototype.extend == "function"){

        jQuery.fn.extend({

            "copy" : function(obj){

                if(!obj.success || typeof obj.success != "function"){
                    obj.success = new Function();
                }

                if(!obj.error || typeof obj.error != "function"){
                    obj.error = new Function();
                }

                if(obj.selector && !obj.content){

                    $(this).click(function(){

                        var str = $(obj.selector).html() || $(obj.selector).val();

                        copy({
                            content : str,
                            success : obj.success,
                            error : obj.error
                        })

                    })

                }else if(!obj.selector && obj.content){

                    $(this).click(function(){

                        var str = obj.content;

                        copy({
                            content : str,
                            success : obj.success,
                            error : obj.error
                        })

                    })

                }else{

                    return false;

                }

            }
        })

    }


    w.Copy = copy;



})(window);