import Vue from 'vue'
import Vuex from 'vuex'
 
Vue.use(Vuex)
 
export default new Vuex.Store({
  state: {
    cart: [],
  },
  mutations: {
    addToCart( state, item ){
      return state.cart.push( item )
    },
    setCart( state, data ){
      Vue.set( state,'cart', data );
    },
    emptyCart( state ){
      Vue.set( state,'cart', [] );
    },
    removeCartItem( state, {index} ){

      return state.cart.splice( index, 1 );
    },
    updateQty( state, {index, qty} ){
      let item = state.cart[index];
      item.total = item.sub_total * Number(qty);
      Vue.set(state.cart, index, item);
    }
  },
  getters:{
    cart( state ) {
      return state.cart;
    },
    cartTotal( state ){
      return state.cart.map( d => d.total ).reduce( ( i, j ) => {
        return (i+j);
      }, 0);
    },
    totalCartItems( state ){
      return Array.isArray(state.cart) ? state.cart.length: 0;
    }
  },
  actions: {
 
  }
})