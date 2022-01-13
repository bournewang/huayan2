<template>
    <!-- <div> -->
        <tr>
            <td>{{index}}
                &nbsp;&nbsp;
                <button data-testid="logistics-items-1-delete-button" dusk="1083-delete-button" class="inline-flex appearance-none cursor-pointer text-70 hover:text-primary mr-3 has-tooltip text-danger" data-original-title="null">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" aria-labelledby="delete" role="presentation" class="fill-current">
                        <path fill-rule="nonzero" d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
                    </svg>
                </button>
            </td>
            <td>
                <v-select class="" name="" v-if="goods_options" :options="goods_options" :reduce="goods => goods.id" label="name">
                    
                </v-select>
            </td>
            <td>{{item.price}}</td>
            <td>
                <input type="text" name="" value="" v-model="item.quantity">
            </td>
        </tr>
    <!-- </div> -->
</template>

<script>
// import Vue from "vue";
import vSelect from "vue-select";

// Vue.component("v-select", vSelect);
export default {
    components: {"v-select": vSelect},
    props: {
        index: 1,
        goods_options: [],
        item: {}
    },
    data: function(){
        return {
            // options: [{"id": 1, "name": "xxxx"}]
        }
    },
    metaInfo() {
        return {
          title: 'SalesOrder',
        }
    },
    mounted() {
        //
    },
}
import 'vue-select/dist/vue-select.css';

</script>

<style>
/* Scoped Styles */
/* @import "vue-select/src/scss/vue-select.scss"; */

</style>
