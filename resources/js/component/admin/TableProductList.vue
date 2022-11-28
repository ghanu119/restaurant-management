<template>
    <div class="container-fluid product-list" v-if="productsData && productsData.length">
        <div class="row product-list-row">
            <div class="col-12 mb-3 border-bottom pb-1 d-flex justify-content-between">
                <div class="table-title">
                    Table: {{ item.name }}
                </div>
                <div class="billing-amount" v-if="item.order_data && item.order_data.placed && item.order_data.placed.total">
                    Amount: &#8377;{{ item.order_data.placed.total }}
                </div>
            </div>
            <div class="col-12 mb-2 border-bottom pb-2">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link mouse-pointer" :class="{'active': selected.view == 'product_list'}" @click.prevent="toggleView('product_list')" aria-current="page" href="#">Product List</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mouse-pointer" :class="{'active': selected.view == 'cart'}" @click.prevent="toggleView('cart')">Cart <span class="badge badge-warning bg-gradient-warning text-white" v-if="cartLength">{{ cartLength }}</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mouse-pointer" :class="{'active': selected.view == 'ordered'}" @click.prevent="toggleView('ordered')">Ordered</a>
                    </li>
                </ul>
            </div>
            <div class="col-12" v-if="selected.view == 'product_list'">
                <div class="card text-center">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item" v-for="(pc, pcIndex) in productCategories" :key="'cat_Tab_'+pcIndex" @click="changeCategoryPanel( pc.id )">
                                <a class="nav-link mouse-pointer" :class="{'active': ((selected.category_tab == null && pcIndex == 0) || (selected.category_tab !== null && selected.category_tab == pc.id))}"  aria-current="true" href="#">{{ pc.name }}</a>
                            </li>
                        </ul>
                    </div>
                    <template v-for="(productList, pIndex) in productsData">
                        <div class="card-body"  v-if="((selected.category_tab == null && pIndex == 0) || (selected.category_tab !== null && selected.category_tab == productList.id))" :key="'product_list_' + pIndex">

                            <div class="row text-center align-items-baseline mt-2"  >
                                <template v-for="(pData, productKey) in productList.products" >
                                    <div class="col-12"  :key="'product_row_'+productKey" >
                                        <div class="row pt-1 border-bottom mb-2">
                                            <div class="col-6 h5 text-left text-secondary">
                                                <label>{{ pData.name }}</label>
                                            </div>
                                            <div class="col-3 h4">
                                                <label>&#8377;{{ pData.price }}</label>
                                            </div>
                                            <div class="col-3 pt-2 p-0 pb-2">
                                                <button class="btn rounded-pill font-semibold btn-outline-info" type="button" @click="addItem( pData )">
                                                    <i class="fa fa-plus"></i> Add
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                </div>

                        </div>
                    </template>
                </div>
            </div>
            <div class="col-12" v-else-if="selected.view == 'cart'" @orderPlaced="orderPlaced">
                <admin-table-order-view :item="item"></admin-table-order-view>
            </div>
            <div class="col-12" v-else-if="selected.view == 'ordered'">
                <admin-table-placed-order :orders="item.order_data.placed" @onTableFree="makeThisTableFree"></admin-table-placed-order>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:{
        item:{
            type: Object,
            default: () => {
                return null
            }
        },
        productsData:{
            type: Array,
            default: () => {
                return [];
            }
        }
    },
    data(){
        return {
            loading: false,
            selected:{
                category_tab: null,
                view: 'product_list'
            }
        }
    },
    created (){

    },
    computed:{
        productCategories () {
            return this.productsData.map( d => {
                return {
                    id: d.id,
                    name: d.name
                };
            })
        },
        allProducts () {
            let products = [];
            this.productsData.map( d => {
                products = products.concat( d.products );
            });
            return products;
        },
        cartLength(){
            if( this.item.order_data && this.item.order_data.selected && Array.isArray( this.item.order_data.selected ) ){
                return this.item.order_data.selected.length;
            }
            return 0;
        }
    },
    methods:{
        addItem ( productData ){

            if( this.item.order_data && this.item.order_data.selected && Array.isArray( this.item.order_data.selected ) ){
                productData.qty = 1;
                productData.subTotal = productData.price;
                this.item.order_data.selected.push( productData );
                Vue.$toast.open({
                    message: 'Item Added!',
                    type: 'info',
                    position: 'top-right',
                    duration: 1000
                });
            }

        },
        changeCategoryPanel( id ){
            this.selected.category_tab = id;
        },
        toggleView( viewId ){
            this.selected.view = viewId;
        },
        orderPlaced(){
            this.toggleView('ordered');
        },
        makeThisTableFree(){
            this.item.dining_status = 0;
            this.item.order_data.placed = {};
            this.item.order_data.selected = [];
        }
    }
}
</script>
