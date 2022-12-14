var extraChargeDatatable;

$(document).ready(function(){
    $(".extra-charges-menu-link").addClass("active");
    extraChargeDatatable = $("#extra_charges_table").dataTable({
        paging: true,
        serverSide: true,
        processing: true,
        ajax: {
            url : serverUrl + "extra-charges/ajax-data",
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
                "targets": 'extra_charges_img',
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
                "targets": 'extra_charges_price',
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
                "targets": 'extra_charges_status',
                render: function( data, type, row  ){
                    return getStatusSwitchHtml( data, row, 'extra_charges_status_switch');

                }
            },
            {
                "targets": 'extra_charges_action',
                render: function( id, type, row  ){
                    var data = {
                        id: id,
                        slug: 'extra-charges',
                        delete_class: 'extra_charges_delete'
                    };
                    return actionBtnHtml( data );

                }
            },
        ],
    });

    $("#extraChargesImage").uploadImage();

    $(".del_extra_charges_img").deleteImage();

    $(document).on("click", ".extra_charges_delete", function (e){
        var data = {
            message: "You won't be able to revert this!",
            slug: 'extra-charges',
            datatable_id: 'extra_charges_table'
        };
        deleteRecordConfirmBox( this, e, data );


    })
    $(document).on("click", ".extra_charges_status_switch", function (e){
        var data = {
            message: "It will change the status of the selected Extra Charges !",
            slug: 'extra-charges',
            datatable_id: 'extra_charges_table'
        };

        confirmStatusChange( this, e, data );



    });

});
