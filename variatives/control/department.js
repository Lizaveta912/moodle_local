require(['jquery'], function($) {
    
    $(window).load(function(){

        window.tableform={};
        window.tableform.autosaveNotification=false;
        window.tableform.loadData=false;
        
        var departmentstable = document.getElementById('departmentstable');

        window.tableform.data=[];
        for(var id in list){
            list[id].vardepartmentvisible=(list[id].vardepartmentvisible==1);
            list[id].vardepartmentobsolete=(list[id].vardepartmentobsolete==1);
            window.tableform.data.push(list[id]);
        }

        window.tableform.handsontable = new Handsontable(departmentstable, {
            data: window.tableform.data,
            colHeaders: true,
            minSpareRows: 1,
            contextMenu: true,
            colHeaders: colHeaders,
            columns: [
                {data: 'id',editor: false},
                {data: 'vardepartmentvisible' , type: 'checkbox'},
                {data: 'vardepartmentobsolete', type: 'checkbox'},
                {data: 'vardepartmentcode'},
                {data: 'vardepartmentname'}
            ],
            afterChange: function (change, source) {
                if (source === 'loadData') {
                    return; //don't post this change
                }
                if(window.tableform.loadData){
                    return; //don't post this change
                }
                var post={};
                for(var i=0; i<change.length; i++){
                    // console.log(change[i], source);
                    if(typeof(post[change[i][0]])==='undefined'){
                        //console.log(window.tableform.data[change[i][0]]);
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
                    data: {op:'departmentupdate',data:post},
                    method:'POST',
                    dataType:'json'
                }).done(function(reply) {
                    //echo json_encode(Array('status'=>'success','rows'=>$rows));
                    if(reply.status==='success'){
                        window.tableform.loadData=true;
                        for(var irow in reply.rows){
                            window.tableform.handsontable.setDataAtRowProp([
                                [irow, "id", reply.rows[irow].id],
                                [irow, "vardepartmentvisible", parseInt(reply.rows[irow].vardepartmentvisible)===1],
                                [irow, "vardepartmentobsolete", parseInt(reply.rows[irow].vardepartmentobsolete)===1],
                                [irow, "vardepartmentcode", reply.rows[irow].vardepartmentcode],
                                [irow, "vardepartmentname", reply.rows[irow].vardepartmentname]
                            ]);
                        }
                        window.tableform.loadData=false;
                    }
                });
            },
            beforeRemoveRow:function(index,amount){
                $.ajax({
                    url: 'ajax.php',
                    data: {op:'departmentdelete',departmentid:window.tableform.handsontable.getDataAtRowProp(index, "id")},
                    method:'POST',
                    dataType:'json'
                }).done(function(reply) {
                });
            }
        });      
    });

});
