<script setup>
import Card from "@/components/Card";
import Icon from "@/components/Icon";
import Textinput from "@/components/Textinput/index.vue";
import Select from "@/components/Select/index.vue";
import InputGroup from "@/components/InputGroup";
import Textarea from "@/components/Textarea";
import { QuillEditor } from "@vueup/vue-quill";
import Button from "@/components/Button/index.vue";
import Modal from '@/components/Modal/Modal';
import "@vueup/vue-quill/dist/vue-quill.snow.css";

const processing = ref(false)
import {onMounted, ref} from "vue";
import VueSelect from "@/components/Select/VueSelect.vue";
import vSelect from "vue-select";
import Checkbox from "@/components/Checkbox/index.vue";
import axiosClient from "@/plugins/axios";
const perpage = ref(10)
const searchTerm = ref("")
const show = ref(false)
const loadingService = ref(false)
const showEditModal = ref(false)
const errors = ref([])
const error = ref([])
const providers = ref([])
const services = ref([])
const countries = ref([])
const payments = ref([])
const  books = ref([
    {
        title: "Database",
        icon: "heroicons-outline:database",
    },
    {
        title: "Server",
        icon: "heroicons-outline:server",
    },
    {
        title: "Finger Print",
        icon: "heroicons-outline:finger-print",
    },
])

const types = ref([
    {label:"social media",value:"social_media_provider"},
    {label:"rent number",value:"rent_number_provider"},
    {label:"website optimization",value:"website_optimisation_provider"},
])
const form = ref({
    email:'',
    amount:'',
    paymentMethod:'',
    bonus:true,
    ip:''
})
const ed_form = ref({
    id:'',
    name:'',
    email:'',
    password:'',
    password_confirmation:'',
})
const actions = ref([
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
])
const columns = ref([
    {
        label: "Id",
        field: "id",
    },
    {
        label: "Method",
        field: "method",
    },
    {
        label: "Email",
        field: "email",
    },
    {
        label: "Balance",
        field: "balance",
    },

    {
        label: "Action",
        field: "action",
    },
])

const clearErrors = (val) => {
    delete errors.value[val]
}
const clearError = (val) => {
    delete error.value[val]
}
const viewData = async (id) => {
    loadingService.value = true
    try {
        let response = await axiosClient.get(`/api/customers/${id}`)
        ed_form.value.email = response.data.email
        ed_form.value.id = response.data.id
        showEditModal.value = true
        loadingService.value = false

    }catch (e) {
        console.log(e.response)
        loadingService.value = false
    }
}
const addData = async () => {
    processing.value = true
    try {
        let response = await axiosClient.post('/api/add-payment',form.value)
        console.log(response)
        form.value.name = ""
        form.value.password = ""
        form.value.password_confirmation = ""
        processing.value = false
        show.value = false
        await getData()
    }catch (e) {
        console.log(e.response)
        processing.value = false
        if(e.response.status === 422) {
            errors.value = e.response.data.errors
        }
    }
}
const updateServiceProvider = async () => {
    processing.value = true
    try {
        await axiosClient.put(`/api/customers/${ed_form.value.id}`,ed_form.value)
        processing.value = false
        await getData()
        showEditModal.value = false
    }catch (e) {
        processing.value = false
        if (e.response.status === 422) {
            error.value = e.response.data.errors
        }
    }
}

const getPayment = async () => {
    loadingService.value = true
    try {
        let response = await axiosClient.get('/api/get-payment')
        payments.value = response.data
        console.log(response)
        loadingService.value = false
    }catch (e) {
        console.log(e.response)
        loadingService.value = false
    }
}

const getData = async () => {
    loadingService.value = true
    try {
        let response = await axiosClient.get('/api/customers')
        countries.value = response.data
        console.log(response)
        loadingService.value = false
    }catch (e) {
        console.log(e.response)
        loadingService.value = false
    }
}
onMounted(async () => {
    await getPayment()
    await getData()
})
</script>
<template>
    <div>
        <Card noborder>
            <div
                class="md:flex justify-between pb-6 md:space-y-0 space-y-3 items-center"
            >
                <h5>Payments</h5>
                <button @click.prevent="show = true" class="btn btn-dark">Add Payments</button>
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
                :is-loading="loadingService"
                styleClass=" vgt-table bordered"
                :rows="payments"
                :pagination-options="{
                  enabled: true,
                  perPage: perpage,
                }"
                :search-options="{
                  enabled: true,
                  externalQuery: searchTerm,
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

                    <span class="flex" v-if="props.column.field == 'action'">

                          <Icon style="font-size: 1.2rem" icon="heroicons:pencil-square" @click.prevent="viewData(props.row.id)"  />

                          <Icon style="font-size: 1.2rem" icon="heroicons-outline:trash" @click.prevent="deleteProvider(props.row.id)"  />

                    </span>
                    <span class="flex" v-if="props.column.field == 'balance'">
                        <p>$ {{props.row.balance}}</p>
                    </span>
                </template>
            </vue-good-table>
        </Card>
        <!--        Add-->
        <Modal
            title="Add Payment"
            :activeModal="show"
            labelClass="btn-outline-dark"
            sizeClass="max-w-5xl"
            centered
            @close="show = false"
        >
            <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
                <VueSelect  label="Customer" class="">
                    <vSelect v-model="form.email" :options="countries" label="email"  placeholder="Select Customer" />
                </VueSelect>
                <Textinput
                    label="Amount"
                    v-model="form.amount"
                    @input="clearErrors('amount')"
                    type="number"
                    :class-input="{'border-red-500':errors.amount}"
                    :error="errors.amount ? `${errors.amount[0]}` : ''"
                    placeholder="Amount"
                />
                <VueSelect  label="Payment Method" class="">
                    <vSelect v-model="form.paymentMethod" :options="['momo','crypto','binance','pm']" label="Payment Method"  placeholder="Payment Method" />
                </VueSelect>
                <Checkbox label="Include Bonus" :checked="form.bonus"  v-model="form.bonus" />
                <Textinput
                    label="Ip"
                    v-model="form.ip"
                    @input="clearErrors('ip')"
                    type="text"
                    :class-input="{'border-red-500':errors.ip}"
                    :error="errors.ip ? `${errors.ip[0]}` : ''"
                    placeholder="Ip"
                />
            </div>
            <div class="float-right mb-3">
                <Button :isLoading="processing" @click.prevent="addData" btn-class="btn-dark">Add Payment</Button>
            </div>

        </Modal>
        <!--        Add-->
        <!--        Service-->
        <!--        Service-->
        <!--        View-->
        <Modal
            title="Update Customer"
            :activeModal="showEditModal"
            labelClass="btn-outline-dark"
            sizeClass="max-w-5xl"
            centered
            @close="showEditModal = false"
        >
            <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">

                <Textinput
                    label="Email"
                    type="email"
                    v-model="ed_form.email"
                    @input="clearError('email')"
                    :class-input="{'border-red-500':error.email}"
                    :error="error.email ? `${error.email[0]}` : ''"
                    placeholder="Email"
                />
            </div>
            <div class="float-right mb-3">
                <Button :isLoading="processing" @click.prevent="updateServiceProvider" btn-class="btn-dark">Update Customer</Button>
            </div>

        </Modal>
        <!--        View-->

    </div>
</template>
<style lang="scss"></style>
