require(['jquery', 'jqueryui'], function ($) {


    window.adjustSubspecialityBlockRow = function (row) {
        row.action = "<a class=\"blocklistbtn\" title=\""+i18n['subspecialityblock_properties']+"\" href=\"subspecialityblock.php?id=" + row.id + "\" target=\"_blank\"><img src=\"../resources/icon_edit.gif\"></a> ";
        row.action += "<a class=\"blocklistbtn\" title=\""+i18n['Report subspeciality waiting']+"\" href=\"./subspecialityreportwaiting.php?varsubspecialityblockid=" + row.id + "))\"><img height=16 src=\"../resources/icon_empty.png\"></a> ";
        row.action += "<a class=\"blocklistbtn\" title=\""+i18n['Report subspeciality enrolled']+"\" href=\"./subspecialityreportenrolled.php?varsubspecialityblockid=" + row.id + "))\"><img height=16 src=\"../resources/icon_report.png\"></a> ";
        row.action += "<a class=\"blocklistbtn\" title=\""+i18n['subspeciality_reminder_subject']+"\" href=\"./subspecialitynotifywaiting.php?varsubspecialityblockid=" + row.id + "))\"><img height=16 src=\"../resources/icon_mail.png\"></a>";
        row.action += "<a class=\"blocklistbtn\" title=\""+i18n['Auto_assignment']+"\" href=\"./subspeciality-assign-start.php?varblockid=" + row.id + "))\"><img height=16 src=\"../resources/icon_assign.png\"></a>  <br>";
        
        
        
        row.action += "<a title=\""+i18n['Delete block']+"\" href=\"javascript:void(deleteSubspecialityBlock(" + row.id + "))\"><img src=\"../resources/icon_delete.png\"></a>";

        row.varsubspecialityblockisarchive=(row.varsubspecialityblockisarchive==1);
        row.varsubspecialityblocktimemin = window.formatDate( new Date(row.varsubspecialityblocktimemin*1000) );
        row.varsubspecialityblocktimemax = window.formatDate( new Date(row.varsubspecialityblocktimemax*1000) );
        
        //varsubspecialityblockname           varchar(128)  utf8_unicode_ci  NO                                       select,insert,update,references           
        //vargroupyear                        bigint(10)    (NULL)           NO              (NULL)                   select,insert,update,references           
        //varformname,
        //varlevelname,
        //vardepartmentname,
        //varspecialityname
        //varsubspecialityblockisarchive      smallint(4)   (NULL)           NO              (NULL)                   select,insert,update,references           
        
        return row;
    };
    
    window.deleteSubspecialityBlock = function(id){
        $.ajax({
            url: 'ajax.php',
            data: {op: 'subspecialityblockdelete', id: id},
            method: 'POST'
        }).done(function () {

            window.removeFromFullData(id);
            window.tableform.data=window.getSubspecialityBlockData();
            window.tableform.handsontable.view.settings.maxRows=window.tableform.data.length;
            window.tableform.handsontable.loadData(window.tableform.data)
        });
    };

    
    window.formatDate = function(dt){
        var d=dt.getDay();
        if(d<10) d='0'+d;
        var m=1+dt.getMonth();
        if(m<10) m='0'+m;
        return d+'.'+m+'.'+dt.getFullYear();
    };

    window.updateFullData = function (record){
        var record;
        for(var i=0; i<window.tableform.fulldata.length; i++){
            if(window.tableform.fulldata[i].id==record.id){
                window.tableform.fulldata[i]=record;
                return;
            }
        }
    };

    window.removeFromFullData = function (id){
        for(var i=0; i<window.tableform.fulldata.length; i++){
            if(window.tableform.fulldata[i].id==id){
                window.tableform.fulldata.splice(i,1);
                return;
            }
        }
    };

    window.afterChange = function(op){
        return function (change, source) {
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
                data: {op: op, data: post},
                method: 'POST'
            }).done(function () {
            });
        };
    };

    
    window.getSubspecialityBlockData = function () {

        var part=[];

        var levelId = $('#filter_varlevelid').val();
        var formId = $('#filter_varformid').val();
        var year=$('#filter_vargroupyear').val();
        var vardepartmentid = $('#filter_vardepartmentid').val();
        var varspecialityid = $('#filter_varspecialityid').val();

        part = [];
        for (var i=0; i<window.tableform.fulldata.length; i++) {
            var matched=true;

            if(levelId && window.tableform.fulldata[i].varlevelid !=levelId){
                matched=false;
            }
            if(formId && window.tableform.fulldata[i].varformid !=formId){
                matched=false;
            }
            if(year.length>0 && window.tableform.fulldata[i].vargroupyear !=year){
                matched=false;
            }
            if(vardepartmentid && window.tableform.fulldata[i].vardepartmentid !=vardepartmentid){
                matched=false;
            }
            if(varspecialityid && window.tableform.fulldata[i].vardepartmentid !=varspecialityid){
                matched=false;
            }
            if(matched){
                part.push(window.tableform.fulldata[i]);
            }
        }
        return part;
    };
    
    $(window).load(function () {
        window.tableform = {};
        window.tableform.autosaveNotification = false;
        window.tableform.loadingData = false;

        var blocktable = document.getElementById('blocktable');

        // adjust data
        window.tableform.fulldata = [];
        for (var id in list) {
            window.tableform.fulldata.push(window.adjustSubspecialityBlockRow(list[id]));
        }

        var columns = [
            {data: 'action', readOnly: true, renderer: "html"},
            {data: 'id', readOnly: true},
            {data: 'varsubspecialityblocktimemin', readOnly: false},
            {data: 'varsubspecialityblockname', readOnly: false},
            {data: 'varformname', readOnly: true},
            {data: 'varlevelname', readOnly: true},
            {data: 'vardepartmentname', readOnly: true},
            {data: 'varspecialityname', readOnly: true},
            {data: 'vargroupyear', readOnly: false},
            // {data: 'varsubspecialityblocktimemax', readOnly: false},
            // {data: 'varsubspecialityblockisarchive', readOnly: false, type:"checkbox"}
        ];



        window.tableform.data=window.getSubspecialityBlockData();

        window.tableform.handsontable = new Handsontable(blocktable, {
            data: window.tableform.data,
            minSpareRows: 0,
            minSpareCols: 0,
            maxRows: window.tableform.data.length,
            colHeaders: colHeaders,
            search: true,
            manualColumnResize: true,
            colWidths: colWidth,
            columns: columns,
            afterChange: window.afterChange('subspecialityblockupdate')          
        });
        
        var refilter=function(){
            window.tableform.data=window.getSubspecialityBlockData();
            window.tableform.handsontable.view.settings.maxRows=window.tableform.data.length;
            window.tableform.handsontable.loadData(window.tableform.data)
        };
        $('#filter_varlevelid').change(refilter);
        $('#filter_varformid').change(refilter);
        $('#filter_vargroupyear').keyup(refilter);
        $('#filter_vardepartmentid').change(refilter);
        $('#filter_varspecialityid').change(refilter);
        
        
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
                data: {op:'blockupdate',data:post},
                method:'POST',
                dataType:'json'
            }).done(function(reply) {
                if(reply.status === 'success' && reply.group[0]){
                    var data=reply.group[0];
                    // console.log(data);
                    if(record.rowid!==''){
                        // update row
                        window.tableform.loadingData=true;
                        window.tableform.data[record.rowid]=window.adjustBlockRow(data);
                        window.updateFullData(window.tableform.data[record.rowid]);
                        window.tableform.handsontable.render();
                        window.tableform.loadingData=false;
                    }else{
                        // add row
                        window.tableform.loadingData=true;
                        window.tableform.fulldata.push(window.adjustBlockRow(data));
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