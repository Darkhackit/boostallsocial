<script setup>
import Textinput from "@/components/Textinput";
import { useField, useForm } from "vee-validate";
import * as yup from "yup";

import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";
// Image Import
import loginBg from "@/assets/images/all-img/page-bg.png"
import logoWhite from "@/assets/images/logo/logo-white.svg"
import logo from "@/assets/images/logo/logo.svg"
import {ref} from "vue";
import Button from "@/components/Button/index.vue";
import axiosClient from "@/plugins/axios";

const router = useRouter()
const form = ref({
    email:'',
})
const errors = ref([])
const processing = ref(false)
const clearErrors = (val) => {
    delete errors.value[val]
}

const onSubmit = async () => {
    processing.value = true
    try {
        let response = await axiosClient.post('/api/auth/forget_password',form.value)
        await router.push({name:'confirm_code',params:{email:response.data.email},query:{r:'forget_password'}})
        processing.value = false
        console.log(response)
    }catch (e) {
        if(e.response.status === 422) {
            errors.value = e.response.data.errors
        }
        processing.value = false
    }
}
</script>
<template>
    <div class="lg-inner-column">
        <div class=" w-full flex flex-col items-center justify-center">
            <div class="auth-box-3">
                <div class="mobile-logo text-center mb-6 lg:hidden block">
                    <router-link to="/"
                    ><img :src="logo" alt="" class="mx-auto"
                    /></router-link>
                </div>
                <div class="text-center 2xl:mb-10 mb-5">
                    <h4 class="font-medium">Forget Password</h4>
                    <div class="text-slate-500 dark:text-slate-400 text-base">
                        A confirmation code will be sent to your email address
                    </div>
                </div>
                <form @submit.prevent="onSubmit" class="space-y-4">
                    <Textinput
                        label="Email"
                        type="email"
                        placeholder="Type your email"
                        name="emil"
                        @input="clearErrors('email')"
                        v-model="form.email"
                        :error="errors.email ? errors.email[0] : ''"
                        classInput="h-[48px]"
                    />

                    <Button :is-loading="processing" btn-class="btn-dark" type="submit" :is-disabled="processing" class="btn btn-dark block w-full text-center">
                        Reset Password
                    </Button>
                </form>
                <div
                    className=" relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6"
                >

                </div>
                <div class="max-w-[242px] mx-auto mt-5 w-full">

                </div>
                <div
                    class="mx-auto font-normal text-slate-500 dark:text-slate-400 2xl:mt-12 mt-6 uppercase text-sm text-center"
                >
                    Don't have an account?
                    <router-link
                        :to="{name:'register'}"
                        class="text-slate-900 dark:text-white font-medium hover:underline"
                    >
                        Sign up</router-link
                    >
                </div>
            </div>
        </div>
    </div>
</template>


<style scoped>

</style>
