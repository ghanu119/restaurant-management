<template>
	<div>
		<template v-if="loading">
			<div class="text-center">
				<i class="fa fa-spin fa-spinner"></i> Loading data...
			</div>
		</template>
		<template v-if="!loading && !( allData && allData.parcel_types.length && allData.pack_of_toppings.length && allData.flavours.length && toppings.length )">
			<div class="text-danger">
				Oops! Refresh the page.
			</div>
		</template>
		<div class="container" ref="orderFormContainer" v-if="!loading && allData && allData.parcel_types.length && allData.pack_of_toppings.length && allData.flavours.length && toppings.length">
      <div class="row">
        <div class="col-2">
          <div class="row">
            <div class="col-12" v-for="( d, index) in sidemenu" :key="'menu' + index">
              <div class="process-title" :class="{'select': index <= selectedSideMenu}">
                <span class="label">
                  {{d.label}}
                </span>
              </div>
            </div>
            <div class="col-12">

              <div class="cart-icon" @click="toggleCart()" v-show="$store.getters.totalCartItems">
                <i class="fa fa-shopping-cart"></i> <small class="cart-count">( {{$store.getters.totalCartItems}} )</small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-10">
          <div class="row">
            <template v-for="( d, index) in sidemenu" >
              <template v-if="index == selectedSideMenu">

                <div class="col-12 head-title" :key="'title_' +index">
                    <span>

                      Select {{d.label}}
                    </span>
                </div>
                <div class="col-12" :key="'listing_' +index" >
                  <div class="row" v-if="d.input_key !== 'quantity'">
                    <template v-for="( item, j) in allData[d.id]">

                      <div class="col-3" :key="'card'+j">
                        <card-input  :data="item" :selected.sync="selected[d.input_key]" ></card-input>
                      </div>
                      <div class="col-3" :key="'topping_card'+j" v-if="d.id == 'pack_of_toppings'">
                        <card-input  :data="custom_topping" :selected.sync="selected.topping_type" ></card-input>
                      </div>
                    </template>

                  </div>
                  <div class="row" v-else>
                    <div class="col-5">
                      <input type="number" class="form-control" min="1" v-model="selected.quantity">

                      <span class="total-amount">
                        Total: &#8377; {{itemData.total || 0}}
                      </span>
                    </div>
                  </div>
                </div>
                <div class="col-12 footer" :key="'footer'+index">
                  <div class="next-action">
                    <button class="prev" @click="selectedSideMenu--" v-if="selectedSideMenu > 0">
                      <i class="fa fa-arrow-alt-left"></i> Prev
                    </button>
                    <span class="price-total">
                      Sub Total: &#8377; {{itemData.sub_total || 0}}
                    </span>
                    <button class="next" @click="selectedSideMenu++" v-if="isNextStepEnable() && selectedSideMenu < sidemenuLength-1">
                      Next <i class="fa fa-arrow-alt-right"></i>
                    </button>
                    <button class="cart" @click="addToCart()" v-if="addToCartEnable() && selectedSideMenu == sidemenuLength-1">
                      Submit
                    </button>
                  </div>
                </div>
              </template>
            </template>
          </div>
        </div>
      </div>
			<div class="cart-icon d-none" @click="toggleCart()" v-show="$store.getters.totalCartItems">
				<i class="fa fa-shopping-cart"></i> <small class="cart-count">( {{$store.getters.totalCartItems}} )</small>
			</div>
			<div class="row ">
				<div class="col-md-9 d-none">
					<template v-for="( d, index) in sidemenu">
						<div class="row p-3 custom-selection-container"  :key="'menu_' + index" v-if="d.id != 'quantity' && allData[d.id]">
							<div class="col-12 header">
								{{d.label}}
							</div>
							<div class="col-md-3" v-for="( item, j) in allData[d.id]" :key="j">
								<card-input  :data="item" :selected.sync="selected[d.input_key]" ></card-input>
							</div>
							<div class="col-md-3" v-if="d.id == 'pack_of_toppings'">
								<card-input  :data="custom_topping" :selected.sync="selected.topping_type" ></card-input>
							</div>
						</div>
						<div class="row p-3 custom-selection-container"  :key="'custom_topping_' + index" v-if="d.id == 'pack_of_toppings' && selected['topping_type'] == 'custom'">
							<div class="col-12 header">
								Manual Toppings
							</div>
							<div class="col-md-3" v-for="( item, j) in toppings" :key="j">
							<card-input  :data="item" :selected.sync="selected.toppings" ></card-input>
							</div>
						</div>
					</template>
					<div class="row p-3">
						<div class="col-12 d-flex justify-content-end">
							<div class="col-3 ">
								<label>Quantity</label>
								<input type="number" class="form-control" min="1" v-model="selected.quantity">
							</div>
						</div>
						<div class="col-12 text-right" >
							<label>Sub Total: &#8377; {{itemData.sub_total || 0}}</label>
						</div>
						<div class="col-12 text-right" >
							<label>Total: &#8377; {{itemData.total || 0}}</label>
						</div>
						<div class="col-12 d-flex justify-content-end">
							<div class="col-3 text-right" v-if="addToCartEnable()">
								<button class="btn btn-primary" @click="addToCart()" :disabled="addToCartProcess"><i class="fa fa-spin fa-spinner" v-if="addToCartProcess"></i>Add To Cart</button>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3" v-show="show.cart">
					<cart-modal @close="toggleCart()" @checkout="showCheckout()"></cart-modal>

				</div>
			</div>
			<div class="checkout-form" v-show="show.checkout">
        <place-order @showCart="toggleCart()" @orderSuccess="orderSuccess()" @showOrderForm="newOrderStart()"> </place-order>
      </div>
		</div>
	</div>
