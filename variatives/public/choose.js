require(['jquery', 'jqueryui'], function ($) {
    
    window.postRatingTimeout=false;
    window.postRating={};
    window.updateRating={};
    window.updateSubspecRating={};
    
    
    window.postRatingDelayed = function(varblockid){
        if(window.postRatingTimeout){
            clearTimeout(window.postRatingTimeout);
        }
        if(!window.postRating[varblockid]){
            window.postRating[varblockid]=window.postRatingFactory(varblockid);
        }
        window.postRatingTimeout=setTimeout(window.postRating[varblockid],1000);
    };

    window.postRatingBtn = function(varblockid){
        if(!window.postRating[varblockid]){
            window.postRating[varblockid]=window.postRatingFactory(varblockid);
        }
        window.postRating[varblockid]();
    };
    
    window.moveUp=function(ev){
        var btn=$(ev.target);
        var varblockid=btn.attr('data-varblockid');
        
        var row=btn.parents('.onecourse').first();
        var prev=row.prev();
        prev.before(row);
        window.updateRatingBtn(varblockid);
    };

    window.moveDown=function(ev){
        var btn=$(ev.target);
        var varblockid=btn.attr('data-varblockid');

        var row=btn.parents('.onecourse').first();
        var next=row.next();
        next.after(row);
        window.updateRatingBtn(varblockid);
        

    };

    window.updateRatingBtn = function(varblockid){
        if(!window.updateRating[varblockid]){
            window.updateRating[varblockid]=window.updateRatingFactory(varblockid);
        }
        window.updateRating[varblockid]();
    };
    
    window.updateRatingFactory = function(varblockid){
        return function(){
            var block=$('#block'+varblockid);
            var courseids=[];
            block.find('.onecourse').each(function(i,el){
                var elem=$(el);
                elem.find('.var_cnt_val').html(i+1).removeClass('undefined').addClass('defined');
                courseids.push(elem.attr('data-courseid'));
            });
            $('#feedback').html('<div class="warning">'+i18n['Updated'] + '</div>');
        };
    };
    
    window.postRatingFactory = function(varblockid){
        return function(){
            var block=$('#block'+varblockid);
            var userid = block.attr('data-userid');
            var courseids=[];
            block.find('.onecourse').each(function(i,el){
                var elem=$(el);
                elem.find('.var_cnt_val').html(i+1).removeClass('undefined').addClass('defined');
                courseids.push(elem.attr('data-courseid'));
            });
            var post={op:'var_update_rating',varblockid:varblockid,userid:userid, courseids:courseids.join()};
            // console.log(post);
            $.ajax({
                url: '../control/ajax.php',
                data: post,
                method: 'POST'
            }).done(function () {
                $('#feedback').html('<div class="success">'+i18n['Saved'] + '</div>');
            });
        };
    };
    
    
    
    window.subspecMoveUp=function(ev){
        var btn=$(ev.target);
        var row=btn.parents('.onesubspeciality').first();
        var prev=row.prev();
        prev.before(row);
        //var varblockid=btn.attr('data-varblockid');
        //window.updateRatingBtn(varblockid);
    }
    window.subspecMoveDown=function(ev){
        var btn=$(ev.target);
        // console.log(btn);
        var row=btn.parents('.onesubspeciality').first();
        var next=row.next();
        next.after(row);
        // var varblockid=btn.attr('data-varblockid');
        // window.updateRatingBtn(varblockid);
    }
    
    
    window.updateSubspecRatingFactory = function(varsubspecialityblockid){
        return function(){
            var block=$('#varsubspecialityblockid'+varsubspecialityblockid);
            // console.log(block);
            var varsubspecialityids=[];
            block.find('.onesubspeciality').each(function(i,el){
                var elem=$(el);
                // console.log(elem);
                elem.find('.var_cnt_val').html(i+1).removeClass('undefined').addClass('defined');
                varsubspecialityids.push(elem.attr('data-varsubspecialityid'));
            });
            $('#feedback').html('<div class="warning">'+i18n['Updated'] + '</div>');
        };
    };
    
    window.updateSubspecRatingBtn = function(varsubspecialityblockid){
        if(!window.updateSubspecRating[varsubspecialityblockid]){
            //console.log('+++varsubspecialityblockid=',varsubspecialityblockid);
            window.updateSubspecRating[varsubspecialityblockid]=window.updateSubspecRatingFactory(varsubspecialityblockid);
        }
        //console.log('varsubspecialityblockid=',varsubspecialityblockid);
        window.updateSubspecRating[varsubspecialityblockid]();
    };
    
    window.postSubspecRatingBtn=function(varsubspecialityblockid){
        var block=$('#varsubspecialityblockid'+varsubspecialityblockid);
        var userid = block.attr('data-userid');
        var varsubspecialityids=[];
        block.find('.onesubspeciality').each(function(i,el){
            var elem=$(el);
            elem.find('.var_cnt_val').html(i+1).removeClass('undefined').addClass('defined');
            varsubspecialityids.push(elem.attr('data-varsubspecialityid'));
        });
        var post={op:'updatesubspecialityrating',varsubspecialityblockid:varsubspecialityblockid,userid:userid, varsubspecialityids:varsubspecialityids.join()};
        // console.log(post);
        $.ajax({
            url: '../control/ajax.php',
            data: post,
            method: 'POST'
        }).done(function () {
            $('#feedback').html('<div class="success">'+i18n['Saved'] + '</div>');
        });
    };
    
    $(window).load(function () {
        $( ".sortablecourses" ).sortable({
            axis: "y",
            update: function( event, ui ) {
                window.updateRatingBtn($(ui.item).attr('data-varblockid'));
            }
        });
        $('.order_btn_down').click(window.moveDown);
        $('.order_btn_up').click(window.moveUp);
        
        
        $( ".sortablesubspeciality" ).sortable({
            axis: "y",
            update: function( event, ui ) {
                // console.log(ui);
                window.updateSubspecRatingBtn($(ui.item).attr('data-varsubspecialityblockid'));
            }
        });
        $('.subspec_btn_down').click(window.subspecMoveDown);
        $('.subspec_btn_up').click(window.subspecMoveUp);
        
        
    });

});