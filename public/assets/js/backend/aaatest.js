define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'aaatest/index' + location.search,
                    add_url: 'aaatest/add',
                    edit_url: 'aaatest/edit',
                    del_url: 'aaatest/del',
                    multi_url: 'aaatest/multi',
                    import_url: 'aaatest/import',
                    table: 'aaatest',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                fixedColumns: true,
                fixedRightNumber: 1,
                search: false,
                searchFormVisible: true,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'fxk', title: __('Fxk'), searchList: {"复选框a":__('复选框a'),"复选框b":__('复选框b'),"复选框c":__('复选框c')}, operate:'FIND_IN_SET', formatter: Table.api.formatter.label},
                        {field: 'dxk', title: __('Dxk'), searchList: {"单选框a":__('单选框a'),"单选框b":__('单选框b'),"单选框c":__('单选框c'),"单选框d":__('单选框d')}, formatter: Table.api.formatter.normal},
                        {field: 'image', title: __('Image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'file', title: __('File'), operate: false, formatter: Table.api.formatter.file},
                        {field: 'join_time', title: __('Join_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'switch', title: __('Switch'), operate: false, table: table, formatter: Table.api.formatter.toggle},
                        {field: 'cs_city', title: __('Cs_city'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'admin_ids', title: __('Admin_ids'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'weigh', title: __('Weigh'), operate: false},
                        {field: 'bq_tags', title: __('Bq_tags'), operate: false, formatter: Table.api.formatter.flag},
                        {field: 'a_json', title: __('A_json'), operate: false},
                        {field: 'state', title: __('State'), searchList: {"待审核":__('待审核'),"通过":__('通过'),"驳回":__('驳回')}, formatter: Table.api.formatter.normal},
                        {field: 'createtime', title: __('Createtime'), operate: false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate: false, formatter: Table.api.formatter.datetime},
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
                url: 'aaatest/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                search: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'fxk', title: __('Fxk'), searchList: {"复选框a":__('复选框a'),"复选框b":__('复选框b'),"复选框c":__('复选框c')}, operate:'FIND_IN_SET', formatter: Table.api.formatter.label},
                        {field: 'dxk', title: __('Dxk'), searchList: {"单选框a":__('单选框a'),"单选框b":__('单选框b'),"单选框c":__('单选框c'),"单选框d":__('单选框d')}, formatter: Table.api.formatter.normal},
                        {field: 'image', title: __('Image'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'file', title: __('File'), operate: false, formatter: Table.api.formatter.file},
                        {field: 'join_time', title: __('Join_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'switch', title: __('Switch'), operate: false, table: table, formatter: Table.api.formatter.toggle},
                        {field: 'cs_city', title: __('Cs_city'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'admin_ids', title: __('Admin_ids'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'weigh', title: __('Weigh'), operate: false},
                        {field: 'bq_tags', title: __('Bq_tags'), operate: false, formatter: Table.api.formatter.flag},
                        {field: 'a_json', title: __('A_json'), operate: false},
                        {field: 'state', title: __('State'), searchList: {"待审核":__('待审核'),"通过":__('通过'),"驳回":__('驳回')}, formatter: Table.api.formatter.normal},
                        {field: 'createtime', title: __('Createtime'), operate: false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate: false, formatter: Table.api.formatter.datetime},
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
                                    url: 'aaatest/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'aaatest/destroy',
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
