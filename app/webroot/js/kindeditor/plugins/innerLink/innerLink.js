KindEditor.plugin('innerLink', function(K) {
    var self = this, dialog = null, name = 'innerLink';
    self.plugin.innerLink = {
        select_options:null,
        requestOptions : function(){
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp=new XMLHttpRequest();
            } else {
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange=function() {
                if (xmlhttp.readyState==4 && xmlhttp.status==200) {
                    // alert(xmlhttp.responseText);
                    try {
                        eval("self.select_options = "+xmlhttp.responseText);
                    } catch(e) {
                        self.select_options = null;
                    }
                }
            }
            xmlhttp.open("GET", "/MediaPages/ajaxKeInnerLink/" + media_ad_id, false);
            xmlhttp.send();
        },
        edit : function() {
            self.plugin.innerLink.requestOptions();
            if (self.select_options==null) {
                return;
            }
            var html_opts = '';
            for(id in self.select_options) {
                html_opts+='<option value="/MediaApp/page/'+id+'">'+self.select_options[id]+'</option>'
            }
            var html = '<div style="padding:20px;"><div class="ke-dialog-row"><label for="keInnerUrl" style="width:60px;">页面</label><select id="keInnerUrl" name="url">'+html_opts+'</select></div></div>',
            dialog = self.createDialog({
                name : name,
                width : 450,
                title : self.lang(name),
                body : html,
                yesBtn : {
                    name : self.lang('yes'),
                    click : function(e) {
                        var url = K.trim(urlBox.val());
                        self.exec('createlink', url).hideDialog().focus();
                    }
                }
            }),
            div = dialog.div,
            urlBox = K('select[name="url"]', div);
            self.cmd.selection();
            var a = self.plugin.getSelectedLink();
            if (a) {
                self.cmd.range.selectNode(a[0]);
                self.cmd.select();
                var url = a.attr('href');
                for (i in urlBox[0].options) {
                    if (urlBox[0].options[i].value == url) {
                        urlBox[0].options[i].selected = true;
                    }
                }
            }
            urlBox[0].focus();
        },
        'delete' : function() {
            self.exec('unlink', null);
        }
    };
    self.clickToolbar(name, self.plugin.innerLink.edit);
});

