<template>
    <div>
        <heading class="mb-6">{{__('Sales Order')}} </heading>

        <card class="flex flex-col mb-8 items-center justify-left" style="min-height: 300px">
            <div class="flex border-b border-40 w-full">
                <div class="px-8 py-2 w-1/5">
                    <label for="customer" class="inline-block text-80 pt-2 leading-tight">{{__('Customer')}}&nbsp;<!----></label>
                </div> 
                <div class="py-2 px-8 w-1/2">
                    <v-select class="" name="" v-model="sales_order.customer_id" v-if="customers" :options="customers" :reduce="item => item.id" label="name">
                    </v-select>
                </div>
            </div>
            <div class="flex border-b border-40 w-full">
                <div class="px-8 py-2 w-1/5">
                    <label for="customer" class="inline-block text-80 pt-2 leading-tight">{{__('Goods')}}&nbsp;<!----></label>
                </div> 
                <div class="py-2 px-8 w-4/5">
            <vxe-table border resizable show-overflow class="table w-full table-tight table-grid" :data="sales_order.items" :edit-config="{trigger: 'click', mode: 'row'}">
              <vxe-column type="seq" width="10%">
                  <template #default="{ row }">
                      <button type="button" name="button" class='text-danger' @click="deleteItem(row.key)">
                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current">
                              <path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
                          </svg>
                      </button>
                      <button type="button" name="button" class='text-success' @click="addItem(row.key)">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                          </svg>
                      </button>   
                  </template>  
              </vxe-column>
              
              <vxe-column field="goods" :title="__('Goods')" :edit-render="{}" width="40%">
                <template #default="{ row }">
                  <span>{{ goodsInfo(row.attributes.goods_id, 'name') }}</span>
                </template>
                <template #edit="{ row }">
                  <v-select class="" name="" v-model="row.attributes.goods_id" @change="calculate" v-if="goods_options" :options="goods_options" :reduce="goods => goods.id" label="name">
                  </v-select>
                </template>
              </vxe-column>
              <vxe-column field="role" :title="__('Image')" :edit-render="{}" width="20%">
                  <template #default="{ row }"> 
                    <img :src="goodsInfo(row.attributes.goods_id, 'thumb')" />
                  </template>
                  <template #edit="{ row }"> 
                    <img :src="goodsInfo(row.attributes.goods_id, 'thumb')" />
                  </template>
              </vxe-column>
              <vxe-column field="num6" :title="__('Price')" :edit-render="{}"  width="10%">
                <template #default="{ row }">
                  <span>{{goodsInfo(row.attributes.goods_id, 'price')}}</span>
                </template>
                <template #edit="{ row }">
                  <span>{{goodsInfo(row.attributes.goods_id, 'price')}}</span>
                </template>
              </vxe-column>
              <vxe-column field="num6" :title="__('Quantity')" :edit-render="{}" width="20%">
                  <template #default="{ row }">
                    <span>{{row.attributes.quantity}}</span>
                  </template>
                <template #edit="{ row }">
                  <vxe-input v-model="row.attributes.quantity" type="number" @change="calculate" placeholder="请输入数量"></vxe-input>
                </template>
              </vxe-column>
            </vxe-table>   
            </div>        
            </div>   
            
            <div class="flex border-b border-40 w-full">
                <div class="px-8 py-2 w-1/5">
                    <label for="total_quantity" class="inline-block text-80 pt-2 leading-tight">{{__('Total Quantity')}}&nbsp;<!----></label>
                </div> 
                <div class="py-2 px-8 w-1/2">{{sales_order.total_quantity}}</div>
            </div>
            <div class="flex border-b border-40 w-full">
                <div class="px-8 py-2 w-1/5">
                    <label for="total_price" class="inline-block text-80 pt-2 leading-tight">{{__('Total Price')}}&nbsp;<!----></label>
                </div> 
                <div class="py-2 px-8 w-1/2">{{sales_order.total_price}}</div>
            </div>                  
            <div class="flex border-b border-40 w-full">
                <div class="px-8 py-2 w-1/5">
                    <label for="total_price" class="inline-block text-80 pt-2 leading-tight">{{__('Paid Price')}}&nbsp;<!----></label>
                </div> 
                <div class="py-2 px-8 w-1/2"><input v-model="sales_order.paid_price" id="paid_price" dusk="paid_price" list="paid_price-list" type="text" :placeholder="__('Paid Price')" class="w-full form-control form-input form-input-bordered"></div>
            </div> 
            <div class="flex border-b border-40 w-full">
                <div class="px-8 py-2 w-1/5">
                    <label for="total_price" class="inline-block text-80 pt-2 leading-tight">{{__('Comment')}}&nbsp;<!----></label>
                </div> 
                <div class="py-2 px-8 w-1/2"><textarea v-model="sales_order.comment" rows="3" id="comment" dusk="comment" list="paid_price-list" type="text" :placeholder="__('Comment')" class="w-full form-control form-input form-input-bordered"/></div>
            </div> 
                          
            <!-- <button type="button" name="button" class="btn btn-default" @click="cancel">{{__('Cancel')}}</button>     -->
            <!-- <button type="button" name="button" class="btn btn-default btn-primary" @click="submit">{{__('Submit')}}</button> -->
        </card>
        <div class="flex items-center">
            <div class="px-8 py-2 w-1/5">
                
            </div> 
            <div class="py-2 px-8 w-1/2 flex items-center">
                <a tabindex="0" @click="cancel" class="btn btn-link dim cursor-pointer text-80 ml-auto mr-6">{{__('Cancel')}}</a> 
                <button type="submit" @click="submit" class="btn btn-default btn-primary inline-flex items-center relative" dusk="update-button"><span class="">
                    {{(this.sales_order.id ? __('Update') : __('New')) + __('Sales Order')}} </span> <!---->
                </button>
            </div>
        </div>   
    </div>
