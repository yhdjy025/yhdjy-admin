var _table = (function () {

    $('body').on('click', '.table-data .op-btn', function () {
        var op = $(this).attr('data-op');
        var key = $(this).parents('tr').attr('data-key');
        var id= $(this).parents('tr').attr('data-id');
        var params = $(this).data('params');
        var url = $(this).attr('href');
        if (!params) {
            params = {key: key};
            if (id) {
                params.id = id;
            }
        }
        switch (op) {
            case 'edit':
                _table.commonDailog(url, params);
                break;
            case 'link':
                var target = $(this).attr('target');
                url = url + '?' + _table.buildUrl(params);;
                if (target == '_blank') {
                    window.open(url);
                }  else {
                    window.location.href = url;
                }
                break;
            case 'delete':
                var title = $(this).text();
                layer.confirm('确定要'+title+'吗？', function (index) {
                    _table.commonPost(url, {key: key});
                    layer.close(index);
                }, function (index) {
                    layer.close(index);
                    return false;
                })
                break;
        }
        return false;
    });

    $('#table-data').on('click', '.table-link', function () {
        var params = $(this).data('params');
        var url = $(this).attr('href');
        url = url + '?' + _table.buildUrl(params);
        var target = $(this).attr('target');
        if (target == '_blank') {
            window.open(url);
        }  else {
            window.location.href = url;
        }
        return false;
    })

    var clipboard = new ClipboardJS('.copy-text');
    clipboard.on('success', function () {
        layer.msg('复制成功！', {icon: 6, time: 500});
    })

    $('.page-option').on('click', '.add-btn', function () {
        var url = $(this).data('url');
        _table.commonDailog(url, {}, '添加');
    });

    $('.page-option').on('click', '.del-btn', function () {

    })

    return {
        commonPost: function (url, params) {
            $.post(url, params, function (ret) {
                if (200 == ret.code) {
                    layer.msg(ret.message, {icon: 6, shade: 0.3}, function () {
                        window.location.reload();
                    });
                } else {
                    layer.msg(ret.message, {icon: 5, shade: 0.3});
                    return false;
                }
            });
        },
        commonDailog: function (url, params, title) {
            title = title ? title : '编辑';
            var area = ['800px', 'auto'];
            if (window.screen.width < 640) {
                area = ['100%', '100%'];
            }
            $.post(url, params, function (ret) {
                if (200 == ret.code) {
                    layer.open({
                        type: 1,
                        title: title,
                        content: ret.data,
                        btn: ['确定', '取消'],
                        area: area,
                        maxWidth:'100%',
                        yes : function (index) {
                            if (typeof dailogSubmit == 'function') {
                                dailogSubmit();
                            }
                        }
                    });
                } else {
                    layer.msg(ret.message, {icon: 5, shade: 0.3});
                    return false;
                }
            });
        },
        buildUrl(params) {
            var pm = [];
            $.each(params,function (i, v) {
                pm.push(i + '=' + v);
            })
            return pm.join('&');
        }
    };
})()
