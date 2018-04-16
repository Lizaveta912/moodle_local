require(['jquery','jqueryui'], function($) {
    
    
    window.deleteSpeciality = function(id){
        // search a row by id
        if(confirm('Delete Speciality?')){
            var record=false;
            for(var i=0; i<window.tableform.data.length; i++){
                if(window.tableform.data[i].id==id){
                    record=window.tableform.data[i];
                    record.rowid=i;
                    // post Delete command
                    $.ajax({
                        url: 'ajax.php',
                        data: {op:'specialitydelete',data:record},
                        method:'POST'
                    }).done(function() {
                        window.tableform.data.splice(i,1);
                        window.tableform.handsontable.view.settings.maxRows=window.tableform.data.length;
                        window.tableform.handsontable.render();
                    });
                    return;
                }
            }
            
        }
    }
    
    
    
    window.editSpeciality=function(id){
        
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
            
            dialog.find('select[name="vardepartmentid"]').val(record.vardepartmentid);
            dialog.find('select[name="varformid"]').val(record.varformid);
            dialog.find('select[name="varlevelid"]').val(record.varlevelid);
            dialog.find('input[name="varspecialitycode"]').val(record.varspecialitycode);
            dialog.find('input[name="varspecialityname"]').val(record.varspecialityname);
            dialog.find('input[name="varspecialityedboid"]').val(record.varspecialityedboid);
            
            dialog.find('input[name="varspecialityvisible"]').prop('checked', record.varspecialityvisible);
            dialog.find('input[name="varspecialityobsolete"]').prop('checked', record.varspecialityobsolete);

        }
        // 
        
        $('#dialog').dialog({
            width: 500
        });
        
    }
    
    

    window.createSpeciality=function(id){

        var dialog=$('#dialog');
        dialog.find('input[name="id"]').val("");
        dialog.find('input[name="rowid"]').val("");

        dialog.find('select[name="vardepartmentid"]').val(0);
        dialog.find('select[name="varformid"]').val(0);
        dialog.find('select[name="varlevelid"]').val(0);
        dialog.find('input[name="varspecialitycode"]').val("");
        dialog.find('input[name="varspecialityname"]').val("");
        dialog.find('input[name="varspecialityedboid"]').val("");

        dialog.find('input[name="varspecialityvisible"]').prop('checked', true);
        dialog.find('input[name="varspecialityobsolete"]').prop('checked', false);
        
        $('#dialog').dialog({
            width: 500
        });

    }
    
    window.adjustSpecialityRow=function(row){
        row.action ="<a href=\"javascript:void(editSpeciality("+row.id+"))\" title=\""+i18n['varspeciality_edit']+"\"><img src=\"../resources/icon_edit.gif\"></a>";
        row.action+="&nbsp;&nbsp;<a href=\"subspecialities.php?varspecialityid="+row.id+"\" title=\""+i18n['varsubspeciality_varspecialityid']+"\"><img src=\"../resources/icon_subitems.png\"></a>";
        row.action+="&nbsp;&nbsp;<a href=\"javascript:void(deleteSpeciality("+row.id+"))\"><img src=\"../resources/icon_delete.png\"></a>";
        row.varspecialityvisible=(row.varspecialityvisible==1);
        row.varspecialityobsolete=(row.varspecialityobsolete==1);
        return row;
    }
    
    $(window).load(function(){

        window.tableform={};
        window.tableform.autosaveNotification=false;
        window.tableform.loadingData = false;
        
        var specialitytable = document.getElementById('specialitytable');

        // adjust data
        window.tableform.data=[];
        for(var id in list){
            window.tableform.data.push(window.adjustSpecialityRow(list[id]));
        }
        
        var columns=[
                {data: 'action',readOnly: true, renderer:"html"},
                {data: 'id',readOnly: true},
                {data: 'vardepartmentname',readOnly: true},
                {data: 'varformname',readOnly: true},
                {data: 'varlevelname',readOnly: true},
                {data: 'varspecialitycode'},
                {data: 'varspecialityname'},
                {data: 'varspecialityedboid'},
                {data: 'varspecialityvisible' , type: 'checkbox'},
                {data: 'varspecialityobsolete', type: 'checkbox'}
        ];

        window.tableform.handsontable = new Handsontable(specialitytable, {
            data: window.tableform.data,
            minSpareRows: 0,
            minSpareCols: 0,
            maxRows: window.tableform.data.length,
            colHeaders: colHeaders,
            search: true,
            manualColumnResize:true,
            //         'action' 'id'  'vardepartmentname' 'varformname' 'varlevelname' 'varspecialitycode' 'varspecialityname' 'varspecialityedboid' 'varspecialityvisible' 'varspecialityobsolete'
            colWidths: [90     , 50   , 150               , 80         , 80           , 90                  , 200              ,60                   ,25                    ,25],
            columns: columns,
            afterChange: function (change, source) {
                if(window.tableform.loadingData){
                    return; //don't save this change
                }
                if (source === 'loadData') {
                    return; //don't save this change
                }
                var post={};
                for(var i=0; i<change.length; i++){

                    if(typeof(post[change[i][0]])==='undefined'){
                        if(window.tableform.data[change[i][0]]){
                            post[change[i][0]]={op:'update',id : window.tableform.data[change[i][0]].id};
                        }else{
                            post[change[i][0]]={op:'create',id : 0 };
                        }
                    }
                    window.tableform.data[change[i][0]][change[i][1]] = change[i][3];
                    post[change[i][0]][change[i][1]] = change[i][3];
                }
                // console.log(post);
                $.ajax({
                    url: 'ajax.php',
                    data: {op:'specialityupdate',data:post},
                    method:'POST'
                }).done(function() {
                });
            }
        });
        
        
        
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
                data: {op:'specialityupdate',data:post},
                method:'POST',
                dataType:'json'
            }).done(function(reply) {
                if(reply.status === 'success' && reply.speciality[0]){
                    var data=reply.speciality[0];
                    // console.log(data);
                    if(record.rowid!==''){
                        // update row
                        window.tableform.loadingData=true;
                        window.tableform.data[record.rowid]=window.adjustSpecialityRow(data);
                        window.tableform.handsontable.render();
                        window.tableform.loadingData=false;
                    }else{
                        // add row
                        window.tableform.loadingData=true;
                        window.tableform.data.push(window.adjustSpecialityRow(data));
                        window.tableform.handsontable.view.settings.maxRows=data.length;
                        window.tableform.handsontable.render();
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
