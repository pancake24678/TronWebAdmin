define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'messages/index' + location.search,
                    add_url: 'messages/add',
                    edit_url: 'messages/edit',
                    del_url: 'messages/del',
                    multi_url: 'messages/multi',
                    import_url: 'messages/import',
                    table: 'messages',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                fixedColumns: true,
                fixedRightNumber: 1,
                search: false,
                searchFormVisible: true,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'admin.nickname', title: __('发送人'), operate: 'LIKE'},
                        {field: 'message_type', title: __('Message_type'), searchList: {"普通消息":__('普通消息'),"系统通知":__('系统通知'),"公告":__('公告')}, formatter: Table.api.formatter.normal},
                        {field: 'is_read_all', title: __('Is_read_all'), searchList: {"0":'个人',"1":'全体'}, formatter: Table.api.formatter.normal},
                        {field: 'receive_admins', title: __('接收人'),operate: 'LIKE',class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'content', title: __('Content'), operate: false,class: 'autocontent',formatter: Table.api.formatter.content},
                        {field: 'fj_file', title: __('Fj_file'), operate: false, formatter: Table.api.formatter.file},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
        recyclebin: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'messages/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                search: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'admin.nickname', title: __('发送人'), operate: 'LIKE'},
                        {field: 'is_read_all', title: __('Is_read_all'), searchList: {"0":'个人',"1":'全体'}, formatter: Table.api.formatter.normal},
                        {field: 'receive_admins', title: __('接收人'),operate: 'LIKE', formatter: Table.api.formatter.content},
                        {field: 'message_type', title: __('Message_type'), searchList: {"普通消息":__('普通消息'),"系统通知":__('系统通知'),"公告":__('公告')}, formatter: Table.api.formatter.normal},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'content', title: __('Content'), operate: false},
                        {field: 'fj_file', title: __('Fj_file'), operate: false, formatter: Table.api.formatter.file},
                        {field: 'createtime', title: __('Createtime'), operate: false, formatter: Table.api.formatter.datetime},
                        {
                            field: 'deletetime',
                            title: __('Deletetime'),
                            width: '160px',
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            width: '160px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'messages/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'messages/destroy',
                                    refresh: true
                                }
                            ],
                            formatter: Table.api.formatter.operate
                        }
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
            $('input[name="row[is_read_all]"]').on('change', function() {
                // 获取选中的值
                let selectedValue = $('input[name="row[is_read_all]"]:checked').val();
                if (selectedValue === '1') {
                    // 全体消息，禁用接收人选择框并清空值
                    $('#c-receive_admin_ids').val('');
                    $('#receive_admin_ids').css('display', 'none');
                } else {
                    // 个人消息，启用接收人选择框
                    $('#receive_admin_ids').css('display', 'block');
                }
            });
        },
        edit: function () {
            Controller.api.bindevent();
            $('input[name="row[is_read_all]"]').on('change', function() {
                // 获取选中的值
                let selectedValue = $('input[name="row[is_read_all]"]:checked').val();
                if (selectedValue === '1') {
                    // 全体消息，禁用接收人选择框并清空值
                    $('#c-receive_admin_ids').val('');
                    $('#receive_admin_ids').css('display', 'none');
                } else {
                    // 个人消息，启用接收人选择框
                    $('#receive_admin_ids').css('display', 'block');
                }
            });
            let selectedValue = $('input[name="row[is_read_all]"]:checked').val();
                if (selectedValue === '1') {
                    // 全体消息，禁用接收人选择框并清空值
                    $('#c-receive_admin_ids').val('');
                    $('#receive_admin_ids').css('display', 'none');
                } else {
                    // 个人消息，启用接收人选择框
                    $('#receive_admin_ids').css('display', 'block');
                }
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
