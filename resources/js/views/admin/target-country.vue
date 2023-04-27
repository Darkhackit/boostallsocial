<script setup>
import Card from "@/components/Card";
import Icon from "@/components/Icon";
import Textinput from "@/components/Textinput/index.vue";
import Select from "@/components/Select/index.vue";
import InputGroup from "@/components/InputGroup";
import Textarea from "@/components/Textarea";

import Button from "@/components/Button/index.vue";
import Modal from '@/components/Modal/Modal';
const processing = ref(false)
import {onMounted, ref} from "vue";
import VueSelect from "@/components/Select/VueSelect.vue";
import vSelect from "vue-select";
import Checkbox from "@/components/Checkbox/index.vue";
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
const medias = ref([])
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
const target_country = ref({
    name:'',
    price:'',
    min:'',
    max:'',
    service_id:'',
    provider:'',
    social_media:'',
    description:'',
})
const ed_target_country = ref({
    id:'',
    name:'',
    price:'',
    min:'',
    max:'',
    service_id:'',
    provider:'',
    social_media:'',
    description:'',
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
        label: "Price",
        field: "price",
    },
    {
        label: "Service",
        field: "service_id",
    },
    {
        label: "Provider",
        field: "provider",
    },
    {
        label: "Social Media",
        field: "social_media",
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
            await axios.delete(`/api/target-country/${id}`)
            await getData()
        }catch (e) {
            console.log(e.response)
        }
    }
}

