<script setup>
import ProgressBar from "@/components/ProgressBar/index.vue";
import VueSelect from "@/components/Select/VueSelect.vue";
import Textinput from "@/components/Textinput/index.vue";
import Textarea from "@/components/Textarea";
import {useRouter, useRoute} from "vue-router";
import { useToast } from "vue-toastification";
import Icon from "@/components/Icon/index.vue";
import vSelect from "vue-select";
const loadingService = ref(false)
import {computed, onBeforeMount, ref, watch} from "vue";
import {debounce} from "lodash";
import Button from "@/components/Button/index.vue";
import axiosClient from "@/plugins/axios";
import Card from "@/components/Card/index.vue";
const toast = useToast()
const  services= ref([])
const service = ref({})
const router = useRouter()
const route = useRoute()
const total = ref("0")
const errors = ref([])
const processing = ref(false)
const form = ref({
    country:'',
    quantity:0,
    link:'',
    payment:1
})

const user = computed(() => JSON.parse(window.localStorage.getItem('boost_user')))

const payments = ref([
    {id:1,name:'Main Balance' , balance:`${user.value.currency} ${user.value.balance}`},
    {id:2,name:'Referral Balance' , balance:`${user.value.currency} ${user.value.referral_balance}`}
])

const loading = ref(false)
const selectedOption = (val) => {
    if (val) {
        console.log(parseFloat(form.value.quantity))
        total.value = `GHC ${parseFloat(form.value.quantity) * parseFloat(val.price) / 1000}`
    }
}
const getService = debounce(async (e) => {
    if (e === "") return
    loadingService.value = true
    processing.value = true
    try {
        let response = await axiosClient.get(`/api/single-service/${route.params.id}`)
        loadingService.value = false
        processing.value = false
        service.value = response.data
        services.value = response.data.countries
        let q = response.data.countries.find(data => data.name == "Worldwide")
        if(q){
            form.value.country = q
        }
    }catch (err) {
        console.log(err)
        loadingService.value = false
        processing.value = false
    }
},500)

 const clearErrors = (val) => {
    delete errors.value[val]
 }

const calculateQuantity = (e) => {
    let quantity = parseFloat(e.target.value) ?? 0
    if (form.value.country) {
        total.value = `GHC ${parseInt(quantity * form.value.country.price).toFixed(1) / 1000}`
    }
    delete  errors.value["quantity"]
}

watch(() => route.params.id,async () => {
    await getService()
})

