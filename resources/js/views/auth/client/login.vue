<script setup>
import Textinput from "@/components/Textinput";
import { useAuthStore } from "@/store/auth";

import { useRouter } from "vue-router";
import { useToast } from "vue-toastification";
// Image Import
import logo from "@/assets/images/logo/logo.svg"
import {ref} from "vue";
import Button from "@/components/Button/index.vue";
import axiosClient from "@/plugins/axios";

const form = ref({
    email:'',
    password:''
})
const toast = useToast()
const router = useRouter()
const store = useAuthStore()
const errors = ref([])
const processing = ref(false)
const showVerify = ref(false)
const clearErrors = (val) => {
    delete errors.value[val]
}
const ShowVerifyEmail = async () => {
    processing.value = true
    try {
       let response = await axiosClient.post('/api/auth/verify_email',{email:form.value.email})
        processing.value = false
       await  router.push({name:'confirm_code',params:{email:response.data.email}})
    }catch (e) {
        console.log(e.response)
        processing.value = false
    }
}

const onSubmit = async () => {
    processing.value = true
    try {
        let response = await axiosClient.post('/api/auth/login',form.value)
        processing.value = false
        toast("Login successful")
        window.localStorage.setItem('boost_user',JSON.stringify(response.data.user))
        window.localStorage.setItem('boost_token',JSON.stringify(response.data.access_token))
        store.authenticated = true
        await router.push({name:'home'})
      }catch (e) {
        console.log(e)
        if(e.response.status === 422) {
            errors.value = e.response.data.errors
        }
        if(e.response.status === 417) {
            showVerify.value = true
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
                    <h4 class="font-medium">Sign In</h4>
                    <div class="text-slate-500 dark:text-slate-400 text-base">
                        Sign in to your account to start using Boostallsocial
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
                    <small @click="ShowVerifyEmail" v-if="!errors.length && showVerify" class="text-red-500 cursor-pointer">click here to verify email</small>

                    <Textinput
                        label="Password"
                        type="password"
                        placeholder="Password"
                        name="password"
                        @input="clearErrors('password')"
                        v-model="form.password"
                        :error="errors.password ? errors.password[0] : ''"
                        hasicon
                        classInput="h-[48px]"
                    />

                    <Button type="submit" btnClass="btn-dark" :is-loading="processing" :is-disabled="processing" class="btn btn-dark block w-full text-center">

                        Sign in
                    </Button>
                </form>
                <div
                    className=" relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6"
                >

                </div>
                <div class="max-w-[242px] mx-auto mt-5 w-full text-center">

                         <router-link class="text-slate-900 dark:text-white font-medium hover:underline"
                          :to="{name:'forget_password'}">Forget Password</router-link>

                </div>
                <div
                    class="mx-auto font-normal text-slate-500 dark:text-slate-400 2xl:mt-12 mt-6 uppercase text-sm text-center"
                >
                    Dont have an account?
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
