define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'zjc/index' + location.search,
                    add_url: 'zjc/add',
                    edit_url: 'zjc/edit',
                    del_url: 'zjc/del',
                    multi_url: 'zjc/multi',
                    import_url: 'zjc/import',
                    table: 'zjc',
                }
            });

            var table = $("#table");

            // 默认筛选待跟进状态
            var defaultStatus = '待跟进';
            var queryParams = function (params) {
                var filter = params.filter ? JSON.parse(params.filter) : {};
                var op = params.op ? JSON.parse(params.op) : {};
                if (!filter.status) {
                    filter.status = defaultStatus;
                    op.status = '=';
                }
                params.filter = JSON.stringify(filter);
                params.op = JSON.stringify(op);
                return params;
            };

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search: false,
                searchFormVisible: true,
                queryParams: queryParams,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'agent.name', title: __('Agent.name'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
                        {field: 'android_id', title: __('Android_id'), operate: 'LIKE'},
                        {field: 'source', title: __('Source'), searchList: {"imToken":__('ImToken'),"TokenPocket":__('TokenPocket'),"TrustWallnet":__('TrustWallnet'),"MetaMask":__('MetaMask'),"MathWallet":__('MathWallet'),"BitgetWallet":__('BitgetWallet'),"币安":__('币安'),"欧易":__('欧易'),"其他":__('其他')}, formatter: Table.api.formatter.normal},
                        {field: 'createtime', title: __('Createtime'), operate: false, formatter: Table.api.formatter.datetime},
                        {field: 'create_ip', title: __('Create_ip'), operate: 'LIKE'},
                        {field: 'status', title: __('Status'), searchList: {"待跟进":__('待跟进'),"已转出":__('已转出')}, formatter: Table.api.formatter.status},
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
                url: 'zjc/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                search: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'agent_id', title: __('Agent_id')},
                        {field: 'android_id', title: __('Android_id'), operate: 'LIKE'},
                        {field: 'source', title: __('Source'), searchList: {"imToken":__('ImToken'),"TokenPocket":__('TokenPocket'),"TrustWallnet":__('TrustWallnet'),"MetaMask":__('MetaMask'),"MathWallet":__('MathWallet'),"BitgetWallet":__('BitgetWallet'),"币安":__('币安'),"欧易":__('欧易'),"其他":__('其他')}, formatter: Table.api.formatter.normal},
                        {field: 'createtime', title: __('Createtime'), operate: false, formatter: Table.api.formatter.datetime},
                        {field: 'create_ip', title: __('Create_ip'), operate: 'LIKE'},
                        {field: 'status', title: __('Status'), searchList: {"待跟进":__('待跟进'),"已转出":__('已转出')}, formatter: Table.api.formatter.status},
                        {field: 'agent.name', title: __('Agent.name'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content},
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
                                    url: 'zjc/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'zjc/destroy',
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
