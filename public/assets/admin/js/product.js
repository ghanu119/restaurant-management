var productsDatatable;

$(document).ready(function(){
    $(".products-menu-link").addClass("active");
    productsDatatable = $("#products_table").dataTable({
        paging: true,
        serverSide: true,
        processing: true,
        ajax: {
            url : serverUrl + "products/ajax-data",
            type: "post",
            headers:{
                "X-CSRF-TOKEN" : requestToken,
            }
        },
        "columns": [
            { "data": "id", class:"text-center" },
            { "data": "name", class:"text-center" },
            { "data": "thumbnail_image_url", class:"text-center", 'orderable' : false },
            { "data": "price", class:"text-center" },
            { "data": "status", class:"text-center" },
            { "data": "id", class:"text-center", 'orderable' : false }
        ],
        "columnDefs": [
            {
                "targets": 'products_img',
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
                "targets": 'products_price',
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
                "targets": 'products_status',
                render: function( data, type, row  ){
                    return getStatusSwitchHtml( data, row, 'products_status_switch');

                }
            },
            {
                "targets": 'products_action',
                render: function( id, type, row  ){
                    var data = {
                        id: id,
                        slug: 'products',
                        delete_class: 'products_delete'
                    };
                    return actionBtnHtml( data );

                }
            },
        ],
    });

    $("#productsImage").uploadImage();

    $(".del_products_img").deleteImage();

    $(document).on("click", ".products_delete", function (e){
        var data = {
            message: "You won't be able to revert this!",
            slug: 'products',
            datatable_id: 'products_table'
        };
        deleteRecordConfirmBox( this, e, data );


    })
    $(document).on("click", ".products_status_switch", function (e){
        var data = {
            message: "It will change the status of the selected product !",
            slug: 'products',
            datatable_id: 'products_table'
        };

        confirmStatusChange( this, e, data );



    });

});
