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
import Checkbox from "@/components/Checkbox/index.vue";
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
const socialMedia = ref({
    name:'',
    image:'',
    input_format:'',
    show_link:false,
    popular_service:false,
    show_comments:false,
    show_quantity:false,
    show_usernames:false,
    show_username:false,
    show_hashtags:false,
    show_answer_number:false,
    show_groups:false,
})
const ed_social = ref({
    id:'',
    name:'',
    image:'',
    input_format:'',
    show_link:false,
    popular_service:false,
    show_comments:false,
    show_quantity:false,
    show_usernames:false,
    show_username:false,
    show_hashtags:false,
    show_answer_number:false,
    show_groups:false,
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
        label: "Image",
        field: "image",
    },
    {
        label: "Format",
        field: "format",
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
            await axios.delete(`/api/delete-social/${id}`)
            await getData()
        }catch (e) {
            console.log(e.response)
        }
    }
}
const viewProvider = async (id) => {
    loadingService.value = true
    try {
        let response = await axios.get(`/api/view-social/${id}`)
        ed_social.value.name = response.data.name
        ed_social.value.id = response.data.id
        ed_social.value.image = response.data.image
        ed_social.value.input_format = response.data.format
        ed_social.value.show_usernames = response.data.show_usernames
        ed_social.value.show_username = response.data.show_username
        ed_social.value.show_answer_number = response.data.show_answer_number
        ed_social.value.show_link = response.data.show_link
        ed_social.value.show_quantity = response.data.show_quantity
        ed_social.value.show_hashtags = response.data.show_hashtags
        ed_social.value.show_comments = response.data.show_comments
        ed_social.value.show_groups = response.data.show_groups
        ed_social.value.popular_service = response.data.popular_service
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
        let response = await axios.post('/api/add-social-media-service',socialMedia.value)
        console.log(response)
        socialMedia.value.name = ""
        socialMedia.value.image = ""
        socialMedia.value.format = ""
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
        await axios.put(`/api/update-social/${ed_social.value.id}`,ed_social.value)
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
        let response = await axios.get('/api/get-social-media')
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
                <h5>Social Media</h5>
                <button @click.prevent="show = true" class="btn btn-dark">Add Social Media Service</button>
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

                          <Icon style="font-size: 1.2rem" icon="heroicons:pencil-square" @click.prevent="viewProvider(props.row.id)"  />

                          <Icon style="font-size: 1.2rem" icon="heroicons-outline:trash" @click.prevent="deleteProvider(props.row.id)"  />

                    </span>
                </template>
            </vue-good-table>
        </Card>
        <!--        Add-->
        <Modal
            title="Add Social Media Service"
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
                    v-model="socialMedia.name"
                    @input="clearErrors('name')"
                    :class-input="errors.name ? 'border-red-500' : ''"
                    :error="errors.name ? `${errors.name[0]}` : ''"
                    placeholder="Name"
                />
                <Textinput
                    label="Image"
                    type="url"
                    v-model="socialMedia.image"
                    @input="clearErrors('image')"
                    :class-input="{'border-red-500':errors.image}"
                    :error="errors.image ? `${errors.image[0]}` : ''"
                    placeholder="IMAGE LINK"
                />
                <Textinput
                    label="Format"
                    v-model="socialMedia.input_format"
                    @input="clearErrors('input_format')"
                    type="text"
                    :class-input="{'border-red-500':errors.input_format}"
                    :error="errors.input_format ? `${errors.input_format[0]}` : ''"
                    placeholder="FORMAT"
                />
            </div>
                <div class="flex space-x-rb flex-wrap mt-5">
                    <Checkbox label="Show Answer Number" :checked="socialMedia.show_answer_number" v-model="socialMedia.show_answer_number" />
                    <Checkbox label="Popular Service" :checked="socialMedia.popular_service" v-model="socialMedia.popular_service" />
                    <Checkbox label="Show Comments" :checked="socialMedia.show_comments" v-model="socialMedia.show_comments" />
                    <Checkbox label="Show Groups" :checked="socialMedia.show_groups" v-model="socialMedia.show_groups" />
                    <Checkbox label="Show HashTags" :checked="socialMedia.show_hashtags" v-model="socialMedia.show_hashtags" />
                    <Checkbox label="Show Link" :checked="socialMedia.show_link" v-model="socialMedia.show_link" />
                    <Checkbox label="Show Quantity" :checked="socialMedia.show_quantity" v-model="socialMedia.show_quantity" />
                    <Checkbox label="Show Username" :checked="socialMedia.show_username" v-model="socialMedia.show_username" />
                    <Checkbox label="Show Usernames" :checked="socialMedia.show_usernames" v-model="socialMedia.show_usernames" />
                </div>
            <div class="float-right mb-3">
                <Button :isLoading="processing" @click.prevent="addData" btn-class="btn-dark">Add Social Media Service</Button>
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
                    v-model="ed_social.name"
                    @input="clearError('name')"
                    :class-input="{'border-red-500':error.name}"
                    :error="error.name ? `${error.name[0]}` : ''"
                    placeholder="Name"
                />
                <Textinput
                    label="Image"
                    type="url"
                    v-model="ed_social.image"
                    @input="clearError('image')"
                    :class-input="{'border-red-500':error.image}"
                    :error="error.image ? `${error.image[0]}` : ''"
                    placeholder="IMAGE LINK"
                />
                <Textinput
                    label="Format"
                    v-model="ed_social.input_format"
                    @input="clearError('input_format')"
                    type="text"
                    :class-input="{'border-red-500':error.input_format}"
                    :error="error.input_format ? `${error.input_format[0]}` : ''"
                    placeholder="FORMAT"
                />
            </div>
            <div class="flex space-x-rb flex-wrap mt-5">
                <Checkbox label="Show Answer Number" :checked="ed_social.show_answer_number" v-model="ed_social.show_answer_number" />
                <Checkbox label="Popular Service" :checked="ed_social.popular_service" v-model="ed_social.popular_service" />
                <Checkbox label="Show Comments" :checked="ed_social.show_comments" v-model="ed_social.show_comments" />
                <Checkbox label="Show Groups" :checked="ed_social.show_groups" v-model="ed_social.show_groups" />
                <Checkbox label="Show HashTags" :checked="ed_social.show_hashtags" v-model="ed_social.show_hashtags" />
                <Checkbox label="Show Link" :checked="ed_social.show_link" v-model="ed_social.show_link" />
                <Checkbox label="Show Quantity" :checked="ed_social.show_quantity" v-model="ed_social.show_quantity" />
                <Checkbox label="Show Username" :checked="ed_social.show_username" v-model="ed_social.show_username" />
                <Checkbox label="Show Usernames" :checked="ed_social.show_usernames" v-model="ed_social.show_usernames" />
            </div>
            <div class="float-right mb-3">
                <Button :isLoading="processing" @click.prevent="updateServiceProvider" btn-class="btn-dark">Update Social Media Service</Button>
            </div>

        </Modal>
        <!--        View-->

    </div>
</template>
<style lang="scss"></style>
