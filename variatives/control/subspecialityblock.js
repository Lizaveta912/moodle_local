require(['jquery', 'jqueryui'], function ($) {




    $(window).load(function () {

        $('#saveupdates').click(function () {
            var record = {};
            $('.formel').each(function (id, el) {
                var elt = $(el);
                if (elt.attr('type') === 'checkbox') {
                    record[elt.attr('name')] = elt.prop('checked');
                } else {
                    record[elt.attr('name')] = elt.val();
                }
            });
            record.id = $('input.formel[name="id"]').val();
            //console.log(record);
            var post = {};
            if (record.id === "" || record.id === "0") {
                record.op = 'create';
                post[0] = record;
            } else {
                record.op = 'update';
                post[record.id] = record;
            }
            $.ajax({
                url: 'ajax.php',
                data: {op: 'subspecialityblockupdate', data: post},
                method: 'POST',
                dataType: 'json'
            }).done(function (reply) {
                if (reply.status === 'success' && reply.block[0]) {
                    var data = reply.block[0];
                    if (data) {
                        $('input.formel[name="id"]').val(data.id);

                    }
                }
            });
        });

    });
});

