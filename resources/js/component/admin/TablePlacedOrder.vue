<template>
    <div class="container">
        <div class="row" v-if="orders && orders.order_items && orders.order_items.length">
            <div class="col-12">
                <fieldset class="flex">
                    <h4 >
                        #{{ orders.day_wise_id }}
                    </h4>
                    <button class="btn btn-success btn-sm ml-2" type="button" @click="makeTableFree()">
                        <i class="fa fa-check text-md-lg"></i>
                    </button>
                </fieldset>
                <!-- <div class="card-body" v-for="(productList, pIndex) in item.order_data.selected" :key="'product_list_' + pIndex"> -->
                <div class="card-body" >
                    <template v-for="(pData, productKey) in orders.order_items">
                        <div class="card mb-1" :key="'product_row_'+productKey">
                            <div class="card-body">
                                <div class="row align-items-baseline">
                                    <div class="col-md-4 col-9">
                                        <label>{{ pData.product_name }} <span class="badge badge-darks d-sm-none">x{{ pData.quantity }}</span></label>
                                        <div v-if="pData.product_charges && pData.product_charges.length">
                                            <template v-for="(sc, scIdx) in pData.product_charges">
                                                <div v-if="sc.quantity > 0" :key="'selected_ec_'+scIdx">
                                                    <span class="badge bg-info text-white">
                                                        {{ sc.name }} X {{ sc.quantity }}
                                                    </span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-4 col-4 d-none d-sm-block">
                                        <label>&#8377;{{ pData.product_price }}</label>
                                    </div>
                                    <div class="col-md-2 col-sm-4 col-4 d-none d-sm-block">
                                        {{ pData.quantity }}
                                    </div>

                                    <div class="col-md-2 col-sm-4 col-3">
                                        &#8377;{{ pData.sub_total }}
                                    </div>
                                    <div class="col-md-2 text-right text-sm-left">
                                        <button class="btn btn-sm btn-danger" type="button" @click="removeItem(pData.id, productKey)" >
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </template>
                </div>
            </div>
            <div class="col-12 mt-2 ">
                <div class="card">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                Total
                            </div>
                            <div class="col-6">
                                <big>
                                    <strong>
                                        &#8377;{{ orders.total }}
                                    </strong>
                                </big>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-2 ">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-end">
                            <div class="col-6">
                                <button class="btn btn-success" type="button" @click="makeTableFree()">
                                    Make This Table Free
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-else>
            <div class="col-12">
                <div class="alert alert-info">
                    <p>
                        No Orders!
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:{
        orders:{
            type: [],
            default: () => {
                return []
            }
        }
    },
    data(){
        return {
            loading: false,

        }
    },
    computed:{

    },
    methods:{

        removeItem(productItemId, productKey){
            const data = {
                item_id: productItemId
            }
            this.$swal({
                title: "Are you sure?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton:true,
                showConfirmButton: true
            }).then( res => {
                if( res.isConfirmed ){
                    axios.post( this.API_ENDPOINT + 'delete-order-item', data )
                        .then(
                            ( res )=>{
                                this.orders = res.data.order_data;

                                Vue.$toast.open({
                                    message: 'Success! Order item successfully removed.',
                                    type: 'success',
                                    position: 'top-right',
                                    duration: 1000
                                });
                            },
                            (err)=>{

                                Vue.$toast.open({
                                    message: 'Error! Something wrong. Try again.',
                                    type: 'error',
                                    position: 'top-right',
                                    duration: 1000
                                });
                            }
                        )

                }
            });
        },
        makeTableFree(){
            this.$swal({
                title: "Are you sure?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton:true,
                showConfirmButton: true
            }).then( res => {
                if( res.isConfirmed ){
                    const data = {
                        table_id: this.orders.table_id
                    }
                    axios.post( this.API_ENDPOINT + 'make-table-free', data)
                        .then(
                            ( res )=>{
                                this.$emit('onTableFree');

                                Vue.$toast.open({
                                    message: 'Success! Now this table is free.',
                                    type: 'success',
                                    position: 'top-right',
                                    duration: 1000
                                });
                            },
                            (err)=>{

                                Vue.$toast.open({
                                    message: 'Error! Something wrong. Try again.',
                                    type: 'error',
                                    position: 'top-right',
                                    duration: 1000
                                });
                            }
                        )
                }
            });

        }
    }
}
</script>
