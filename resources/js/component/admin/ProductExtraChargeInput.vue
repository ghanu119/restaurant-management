<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="form-group form-inline">
                    <select name="extraCharge" id="extraCharge" v-model="selected.charge" class="form-control">
                        <option value="">Select Extra Charge</option>
                        <option v-for="(item, index) in chargesList" :value="item.id" :key="index">{{ item.name }}</option>
                    </select>
                    <button class="btn btn-sm btn-info ml-2" type="button" @click="addCharge()">
                        Add
                    </button>
                </div>
            </div>
            <div class="col-12 mt-2">
                <table class="table">
                    <thead>
                        <tr>

                            <th>
                                #
                            </th>
                            <th>
                                Title
                            </th>
                            <th>
                                Price
                            </th>
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in selected.charges" :key="'table_' + index">
                            <td>
                                {{ index+1 }}
                            </td>
                            <td>
                                {{ item.name }}
                            </td>
                            <td>
                                <input type="hidden"  :name="'charges['+index+'][id]'" :value="item.id">
                                <input type="text" class="form-control" v-model="item.price" :name="'charges['+index+'][price]'">
                            </td>
                            <td>
                                <button class="btn btn-danger btn-sm" type="button" @click="removeCharge(index)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:{
        chargesList:{
            type: Array,
            default: () => {
                return [];
            }
        },
        selectedCharges:{
            type: Array,
            default: () => {
                return [];
            }
        }
    },
    data(){
        return {
            selected: {
                charge: "",
                charges: []
            },
        }
    },
    created(){
        if( Array.isArray( this.selectedCharges ) && this.selectedCharges.length ){
            this.selected.charges = this.selectedCharges;
        }
    },
    methods:{
        addCharge(){
            if( this.selected.charge !== "" && this.selected.charge !== null ){
                const findIndex = this.chargesList.findIndex( d => d.id == this.selected.charge );
                if( findIndex > -1 ){
                    this.selected.charges.push({
                        id: this.selected.charge,
                        name: this.chargesList[findIndex].name,
                        price: this.chargesList[findIndex].price || 0
                    })
                    this.selected.charge = "";
                }
            }
        },
        removeCharge( idx ){
            this.selected.charges.splice( idx, 1 );
        }
    }

}
</script>
