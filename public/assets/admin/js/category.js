var categoryDatatable;

$(document).ready(function(){
    $(".category-menu-link").addClass("active");
    categoryDatatable = $("#category_table").dataTable({
        paging: true,
        serverSide: true,
        processing: true,
        ajax: {
            url : serverUrl + "category/ajax-data",
            type: "post",
            headers:{
                "X-CSRF-TOKEN" : requestToken,
            }
        },
        "columns": [
            { "data": "id", class:"text-center" },
            { "data": "name", class:"text-center" },
            { "data": "thumbnail_image_url", class:"text-center", 'orderable' : false },
            { "data": "status", class:"text-center" },
            { "data": "id", class:"text-center", 'orderable' : false }
        ],
        "columnDefs": [
            {
                "targets": 'category_img',
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
                "targets": 'category_status',
                render: function( data, type, row  ){
                    return getStatusSwitchHtml( data, row, 'category_status_switch');

                }
            },
            {
                "targets": 'category_action',
                render: function( id, type, row  ){
                    var data = {
                        id: id,
                        slug: 'category',
                        delete_class: 'category_delete'
                    };
                    return actionBtnHtml( data );

                }
            },
        ],
    });

    $("#categoryImage").uploadImage();

    $(".category_img").deleteImage();

    $(document).on("click", ".category_delete", function (e){
        var data = {
            message: "You won't be able to revert this!",
            slug: 'category',
            datatable_id: 'category_table'
        };
        deleteRecordConfirmBox( this, e, data );


    })
    $(document).on("click", ".category_status_switch", function (e){
        var data = {
            message: "It will change the status of the selected Table !",
            slug: 'category',
            datatable_id: 'category_table'
        };

        confirmStatusChange( this, e, data );



    });

});
