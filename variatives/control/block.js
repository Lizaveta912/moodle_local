require(['jquery', 'jqueryui'], function ($) {
    
    $('#saveupdates').click(function(){
        var record={};
        $('.formel').each(function(id, el){
            var elt=$(el);
            if(elt.attr('type')==='checkbox'){
                record[elt.attr('name')]=elt.prop('checked');
            }else{
                record[elt.attr('name')]=elt.val();
            }
        });
        record.id=$('input.formel[name="id"]').val();
        //console.log(record);
        var post={};
        if(record.id==="" || record.id==="0"){
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
            if(reply.status === 'success' && reply.block[0]){
                var data=reply.block[0];
                if(data){
                    $('input.formel[name="id"]').val(data.id);
                    $('.grps').show();
                    $('.crss').show();
                    window.tableblockcourses.loadData(data.id);
                    window.tableblockgroups.loadData(data.id);
                }
            }
        });
    });
    
    

    var rowSaverFactory=function(op,tableObj){
        return function (change, source) {
            if (tableObj.loadingData) {
                return; //don't save this change
            }
            if (source === 'loadData') {
                return; //don't save this change
            }
            var post = {};
            for (var i = 0; i < change.length; i++) {

                if (typeof (post[change[i][0]]) === 'undefined') {
                    if (tableObj.data[change[i][0]].id) {
                        post[change[i][0]] = {
                            op: 'update',
                            id: tableObj.data[change[i][0]].id,
                            varblockid: $('input[name="id"]').val()
                        };
                    } else {
                        post[change[i][0]] = {
                            op: 'create',
                            id: 0,
                            varblockid: $('input[name="id"]').val()
                        };                        
                    }
                }
                tableObj.data[change[i][0]][change[i][1]] = change[i][3];
                post[change[i][0]][change[i][1]] = change[i][3];
            }
            // console.log(post);
            $.ajax({
                url: 'ajax.php',
                data: {op: op, data: post},
                method: 'POST'
            }).done(function () {
                tableObj.loadData($('input[name="id"]').val());
            });
        }
    }



    
    // blockgroup
    window.deleteBlockGroup = function(id){
        $.ajax({
                url: 'ajax.php',
                data: {op: 'blockgroupdelete', id: id},
                dataType:'json',
                method: 'POST'
            }).done(function (reply) {
                window.tableblockgroups.loadData($('input[name="id"]').val());
        });
    }
    // blockcourse
    window.deleteBlockCourse = function(id){
        $.ajax({
                url: 'ajax.php',
                data: {op: 'blockcoursedelete', id: id},
                dataType:'json',
                method: 'POST'
            }).done(function (reply) {
                window.tableblockcourses.loadData($('input[name="id"]').val());
            }
        );
    };
    
    $(window).load(function () {
        
        // =================== blockgroup = begin ==============================
        window.tableblockgroups = {};
        window.tableblockgroups.loadingData = false;

        window.tableblockgroups.adjustRow = function (row) {
            // row.action = "<a href=\"block.php?id=" + row.id + "\" target=\"_blank\"><img src=\"../resources/icon_edit.gif\"></a>&nbsp;&nbsp;";
            row.action = "<a href=\"javascript:void(deleteBlockGroup(" + row.id + "))\"><img src=\"../resources/icon_delete.png\"></a>";
            // row.varblockisarchive=(row.varblockisarchive==1);
            // row.varblocktimestampfrom = window.formatDate( new Date(row.varblocktimestampfrom*1000) );
            // row.varblocktimestampto = window.formatDate( new Date(row.varblocktimestampto*1000) );
            // console.log('row=',row);
            return row;
        };
    
        window.tableblockgroups.loadData = function (varblockid) {
            
            // window.tableblockgroups.data
            $.ajax({
                url: 'ajax.php',
                data: {op: 'blockgrouplist', varblockid: varblockid},
                dataType:'json',
                method: 'POST'
            }).done(function (reply) {
                //console.log('received data ',reply);
                // window.tableblockgroups.data=reply.list;
                window.tableblockgroups.data = [];
                for (var id=0; id<reply.list.length; id++) {
                    window.tableblockgroups.data.push(window.tableblockgroups.adjustRow(reply.list[id]));
                }
                //console.log('loading data ',window.tableblockgroups.data);
                window.tableblockgroups.handsontable.loadData(window.tableblockgroups.data);
            });
        };

        var blockgroups = document.getElementById('blockgroups');
        
        var columns = [
            {data: 'action', readOnly: true, renderer: "html"},
            // {data: 'id', readOnly: true},
            {
                data: 'vardepartmentid',
                editor: 'select',
                selectOptions:departmentOptions,
                renderer: function (instance, td, row, col, prop, value, cellProperties) { td.innerHTML = departmentOptions[value] || ''; return td; },
                validator: function (value, callback) { callback(typeof(departmentOptions[value])!=='undefined');  }
            },
            {
                data: 'varspecialityid',
                editor: 'select',
                selectOptions:specialityOptions,
                renderer: function (instance, td, row, col, prop, value, cellProperties) { td.innerHTML = specialityOptions[value] || ''; return td; },
                validator: function (value, callback) { callback(typeof(specialityOptions[value])!=='undefined');  }
            },
            {
                data: 'varsubspecialityid',
                editor: 'select',
                selectOptions:subspecialityOptions,
                renderer: function (instance, td, row, col, prop, value, cellProperties) { td.innerHTML = subspecialityOptions[value] || ''; return td; },
                validator: function (value, callback) { callback(typeof(subspecialityOptions[value])!=='undefined');  }
            },
            {data: 'varblockgroupnumcourses', readOnly: false}
        ];
        
        
        
        // window.tableblockgroups.data=[];
        window.tableblockgroups.handsontable = new Handsontable(blockgroups, {
            // data: window.tableblockgroups.data,
            minSpareRows: 1,
            maxSpareRows: 1,
            colHeaders: blockgroupColHeaders,
            manualColumnResize: true,
            columns: columns,
            afterChange: rowSaverFactory('blockgroupudate',window.tableblockgroups),
            afterInit:function(){window.tableblockgroups.loadingData = true; window.tableblockgroups.loadData($('input[name="id"]').val()); window.tableblockgroups.loadingData = false;}
        });
        
        if(parseInt($('input[name="id"]').val())>0){
            $('.grps').show();
        }else{
            $('.grps').hide();
        }
        // =================== blockgroup = end ================================



        // =================== blockcourse = begin =============================

        window.tableblockcourses = {};
        window.tableblockcourses.loadingData = false;

        window.tableblockcourses.adjustRow = function (row) {
            // row.action = "<a href=\"block.php?id=" + row.id + "\" target=\"_blank\"><img src=\"../resources/icon_edit.gif\"></a>&nbsp;&nbsp;";
            row.action = "<a href=\"javascript:void(deleteBlockCourse(" + row.id + "))\"><img src=\"../resources/icon_delete.png\"></a>";
            
            if(!row.course_fullname){
                row.course_fullname="------";
            }
            row.course_fullname="<a href=\"javascript:void(selectBlockCourse(" + row.id + "))\">"+row.course_fullname+"</a>";
            
            // row.varblockisarchive=(row.varblockisarchive==1);
            // row.varblocktimestampfrom = window.formatDate( new Date(row.varblocktimestampfrom*1000) );
            // row.varblocktimestampto = window.formatDate( new Date(row.varblocktimestampto*1000) );
            // console.log('row=',row);
            return row;
        };
        window.tableblockcourses.loadData = function (varblockid) {
            // window.tableblockcourses.data
            // console.log('loadbf');
            $.ajax({
                url: 'ajax.php',
                data: {op: 'blockcourselist', varblockid: varblockid},
                dataType:'json',
                method: 'POST'
            }).done(function (reply) {
                //console.log('received data ',reply);
                // window.tableblockgroups.data=reply.list;
                window.tableblockcourses.data = [];
                for (var id=0; id<reply.list.length; id++) {
                    window.tableblockcourses.data.push(window.tableblockcourses.adjustRow(reply.list[id]));
                }
                //console.log('loading data ',window.tableblockgroups.data);
                window.tableblockcourses.handsontable.loadData(window.tableblockcourses.data);
            });
        };

        var blockcourses = document.getElementById('blockcourses');
        var columns = [
            {data: 'action', readOnly: true, renderer: "html"},
            {data: 'varblockcourserating', readOnly: true},
            {data: 'varblockcoursegroup', readOnly: false},
            {data: 'course_fullname', readOnly: true, renderer: "html"}            
        ];
        
        // window.tableblockcourses.data=[];
        window.tableblockcourses.handsontable = new Handsontable(blockcourses, {
            // data: window.tableblockgroups.data,
            minSpareRows: 1,
            maxSpareRows: 1,
            colHeaders: blockcourseColHeaders,
            manualColumnResize: true,
            columns: columns,
            afterChange: rowSaverFactory('blockcourseupdate',window.tableblockcourses),
            afterInit:function(){window.tableblockgroups.loadingData = true; window.tableblockcourses.loadData($('input[name="id"]').val()); window.tableblockgroups.loadingData = false;}
        });
        
        if(parseInt($('input[name="id"]').val())>0){
            $('.crss').show();
        }else{
            $('.crss').hide();
        }
        // =================== blockcourse = end ===============================
        
        
        // =================== course selector = begin =========================
        window.selectBlockCourse =function(id){
            // search a row by id
            var record=false;
            for(var i=0; i<window.tableblockcourses.data.length; i++){
                if(window.tableblockcourses.data[i].id==id){
                    record=window.tableblockcourses.data[i];
                    record.rowid=i;
                }
            }
            // load form data
            if(record!==false){
                var dialog=$('#dialog');
                dialog.find('input[name="id"]').val(record.id);
                dialog.find('input[name="rowid"]').val(record.rowid);
                dialog.find('#course_name_part').val('');
            }
            // 

            $('#dialog').dialog({
                width: 500
            });
        };
        window.setCourse=function(courseId, courseName){
            var dialog=$('#dialog');
            var i = dialog.find('input[name="rowid"]').val();
            // console.log(i, courseId, courseName);
            // var course_fullname="<a href=\"javascript:void(selectBlockCourse(" + courseId + "))\">"+courseName+"</a>";
            // window.tableblockcourses.data[i].courseid=courseId;
            // window.tableblockcourses.data[i].course_fullname=course_fullname;
            // window.tableblockcourses.handsontable.render();
            $.ajax({
                url: 'ajax.php',
                data: {
                    op: 'blockcourseupdate', 
                    data:[
                        {
                            id:window.tableblockcourses.data[i].id,
                            op:'update',
                            varblockid:window.tableblockcourses.data[i].varblockid,
                            courseid:courseId
                        }
                    ]
                },
                method: 'POST'
            }).done(function () {
                window.tableblockcourses.loadData($('input[name="id"]').val());
            });
            $('#dialog').dialog("close");
        };
        window.searchTimeout=false;
        window.searchCourses=function(){
            var substr=$('#course_name_part').val();
            $.ajax({
                    url: 'ajax.php',
                    data: {op: 'blockcoursefind', substr: substr},
                    dataType:'json',
                    method: 'POST'
            }).done(function (reply) {
                var found_courses = $('#found_courses');
                found_courses.empty();
                if(reply.list.length>=20){
                    found_courses.append(
                            "<div class='wrning'>"+i18n.tooManyCourses+"</div>"
                    );
                }
                if(reply.list.length==0){
                    found_courses.append(
                            "<div class='wrning'>"+i18n.noCoursesFound+"</div>"
                    );
                }
                var html, name; 
                for (var id=0; id<reply.list.length; id++) {
                    html=''; 
                    name=reply.list[id].course_fullname.replace(/["']/g,'`');
                    html+="<div class=\"oneCourseFound\"><a href='javascript:void(setCourse("+reply.list[id].id+",\""+name+"\"))'>"+name+"</a></div>";
                    found_courses.append(html);
                }
            }); 
        }
        $('#course_name_part').keyup(function(){
            var substr=$('#course_name_part').val();
            if(substr.length>0){
                if(window.searchTimeout){
                    clearTimeout(window.searchTimeout);
                }
                window.searchTimeout=setTimeout(window.searchCourses,1000);
               
            }else{
                $('#found_courses').empty();
            }
        });
        // =================== course selector = end ===========================


    });
});

