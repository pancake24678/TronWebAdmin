define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            $(".btn-add").data("area", ["48%", "45%"]);

            //添加规则
            $(document).on('click', '.btn-buildindex', function (event) {
                var options = {
                    shadeClose: true,
                    shade: [0.3, '#393D49'],
                    area: ["60%", "80%"],
                    callback: function (value) {
                        CallBackFun(value.id, value.name);
                    }
                };
                Fast.api.open('buiapi/buildindex', '生成接口', options);
            });


            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'buiapi/index' + location.search,
                    add_url: 'buiapi/add',
                    del_url: 'buiapi/del',
                    multi_url: 'buiapi/multi',
                    buildindex_url: 'buiapi/buildindex',
                }
            });

            var table = $("#table");


            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name')},
                        {field: 'desc', title: __('Desc')},
                        {field: 'table', title: __('Table')},
                        {
                            field: 'operate',
                            title: __('Operate'),
                            buttons: [
                                {
                                    name: 'fields',
                                    title: "规则管理",
                                    text: "规则管理",
                                    extend: "data-area='[\"\90%\"\,\"\90%\"\]'",
                                    url: 'buiapi/rulelist?table={table}',
                                    icon: 'fa fa-table',
                                    classname: 'btn btn-info btn-xs btn-execute btn-dialog'
                                },
                            ],
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        rulelist: function () {

            //添加规则
            $(document).on('click', '.btn-add', function (event) {
                var url = $(this).attr('data-url');
                if (!url) return false;
                var msg = $(this).attr('data-title');
                var width = $(this).attr('data-width');
                var height = $(this).attr('data-height');
                var area = [$(window).width() > 800 ? (width ? width : '800px') : '95%', $(window).height() > 600 ? (height ? height : '600px') : '95%'];
                var options = {
                    shadeClose: true,
                    shade: [0.3, '#393D49'],
                    area: area,
                    callback: function (value) {
                        CallBackFun(value.id, value.name);
                    }
                };
                Fast.api.open(url, msg, options);
            });

            //删除标签
            $(".delete").click(function () {
                var id = $(this).attr("data-id");
                var type = $(this).attr("data-type");
                var rule = $(this).attr("data-rule");
                $.get("buiapi/rule_del", {id: id, type: type, rule: rule}, function (result) {
                    if (result.code < 1) {
                        location.reload();
                    } else {
                        Layer.alert(result.msg);
                    }
                });
            });
            //隐藏字段
            $(".hiddens").click(function () {
                var id = $(this).attr("data-id");
                $.get("buiapi/field_hidden?id=" + id, {}, function (result) {
                    if (result.code < 1) {
                        location.reload();
                    } else {
                        Layer.alert(result.msg);
                    }
                });
            });
            $(".btn-refresh").on('click', function () {
                location.reload();
            });

            Controller.api.bindevent();
        },
        ruleadd: function () {
            Controller.api.bindevent();
        },
        buildindex: function () {
            require(['bootstrap-select', 'bootstrap-select-lang']);
            var mainfields = [];
            var relationfields = {};
            var maintable = [];
            var relationtable = [];
            var relationmode = ["belongsto", "hasone"];

            var renderselect = function (select, data) {
                var html = [];
                for (var i = 0; i < data.length; i++) {
                    html.push("<option value='" + data[i] + "'>" + data[i] + "</option>");
                }
                $(select).html(html.join(""));
                select.trigger("change");
                if (select.data("selectpicker")) {
                    select.selectpicker('refresh');
                }
                return select;
            };

            $("select[name=table] option").each(function () {
                maintable.push($(this).val());
            });
            $(document).on('change', "input[name='isrelation']", function () {
                $("#relation-zone").toggleClass("hide", !$(this).prop("checked"));
            });
            $(document).on('change', "select[name='table']", function () {
                var that = this;
                Fast.api.ajax({
                    url: "buiapi/get_field_list",
                    data: {table: $(that).val()},
                }, function (data, ret) {
                    mainfields = data.fieldlist;
                    $("#relation-zone .relation-item").remove();
                    renderselect($("#fields"), mainfields);
                    renderselect($("#searchfields"), mainfields);
                    return false;
                });
                return false;
            });
            $(document).on('click', "a.btn-newrelation", function () {
                var that = this;
                var index = parseInt($(that).data("index")) + 1;
                var content = Template("relationtpl", {index: index});
                var exists = [$("select[name='table']").val()];


                var tableNumber = parseInt($(".relation-item").length) + 1;
                if (tableNumber == maintable.length) {
                    Layer.alert("没有新的关联表可用");
                    return false;
                }

                content = $(content.replace(/\[index\]/, index));
                $(this).data("index", index);
                $(content).insertBefore($(that).closest(".row"));
                $('select', content).selectpicker();


                $("select.relationtable").each(function () {
                    exists.push($(this).val());
                });
                relationtable = [];
                $.each(maintable, function (i, j) {
                    if ($.inArray(j, exists) < 0) {
                        relationtable.push(j);
                    }
                });
                renderselect($("select.relationtable", content), relationtable);
                $("select.relationtable", content).trigger("change");
            });
            $(document).on('click', "a.btn-removerelation", function () {
                $(this).closest(".row").remove();
            });
            $(document).on('change', "#relation-zone select.relationmode", function () {
                var table = $("select.relationtable", $(this).closest(".row")).val();
                var that = this;
                Fast.api.ajax({
                    url: "buiapi/get_field_list",
                    data: {table: table},
                }, function (data, ret) {
                    renderselect($(that).closest(".row").find("select.relationprimarykey"), $(that).val() == 'belongsto' ? data.fieldlist : mainfields);
                    renderselect($(that).closest(".row").find("select.relationforeignkey"), $(that).val() == 'hasone' ? data.fieldlist : mainfields);
                    return false;
                });
            });
            $(document).on('change', "#relation-zone select.relationtable", function () {
                var that = this;
                Fast.api.ajax({
                    url: "buiapi/get_field_list",
                    data: {table: $(that).val()},
                }, function (data, ret) {
                    renderselect($(that).closest(".row").find("select.relationmode"), relationmode);
                    renderselect($(that).closest(".row").find("select.relationfields"), mainfields)
                    renderselect($(that).closest(".row").find("select.relationforeignkey"), data.fieldlist)
                    renderselect($(that).closest(".row").find("select.relationfields"), data.fieldlist)
                    return false;
                });
            });


            $(document).on('click', ".btn-command", function () {
                var form = $(this).closest("form");
                var textarea = $("textarea[rel=command]", form);
                textarea.val('');
                Fast.api.ajax({
                    url: "buiapi/buildcommand",
                    data: form.serialize(),
                }, function (data, ret) {
                    textarea.val(data.command);
                    return false;
                });
            });


            $(document).on('click', ".btn-execute", function () {
                var form = $(this).closest("form");
                var textarea = $("textarea[rel=result]", form);
                textarea.val('');
                Fast.api.ajax({
                    url: "buiapi/execcommand",
                    data: form.serialize(),
                }, function (data, ret) {
                    //console.log(data);
                    textarea.val(data.result);
                    window.parent.$(".toolbar .btn-refresh").trigger('click');
                    top.window.Fast.api.refreshmenu();
                    return false;
                }, function () {
                    window.parent.$(".toolbar .btn-refresh").trigger('click');
                });
            });
            $("select[name='table']").trigger("change");
            Controller.api.bindevent();
        },


        fieldview: function () {
            Controller.api.bindevent();
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});