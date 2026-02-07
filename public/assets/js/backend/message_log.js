define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'message_log/index' + location.search,
                    detail_url: 'message_log/detail',
                    read_url: 'message_log/read',
                    del_url: 'message_log/del',
                    table: 'message_log',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'is_read',
                fixedColumns: true,
                fixedRightNumber: 1,
                sortOrder: 'asc',
                search: false,
                searchFormVisible: true,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'messages.message_type', title: __('Messages.message_type'), searchList: {"普通消息":__('普通消息'),"系统通知":__('系统通知'),"公告":__('公告')}, formatter: Table.api.formatter.normal},
                        {field: 'messages.title', title: __('Messages.title'), operate: 'LIKE'},
                        {field: 'messages.content', title: __('Messages.content'), operate: 'LIKE', class:'autocontent', formatter: Table.api.formatter.content},
                        {field: 'messages.fj_file', title: __('Messages.fj_file'), operate: false, formatter: Table.api.formatter.file},
                        {field: 'is_read', title: __('Is_read'), searchList: {"0":'未读',"1":'已读'}, formatter: Table.api.formatter.normal},
                        {field: 'createtime', title: __('发送时间'), operate: false, formatter: Table.api.formatter.datetime},
                        {field: 'read_time', title: __('Read_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {
                            field: 'operate',
                            width: "150px",
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'detail',
                                    title: '查看详情',
                                    classname: 'btn btn-xs btn-info btn-click btn-dialog',
                                    text: '查看详情',
                                    url: $.fn.bootstrapTable.defaults.extend.detail_url,
                                },
                            ],
                            formatter: Table.api.formatter.operate
                        },
                    ]
                ]
            });
            var index = Layer.load(0)
            table.on('common-search.bs.table', function (ret) {
                index = Layer.load(0)
            });
            table.on('refresh.bs.table', function (ret) {
                index = Layer.load(0)
            });
            table.on('load-success.bs.table', function (ret) {
                index && Layer.close(index);
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        detail: function () {
            $("input").attr("disabled", "disabled");
            $("#c-fj_file").attr("disabled", "disabled");
            $("#c-content").attr("disabled", "disabled");
            $("#c-title").attr("disabled", "disabled");
            $("#faupload-fj_file").attr("disabled", "disabled");
            $("#fachoose-fj_file").attr("disabled", "disabled");
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
