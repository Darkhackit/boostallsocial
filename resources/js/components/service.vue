<template>
    <div>
        <Card noborder>
            <div
                class="md:flex justify-between pb-6 md:space-y-0 space-y-3 items-center"
            >
                <h5>{{ title }}</h5>
                <InputGroup
                    v-model="searchTerm"
                    placeholder="Search"
                    type="text"
                    prependIcon="heroicons-outline:search"
                    merged
                />
            </div>

            <vue-good-table
                :columns="columns"
                styleClass=" vgt-table bordered centered"
                :rows="items"
                :pagination-options="{
                  enabled: true,
                  perPage: perpage,
                }"
                :search-options="{
                  enabled: true,
                  externalQuery: searchTerm,
                }"
            >
                <template v-slot:table-row="props">
                  <span v-if="props.column.field == 'rate'" class="flex">
                      {{props.row.price ?? props.row.rate ?? props.row.prices[0].price}}
                  </span>
                    <span v-if="props.column.field == 'service'" class="flex">
                      {{props.row.service ?? props.row.id_service}}
                  </span>
                    <span v-if="props.column.field == 'name'" class="flex">
                      {{props.row.name ?? props.row.public_name}}
                  </span>
                    <span v-if="props.column.field == 'min'" class="flex">
                      {{props.row.min ?? props.row.prices[0].minimum}}
                  </span>
                    <span v-if="props.column.field == 'max'" class="flex">
                      {{props.row.max ?? props.row.prices[0].maximum}}
                  </span>
<!--                    <span v-if="props.column.field == 'type'" class="flex">-->
<!--                      {{props.row.type ?? props.row.prices[0].type_name}}-->
<!--                  </span>-->
                    <span v-if="props.column.field == 'dripfeed'" class="flex">
                      {{props.row.dripfeed ?? props.row.prices[0].is_drip}}
                  </span>
                </template>
            </vue-good-table>
        </Card>
    </div>
</template>
<script>
import Dropdown from "@/components/Dropdown";
import Card from "@/components/Card";
import Icon from "@/components/Icon";
import InputGroup from "@/components/InputGroup";
import Pagination from "@/components/Pagination";
import { MenuItem } from "@headlessui/vue";

export default {
    components: {
        Pagination,
        InputGroup,
        Dropdown,
        Icon,
        Card,
        MenuItem,
    },
    props:{
        items: {
            type:Array,
            required:true
        },
        title: {
            type:String,
            required:true
        }
    },

    data() {
        return {
            current: 1,
            perpage: 10,
            pageRange: 5,
            searchTerm: "",
            actions: [
                {
                    name: "view",
                    icon: "heroicons-outline:eye",
                },
                {
                    name: "edit",
                    icon: "heroicons:pencil-square",
                },
                {
                    name: "delete",
                    icon: "heroicons-outline:trash",
                },
            ],
            options: [
                {
                    value: "1",
                    label: "1",
                },
                {
                    value: "3",
                    label: "3",
                },
                {
                    value: "5",
                    label: "5",
                },
            ],
            columns: [
                {field: 'service', label: 'Service'},
                {field: 'name', label: 'Name'},
                {field: 'rate', label: 'Price'},
                {field: 'min', label: 'Min'},
                {field: 'max', label: 'Max'},
                {field: 'type', label: 'serviceType'},
                {field: 'auto', label: 'Auto'},
                {field: 'dripfeed', label: 'Dripfeed'},
                {field: 'category', label: 'Category'},
            ],
        };
    },
};
</script>
<style lang="scss"></style>
