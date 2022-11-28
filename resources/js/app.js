require('./bootstrap');
import Vue from 'vue';
import VueToast from 'vue-toast-notification';
import VueSweetalert2 from 'vue-sweetalert2';
import store from './store';
import 'vue-toast-notification/dist/theme-sugar.css';
import 'sweetalert2/dist/sweetalert2.min.css';

window.Vue = require('vue').default;

Vue.use(VueToast);
Vue.use(VueSweetalert2);

// Vue.component('order-form', require('./component/OrderForm.vue').default);

// Vue.component('admin-order-list', require('./component/Admin/OrderList.vue').default);
Vue.component('admin-product-extra-charge-input', require('./component/Admin/ProductExtraChargeInput.vue').default);
Vue.component('admin-manage-table', require('./component/Admin/ManageTable.vue').default);
Vue.component('admin-table-product-list', require('./component/Admin/TableProductList.vue').default);
Vue.component('admin-table-order-view', require('./component/admin/OrderList.vue').default);
Vue.component('admin-table-placed-order', require('./component/admin/TablePlacedOrder.vue').default);

Vue.prototype.API_ENDPOINT = 'http://restaurant.orderprocess.ga/api/';
Array.prototype.findByKey = function( key, val ) {
    const found = this.find( ( item ) => { return item[ key ] == val});

    if( found ){
        return found;
    }
    return null;
};

const app = new Vue({
    el: '#rest-sys',
    store
});
