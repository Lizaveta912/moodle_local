require(['jquery', 'jqueryui'], function ($) {
    
    window.adjustEnrollmentRow = function (row) {
        row.action ='';
        row.action += "<a href=\"javascript:void(editEnroll(" + row.enrollid + "))\"><img src=\"../resources/icon_edit.gif\"></a>";
        // row.action += "&nbsp;<a href=\"./reportwaiting.php?blockid=" + row.id + "))\"><img height=16 src=\"../resources/icon_empty.png\"></a>";
        // row.action += "&nbsp;<a href=\"./reportenrolled.php?blockid=" + row.id + "))\"><img height=16 src=\"../resources/icon_report.png\"></a>";
        row.action += "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"javascript:void(deleteEnroll(" + row.enrollid + "))\"><img src=\"../resources/icon_delete.png\"></a>";
        
        row.userfullname=row.userlastname+' '+row.userfirstname;
        
        // row.varblockisarchive=(row.varblockisarchive==1);
        // row.varblocktimestampfrom = window.formatDate( new Date(row.varblocktimestampfrom*1000) );
        // row.varblocktimestampto = window.formatDate( new Date(row.varblocktimestampto*1000) );
        return row;
    };


    window.deleteEnroll = function(id){
        $.ajax({
            url: 'ajax.php',
            data: {op: 'enrolldelete', id: id},
            method: 'POST'
        }).done(window.searchEnrolls);
    };
    
    
    window.editEnroll = function(id){
        $.ajax({
            url: 'ajax.php',
            data: {op: 'enrollmentget', id: id},
            method: 'POST',
            dataType:'json'
        }).done(function (reply) {
            if(reply.status==='success'){
                var tmp,name;
                with(reply.enroll){
                   
                    $('#enrollid').val(enrollid);

                    tmp=userlastname+' '+userfirstname+' ('+ vargroupcode +' '+ varlevelname +' '+ varformname +' '+ varspecialityname +')';
                    name=tmp.replace(/["']/g,'`');
                    window.setUser(userid, name);
                    
                    name=varblockname.replace(/["']/g,'`');
                    window.setBlock(varblockid,name);
                    
                    name=course_fullname.replace(/["']/g,'`');
                    window.setCourse(courseid,name);

                }
                $('#enroll_block_selector_filter').val('');
                $('#enroll_user_selector_filter').val('');
                $('#enroll_course_selector_filter').val('');

                $('#dialog').dialog({
                    width: 500
                });
        
            }
            
        });
    };
    
    
    window.createEnroll=function(id){
        var dialog=$('#dialog');
        
        $('#enrollid').val('');
        window.setUser('', '...');
        window.setBlock('','...');
        window.setCourse('','...');
        
        $('#enroll_block_selector_filter').val('');
        $('#enroll_user_selector_filter').val('');
        $('#enroll_course_selector_filter').val('');
        
        dialog.dialog({
            width: 500
        });
        
    }



    
    
    window.searchEnrolls=function(start){
        
        
        var startval=parseInt(start||0);
        window.filterData={
            varblockcoursegroup:$('#filter_varblockcoursegroup').val(),
            course_fullname:$('#filter_course_fullname').val(),
            username:$('#filter_username').val(),
            vardepartmentid:$('#filter_vardepartmentid').val(),
            varspecialityid:$('#filter_varspecialityid').val(),
            varformid:$('#filter_varformid').val(),
            varlevelid:$('#filter_varlevelid').val(),
            vargroupyear:$('#filter_vargroupyear').val(),
            vargroupcode:$('#filter_vargroupcode').val(),
            varblockname:$('#filter_varblockname').val(),
            start:startval,
            orderby:'user'
        };
        $.ajax({
                url: 'ajax.php',
                data: {op: 'enroll_list', filter:filterData  },
                dataType:'json',
                method: 'POST'
        }).done(function (reply) {
            if(reply.status==='success'){
                window.tableform.data=[];
                for(var i=0; i<reply.list.rows.length; i++){
                    window.tableform.data.push(adjustEnrollmentRow(reply.list.rows[i]));
                }
                window.tableform.handsontable.loadData(window.tableform.data);
                // draw paging links
                var pgnum=0;
                var paging_html='';
                var rows_per_page=reply.list.rows_per_page;
                
                paging_html+='<span>Total '+reply.list.n_records+'&nbsp;</span>';
                for(var i=0; i<reply.list.n_records; i+=rows_per_page){
                    pgnum++;
                    if(startval===i){
                        paging_html+='<span class="pgnum active">'+pgnum+'</span>';
                    }else{
                        paging_html+='<a href="javascript:void(searchEnrolls('+i+'))" class="pgnum">'+pgnum+'</a>';
                    }
                }
                $('#blocktablepaging').empty().html(paging_html);
            }else{
                alert('Data retrieval error: '+reply.message);
            }
        }); 
    }
    
    $(window).load(function () {
        window.tableform = {};
        window.tableform.autosaveNotification = false;
        window.tableform.loadingData = false;
        window.tableform.blocktable = document.getElementById('blocktable');
        
        var columns = [
            {data: 'action'      , readOnly: true, renderer: "html"},// 0 action  // edit|delete
            {data: 'userfullname', readOnly: true},// 1 `user`.lastname userlastname, `user`.firstname userfirstname,  userfullname
            {data: 'varblockname', readOnly: true},// 1 `user`.lastname userlastname, `user`.firstname userfirstname,  userfullname
            {data: 'vargroupcode', readOnly: true},// 2 var_group.vargroupcode,
            {data: 'vargroupyear', readOnly: true},// 3 var_group.vargroupyear,
            {data: 'varblockcoursegroup', readOnly: true},// 8 var_blockcourse.varblockcoursegroup ,
            {data: 'course_fullname', readOnly: true},// 9 course.fullname AS course_fullname
            {data: 'vardepartmentname', readOnly: true},// 4 var_department.vardepartmentname,
            {data: 'varformname', readOnly: true},// 5 var_form.varformname,
            {data: 'varlevelname', readOnly: true},// 6 var_level.varlevelname,
            {data: 'varspecialityname', readOnly: true},// 7 var_speciality.varspecialityname,
        ];
        
        window.tableform.handsontable = new Handsontable(window.tableform.blocktable, {
            data: [],
            minSpareRows: 0,
            minSpareCols: 0,
            maxRows: 1,// window.tableform.data.length,
            colHeaders: colHeaders,
            search: true,
            manualColumnResize: true,
            colWidths: colWidths,
            columns: columns
        });
        window.searchEnrolls();
        
        
        window.searchTimeout=false;
        var delayedSearch=function(){
            if(window.searchTimeout){
                clearTimeout(window.searchTimeout);
            }
            window.searchTimeout=setTimeout(window.searchEnrolls,1000);
        };

        //        window.filterData={
        //            varblockcoursegroup:$('#filter_varblockcoursegroup').val(),
        //            course_fullname:$('#filter_course_fullname').val(),
        //            username:$('#filter_username').val(),
        //            vardepartmentid:$('#filter_vardepartmentid').val(),
        //            varspecialityid:$('#filter_varspecialityid').val(),
        //            varformid:$('#filter_varformid').val(),
        //            varlevelid:$('#filter_varlevelid').val(),
        //            vargroupyear:$('#filter_vargroupyear').val(),
        //            vargroupcode:$('#filter_vargroupcode').val(),
        //            varblockname:$('#filter_varblockname').val(),
        //            start:0
        //        };

        $('#filter_vargroupcode').keyup(delayedSearch);
        $('#filter_vargroupyear').keyup(delayedSearch);
        $('#filter_varblockname').keyup(delayedSearch);
        $('#filter_username').keyup(delayedSearch);
        $('#filter_course_fullname').keyup(delayedSearch);
        $('#filter_varblockcoursegroup').keyup(delayedSearch);

        $('#filter_varlevelid').change(delayedSearch);
        $('#filter_varformid').change(delayedSearch);
        $('#filter_varspecialityid').change(delayedSearch);
        $('#filter_vardepartmentid').change(delayedSearch);





        // -------------- enroll_block_selector_filter - begin -----------------
        window.enroll_block_selector=function(){
            $('#enroll_block_selector').toggle();
        };
        window.setBlock=function(id,name){
            $('#varblockid').val(id);
            $('#varblockname').html(name);
            $('#enroll_block_selector').hide();
        };
        window.enroll_block_selector_filter_prev='';
        window.enroll_block_selector_filter_apply=function(){
            var keyword=$('#enroll_block_selector_filter').val().trim();
            if(window.enroll_block_selector_filter_prev===keyword){
                return;
            }
            window.enroll_block_selector_filter_prev=keyword;
            if(keyword.length===0){
                $('#enroll_block_selector_variants').empty();
            }
            $.ajax({
                url: 'ajax.php',
                data: {op: 'enroll_block_selector', keyword:keyword  },
                dataType:'json',
                method: 'POST'
            }).done(function (reply) {
                if(reply.status==='success'){
                    var search_results=$('#enroll_block_selector_variants');
                    search_results.empty();
                    if(reply.list.length>=20){
                        search_results.append("<div class='wrning'>"+i18n.tooManyBlocks+"</div>");
                    }
                    if(reply.list.length===0){
                        search_results.append("<div class='wrning'>"+i18n.blocksNotFound+"</div>");
                    }
                    var html, name;
                    for (var id=0; id<reply.list.length; id++) {
                        html=''; 
                        name=reply.list[id].varblockname.replace(/["']/g,'`')+','+  reply.list[id].vargroupyear+','+  reply.list[id].varformname+','+  reply.list[id].varlevelname;
                        html+="<div class=\"oneRow\"><a href='javascript:void(setBlock("+reply.list[id].id+",\""+name+"\"))'>"+name+"</a></div>";
                        search_results.append(html);
                    }
                }
            }); 
        };
        window.enroll_block_selector_filter_timeout=false;
        $('#enroll_block_selector_filter').keyup(function(){
            if(window.enroll_block_selector_filter_timeout){
                clearTimeout(window.enroll_block_selector_filter_timeout);
            }
            window.enroll_block_selector_filter_timeout=setTimeout(window.enroll_block_selector_filter_apply,1000);
        });
        // -------------- enroll_block_selector_filter - end -------------------

       
       

        // -------------- enroll_user_selector_filter - begin -----------------
        window.enroll_user_selector=function(){
            $('#enroll_user_selector').toggle();
        };
        window.setUser=function(id,name){
            $('#userid').val(id);
            $('#username').html(name);
            $('#enroll_user_selector').hide();
        };
        window.enroll_user_selector_filter_prev='';
        window.enroll_user_selector_filter_apply=function(){
            var keyword=$('#enroll_user_selector_filter').val().trim();
            var varblockid=$('#varblockid').val();
            if(window.enroll_user_selector_filter_prev===keyword){
                return;
            }
            window.enroll_user_selector_filter_prev=keyword;
            if(keyword.length===0){
                $('#enroll_user_selector_variants').empty();
            }
            $.ajax({
                url: 'ajax.php',
                data: {op: 'enroll_user_selector', keyword:keyword, varblockid:varblockid  },
                dataType:'json',
                method: 'POST'
            }).done(function (reply) {
                if(reply.status==='success'){
                    var search_results=$('#enroll_user_selector_variants');
                    search_results.empty();
                    if(reply.list.length>=20){
                        search_results.append("<div class='wrning'>"+i18n.tooManyUsers+"</div>");
                    }
                    if(reply.list.length===0){
                        search_results.append("<div class='wrning'>"+i18n.usersNotFound+"</div>");
                    }
                    var html, name, tmp;
                    for (var id=0; id<reply.list.length; id++) {
                        html=''; 
                        with(reply.list[id]){
                            tmp=userlastname+' '+userfirstname+' ('+ vargroupcode +' '+ varlevelname +' '+ varformname +' '+ varspecialityname +')';
                            name=tmp.replace(/["']/g,'`');
                            html+="<div class=\"oneRow\"><a href='javascript:void(setUser("+userid+",\""+name+"\"))'>"+name+"</a></div>";
                        }
                        search_results.append(html);
                    }
                }
            }); 
        };
        window.enroll_user_selector_filter_timeout=false;
        $('#enroll_user_selector_filter').keyup(function(){
            if(window.enroll_user_selector_filter_timeout){
                clearTimeout(window.enroll_user_selector_filter_timeout);
            }
            window.enroll_user_selector_filter_timeout=setTimeout(window.enroll_user_selector_filter_apply,1000);
        });
        // -------------- enroll_user_selector_filter - end -------------------

       


        // -------------- enroll_course_selector_filter - begin -----------------
        window.enroll_course_selector=function(){
            $('#enroll_course_selector').toggle();
        };
        window.setCourse=function(id,name){
            // console.log(id,name);
            $('#courseid').val(id);
            $('#course_fullname').html(name);
            $('#enroll_course_selector').hide();
        };
        window.enroll_course_selector_filter_prev='';
        window.enroll_course_selector_filter_apply=function(){
            var keyword=$('#enroll_course_selector_filter').val().trim();
            var varblockid=$('#varblockid').val();
            if(window.enroll_course_selector_filter_prev===keyword){
                return;
            }
            window.enroll_course_selector_filter_prev=keyword;
            if(keyword.length===0){
                $('#enroll_course_selector_variants').empty();
            }
            $.ajax({
                url: 'ajax.php',
                data: {op: 'enroll_course_selector', keyword:keyword, varblockid:varblockid  },
                dataType:'json',
                method: 'POST'
            }).done(function (reply) {
                if(reply.status==='success'){
                    var search_results=$('#enroll_course_selector_variants');
                    search_results.empty();
                    if(reply.list.length>=20){
                        search_results.append("<div class='wrning'>"+i18n.tooManyCourses+"</div>");
                    }
                    if(reply.list.length===0){
                        search_results.append("<div class='wrning'>"+i18n.noCoursesFound+"</div>");
                    }
                    var html, name, tmp;
                    for (var id=0; id<reply.list.length; id++) {
                        html=''; 
                        with(reply.list[id]){
                            tmp=varblockcoursegroup+' / '+course_fullname;
                            name=tmp.replace(/["']/g,'`');
                            html+="<div class=\"oneRow\"><a href='javascript:void(setCourse(" + courseid + ",\"" + name + "\"))'>" + name + "</a></div>";
                        }
                        search_results.append(html);
                    }
                }
            }); 
        };
        window.enroll_course_selector_filter_timeout=false;
        $('#enroll_course_selector_filter').keyup(function(){
            if(window.enroll_course_selector_filter_timeout){
                clearTimeout(window.enroll_course_selector_filter_timeout);
            }
            window.enroll_course_selector_filter_timeout=setTimeout(window.enroll_course_selector_filter_apply,1000);
        });
        // -------------- enroll_course_selector_filter - end ------------------
        
        // -------------- save enrollment - begin ------------------------------
        $('#saveupdates').click(function(){
            var postdata={
                op:'enrollmenupdate',
                enrollid : $('#enrollid').val(),
                varblockid : $('#varblockid').val(),
                userid : $('#userid').val(),
                courseid : $('#courseid').val()
            };
            $.ajax({
                url: 'ajax.php',
                data: postdata,
                dataType:'json',
                method: 'POST'
            }).done(function (reply) {
                if(reply.status==='success'){
                    window.searchEnrolls();
                    $('#dialog').dialog("close");
                }else{
                    alert(reply.message);
                }
            }); 
        });
        // -------------- save enrollment - end --------------------------------
        
       
    });
});