</template>

<script>
// import PlaceOrder from './PlaceOrder.vue';
export default {
//   components: { PlaceOrder },
  data(){
    return {
      allData:{

        parcel_types: [],
        flavours: [],
        pack_of_toppings: [],
      },
	  toppings: [],
      selected:{
        parcel_type: '',
        flavour: [],
        topping_type: '',
        toppings: [],
        quantity: 1
      },
      parcel_type: '',
      loading: true,
      addToCartProcess: false,
      custom_topping: {
        id:'custom',
        name: 'Customize',
      },
      collapsed: {
        parcel: true,
        flavour: false,
        topping: false
      },
      sidemenu: [
        {
          id: 'parcel_types',
          label: 'Type',
          input_key: 'parcel_type'
        },
        {
          id: 'flavours',
          label: 'Flavour',
          input_key: 'flavour'
        },
        {
          id: 'pack_of_toppings',
          label: 'Toppings',
          input_key: 'topping_type'
        },
        {
          id: 'quantity',
          label: 'Quantity',
          input_key:  'quantity'
        }
      ],
      selectedSideMenu: 0,
      show:{
        cart: false,
        checkout: false
      }
    }
  },
  mounted(){
    this.setToken();
    this.getParcelTypes();
    this.getFlavours();
    this.getToppings();
  },
  computed: {
    itemData () {
      let data = [];
      let sub_total = 0;
      let total = 0;
      if( this.selected.parcel_type ){
        const selectedParcel = this.allData.parcel_types.findByKey( 'id', this.selected.parcel_type );
        if( selectedParcel ){
          data['parcel_type'] = selectedParcel;
          sub_total += selectedParcel.price;
        }
      }
      if( this.selected.flavour ){
		let selectedFlavour;
		if( Array.isArray( this.selected.flavour ) ){
			selectedFlavour = this.allData.flavours.filter( d => this.selected.flavour.includes( d.id ) );

		}else{

			selectedFlavour = this.allData.flavours.findByKey( 'id', this.selected.flavour );
		}
        if( selectedFlavour ){
          data['flavour'] = selectedFlavour;
		  if( Array.isArray( selectedFlavour ) && selectedFlavour.length ){
			  sub_total += selectedFlavour.map( d => d.price).reduce( ( a,b ) => a+b);

		  }else{
			  sub_total += selectedFlavour.price;

		  }
        }
      }
      if( this.selected.topping_type ){
        if( this.selected.topping_type != 'custom' ){

          const selectedToppingType = this.allData.pack_of_toppings.findByKey( 'id', this.selected.topping_type );
          if( selectedToppingType ){
            data['topping_type'] = selectedToppingType;
            sub_total += selectedToppingType.price;
          }
        }else{
          data['topping_type'] = this.custom_topping;
          data['toppings'] = this.toppings.filter( ( item ) => {
            if( this.selected.toppings.indexOf( item.id ) > -1 ){
              sub_total += item.price;
              return true;
            }
          });

        }
      }
      if( this.selected.quantity ){
        data['quantity'] = this.selected.quantity;
        data['total'] = this.selected.quantity * sub_total;

      }
      data['sub_total'] = sub_total;
      return data;
    },
    selectedPackToppings () {
      if( this.selected.topping_type && this.selected.topping_type !== 'custom' ){
        const selected = this.pack_of_toppings.find( d => d.id == this.selected.topping_type );
        if( selected ){
          return selected.toppings;
        }
      }
      return [];
    },
    sidemenuLength(){

      return ( this.sidemenu.length)
    }
  },
  watch: {
    'selected.topping_type'() {
      this.selected.toppings = [];
    }
  },
  methods:{
    setToken () {
      this.loadingUpdate( true);
      const data = {
        __token: localStorage.getItem('__token')
      }
      axios.post( this.API_ENDPOINT + 'set-guest-token', data)
          .then(
              ( res )=>{
                  localStorage.setItem('__token', res.data.__token);
                  this.loadingUpdate( false);
              },
              ( err ) => {
                  this.loadingUpdate( false);
                  console.log(err, "ERRR");
              }
          ).finally( () => this.loadingUpdate(false));
    },
    getParcelTypes () {
        this.loadingUpdate( true);
        axios.post( this.API_ENDPOINT + 'parcel-types')
            .then(
                ( res )=>{
                    this.allData['parcel_types'] =res.data;
                    this.loadingUpdate( false);
                },
                ( err ) => {
                    this.loadingUpdate( false);
                    console.log(err, "ERRR");
                }
            ).finally( () => this.loadingUpdate(false));
    },
    getFlavours () {
      this.loadingUpdate( true);
        axios.post( this.API_ENDPOINT + 'flavours')
            .then(
                ( res )=>{
                    this.allData['flavours'] =res.data;
                    this.loadingUpdate( false );
                },
                ( err ) => {
                  console.log(err, "ERRR");
                  this.loadingUpdate( false );
                }
            ).finally( () => this.loadingUpdate(false));
    },
    getToppings () {
      this.loadingUpdate( true );
        axios.post( this.API_ENDPOINT + 'toppings')
            .then(
              ( res )=>{
                  this.loadingUpdate( true );
                    if( res && res.data ){
                      if( res.data.packOfToppings ){
                        this.allData['pack_of_toppings'] = res.data.packOfToppings;
                      }
                      if( res.data.toppings ){
                        this.toppings = res.data.toppings;
                      }
                    }
                },
                ( err ) => {
                  this.loadingUpdate( true );
                  console.log(err, "ERRR");
                }
            ).finally( () => this.loadingUpdate(false));
    },
    loadingUpdate ( val ) {
      this.loading = val;
    },
    addToCartEnable() {
      if( this.selected.parcel_type == '' ){
        return false;
      }
      if( this.selected.flavour == '' ){
        return false;
      }
      if( this.selected.topping_type == '' ){
        return false;
      }else{
        if( this.selected.topping_type == 'custom' && this.selected.toppings.length == 0 ){
          return false;
        }
      }
      if( this.selected.quantity <= 0 ){
        return false;
      }
      return true;
    },
    isNextStepEnable() {
      // const formWizard = this.$refs.formWizard;
      if( true ){
        const activeTabIndex = this.selectedSideMenu;
        if( activeTabIndex == 0 ){
          if( this.selected.parcel_type == '' ){
            return false;
          }
        }
        if( activeTabIndex == 1 ){
          if( this.selected.flavour == '' ){
            return false;
          }
        }
        if( activeTabIndex == 2 ){
          if( this.selected.topping_type == '' ){
            return false;
          }else{
            if( this.selected.topping_type == 'custom' && this.selected.toppings.length == 0 ){
              return false;
            }
          }
        }
        if( activeTabIndex == 3 ){
          if( this.selected.quantity <= 0 ){
            return false;
          }
        }
        return true;
      }
      return false;
    },
    stepChange ( prevIndex, nextIndex ) {
      if( nextIndex == 1 ){
        this.getFlavours();
      }
      if( nextIndex == 2 ){
        this.getToppings();
      }
    },
    addToCart(){
		if( this.addToCartProcess ){
			return;
		}
		this.addToCartProcess = true;
		const cartData = {
			parcel_type: this.itemData.parcel_type.id,
			flavours: this.itemData.flavour.map( d => d.id),
			pack_of_topping: this.itemData.topping_type.id,
			toppings: this.itemData.toppings ? this.itemData.toppings.map( d => d.id ) : null,
			quantity: this.itemData.quantity,
			__token: localStorage.getItem('__token')
		}
		axios.post( this.API_ENDPOINT + 'add-to-cart', cartData)
            .then(
              ( res ) => {
					localStorage.setItem('__token', res.data.__token);
					this.$store.commit( 'setCart', res.data.cartData);
					this.selected = {
						parcel_type: '',
						flavour: [],
						topping_type: '',
						toppings: [],
						quantity: 1
					};
          this.selectedSideMenu = 0;
					this.$refs.orderFormContainer.scrollIntoView({ behavior: 'smooth'});
			  },
			  ( err ) => {
				console.log('AddtoCart',err);
			  }
			).finally( () => { this.addToCartProcess = false });

    },
    toggleCart () {
      this.show.cart = !this.show.cart;
    },
    showCheckout () {
      this.show.cart = false;
      this.show.checkout = true;
    },
    orderSuccess () {
    },
    newOrderStart () {
      this.show.checkout = false;
      this.show.cart = false;
    }
  }
}
</script>

<style>

</style>
