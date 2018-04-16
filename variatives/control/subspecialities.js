require(['jquery'], function ($) {

    $(window).load(function () {

        window.tableform = {};
        
        var subspectable = document.getElementById('subspectable');

        window.tableform.deleteSubSpec = function (id) {
            $.ajax({
                url: 'ajax.php',
                data: {op: 'subspecialitydelete', id: id},
                method: 'POST',
                dataType: 'json'
            }).done(function (reply) {
                for (var i = 0; i < window.tableform.data.length; i++) {
                    if (window.tableform.data[i].id == id) {
                        window.tableform.data.splice(i, 1);
                    }
                }
                window.tableform.handsontable.view.settings.maxRows = window.tableform.data.length;
                window.tableform.handsontable.loadData(window.tableform.data)
            });
        };
        


        window.tableform.autosaveNotification = false;
        window.tableform.loadData = false;


        window.tableform.data = [];
        for (var id in list) {
            // list[id].vardepartmentvisible=(list[id].vardepartmentvisible==1);
            // list[id].vardepartmentobsolete=(list[id].vardepartmentobsolete==1);
            list[id].action = '';
            list[id].action += "<a title=\"" + i18n['DeletesubSpeciality'] + "\" href=\"javascript:void(window.tableform.deleteSubSpec(" + id + "))\"><img src=\"../resources/icon_delete.png\"></a>";

            window.tableform.data.push(list[id]);
        }

        window.tableform.handsontable = new Handsontable(subspectable, {
            data: window.tableform.data,
            minSpareRows: 1,
            contextMenu: true,
            colHeaders: colHeaders,
            columns: [
                {data: 'action', readOnly: true, renderer: "html"},
                {data: 'id', editor: false},
                {data: 'varsubspecialitytitle'},
                {data: 'varsubspecialityurl'}
            ],
            afterChange: function (change, source) {
                if (source === 'loadData') {
                    return; //don't post this change
                }
                if (window.tableform.loadData) {
                    return; //don't post this change
                }
                var post = {};
                for (var i = 0; i < change.length; i++) {
                    // console.log(change[i], source);
                    if (typeof (post[change[i][0]]) === 'undefined') {
                        //console.log(window.tableform.data[change[i][0]]);
                        if (window.tableform.data[change[i][0]]) {
                            post[change[i][0]] = {op: 'update', id: window.tableform.data[change[i][0]].id, varspecialityid: config.varspecialityid};
                        } else {
                            post[change[i][0]] = {op: 'create', id: 0, varspecialityid: config.varspecialityid};
                        }
                    }
                    window.tableform.data[change[i][0]][change[i][1]] = change[i][3];
                    post[change[i][0]][change[i][1]] = change[i][3];
                }
                // console.log(post);
                $.ajax({
                    url: 'ajax.php',
                    data: {op: 'subspecialityupdate', data: post},
                    method: 'POST',
                    dataType: 'json'
                }).done(function (reply) {
                    //echo json_encode(Array('status'=>'success','rows'=>$rows));
                    if (reply.status === 'success') {
                        window.tableform.loadData = true;
                        for (var irow in reply.rows) {
                            window.tableform.handsontable.setDataAtRowProp([
                                [irow, "id", reply.rows[irow].id],
                                [irow, "varsubspecialitytitle", reply.rows[irow].varsubspecialitytitle]
                            ]);
                        }
                        window.tableform.loadData = false;
                    }
                });
            },
            beforeRemoveRow: function (index, amount) {
                $.ajax({
                    url: 'ajax.php',
                    data: {op: 'subspecialitydelete', id: window.tableform.handsontable.getDataAtRowProp(index, "id")},
                    method: 'POST',
                    dataType: 'json'
                }).done(function (reply) {
                });
            }
        });
    });

});