const viewData = async (id) => {
    loadingService.value = true
    try {
        let response = await axios.get(`/api/target-country/${id}`)
        ed_target_country.value.name = response.data.name
        ed_target_country.value.price = response.data.price
        ed_target_country.value.min = response.data.min
        ed_target_country.value.max = response.data.max
        ed_target_country.value.description = response.data.description
        ed_target_country.value.service_id = response.data.service_id
        ed_target_country.value.provider = response.data.provider
        ed_target_country.value.social_media = response.data.social_media
        ed_target_country.value.id = response.data.id
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
        let response = await axios.post('/api/target-country',target_country.value)
        console.log(response)
        target_country.value.name = ""
        target_country.value.description = ""
        target_country.value.min = ""
        target_country.value.max = ""
        target_country.value.provider = ""
        target_country.value.social_media = ""
        target_country.value.price = ""
        target_country.value.service_id = ""
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
        await axios.put(`/api/target-country/${ed_target_country.value.id}`,ed_target_country.value)
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

const getProviders = async () => {
    loadingService.value = true
    try {
        let response = await axios.get('/api/get-provider-names')
        providers.value = response.data
        console.log(response)
        loadingService.value = false
    }catch (e) {
        console.log(e.response)
        loadingService.value = false
    }
}
const getMedias = async () => {
    loadingService.value = true
    try {
        let response = await axios.get('/api/get-media-names')
        medias.value = response.data
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
        let response = await axios.get('/api/target-country')
        countries.value = response.data
        console.log(response)
        loadingService.value = false
    }catch (e) {
        console.log(e.response)
        loadingService.value = false
    }
}
onMounted(async () => {
    await getData()
    await getProviders()
    await getMedias()
})
</script>
<template>
    <div>
        <Card noborder>
            <div
                class="md:flex justify-between pb-6 md:space-y-0 space-y-3 items-center"
            >
                <h5>Target Country</h5>
                <button @click.prevent="show = true" class="btn btn-dark">Add Target Country</button>
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
                :rows="countries"
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
                    <span class="flex" v-if="props.column.field == 'price'">
                        <p>GHC {{props.row.price}}</p>
                    </span>
                </template>
            </vue-good-table>
        </Card>
        <!--        Add-->
        <Modal
            title="Add Target Country"
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
                    v-model="target_country.name"
                    @input="clearErrors('name')"
                    :class-input="errors.name ? 'border-red-500' : ''"
                    :error="errors.name ? `${errors.name[0]}` : ''"
                    placeholder="Name"
                />
                <Textinput
                    label="Price"
                    type="text"
                    v-model="target_country.price"
                    @input="clearErrors('price')"
                    :class-input="{'border-red-500':errors.price}"
                    :error="errors.price ? `${errors.price[0]}` : ''"
                    placeholder="Price"
                />
                <Textinput
                    label="Minimum"
                    v-model="target_country.min"
                    @input="clearErrors('min')"
                    type="number"
                    :class-input="{'border-red-500':errors.min}"
                    :error="errors.min ? `${errors.min[0]}` : ''"
                    placeholder="Min"
                />
                <Textinput
                    label="Maximum"
                    v-model="target_country.max"
                    @input="clearErrors('max')"
                    type="number"
                    :class-input="{'border-red-500':errors.max}"
                    :error="errors.max ? `${errors.max[0]}` : ''"
                    placeholder="Max"
                />
                <Textinput
                    label="Service ID"
                    v-model="target_country.service_id"
                    @input="clearErrors('service_id')"
                    type="number"
                    :class-input="{'border-red-500':errors.service_id}"
                    :error="errors.service_id ? `${errors.service_id[0]}` : ''"
                    placeholder="Service ID"
                />
                <VueSelect  label="Provider" class="">
                    <vSelect v-model="target_country.provider" :options="providers"  placeholder="Search Service" />
                </VueSelect>
                <VueSelect  label="Social Media" class="">
                    <vSelect v-model="target_country.social_media" :options="medias" label="title"  placeholder="Search Service" />
                </VueSelect>
                <Textarea label="Description" v-model="target_country.description" placeholder="Description" />
            </div>
            <div class="float-right mb-3">
                <Button :isLoading="processing" @click.prevent="addData" btn-class="btn-dark">Add Social Media Service</Button>
            </div>

        </Modal>
        <!--        Add-->
        <!--        Service-->
        <!--        Service-->
        <!--        View-->
        <Modal
            title="Update Target Country"
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
                    v-model="ed_target_country.name"
                    @input="clearError('name')"
                    :class-input="error.name ? 'border-red-500' : ''"
                    :error="error.name ? `${error.name[0]}` : ''"
                    placeholder="Name"
                />
                <Textinput
                    label="Price"
                    type="text"
                    v-model="ed_target_country.price"
                    @input="clearError('price')"
                    :class-input="{'border-red-500':error.price}"
                    :error="error.price ? `${error.price[0]}` : ''"
                    placeholder="Price"
                />
                <Textinput
                    label="Minimum"
                    v-model="ed_target_country.min"
                    @input="clearError('min')"
                    type="number"
                    :class-input="{'border-red-500':error.min}"
                    :error="error.min ? `${error.min[0]}` : ''"
                    placeholder="Min"
                />
                <Textinput
                    label="Maximum"
                    v-model="ed_target_country.max"
                    @input="clearError('max')"
                    type="number"
                    :class-input="{'border-red-500':error.max}"
                    :error="error.max ? `${error.max[0]}` : ''"
                    placeholder="Max"
                />
                <Textinput
                    label="Service ID"
                    v-model="ed_target_country.service_id"
                    @input="clearError('service_id')"
                    type="number"
                    :class-input="{'border-red-500':error.service_id}"
                    :error="error.service_id ? `${error.service_id[0]}` : ''"
                    placeholder="Service ID"
                />
                <VueSelect  label="Provider" class="">
                    <vSelect v-model="ed_target_country.provider" :options="providers"  placeholder="Search Service" />
                </VueSelect>
                <VueSelect  label="Social Media" class="">
                    <vSelect v-model="ed_target_country.social_media" :options="medias" label="title"  placeholder="Search Service" />
                </VueSelect>
                <Textarea label="Description" v-model="ed_target_country.description" placeholder="Description" />
            </div>>
            <div class="float-right mb-3">
                <Button :isLoading="processing" @click.prevent="updateServiceProvider" btn-class="btn-dark">Update Target Country</Button>
            </div>

        </Modal>
        <!--        View-->

    </div>
</template>
<style lang="scss"></style>
