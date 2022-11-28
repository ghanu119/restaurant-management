<template>
    <div class="container">
        <div class="row">
            <div class="col-12 pt-3 text-center" v-show="show.tableView">
                <div class="head-title align-self-center">
                    <span>
                        Tables
                    </span>
                </div>
            </div>
            <div class="col-11 pb-2 pt-1 status-count" v-show="show.tableView">
                    <span class="d-flex">
                        {{ freeTablesCount }} <div class="h-pipe"></div> <span class="status-title">Available</span>

                    </span>
                    <span class="d-flex">
                        {{ (tablesData.length) - freeTablesCount }} <div class="h-pipe"></div><span class="status-title">Serving</span>

                    </span>
            </div>
            <hr />
            <div class="col-12 tables-list" v-show="show.tableView">
                <div class="row">
                    <template v-for="(item, index) in tablesData" >
                        <div class="col-md-4 border shadow" :class="{'serving': item.dining_status == 1}" :key="'table_detail'+ index" @click="showOrderForm(item)">

                            <div class="table-capacity upper-side" >
                                <template v-for="cI in item.capacity" >
                                    <i class="fa fa-user"  v-if="cI % 2 == 0" :key="'capacity_' + cI"></i>
                                </template>
                            </div>

                            <div class="table-view" :class="{'serving': item.dining_status == 1}">
                                <span class="table-name">
                                    {{ item.name }}
                                </span>
                                <br>
                                <span class="table-billing" v-if="item.order_data && item.order_data.placed && item.order_data.placed.total">
                                    <span class="table-billing-icon">&#8377;</span>{{ item.order_data.placed.total || 0 }}
                                </span>
                            </div>
                            <div class="table-capacity down-side" v-if="item.capacity > 1">
                                <template v-for="cI in item.capacity" >
                                    <i class="fa fa-user"  v-if="cI % 2 == 0" :key="'capacity_' + cI"></i>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <div class="col-md-12 p-2" v-if="orderFormTable !== null && !show.tableView" @click="showAllTables">
                <i class="fa fa-arrow-left"></i>
            </div>
            <div class="col-md-12" v-if="orderFormTable !== null && !show.tableView">
                <template v-for="(item, index) in tablesData" >
                    <admin-table-product-list v-show="item.id == orderFormTable" :key="'product_list_' + index" :item="item" :products-data="data.products" />
                </template>

            </div>
        </div>
    </div>
</template>
<style type="text/css">
    html{
        font-family: monospace;
    }
    body{
        background: #e8e8e8;
    }
    .head-title{
        font-size: 1.7rem;
        font-family: cursive;
        color: #000;
        text-align: center;
    }
    .head-title span{
        border-bottom: #20388f solid 1px;

    }
    .status-count{
        background: #FFF;
        margin: auto;
        margin-top: 5px;
        margin-bottom: 5px;
        border-radius: 5px;
    }
    .status-count span.d-flex{
        gap: 5px;
        margin-bottom: 2px;
    }
    .status-count span.d-flex .status-title{
        color: #adaeb2;
    }
    .status-count span.d-flex .h-pipe{
        border-right: #000 solid 0.5px;
    }
    .status-count span{
        font-size: 1.2rem !important;
        font-family: cursive;
    }
    .status-count span .badge{
        font-size: 1rem !important;
    }
    .tables-list .col-md-4 {
        height: 150px;
        margin-bottom: 25px;
        margin-right: 7px;
        flex: 30%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: #FFF;
    }
    .tables-list .table-view {
        padding: 10px;
        text-align: center;
        align-self: center;
        background: #f3f3f3;
        width: 80%;
        /* position: absolute;
        top: 50%;
        left: 50%;
        transform: translate( -50%, -50%); */
        border-radius: 30px;
        color: #000;
        font-weight: bold;
        font-family: monospace;
        font-size: 1.8rem;
        line-height: 17px;
    }
    .tables-list .col-md-4.serving .table-view{
        background: #ffe1e1;
        color: #000;
    }
    .tables-list .table-view .table-billing-icon{
        font-size: 0.9rem;
    }
    .tables-list .table-view .table-billing{
        font-size: 1.2rem;
        letter-spacing: 1px;
        color: #0FAFD2;
    }
    .tables-list .table-capacity.upper-side{
        margin-bottom: 10px;
    }
    .tables-list .table-capacity.down-side{
        margin-top: 10px;
    }
    .tables-list .table-capacity{
        font-size: 1.6rem;
        color: #000;
        display: flex;
        width: 80%;
        justify-content: space-around;
        align-self: center;
    }
    .fa-arrow-left{
        font-size: 3rem;
        color: #000;
    }
    .product-list .product-list-row{
        background: #fff;
    }
    .product-list .product-list-row .table-title,
    .product-list .product-list-row .billing-amount{
        padding-top: 3px;
        font-size: 1.2rem;
        font-weight: bold;
        color: #676a6e;
    }
    .tables-list .col-md-4.serving .table-capacity{
        color: #707878;
    }
    @media only screen and (max-width: 763px) {
        .tables-list .col-md-4{
            flex: 45%;
            border: #0FAFD2 solid 1px !important;
        }
    }
    @media only screen and (max-width: 435px) {
        .tables-list .row{
            padding: 3px;
        }
        .tables-list .col-md-4{
            flex: 100%;
        }
    }
