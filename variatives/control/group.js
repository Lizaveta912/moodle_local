

require(['jquery', 'jqueryui'], function ($) {

    window.adjustGroupRow = function (row) {
        row.action = "<a href=\"javascript:void(editGroup(" + row.id + "))\"><img src=\"../resources/icon_edit.gif\"></a>";
        row.action += "&nbsp;&nbsp;<a href=\"javascript:void(deleteGroup(" + row.id + "))\"><img src=\"../resources/icon_delete.png\"></a>";
        // row.varspecialityvisible=(row.varspecialityvisible==1);
        // row.varspecialityobsolete=(row.varspecialityobsolete==1);
        return row;
    }


    window.updateFullData = function (record){
        var record;
        for(var i=0; i<window.tableform.fulldata.length; i++){
            if(window.tableform.fulldata[i].id==record.id){
                window.tableform.fulldata[i]=record;
                return;
            }
        }
    }

    window.removeFromFullData = function (id){
        var record;
        for(var i=0; i<window.tableform.fulldata.length; i++){
            if(window.tableform.fulldata[i].id==id){
                window.tableform.fulldata.splice(i,1);
                return;
            }
        }
    }

    window.getGroupData = function () {

        var part=[];

        var deptId = $('#filter_vardepartmentid').val();
        var codePart=$('#filter_vargroupcode').val();
        part = [];
        for (var i=0; i<window.tableform.fulldata.length; i++) {
            var matched=true;

            if(deptId && window.tableform.fulldata[i].vardepartmentid !=deptId){
                matched=false;
            }
            if(codePart.length>0 && window.tableform.fulldata[i].vargroupcode.indexOf(codePart)<0){
                matched=false;
            }
            
            if(matched){
                part.push(window.tableform.fulldata[i]);
            }
        }
        return part;
    };

    
    window.deleteGroup = function(id){
        // search a row by id
        if(confirm('Delete Group?')){
            var record=false;
            for(var i=0; i<window.tableform.data.length; i++){
                if(window.tableform.data[i].id==id){
                    record=window.tableform.data[i];
                    record.rowid=i;
                    // post Delete command
                    $.ajax({
                        url: 'ajax.php',
                        data: {op:'groupdelete',data:record},
                        method:'POST'
                    }).done(function() {
                        window.removeFromFullData(id);
                        window.tableform.data=window.getGroupData();
                        window.tableform.handsontable.view.settings.maxRows=window.tableform.data.length;
                        window.tableform.handsontable.loadData(window.tableform.data)
                    });
                    return;
                }
            }
            
        }
    }
    
    window.editGroup=function(id){
        // search a row by id
        var record=false;
        for(var i=0; i<window.tableform.data.length; i++){
            if(window.tableform.data[i].id==id){
                record=window.tableform.data[i];
                record.rowid=i;
            }
        }
        // load form data
        if(record!==false){
            var dialog=$('#dialog');
            dialog.find('input[name="id"]').val(record.id);
            dialog.find('input[name="rowid"]').val(record.rowid);
            dialog.find('select[name="cohortid"]').val(record.cohortid);
            dialog.find('input[name="vargroupcode"]').val(record.vargroupcode);
            dialog.find('select[name="vardepartmentid"]').val(record.vardepartmentid);
            dialog.find('input[name="vargroupyear"]').val(record.vargroupyear);
            dialog.find('select[name="varspecialityid"]').val(record.varspecialityid);
            dialog.find('select[name="varformid"]').val(record.varformid);
            dialog.find('select[name="varlevelid"]').val(record.varlevelid);
            dialog.find('input[name="vargroupedbocode"]').val(record.vargroupedbocode);
            dialog.find('textarea[name="vargroupnotes"]').val(record.vargroupnotes);
        }
        
        $('#dialog').dialog({
            width: 500
        });
        
    };


    window.createGroup=function(id){
        var dialog=$('#dialog');
        dialog.find('input[name="id"]').val("");
        dialog.find('input[name="rowid"]').val("");
        dialog.find('select[name="cohortid"]').val("");
        dialog.find('input[name="vargroupcode"]').val("");
        dialog.find('select[name="vardepartmentid"]').val("");
        dialog.find('input[name="vargroupyear"]').val("");
        dialog.find('select[name="varspecialityid"]').val("");
        dialog.find('select[name="varformid"]').val("");
        dialog.find('select[name="varlevelid"]').val("");
        dialog.find('input[name="vargroupedbocode"]').val("");
        dialog.find('textarea[name="vargroupnotes"]').val("");
        
        $('#dialog').dialog({
            width: 500
        });
        
    }


    $(window).load(function () {
        window.tableform = {};
        window.tableform.autosaveNotification = false;
        window.tableform.loadingData = false;

        var grouptable = document.getElementById('grouptable');



        // adjust data
        window.tableform.fulldata = [];
        for (var id in list) {
            window.tableform.fulldata.push(window.adjustGroupRow(list[id]));
        }

        var columns = [
            {data: 'action', readOnly: true, renderer: "html"},
            {data: 'id', readOnly: true},
            {data: 'vargroupcode'},
            {data: 'cohortname', readOnly: true},
            {data: 'vargroupyear'},
            
            {data: 'vardepartmentname', readOnly: true},
            {data: 'varformname', readOnly: true},
            {data: 'varlevelname', readOnly: true},
            {data: 'varspecialityname', readOnly: true},
            {data: 'vargroupedbocode'}
        ];




        window.tableform.data=window.getGroupData();

        window.tableform.handsontable = new Handsontable(grouptable, {
            data: window.tableform.data,
            minSpareRows: 0,
            minSpareCols: 0,
            maxRows: window.tableform.data.length,
            colHeaders: colHeaders,
            search: true,
            manualColumnResize: true,
            //         'action' 'id'  'vargroupcode' vargroupyear 'vardepartmentname' 'varformname'  'varlevelname' 'varspecialityname' 'vargroupedbocode' 'cohortname'
            colWidths: [50,      50,   80, 70,         50,          150,                   90, 100, 200, 60],
            columns: columns,
            afterChange: function (change, source) {
                if (window.tableform.loadingData) {
                    return; //don't save this change
                }
                if (source === 'loadData') {
                    return; //don't save this change
                }
                var post = {};
                for (var i = 0; i < change.length; i++) {

                    if (typeof (post[change[i][0]]) === 'undefined') {
                        if (window.tableform.data[change[i][0]]) {
                            post[change[i][0]] = {op: 'update', id: window.tableform.data[change[i][0]].id};
                        } else {
                            post[change[i][0]] = {op: 'create', id: 0};
                        }
                    }
                    window.tableform.data[change[i][0]][change[i][1]] = change[i][3];
                    
                    window.updateFullData(window.tableform.data[change[i][0]]);
                    post[change[i][0]][change[i][1]] = change[i][3];
                }
                // console.log(post);
                $.ajax({
                    url: 'ajax.php',
                    data: {op: 'groupupdate', data: post},
                    method: 'POST'
                }).done(function () {
                });
            }
        });
        
        var refilter=function(){
            window.tableform.data=window.getGroupData();
            window.tableform.handsontable.view.settings.maxRows=window.tableform.data.length;
            window.tableform.handsontable.loadData(window.tableform.data)
        };
        $('#filter_vardepartmentid').change(refilter);
        $('#filter_vargroupcode').keyup(refilter);
        
        
        
        
        
        
        // function to post form data
        var postUpdates = function(){
            var record={};
            $('.formel').each(function(id, el){
                var elt=$(el);
                if(elt.attr('type')==='checkbox'){
                    record[elt.attr('name')]=elt.prop('checked');
                }else{
                    record[elt.attr('name')]=elt.val();
                }
                
            });
            
            var post={};
            if(record.id===""){
                record.op='create';
                post[0]=record;
            }else{
                record.op='update';
                post[record.id]=record;
            }
            $.ajax({
                url: 'ajax.php',
                data: {op:'groupupdate',data:post},
                method:'POST',
                dataType:'json'
            }).done(function(reply) {
                if(reply.status === 'success' && reply.group[0]){
                    var data=reply.group[0];
                    // console.log(data);
                    if(record.rowid!==''){
                        // update row
                        window.tableform.loadingData=true;
                        window.tableform.data[record.rowid]=window.adjustGroupRow(data);
                        window.updateFullData(window.tableform.data[record.rowid]);
                        window.tableform.handsontable.render();
                        window.tableform.loadingData=false;
                    }else{
                        // add row
                        window.tableform.loadingData=true;
                        window.tableform.fulldata.push(window.adjustGroupRow(data));
                        refilter();
                        window.tableform.loadingData=false;
                    }
                    
                }
                // close dialog
                $('#dialog').dialog("close");
            });
        };
        $('#saveupdates').click(postUpdates);
    });

});