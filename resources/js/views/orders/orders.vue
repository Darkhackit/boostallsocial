<script setup>
import Card from "@/components/Card";
import {onMounted, ref, watch} from 'vue'
import axiosClient from "@/plugins/axios";
const orders = ref([])
const timer = ref("")
const loading = ref(false)


const columns = ref([

    {
        label: "Order",
        field: "order",
    },
    {
        label: "Service Name",
        field: "service_name",
    },
    {
        label: "Link",
        field: "link",
    },

    {
        label: "Quantity",
        field: "quantity",
    },

    {
        label: "Start Count",
        field: "start_count",
    },
    {
        label: "Status",
        field: "status",
    },
])

const getOrders = async () => {
    try {
        let response = await axiosClient.get('/api/social/orders')
        orders.value = response.data
        console.log(response)
    }catch (e) {
        console.log(e.response)
    }
}
watch(() => orders.value,async () => {
  let uncompleted =  orders.value.find(order => order.status !== 'completed')
    if (uncompleted) {
        timer.value = setInterval(await getOrders,100000)
    }else {
      timer.value =   clearInterval(timer.value)
    }
})
onMounted(async () => {
    await getOrders();
})
</script>
<template>
    <div class="grid sm:grid-cols-6  mt-3">

        <div class="col-start-2 col-span-4 ">
            <Card>
                <div class="flex justify-between">
                    <h1 class="text-2xl font-bold">Social Media Service Orders</h1>
                </div>
                <vue-good-table
                    :is-loading="loading"
                    :columns="columns"
                    styleClass=" vgt-table centered  lesspadding2 table-head "
                    :rows="orders"

                    :sort-options="{
                    enabled: false,
                  }"
                            :select-options="{
                    enabled: true,
                    selectOnCheckboxOnly: true, // only select when checkbox is clicked instead of the row
                    selectioninfoClass: 'custom-class',
                    selectionText: 'rows selected',
                    clearSelectionText: 'clear',
                    disableSelectinfo: true, // disable the select info-500 panel on top
                    selectAllByGroup: true, // when used in combination with a grouped table, add a checkbox in the header row to check/uncheck the entire group
                  }"
                >
                    <template v-slot:table-row="props">
            <span v-if="props.column.field == 'earning'" class="flex">
              <span
                  class="flex text-center justify-center text-sm text-slate-600 dark:text-slate-300 capitalize"
              >{{props.row.currency}} {{ props.row.earning }}</span
              >
            </span>
                    </template>
                </vue-good-table>

            </Card>
        </div>


    </div>
</template>

<style scoped>

</style>
