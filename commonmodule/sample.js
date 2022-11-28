var {MODEL_TABLE_NAME}Datatable;

$(document).ready(function(){
    $(".{MODEL_TABLE_NAME}-menu-link").addClass("active");
    {MODEL_TABLE_NAME}Datatable = $("#{MODEL_TABLE_NAME}_table").dataTable({
        paging: true,
        serverSide: true,
        processing: true,
        ajax: {
            url : serverUrl + "{MODEL_ROUTE_PREFIX}/ajax-data",
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
                "targets": '{MODEL_TABLE_NAME}_img', 
                render: function( data, type, row  ){
                    var $img = $("<img/>",{
                        class: "img img-thumbnail",
                        style : "height:75px;",
                        src: data
                    })
                    return $img[0].outerHTML;
                    
                } 
            },
            { 
                "targets": '{MODEL_TABLE_NAME}_price', 
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
                "targets": '{MODEL_TABLE_NAME}_status', 
                render: function( data, type, row  ){
                    return getStatusSwitchHtml( data, row, '{MODEL_TABLE_NAME}_status_switch');
                    
                } 
            },
            { 
                "targets": '{MODEL_TABLE_NAME}_action', 
                render: function( id, type, row  ){
                    var data = {
                        id: id,
                        slug: '{MODEL_ROUTE_PREFIX}',
                        delete_class: '{MODEL_TABLE_NAME}_delete'
                    };
                    return actionBtnHtml( data );
                    
                } 
            },
        ],
    });

    $("#{MODEL_TABLE_NAME}Image").uploadImage();

    $(".del_{MODEL_TABLE_NAME}_img").deleteImage();
    
    $(document).on("click", ".{MODEL_TABLE_NAME}_delete", function (e){
        var data = {
            message: "You won't be able to revert this!",
            slug: '{MODEL_ROUTE_PREFIX}',
            datatable_id: '{MODEL_TABLE_NAME}_table'
        };
        deleteRecordConfirmBox( this, e, data );
        
          
    })
    $(document).on("click", ".{MODEL_TABLE_NAME}_status_switch", function (e){
        var data = {
            message: "It will change the status of the selected flavour !",
            slug: '{MODEL_ROUTE_PREFIX}',
            datatable_id: '{MODEL_TABLE_NAME}_table'
        };

        confirmStatusChange( this, e, data );
        
        
          
    });

});