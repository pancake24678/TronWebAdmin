define([], function () {
    require([], function () {
    //绑定data-toggle=addresspicker属性点击事件

    $(document).on('click', "[data-toggle='addresspicker']", function () {
        var that = this;
        var callback = $(that).data('callback');
        var input_id = $(that).data("input-id") ? $(that).data("input-id") : "";
        var lat_id = $(that).data("lat-id") ? $(that).data("lat-id") : "";
        var lng_id = $(that).data("lng-id") ? $(that).data("lng-id") : "";
        var zoom_id = $(that).data("zoom-id") ? $(that).data("zoom-id") : "";
        var lat = lat_id ? $("#" + lat_id).val() : '';
        var lng = lng_id ? $("#" + lng_id).val() : '';
        var zoom = zoom_id ? $("#" + zoom_id).val() : '';
        var url = "/addons/address/index/select";
        url += (lat && lng) ? '?lat=' + lat + '&lng=' + lng + (input_id ? "&address=" + $("#" + input_id).val() : "") + (zoom ? "&zoom=" + zoom : "") : '';
        Fast.api.open(url, '位置选择', {
            callback: function (res) {
                input_id && $("#" + input_id).val(res.address).trigger("change");
                lat_id && $("#" + lat_id).val(res.lat).trigger("change");
                lng_id && $("#" + lng_id).val(res.lng).trigger("change");
                zoom_id && $("#" + zoom_id).val(res.zoom).trigger("change");

                try {
                    //执行回调函数
                    if (typeof callback === 'function') {
                        callback.call(that, res);
                    }
                } catch (e) {

                }
            }
        });
    });
});

require(['fast', 'layer'], function (Fast, Layer) {
    var _fastOpen = Fast.api.open;
    Fast.api.open = function (url, title, options) {
        options = options || {};
        options.area = Config.betterform.area;
        options.offset = Config.betterform.offset;
        options.anim = Config.betterform.anim;
        options.shadeClose = Config.betterform.shadeClose;
        options.shade = Config.betterform.shade;
        return _fastOpen(url, title, options);
    };
    if (isNaN(Config.betterform.anim)) {
        var _layerOpen = Layer.open;
        Layer.open = function (options) {
            var classNameArr = {slideDown: "layer-anim-slide-down", slideLeft: "layer-anim-slide-left", slideUp: "layer-anim-slide-up", slideRight: "layer-anim-slide-right"};
            var animClass = "layer-anim " + classNameArr[options.anim] || "layer-anim-fadein";
            var index = _layerOpen(options);
            var layero = $('#layui-layer' + index);

            layero.addClass(classNameArr[options.anim] + "-custom");
            layero.addClass(animClass).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                $(this).removeClass(animClass);
            });
            return index;
        }
    }
});
require(['../addons/bootstrapcontextmenu/js/bootstrap-contextmenu'], function (undefined) {
    if (Config.controllername == 'index' && Config.actionname == 'index') {
        $("body").append(
            '<div id="context-menu">' +
            '<ul class="dropdown-menu" role="menu">' +
            '<li><a tabindex="-1" data-operate="refresh"><i class="fa fa-refresh fa-fw"></i>刷新</a></li>' +
            '<li><a tabindex="-1" data-operate="refreshTable"><i class="fa fa-table fa-fw"></i>刷新表格</a></li>' +
            '<li><a tabindex="-1" data-operate="close"><i class="fa fa-close fa-fw"></i>关闭</a></li>' +
            '<li><a tabindex="-1" data-operate="closeOther"><i class="fa fa-window-close-o fa-fw"></i>关闭其他</a></li>' +
            '<li class="divider"></li>' +
            '<li><a tabindex="-1" data-operate="closeAll"><i class="fa fa-power-off fa-fw"></i>关闭全部</a></li>' +
            '</ul>' +
            '</div>');

        $(".nav-addtabs").contextmenu({
            target: "#context-menu",
            scopes: 'li[role=presentation]',
            onItem: function (e, event) {
                var $element = $(event.target);
                var tab_id = e.attr('id');
                var id = tab_id.substr('tab_'.length);
                var con_id = 'con_' + id;
                switch ($element.data('operate')) {
                    case 'refresh':
                        $("#" + con_id + " iframe").attr('src', function (i, val) {
                            return val;
                        });
                        break;
                    case 'refreshTable':
                        try {
                            if ($("#" + con_id + " iframe").contents().find(".btn-refresh").size() > 0) {
                                $("#" + con_id + " iframe")[0].contentWindow.$(".btn-refresh").trigger("click");
                            }
                        } catch (e) {

                        }
                        break;
                    case 'close':
                        if (e.find(".close-tab").length > 0) {
                            e.find(".close-tab").click();
                        }
                        break;
                    case 'closeOther':
                        e.parent().find("li[role='presentation']").each(function () {
                            if ($(this).attr('id') == tab_id) {
                                return;
                            }
                            if ($(this).find(".close-tab").length > 0) {
                                $(this).find(".close-tab").click();
                            }
                        });
                        break;
                    case 'closeAll':
                        e.parent().find("li[role='presentation']").each(function () {
                            if ($(this).find(".close-tab").length > 0) {
                                $(this).find(".close-tab").click();
                            }
                        });
                        break;
                    default:
                        break;
                }
            }
        });
    }
    $(document).on('click', function () { // iframe内点击 隐藏菜单
        try {
            top.window.$(".nav-addtabs").contextmenu("closemenu");
        } catch (e) {
        }
    });

});
require.config({
    paths: {
        'encryptpwd-cryptojs': '../addons/encryptpwd/js/crypto-js.min',
    },
    shim: {}
});
require(['form'], function (Form) {

    if (!Config.encryptpwd.state || !Config.encryptpwd.selector || !Config.encryptpwd.key || Config.encryptpwd.excluded) {
        return;
    }

    var _bindevent = Form.events.bindevent;
    Form.events.bindevent = function (form) {
        $(Config.encryptpwd.selector, form).each(function () {
            var $this = $(this);
            $this.attr("data-rule", "required");
        });

        _bindevent.apply(this, [form]);
    };

    var _submit = Form.api.submit;
    Form.api.submit = function (form, success, error, submit) {
        if ($(Config.encryptpwd.selector, form).length > 0) {
            require(['encryptpwd-cryptojs'], function (CryptoJS) {

                var fields = [];
                var ivArr = [];
                var nameArr = [];

                $(Config.encryptpwd.selector || 'input[type=password]', form).each(function () {
                    var $this = $(this);

                    var randomIv = CryptoJS.lib.WordArray.random(16 / 2).toString(CryptoJS.enc.Hex);
                    var encryptData = CryptoJS.AES.encrypt($(this).val(), CryptoJS.enc.Utf8.parse(Config.encryptpwd.key), {
                        mode: CryptoJS.mode.CBC,
                        iv: CryptoJS.enc.Utf8.parse(randomIv),
                        padding: CryptoJS.pad.Pkcs7
                    });

                    ivArr.push(randomIv);
                    $this.val(encryptData.toString());
                    nameArr.push($(this).attr("name"));
                    fields.push($(this));
                });
                form.append($("<input />").attr("type", "hidden").val(btoa(nameArr.join(","))).attr("name", "encryptpwdFields"));
                form.append($("<input />").attr("type", "hidden").val(btoa(ivArr.join(","))).attr("name", "encryptpwdIv"));

                _submit.apply(this, [form, success, function (data, ret, msg) {
                    $("input[name='encryptpwdFields']", form).remove();
                    $("input[name='encryptpwdIv']", form).remove();
                    fields.forEach(function (field) {
                        field.val('');
                    });
                    return error.apply(this, [data, ret, msg]);
                }, submit]);
            });
        } else {
            _submit.apply(this, [form, success, error, submit]);
        }
    }
});

