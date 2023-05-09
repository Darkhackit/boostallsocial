<script setup>
import Card from "@/components/Card";
import {ref ,defineProps} from 'vue'


const props = defineProps({
    referrals:{
        type:Array,
        required:true
    },
    loading: {
        type:Boolean,
        required:true
    }
})
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
    <div>
        <Card title="" noborder>
            <div class="-mx-6">
                <vue-good-table
                    :is-loading="props.loading"
                    :columns="columns"
                    styleClass=" vgt-table centered  lesspadding2 table-head "
                    :rows="props.referrals"

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
            </div>
        </Card>
    </div>
</template>

<style lang="scss" scoped>
.action-btn {
    @apply h-6 w-6 flex flex-col items-center justify-center border border-slate-200 dark:border-slate-700 rounded;
}
</style>