const buyNow = async () => {
    processing.value = true
    try {
        let response = await axiosClient.post('/api/order/social_media',form.value)
        console.log(response)
        processing.value = false
    }catch (e) {
        console.log(e)
        processing.value = false
        if(e.response.status === 422) {
            errors.value = e.response.data.errors
        }
        if (e.response.status === 417) {
            toast.warning('You dont have enough balance to complete the order')
         return await router.push({name:'add-funds'})
        }
    }
}
onBeforeMount(async () => {
    await getService()
})
</script>
<template>
    <div class="grid sm:grid-cols-3 mt-3">
        <div></div>
        <div>
            <Card noborder >
                <div class="mt-6 font-medium text-center">
                    <p class="text-muted text-slate-500 dark:text-white">Please Choose The Website Or App</p>
                </div>
                <div class="text-center">
                    <ProgressBar
                        max="w-2"
                        :value="66.6"
                        height="h-3"
                        title="2/3 step"
                    />
                </div>
              <div>
                  <div class="mt-5">
                      <VueSelect  label="Target Country" class="">
                          <vSelect :option:selected="selectedOption(form.country)"  :options="services" label="name" v-model="form.country" placeholder="Select Country" />
                      </VueSelect>
                  </div>
                  <div class="mt-3" v-if="service.show_link" >
                      <Textinput
                          label="Link"
                          type="url"
                          v-model="form.link"
                          @input="clearErrors('link')"
                          :placeholder="service.format"
                          :error="errors.link ? errors.link[0] : '' "
                      />
                  </div>
                  <div class="mt-3" v-if="service.show_quantity">
                      <Textinput
                          label="Quantity"
                          type="number"
                          v-model="form.quantity"
                          @input="calculateQuantity"
                          placeholder="Quantity"
                          :error="errors.quantity ? errors.quantity[0] : '' "
                      />
                      <small class="font-black text-black-500 dark:text-white">{{ form.country.currency }} {{form.country.price}} per 1000</small>
                  </div>
                  <div class="mt-3" v-if="service.show_comments">
                      <Textarea @input="clearErrors('comments')" label="Comments" placeholder="Comments"  :error="errors.comments ? errors.comments[0] : '' " />
                  </div>
                  <div class="mt-3" v-if="service.show_usernames">
                      <Textarea @input="clearErrors('usernames')" :error="errors.usernames ? errors.usernames[0] : '' " label="Usernames" placeholder="Usernames" />
                  </div>
                  <div class="mt-3" v-if="service.show_username">
                      <Textinput
                          @input="clearErrors('username')"
                          :error="errors.usernames ? errors.username[0] : '' "
                          label="Username"
                          type="url"
                          placeholder="Username"
                      />
                  </div>
                  <div class="mt-3" v-if="service.show_hashtags">
                      <Textarea
                          label="HashTags"
                          @input="clearErrors('hashtags')"
                          :error="errors.hashtags ? errors.hashtags[0] : '' "
                          placeholder="HashTags"
                      />
                  </div>
                  <div class="mt-3" v-if="service.show_answer">
                      <Textinput
                          label="Answer"
                          @input="clearErrors('answer')"
                          :error="errors.answer ? errors.answer[0] : '' "
                          type="url"
                          placeholder="Answer"
                      />
                  </div>
                  <div class="mt-3" v-if="service.show_groups">
                      <Textarea
                          label="Groups"
                          @input="clearErrors('groups')"
                          :error="errors.groups ? errors.groups[0] : '' "
                          placeholder="Groups" />
                  </div>
                  <div class="mt-3">
                      <Textinput
                          v-model="total"
                          readonly
                          disabled
                          class-input="text-white"
                          label="Total"
                          type="text"
                          placeholder="Total"
                      />
                  </div>
              </div>
                <div class="grid grid-cols-2 gap-4 mt-3">
                    <Card v-for="payment in payments"  :className="payment.id == form.payment ? 'bg-blue-300 dark:bg-blue-300 cursor-pointer ' : ''" class="cursor-pointer" >
                        <div @click="form.payment = payment.id">
                            <p class="flex justify-center text-center">
                                <Icon v-show="payment.id == form.payment" :class="payment.id == form.payment ? 'dark:text-black-500' : 'dark:text-white'" class="font-bold text-black-500  text-3xl" icon="material-symbols:check"  />
                            </p>
                            <p :class="payment.id == form.payment ? 'dark:text-black-500' : 'dark:text-white'" class="text-center font-medium text-black-500 text-base md:text-lg ">{{payment.name}}</p>
                            <p :class="payment.id == form.payment ? 'dark:text-black-500' : 'dark:text-white'" class="text-center font-medium  text-base md:text-lg ">{{payment.balance}}</p>
                        </div>

                    </Card>
                </div>
                  <div class="mt-3">
                      <Button @click.prevent="buyNow" :is-loading="processing" :disabled="processing" btn-class="bg-black-500 w-full p-2 text-vtd-primary-50 rounded">Pay Now</Button>
                  </div>

            </Card>

        </div>
        <div class="p-2 md:p-8 mt-3 md:mt-0 ">
            <h2 class="text-lg">Description</h2>
            <p class="text-gray-950 dark:text-white" v-html="form.country.description">
            </p>
        </div>
    </div>
</template>
<style lang=""></style>