require.config({
    paths: {
        'summernote': '../addons/summernote/lang/summernote-zh-CN.min',
        'purify': '../addons/summernote/js/purify.min'
    },
    shim: {
        'summernote': ['../addons/summernote/js/summernote.min', 'css!../addons/summernote/css/summernote.min.css'],
    }
});
require(['form', 'upload'], function (Form, Upload) {
    var _bindevent = Form.events.bindevent;
    Form.events.bindevent = function (form) {
        _bindevent.apply(this, [form]);
        try {
            //绑定summernote事件
            if ($(Config.summernote.classname || '.editor', form).length > 0) {
                var selectUrl = typeof Config !== 'undefined' && Config.modulename === 'index' ? 'user/attachment' : 'general/attachment/select';
                require(['summernote', 'purify'], function (undefined, DOMPurify) {
                    var imageButton = function (context) {
                        var ui = $.summernote.ui;
                        var button = ui.button({
                            contents: '<i class="fa fa-file-image-o"/>',
                            tooltip: __('Choose'),
                            click: function () {
                                parent.Fast.api.open(selectUrl + "?element_id=&multiple=true&mimetype=image/", __('Choose'), {
                                    callback: function (data) {
                                        var urlArr = data.url.split(/\,/);
                                        $.each(urlArr, function () {
                                            var url = Fast.api.cdnurl(this, true);
                                            context.invoke('editor.insertImage', url);
                                        });
                                    }
                                });
                                return false;
                            }
                        });
                        return button.render();
                    };
                    var attachmentButton = function (context) {
                        var ui = $.summernote.ui;
                        var button = ui.button({
                            contents: '<i class="fa fa-file"/>',
                            tooltip: __('Choose'),
                            click: function () {
                                parent.Fast.api.open(selectUrl + "?element_id=&multiple=true&mimetype=*", __('Choose'), {
                                    callback: function (data) {
                                        var urlArr = data.url.split(/\,/);
                                        $.each(urlArr, function () {
                                            var url = Fast.api.cdnurl(this, true);
                                            var node = $("<a href='" + url + "'>" + url + "</a>");
                                            context.invoke('insertNode', node[0]);
                                        });
                                    }
                                });
                                return false;
                            }
                        });
                        return button.render();
                    };
                    if (Config.summernote.isdompurify) {
                        // 添加 hook 过滤 iframe 来源
                        DOMPurify.addHook('uponSanitizeElement', function (node, data, config) {
                            if (data.tagName === 'iframe') {
                                var allowedIframePrefixes = Config.nkeditor.allowiframeprefixs || [];
                                var src = node.getAttribute('src');

                                // 判断是否匹配允许的前缀
                                var isAllowed = false;
                                for (var i = 0; i < allowedIframePrefixes.length; i++) {
                                    if (src && src.indexOf(allowedIframePrefixes[i]) === 0) {
                                        isAllowed = true;
                                        break;
                                    }
                                }

                                if (!isAllowed) {
                                    // 不符合要求则移除该节点
                                    return node.parentNode.removeChild(node);
                                }

                                // 添加安全属性
                                node.setAttribute('allowfullscreen', '');
                                node.setAttribute('allow', 'fullscreen');
                            }
                        });

                        var purifyOptions = {
                            ADD_TAGS: ['iframe'],
                            FORCE_REJECT_IFRAME: false
                        };
                        $.extend($.summernote.plugins, {
                            'dompurify': function (context) {
                                // 重写代码过滤方法
                                const originalPurify = context.options.modules.codeview.prototype.purify;
                                context.options.modules.codeview.prototype.purify = function (html) {
                                    html = DOMPurify.sanitize(html, purifyOptions);
                                    return originalPurify.call(this, html);
                                };
                            }
                        });
                    }

                    $(Config.summernote.classname || '.editor', form).each(function () {
                        $(this).summernote($.extend(true, {}, {
                            height: isNaN(Config.summernote.height) ? null : parseInt(Config.summernote.height),
                            minHeight: parseInt(Config.summernote.minHeight || 250),
                            toolbar: Config.summernote.toolbar,
                            followingToolbar: parseInt(Config.summernote.followingToolbar),
                            placeholder: Config.summernote.placeholder || '',
                            airMode: parseInt(Config.summernote.airMode) || false,
                            lang: 'zh-CN',
                            fontNames: Config.summernote.fontNames || [],
                            fontNamesIgnoreCheck: ["Open Sans", "Microsoft YaHei", '微软雅黑', '宋体', '黑体', '仿宋', '楷体', '幼圆'],
                            buttons: {
                                image: imageButton,
                                attachment: attachmentButton,
                            },
                            plugins: {
                                'dompurify': true
                            },
                            dialogsInBody: true,
                            callbacks: {
                                onChange: function (contents) {
                                    if (Config.summernote.isdompurify) {
                                        contents = DOMPurify.sanitize(contents, purifyOptions);
                                    }
                                    $(this).val(contents);
                                    $(this).trigger('change');
                                },
                                onInit: function () {
                                },
                                onImageUpload: function (files) {
                                    var that = this;
                                    //依次上传图片
                                    for (var i = 0; i < files.length; i++) {
                                        Upload.api.send(files[i], function (data) {
                                            var url = Fast.api.cdnurl(data.url, true);
                                            $(that).summernote("insertImage", url, 'filename');
                                        });
                                    }
                                },
                                onPaste: function (e) {
                                    if (Config.summernote.pasteAsPlainText || false) {
                                        var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                                        e.preventDefault();
                                        setTimeout(function () {
                                            document.execCommand('insertText', false, bufferText);
                                        }, 10);
                                    }
                                }
                            }
                        }, $(this).data("summernote-options") || {}));
                    });
                });
            }
        } catch (e) {

        }

    };
});

});