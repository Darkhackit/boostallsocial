<script setup>
import {ref} from 'vue'
import Textinput from "@/components/Textinput/index.vue";
const user = ref(JSON.parse(window.localStorage.getItem('boost_user')))
const processing = ref(false)
const form = ref({
    payment:3,
    amount:20,
    payment_name:'Mobile Money',
    show_input:false,
    description:''
})
const payments = ref([
    {id:3,name:'Mobile Money',description:null,show_input:false, img_link:'https://res.cloudinary.com/dubea2mve/image/upload/c_scale,q_99,w_47/v1683632132/pngaaa.com-5179549_bxfuff.png'},
    {id:2,name:'Coinbase',description:`1 $ = GHS 11.06`,show_input:true, img_link:'https://temp-number.org/app/img/0xprocessing.6b14f7c1.svg'},
    {id:1,name:'Perfect Money',description:`1 $ = GHS 11.06`,show_input:true, img_link:'https://temp-number.org/app/img/perfectMoney.71b026fd.svg'},
    {id:4,name:'Binance',description:`1 $ = GHS 11.06`,show_input:true, img_link:'https://res.cloudinary.com/dubea2mve/image/upload/c_scale,h_196,w_1269/v1683633607/binance-logo-1_yvj2zh.svg'},
])

const doPayment = async () => {
    processing.value = true
    try {
        let response = await axiosClient.post(`/api/payments`,{id:form.value.payment,amount:form.value.amount})
        processing.value = false
        console.log(response)
        return window.open(`${response.data.data.hosted_url}`,'_blank')
    }catch (e) {
        console.log(e.response)
        processing.value = false
    }
}
const getPaymentInfo = (payment) => {
    form.value.payment = payment.id
    form.value.payment_name = payment.name
    form.value.show_input = payment.show_input
    form.value.description = payment.description
}
import Card from "@/components/Card/index.vue";
import Button from "@/components/Button/index.vue";
import axiosClient from "@/plugins/axios";
</script>
<template>
    <div class="grid sm:grid-cols-6  mt-3">

       <div class="col-start-2 col-span-4 ">
           <Card>
               <div class="flex justify-between">
                   <h1 class="text-2xl font-bold">Add Funds</h1>
                   <h1 class="text-2xl font-medium">{{user.currency}} {{user.balance}}</h1>
               </div>
               <div class="mt-6 font-medium">
                   <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                       <Card v-for="payment in payments" :key="payment.id" :class-name="payment.id == form.payment ? 'bg-yellow-300 dark:bg-yellow-300' : ''">
                           <div class="object-center flex justify-center items-center" @click="getPaymentInfo(payment)">
                               <img  :src="payment.img_link" class="" />
                           </div>
                       </Card>
                   </div>
                   <div class="mt-5">
                      <p class="text-lg text-black-500 dark:text-white">{{form.payment_name}}</p>
                      <p class=" text-black-500 dark:text-white">
                          {{form.description}}
                      </p>
                       <div class="mt-3 grid grid-cols-1 md:grid-cols-4" v-if="form.show_input">
                           <Textinput label="Amount" placeholder="Amount" type="number" v-model="form.amount" />
                       </div>
                       <div class="mt-3 grid grid-cols-1 md:grid-cols-4" v-if="form.show_input">
                           <Button :is-loading="processing" :is-disabled="processing" @click="doPayment" btn-class="btn-dark">Pay Now</Button>
                       </div>

                   </div>
               </div>
           </Card>
       </div>


    </div>
</template>

<style scoped>

</style>
