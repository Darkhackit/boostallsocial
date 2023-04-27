<script setup>
import Card from "@/components/Card";
import Icon from "@/components/Icon";
import Textinput from "@/components/Textinput/index.vue";
import Select from "@/components/Select/index.vue";
import InputGroup from "@/components/InputGroup";
import service from "@/components/service.vue";

import Button from "@/components/Button/index.vue";
import Modal from '@/components/Modal/Modal';
const processing = ref(false)
import {onMounted, ref} from "vue";
import Service from "@/components/service.vue";
 const perpage = ref(10)
 const searchTerm = ref("")
const show = ref(false)
const loadingService = ref(false)
const showEditModal = ref(false)
const showProviderDetails = ref(false)
const service_name = ref('')
const errors = ref([])
const error = ref([])
const providers = ref([])
const services = ref([])

const types = ref([
    {label:"social media",value:"social_media_provider"},
    {label:"rent number",value:"rent_number_provider"},
    {label:"website optimization",value:"website_optimisation_provider"},
])
const provider = ref({
    name:'',
    url:'',
    api_key:'',
    type:''
})
const ed_provider = ref({
    name:'',
    url:'',
    api_key:'',
    type:'',
    id:''
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
         label: "Name",
         field: "name",
     },
     {
         label: "URL",
         field: "url",
     },
     {
         label: "Type",
         field: "type",
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
const deleteProvider = async (id) => {
    if (window.confirm('Are you sure?')) {
        try {
            await axios.delete(`/api/delete-provider/${id}`)
            await getData()
        }catch (e) {
            console.log(e.response)
        }
    }
}
const viewProvider = async (id) => {
     loadingService.value = true
    try {
        let response = await axios.get(`/api/target-country/${id}`)
        ed_provider.value.name = response.data.name
        ed_provider.value.url = response.data.url
        ed_provider.value.type = response.data.type
        ed_provider.value.api_key = response.data.api_key
        ed_provider.value.id = response.data.id
        showEditModal.value = true
        loadingService.value = false

    }catch (e) {
        console.log(e.response)
        loadingService.value = false
    }
}
const getProviderService = async (id) => {
    loadingService.value = true
    try {
        let response = await axios.get(`/api/service-provider/${id}`)
        services.value = response.data?.services
        service_name.value = response.data?.name
        console.log(response)
        loadingService.value = false
        showProviderDetails.value = true
    }catch (e) {
        console.log(e.response)
        loadingService.value = false
    }

}
const addData = async () => {
    processing.value = true
    try {
        let response = await axios.post('/api/add-provider',provider.value)
        console.log(response)
        provider.value.name = ""
        provider.value.url = ""
        provider.value.api_key = ""
        provider.value.type = ""
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
        await axios.put(`/api/update-provider/${ed_provider.value.id}`,ed_provider.value)
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
const getData = async () => {
     loadingService.value = true
    try {
        let response = await axios.get('/api/get-provider')
        providers.value = response.data
        console.log(response)
        loadingService.value = false
    }catch (e) {
        console.log(e.response)
        loadingService.value = false
    }
}
onMounted(async () => {
    await getData()
})
</script>
<template>
    <div>
        <Card noborder>
            <div
                class="md:flex justify-between pb-6 md:space-y-0 space-y-3 items-center"
            >
                <h5>Providers</h5>
                <button @click.prevent="show = true" class="btn btn-dark">Add Provider</button>
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
                :rows="providers"
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

                          <Icon style="font-size: 1.2rem" icon="heroicons-outline:eye" @click.prevent="getProviderService(props.row.id)"  />


                          <Icon style="font-size: 1.2rem" icon="heroicons:pencil-square" @click.prevent="viewProvider(props.row.id)"  />

                          <Icon style="font-size: 1.2rem" icon="heroicons-outline:trash" @click.prevent="deleteProvider(props.row.id)"  />

                    </span>
                </template>
            </vue-good-table>
        </Card>
        <!--        Add-->
        <Modal
            title="Add Provider"
            :activeModal="show"
            labelClass="btn-outline-dark"
            sizeClass="max-w-5xl"
            centered
            @close="show = false"
        >
            <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
                <Textinput
                    label="Name"
                    type="text"
                    v-model="provider.name"
                    @input="clearErrors('name')"
                    :class-input="{'border-red-500':errors.name}"
                    :error="errors.name ? `${errors.name[0]}` : ''"
                    placeholder="Name"
                />
                <Textinput
                    label="URL"
                    type="url"
                    v-model="provider.url"
                    @input="clearErrors('url')"
                    :class-input="{'border-red-500':errors.url}"
                    :error="errors.url ? `${errors.url[0]}` : ''"
                    placeholder="URL"
                />
                <Textinput
                    label="API KEY"
                    v-model="provider.api_key"
                    @input="clearErrors('api_key')"
                    type="text"
                    :class-input="{'border-red-500':errors.api_key}"
                    :error="errors.api_key ? `${errors.api_key[0]}` : ''"
                    placeholder="Api Key"
                />

                <Select :options="types" label="TYPE" v-model="provider.type" />
            </div>
            <div class="float-right mb-3">
                <Button :isLoading="processing" @click.prevent="addData" btn-class="btn-dark">Add Provider</Button>
            </div>

        </Modal>
        <!--        Add-->
        <!--        Service-->
        <Modal
            title="Add Provider"
            :activeModal="showProviderDetails"
            labelClass="btn-outline-dark"
            sizeClass="max-w-10xl"
            centered

            @close="showProviderDetails = false"
        >
            <service :items="services" :title="service_name" />
        </Modal>
        <!--        Service-->
        <!--        View-->
        <Modal
            title="Add Provider"
            :activeModal="showEditModal"
            labelClass="btn-outline-dark"
            sizeClass="max-w-5xl"
            centered
            @close="showEditModal = false"
        >
            <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-5">
                <Textinput
                    label="Name"
                    type="text"
                    v-model="ed_provider.name"
                    @input="clearError('name')"
                    :class-input="{'border-red-500':error.name}"
                    :error="error.name ? `${error.name[0]}` : ''"
                    placeholder="Name"
                />
                <Textinput
                    label="URL"
                    type="url"
                    v-model="ed_provider.url"
                    @input="clearError('url')"
                    :class-input="{'border-red-500':error.url}"
                    :error="error.url ? `${error.url[0]}` : ''"
                    placeholder="URL"
                />
                <Textinput
                    label="API KEY"
                    v-model="ed_provider.api_key"
                    @input="clearError('api_key')"
                    type="text"
                    :class-input="{'border-red-500':error.api_key}"
                    :error="error.api_key ? `${error.api_key[0]}` : ''"
                    placeholder="Api Key"
                />

                <Select :options="types" label="TYPE" v-model="ed_provider.type" />
            </div>
            <div class="float-right mb-3">
                <Button :isLoading="processing" @click.prevent="updateServiceProvider" btn-class="btn-dark">Update Provider</Button>
            </div>

        </Modal>
        <!--        View-->

    </div>
</template>
<style lang="scss"></style>