</style>
<script>
export default {
    props:{

    },
    data(){
        return {
            loading: false,
            data:{
                tables: [],
                products: [],
                extraCharges: []
            },
            default:{
                tableData:{
                    showOrderForm: false,
                    order_data: {
                        'order_id': null,
                        'table_id': null,
                        'selected': [],
                        'placed': {}
                    },
                    selectedProduct: "",
                    selectedCategory: null
                }
            },
            orderFormTable: null,
            show:{
                tableView: true
            }
        }
    },
    created (){
        this.init();
    },
    computed:{
        tablesData (){
          return this.data.tables.sort( (a,b) =>{
            return a.dining_status < b.dining_status ? -1 : 1;
          })
        },
        productCategories () {
            return this.data.products.map( d => {
                return {
                    id: d.id,
                    name: d.name
                };
            })
        },
        allProducts () {
            let products = [];
            this.data.products.map( d => {
                products = products.concat( d.products );
            });
            return products;
        },
        freeTablesCount (){
          return this.data.tables.filter( d => d.dining_status == 0).length
        },
    },
    methods:{
        init(){
            this.getTables();
            this.getProducts();
        },
        getTables(){
            axios.post( this.API_ENDPOINT + 'get-tables')
                .then(
                    ( res )=>{
                        const data = res.data.map( d => {
                            let defaultData = JSON.parse( JSON.stringify( this.default.tableData ) );
                            defaultData.table_id = d.id;
                            if( d.dining_status == 1 && d.current_order && d.current_order.id ){
                                defaultData.order_data.placed = d.current_order;
                                delete d.current_order;
                            }
                            return Object.assign(d, defaultData);
                        })
                        console.log( data );
                        this.data.tables = data;
                    },
                    ( err ) => {

                        console.log(err, "ERRR");
                    }
                ).finally( () => this.loadingUpdate(false));
        },
        getProducts(){
            axios.post( this.API_ENDPOINT + 'products')
                .then(
                    ( res )=>{
                        this.data.products = res.data;
                    },
                    ( err ) => {

                        console.log(err, "ERRR");
                    }
                ).finally( () => this.loadingUpdate(false));
        },
        loadingUpdate( loaderVal ){
            this.loading = loaderVal;
        },
        showOrderForm ( item ) {
            this.show.tableView = false;
            this.orderFormTable = item.id;
        },
        showAllTables(){
            this.init();
            this.show.tableView = true;
        },
        getCapacityClass( capacity ){
            let classes = [ 'pos-' + capacity ];
            if( capacity %2 == 0){
                classes.push( 'bottom');
            }
            return classes.concat(" ");
        }
    }
}
</script>
