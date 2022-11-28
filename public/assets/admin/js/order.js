var orderDatatable;

$(document).ready(function(){
    $(".order-menu-link").addClass("active");
    orderDatatable = $("#order_table").dataTable({
        paging: true,
        serverSide: true,
        processing: true,
        ajax: {
            url : serverUrl + "orders/ajax-data",
            type: "post",
            headers:{
                "X-CSRF-TOKEN" : requestToken,
            }
        },
        "columns": [
            { "data": "id", class:"text-center" },
            { "data": "day_wise_id", class:"text-center" },
            { "data": "table_name", class:"text-center" },
            { "data": "table.dining_status", class:"text-center" },
            { "data": "customer.name", class:"text-center", 'orderable' : false },
            { "data": "order_items_count", class:"text-center", 'orderable' : false },
            { "data": "total", class:"text-center" },
            { "data": "order_date", class:"text-center" },
            { "data": "status", class:"text-center" },
            { "data": "id", class:"text-center", 'orderable' : false }
        ],
        "columnDefs": [
            {
                "targets": 'order_price',
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
                "targets": 'table_status',
                render: function( data, type, row  ){
                    $label = 'Free';
                    $labelClass = 'badge-primary';
                    if( data == 1 ){
                        $label = 'Serving';
                        $labelClass = 'badge-danger';
                    }

                    return $("<label/>",{
                        text: $label,
                        class: 'badge ' + $labelClass,
                    })[0].outerHTML;

                }
            },
            {
                "targets": 'order_status',
                render: function( data, type, row  ){
                    $label = 'Pending';
                    $labelClass = 'badge-warning';
                    if( data == 2 ){
                        $label = 'Preparing';
                        $labelClass = 'badge-primary';
                    }
                    if( data == 3 ){
                        $label = 'Done';
                        $labelClass = 'badge-success';
                    }

                    return $("<label/>",{
                        text: $label,
                        class: 'badge ' + $labelClass,
                    })[0].outerHTML;

                }
            },
            {
                "targets": 'order_action',
                render: function( id, type, row  ){
                    var data = {
                        id: id,
                        slug: 'orders',
                        delete_class: 'order_delete'
                    };
                    return actionBtnHtml( data, true );

                }
            },
        ],
        order:[
            [0, 'desc']
        ]
    });

    $(document).on("click", ".color_delete", function (e){
        var data = {
            message: "You won't be able to revert this!",
            slug: 'toppings',
            datatable_id: 'color_table'
        };
        deleteRecordConfirmBox( this, e, data );


    })
    $(document).on("click", ".color_status_switch", function (e){
        var data = {
            message: "It will change the status of the selected topping !",
            slug: 'toppings',
            datatable_id: 'color_table'
        };

        confirmStatusChange( this, e, data );



    });

});
