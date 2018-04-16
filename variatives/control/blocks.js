require(['jquery', 'jqueryui'], function ($) {



    window.adjustBlockRow = function (row) {
        row.action = "<input type=checkbox class=blockcheckbox value=\"" + row.id + "\"> ";
        row.action += "<a class=\"blocklistbtn\" title=\""+i18n['Block Properties']+"\" href=\"block.php?id=" + row.id + "\" target=\"_blank\"><img src=\"../resources/icon_edit.gif\"></a> ";
        row.action += "<a class=\"blocklistbtn\" title=\""+i18n['Block Create Copy']+"\" href=\"javascript:void(window.block_create_copy(" + row.id + "))\"><img src=\"../resources/icon_copy.png\"></a> ";
        row.action += "<a class=\"blocklistbtn\" title=\""+i18n['Report waiting']+"\" href=\"./reportwaiting.php?varblockid=" + row.id + "\"><img height=16 src=\"../resources/icon_empty.png\"></a> ";
        row.action += "<a class=\"blocklistbtn\" title=\""+i18n['Report enrolled']+"\" href=\"./reportenrolled.php?varblockid=" + row.id + "\"><img height=16 src=\"../resources/icon_report.png\"></a> ";
        row.action += "<a class=\"blocklistbtn\" title=\""+i18n['Course_stats']+"\" href=\"block_course_stat.php?varblockid=" + row.id + "\" target=\"_blank\"><img src=\"../resources/icon_stat.png\"></a> ";
        row.action += "<a class=\"blocklistbtn\" title=\""+i18n['notification_reminder_subject']+"\" href=\"./notifywaiting.php?varblockid=" + row.id + "\"><img height=16 src=\"../resources/icon_mail.png\"></a>";
        row.action += "<a class=\"blocklistbtn\" title=\""+i18n['Auto_assignment']+"\" href=\"./assign-start.php?varblockid=" + row.id + "\"><img height=16 src=\"../resources/icon_assign.png\"></a>  <br>";
        
        
        
        row.action += "<a title=\""+i18n['Delete block']+"\" href=\"javascript:void(deleteBlock(" + row.id + "))\"><img src=\"../resources/icon_delete.png\"></a>";
        row.varblockisarchive=(row.varblockisarchive==1);
        row.varblocktimestampfrom = window.formatDate( new Date(row.varblocktimestampfrom*1000) );
        row.varblocktimestampto = window.formatDate( new Date(row.varblocktimestampto*1000) );
        return row;
    };
    
    window.formatDate = function(dt){
        var d=dt.getDate();
        if(d<10) d='0'+d;
        var m=1+dt.getMonth();
        if(m<10) m='0'+m;
        return d+'.'+m+'.'+dt.getFullYear();
    }

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
        var record;
        for(var i=0; i<window.tableform.fulldata.length; i++){
            if(window.tableform.fulldata[i].id==id){
                window.tableform.fulldata.splice(i,1);
                return;
            }
        }
    };

    window.deleteBlock = function(id){
        $.ajax({
            url: 'ajax.php',
            data: {op: 'blockdelete', id: id},
            method: 'POST'
        }).done(function () {

            window.removeFromFullData(id);
            window.tableform.data=window.getBlockData();
            window.tableform.handsontable.view.settings.maxRows=window.tableform.data.length;
            window.tableform.handsontable.loadData(window.tableform.data)
        });
    }
    
    window.block_create_copy = function(id){
        $.ajax({
            url: 'ajax.php',
            data: {op: 'blockcopy', id: id},
            method: 'POST'
        }).done(function () {
            window.location.reload();
        });
    };
    
    
    window.getBlockData = function () {

        var part=[];

        var levelId = $('#filter_varlevelid').val();
        var formId = $('#filter_varformid').val();
        var year=$('#filter_vargroupyear').val();
        var blockname=$('#filter_varblockname').val().split(/ +/);

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
            for(var k=0; k<blockname.length; k++){
                if(blockname[k].length>0 && window.tableform.fulldata[i].varblockname.indexOf(blockname[k])<0){
                    matched=false;
                }                
            }
            if(matched){
                part.push(window.tableform.fulldata[i]);
            }
        }
        return part;
    };
    
    window.startEnrollment=function(){
       var checkboxes= $('input.blockcheckbox:checked');
       if(checkboxes.length>0){
           var ids=[];
           checkboxes.each(function(i,el){
               ids.push(el.value);
           });
           // console.log(ids);
           window.location.href="assign-start.php?varblockid="+ids.join(',');
       }
    };
    
    window.reportEnrolled=function(){
       var checkboxes= $('input.blockcheckbox:checked');
       if(checkboxes.length>0){
           var ids=[];
           checkboxes.each(function(i,el){
               ids.push(el.value);
           });
           // console.log(ids);
           window.location.href="reportenrolled.php?varblockid="+ids.join(',');
       }
    };
    
    
    
    $(window).load(function () {
        window.tableform = {};
        window.tableform.autosaveNotification = false;
        window.tableform.loadingData = false;

        var blocktable = document.getElementById('blocktable');

        // adjust data
        window.tableform.fulldata = [];
        for (var id in list) {
            window.tableform.fulldata.push(window.adjustBlockRow(list[id]));
        }

        var columns = [
            {data: 'action', readOnly: true, renderer: "html"},
            {data: 'id', readOnly: true},
            {data: 'varblockname', readOnly: false},
            {data: 'varformname', readOnly: true},
            {data: 'varlevelname', readOnly: true},
            {data: 'vargroupyear', readOnly: false},
            {data: 'varblocktimestampfrom', readOnly: false},
            {data: 'varblocktimestampto', readOnly: false},
            {data: 'varblockisarchive', readOnly: false, type:"checkbox"}
        ];

        window.tableform.data=window.getBlockData();

        window.tableform.handsontable = new Handsontable(blocktable, {
            data: window.tableform.data,
            minSpareRows: 0,
            minSpareCols: 0,
            maxRows: window.tableform.data.length,
            colHeaders: colHeaders,
            search: true,
            manualColumnResize: true,
            colWidths: [150, 35,   200,  60, 80, 80, 130, 130, 50],
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
                    data: {op: 'blockupdate', data: post},
                    method: 'POST'
                }).done(function () {
                });
            }
        });
        
        var refilter=function(){
            window.tableform.data=window.getBlockData();
            window.tableform.handsontable.view.settings.maxRows=window.tableform.data.length;
            window.tableform.handsontable.loadData(window.tableform.data)
        };
        $('#filter_varlevelid').change(refilter);
        $('#filter_varformid').change(refilter);
        $('#filter_vargroupyear').keyup(refilter);
        $('#filter_varblockname').keyup(refilter);



        
        
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