</template>

<script>
// import Vue from "vue";
import vSelect from "vue-select";
import Row from "./Row";

// import CreateResource from '@nova/views/Create'
// import CreateResource from '@/views/Create'

export default {
    // mixins: [CreateResource],

// Vue.component("v-select", vSelect);
// export default {
    components: {"v-select": vSelect, "row": Row},
    data: function(){
        return {
            // items: [],
            // sales_order_id: null,
            sales_order: {items:[]},
            goods_detail: null,
            goods_options: [],
            customers: [],
            // customer_id: null,
            // total_quantity: 0,
            // total_price: 0,
            // paid_price: 0,
            // comment: null,
            // items: []
        }
    },
    metaInfo() {
        return {
          title: 'SalesOrder',
        }
    },
    methods: {
        initRow(){
            return {layout: "items", key: Math.random(), attributes: {quantity: 0}}
        },
        addItem(key){
            for (var i=0; i<this.sales_order.items.length; i++) {
                if (this.sales_order.items[i].key == key) {
                    this.sales_order.items.splice(i+1,0,this.initRow())
                    return;
                }
            }
            this.sales_order.items.splice(0,0, this.initRow())
        },
        deleteItem(key) {
            // find index
            for (var i=0; i<this.sales_order.items.length; i++) {
                if (this.sales_order.items[i].key == key) {
                    this.sales_order.items.splice(i,1)
                    return;
                }
            }
        },
        goodsInfo(id, key) {
            if (!id) return null;
            return this.goods_detail && this.goods_detail[id] ? this.goods_detail[id][key] : null;
        },
        calculate(){
            this.axios.post('/ajax/sales-orders/calculate', {goods: this.sales_order.items}).then((response) => {
                console.log(response.data)
                if (response.data.success) {
                    var data = response.data.data
                    this.$set(this.sales_order, 'total_quantity', data.total_quantity)
                    this.$set(this.sales_order, 'total_price', data.total_price)
                }
            })
        },
        cancel(){
            if (this.sales_order.id) {
                this.$router.push({path: '/resources/sales-orders/'+this.sales_order.id})
            }else{
                this.$router.push({path: '/resources/sales-orders'})
            }
        },
        submit(){
            this.axios.post('/ajax/sales-orders', this.sales_order).then((response) => {
                console.log(response.data.data)
                if (response.data.success) { 
                    this.$router.push({path: '/resources/sales-orders/'+response.data.data.id})
                }
            })
        }
    },
    mounted() {
        //
        if (this.$route.query.id) {
            this.axios.get('/ajax/sales-orders/'+this.$route.query.id).then((response) => {
                if (response.data.success) {
                    this.sales_order = response.data.data
                }
            })
        }else{
            this.sales_order.items.push(this.initRow())
        }
        // console.log("query id: " +)
        this.axios.get('/ajax/customers').then((response) => {
            console.log(response.data.success)
            if (response.data.success) {
                this.customers = response.data.data
            }
        })
        this.axios.get('/ajax/goods').then((response) => {
            console.log(response.data.success)
            if (response.data.success) {
                this.goods_detail = response.data.data
            }
        })
        // this.goods_options = {'aa': 'aaaaa', 'bb': 'bbbb'}
        this.axios.get('/ajax/goods/options').then((response) => {
            console.log(response.data.success)
            if (response.data.success) {
                this.goods_options = response.data.data
                console.log(this.goods_options)
                // this.addItem(null)
            }
        })
    },
}
import 'vue-select/dist/vue-select.css';

</script>

<style>
/* Scoped Styles */
/* @import "vue-select/src/scss/vue-select.scss"; */

</style>
