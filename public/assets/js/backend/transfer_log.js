define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'transfer_log/index',
                    add_url: 'transfer_log/add',
                    edit_url: 'transfer_log/edit',
                    del_url: 'transfer_log/del',
                    multi_url: 'transfer_log/multi',
                    import_url: 'transfer_log/import',
                    table: 'transfer_log',
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
                queryParams: function (params) {
                    var search = location.search;
                    if (search && search.length > 1) {
                        var query = new URLSearchParams(search);
                        query.forEach(function (value, key) {
                            params[key] = value;
                        });
                    }
                    return params;
                },
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), operate: false},
                        {field: 'admin.username', title: __('操作人'), operate: 'LIKE'},
                        {field: 'from_address', title: __('From_address'), operate: 'LIKE'},
                        {field: 'to_address', title: __('To_address'), operate: 'LIKE'},
                        {field: 'percent', title: __('Percent'), operate: false},
                        {field: 'amount_human', title: __('金额'), operate: false},
                        {field: 'tx_id', title: __('Tx_id'), operate: false},
                        {field: 'status', title: __('状态'), searchList: {"0":__("失败"),"1":__("成功")}, formatter: Table.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), operate: false, formatter: Table.api.formatter.datetime},
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
        index1: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'transfer_log/index1' + location.search,
                    table: 'transfer_log',
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
                        {field: 'id', title: __('Id')},
                        {field: 'from_address', title: __('From_address'), operate: 'LIKE'},
                        {field: 'to_address', title: __('To_address'), operate: 'LIKE'},
                        {field: 'percent', title: __('Percent'), operate: false},
                        {field: 'amount_human', title: __('金额'), operate: false},
                        {field: 'tx_id', title: __('Tx_id'), operate: false},
                        {field: 'status', title: __('状态'), searchList: {"0":__("失败"),"1":__("成功")}, formatter: Table.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), operate: false, formatter: Table.api.formatter.datetime},
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
                url: 'transfer_log/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                search: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'user_id', title: __('User_id')},
                        {field: 'admin_id', title: __('Admin_id')},
                        {field: 'from_address', title: __('From_address'), operate: 'LIKE'},
                        {field: 'to_address', title: __('To_address'), operate: 'LIKE'},
                        {field: 'percent', title: __('Percent')},
                        {field: 'amount_sun', title: __('Amount_sun')},
                        {field: 'amount_human', title: __('Amount_human'), operate: 'LIKE'},
                        {field: 'decimals', title: __('Decimals')},
                        {field: 'tx_id', title: __('Tx_id'), operate: 'LIKE'},
                        {field: 'result_json', title: __('Result_json'), operate: false},
                        {field: 'status', title: __('Status')},
                        {field: 'createtime', title: __('Createtime'), operate: false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate: false, formatter: Table.api.formatter.datetime},
                        {field: 'admin.username', title: __('Admin.username'), operate: 'LIKE'},
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
                                    url: 'transfer_log/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'transfer_log/destroy',
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
