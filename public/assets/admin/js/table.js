var tableDatatable;

$(document).ready(function(){
    $(".tables-menu-link").addClass("active");
    tableDatatable = $("#tables_table").dataTable({
        paging: true,
        serverSide: true,
        processing: true,
        ajax: {
            url : serverUrl + "tables/ajax-data",
            type: "post",
            headers:{
                "X-CSRF-TOKEN" : requestToken,
            }
        },
        "columns": [
            { "data": "id", class:"text-center" },
            { "data": "name", class:"text-center" },
            { "data": "thumbnail_image_url", class:"text-center", 'orderable' : false },
            { "data": "capacity", class:"text-center" },
            { "data": "price", class:"text-center" },
            { "data": "status", class:"text-center" },
            { "data": "id", class:"text-center", 'orderable' : false }
        ],
        "columnDefs": [
            {
                "targets": 'tables_img',
                render: function( data, type, row  ){
                    if( data === null ){
                        return '-';
                    }
                    var $img = $("<img/>",{
                        class: "img img-thumbnail",
                        style : "height:75px;",
                        src: data
                    })
                    return $img[0].outerHTML;

                }
            },
            {
                "targets": 'tables_price',
                render: function( data, type, row  ){
                   if( data > 0 ){
                       var $rupeeIco = $("<i/>",{
                           class: "fa fa-rupee-sign"
                       })[0].outerHTML;
                       return $rupeeIco + " " + data;
                   }
                   return " - ";
                }
            },
            {
                "targets": 'tables_status',
                render: function( data, type, row  ){
                    return getStatusSwitchHtml( data, row, 'tables_status_switch');

                }
            },
            {
                "targets": 'tables_action',
                render: function( id, type, row  ){
                    var data = {
                        id: id,
                        slug: 'tables',
                        delete_class: 'tables_delete'
                    };
                    return actionBtnHtml( data );

                }
            },
        ],
    });

    $("#tablesImage").uploadImage();

    $(".tables_img").deleteImage();

    $(document).on("click", ".tables_delete", function (e){
        var data = {
            message: "You won't be able to revert this!",
            slug: 'tables',
            datatable_id: 'tables_table'
        };
        deleteRecordConfirmBox( this, e, data );


    })
    $(document).on("click", ".tables_status_switch", function (e){
        var data = {
            message: "It will change the status of the selected Table !",
            slug: 'tables',
            datatable_id: 'tables_table'
        };

        confirmStatusChange( this, e, data );



    });

});
