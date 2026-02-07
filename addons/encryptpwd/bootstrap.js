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
