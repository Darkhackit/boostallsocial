<script setup>
import Card from "@/components/Card";
import {ref} from 'vue'
const orders = ref([])
const loading = ref(false)


const columns = ref([

    {
        label: "Email",
        field: "email",
    },
    {
        label: "Registration Date",
        field: "joined",
    },

    {
        label: "Last Payment Date",
        field: "quantity",
    },

    {
        label: "Earning",
        field: "earning",
    },
])
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
