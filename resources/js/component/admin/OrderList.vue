<template>
    <div class="container ">
        <div class="row order-list" v-if="item.order_data && item.order_data.selected && item.order_data.selected.length">
            <div class="col-12 p-0">

                <!-- <div class="card-body" v-for="(productList, pIndex) in item.order_data.selected" :key="'product_list_' + pIndex"> -->
                <template v-for="(pData, productKey) in item.order_data.selected">
                    <div class="card mb-1" :key="'product_row_'+productKey">
                        <div class="card-body">
                            <div class="row align-items-baseline">
                                <div class="col-md-4 col-8 mb-sm-0 mb-2">
                                    <label class="h5 text-secondary ">{{ pData.name }}</label>
                                    <br>
                                    <span class="h4 text-gray-600"> &#8377;{{ pData.price }} </span>
                                    <div v-if="pData.charges_list && pData.charges_list.length">
                                        <template v-for="(sc, scIdx) in pData.charges_list">
                                            <div v-if="sc.qty > 0" :key="'selected_ec_'+scIdx">
                                                <span class="badge bg-info text-white">
                                                    {{ sc.extra_charge.name }} X {{ sc.qty }}
                                                </span>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                <div class="col-md-2 col-4 p-0">
                                    <div class="flex flex-row flex-nowrap justify-content-between align-items-baseline">

                                        <button class="btn" :disabled="pData.qty <= 1" type="button" @click="updateProductQty(pData, productKey, -1)">
                                            <i class="fa fa-minus-circle"></i>
                                        </button>
                                        <span class="h5">
                                            {{ pData.qty }}
                                        </span>
                                        <button class="btn" type="button" @click="updateProductQty(pData, productKey, +1)">
                                            <i class="fa fa-plus-circle"></i>
                                        </button>
                                    </div>
                                    <div class="text-center p-1 h4 text-info">
                                        &#8377;{{ pData.subTotal }}

                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-12 mb-sm-0 mb-2 text-right">
                                    <button class="btn btn-sm btn-info" type="button" v-if="pData.charges_list && pData.charges_list.length" @click="toggleExtraCharge(productKey)">
                                        Add Extra
                                    </button>
                                    <button class="btn btn-sm btn-danger" type="button" @click="removeItem(productKey)" >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-collapse collapse" :class="{'show': show.extraCharge == productKey, 'collapsed': show.extraCharge != productKey}" :key="'product_collapse_'+productKey" v-if="pData.charges_list && pData.charges_list.length">
                        <div class="accordion-body">
                            <div class="row justify-content-end">
                                <div class="col-md-4 col-6 mb-2 border-bottom pb-3" v-for="(ec, ecIdx) in pData.charges_list" :key="'extra_charge' + ecIdx">
                                    <div class="row justify-content-end text-center align-items-center">
                                        <div class="col-12">
                                            <span class="badge bg-primary text-white text-uppercase">
                                                {{ ec.extra_charge.name }}
                                            </span>
                                        </div>
                                        <div class="col-12 h5">
                                            &#8377;{{ ec.price || ec.extra_charge.price }}
                                        </div>
                                        <div class="col-12">
                                            <button class="btn" v-if="ec.qty > 0" type="button" @click="updateProductExtaChargeQty(productKey, ecIdx, -1)">
                                                <i class="fa fa-minus-circle"></i>
                                            </button>
                                            <span class="h5" v-if="ec.qty > 0">
                                                {{ ec.qty }}
                                            </span>
                                            <button class="btn" type="button" @click="updateProductExtaChargeQty(productKey, ecIdx, +1)">
                                                <i class="fa fa-plus-circle"></i>
                                            </button>
                                            <!-- <input type="number" @change="getProductSubTotal(productKey)" class="form-control text-center" v-model="ec.qty" placeholder="Enter Quantity"> -->
                                        </div>
                                        <div class="col-12 h5 font-semibold text" v-if="ec.qty > 0">
                                            &#8377;{{ ec.qty * (ec.price || ec.extra_charge.price ) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            <div class="col-12 order-total">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="row text-center h5">
                            <div class="col-6">
                                Grand Total
                            </div>
                            <div class="col-6 amount">
                                <span class="amount-icon">&#8377;</span>{{ orderTotal }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-2 " v-if="!(item.order_data.placed && item.order_data.placed.id)">
                <div class="card order-">
                    <div class="card-body">
                        <div class="row justify-content-end">
                            <div class="col-md-8 col-sm-12">
                                <div class="form-group form-inline">
                                    <input type="text" v-model="user.name" class="form-control" placeholder="Customer Name">
                                    <input type="text" v-model="user.mobile" class="ml-2 form-control" placeholder="Mobile no.">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-2 ">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="row justify-content-end">
                            <div class="col-md-6 col-sm-12 d-flex justify-content-between">
                                <button class="btn btn-danger" type="button" @click="clearCart()">
                                    Clear All
                                </button>
                                <button class="btn btn-primary" type="button" @click="placeOrder()">
                                    Place Order
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
        item:{
            type: Object,
            default: () => {
                return null
            }
        }
    },
    data(){
        return {
            loading: false,
            show:{
                extraCharge: null
            },
            user:{
                name: null,
                mobile: null
            }
        }
    },
    computed:{
        orderTotal (){
            if( this.item && this.item.order_data && this.item.order_data.selected ){
                let sum = 0;
                this.item.order_data.selected.forEach( d => {
                    sum += d.subTotal
                });
                return sum;
            }
            return 0;
        }
    },
    methods:{
        toggleExtraCharge( key ){
            if( this.show.extraCharge == key ){
                this.show.extraCharge = null;
            }else{

                this.show.extraCharge = key;
            }
        },
        getProductSubTotal ( productKey ){
            const d = this.item.order_data.selected[ productKey ];
            let total = 0;
            let price = d.price;
            if( d.charges_list && d.charges_list.length ){
                d.charges_list.forEach( ec => {
                    if( ec.qty > 0 ){
                        let ecPrice = ec.price;
                        if( (ecPrice == 0 || ec.price == null ) && ec.extra_charge && ec.extra_charge.price ){
                            ecPrice = ec.extra_charge.price;
                        }
                        ecPrice *= ec.qty;
                        price += ecPrice;
                    }
                })
            }
            total = price * d.qty;
            this.$nextTick( () => {
                d.subTotal = total;
                this.$set( this.item.order_data.selected, productKey, d);
            })
        },
        removeItem(productKey){
            if( this.item && this.item.order_data && this.item.order_data.selected ){
                this.$swal({
                    title: "Are you sure?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    showCancelButton:true,
                    showConfirmButton: true
                }).then( res => {
                    if( res.isConfirmed ){
                        this.item && this.item.order_data && this.item.order_data.selected.splice( productKey, 1 );
                        Vue.$toast.open({
                            message: 'Item removed!',
                            type: 'error',
                            position: 'top-right',
                            duration: 1000
                        });

                    }
                });
            }
        },
        clearCart(){
            this.item.order_data.selected = [];
        },
        updateProductQty( product, prodIdx, qty ){
            const newQty = product.qty + qty;
            console.log( newQty );
            if( newQty > 1 ){
                product.qty = newQty;
            }else{
                product.qty = 1;
            }
            this.getProductSubTotal( prodIdx );
        },
        updateProductExtaChargeQty( productKey, ecKey, qty ){
            const d = this.item.order_data.selected[ productKey ];
            if( d && d.charges_list && d.charges_list.length ){
                const chargeList = d.charges_list[ ecKey];
                if( chargeList ){
                    const newQty = (chargeList.qty || 0) + qty;
                    if( newQty > 0 ){
                        chargeList.qty = newQty
                    }else{
                        chargeList.qty = 0
                    }
                    this.getProductSubTotal( productKey );
                }
            }
        },
        placeOrder(){
            let orderId = undefined;

            if( this.item && this.item.order_data && this.item.order_data.placed && this.item.order_data.placed.id ){
                orderId = this.item.order_data.placed.id;
            }
            if( this.item && this.item.order_data && this.item.order_data.selected ){
                let orderData = {
                    name: this.user.name,
                    mobile: this.user.mobile,
                    order_id: orderId,
                    table_id: this.item.id,
                    products: []
                };
                this.item.order_data.selected.forEach( d => {
                    if( d.qty > 0 ){
                        let productData = {
                            id: d.id,
                            qty: d.qty
                        };
                        if( d.charges_list && d.charges_list.length ){
                            const checkCharge = d.charges_list.findIndex( cD => cD.qty > 0 );
                            if( checkCharge > -1 ){
                                productData.extraCharges = [];
                                d.charges_list.forEach( cD => {
                                    if( cD.qty > 0 ){
                                        const chargeData = {
                                            id: cD.id,
                                            qty: cD.qty
                                        };
                                        productData.extraCharges.push( chargeData );
                                    }
                                })
                            }
                        }
                        orderData.products.push( productData );
                    }
                })
                this.$swal({
                    title: "Are you sure?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    showCancelButton:true,
                    showConfirmButton: true
                }).then( res => {
                    if( res.isConfirmed ){

                        axios.post( this.API_ENDPOINT + 'place-order', orderData )
                                    .then(
                                        ( res )=>{
                                            console.log( res.data );
                                            this.item.dining_status = 1;
                                            this.item.order_data.placed = res.data.order_data;
                                            this.clearCart();
                                            this.$emit('orderPlaced');
                                            Vue.$toast.open({
                                                message: 'Order placed successfully!',
                                                type: 'success',
                                                position: 'top-right',
                                                duration: 1000
                                            });
                                        },
                                        (err) => {
                                            Vue.$toast.open({
                                                message: 'Error! Something wrong. Try again.',
                                                type: 'error',
                                                position: 'top-right',
                                                duration: 1000
                                            });
                                        }
                                    );
                    }
                });
            }

        }
    }
}
</script>
