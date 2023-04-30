<script setup>
import Textinput from "@/components/Textinput";
import { useAuthStore } from "@/store/auth";

import { useRouter,useRoute } from "vue-router";
import { useToast } from "vue-toastification";
// Image Import
import loginBg from "@/assets/images/all-img/page-bg.png"
import logoWhite from "@/assets/images/logo/logo-white.svg"
import logo from "@/assets/images/logo/logo.svg"
import {ref} from "vue";


const router = useRouter()
const route = useRoute()
const store = useAuthStore()
const form = ref({
    email:store.changePasswordEmail,
    password:'',
    password_confirmation:''
})
const errors = ref([])
const processing = ref(false)
const clearErrors = (val) => {
    delete errors.value[val]
}

const onSubmit = async () => {
    processing.value = true
    try {
        let response = await axios.post('/api/auth/change_password',form.value)
        router.push({name:'login'})
        processing.value = false
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
        <div class="flex flex-col items-center justify-center w-full ">
            <div class="auth-box-3">
                <div class="block mb-6 text-center mobile-logo lg:hidden">
                    <router-link to="/"
                    ><img :src="logo" alt="" class="mx-auto"
                    /></router-link>
                </div>
                <div class="mb-5 text-center 2xl:mb-10">
                    <h4 class="font-medium">Change Password</h4>
                    <div class="text-base text-slate-500 dark:text-slate-400">
                        Sign in to your account to start using Boostallsocial
                    </div>
                </div>
                <form @submit.prevent="onSubmit" class="space-y-4">
                    <Textinput
                        label="Password"
                        type="password"
                        placeholder="New Password "
                        name="password"
                        @input="clearErrors('password')"
                        v-model="form.password"
                        :error="errors.password ? errors.password[0] : ''"
                        hasicon
                        classInput="h-[48px]"
                    />
                    <Textinput
                    label="Password Confirmation"
                    type="password"
                    placeholder="Password Confirmation "
                    name="password"
                    @input="clearErrors('password_confirmation')"
                    v-model="form.password_confirmation"
                    :error="errors.password_confirmation ? errors.password_confirmation[0] : ''"
                    hasicon
                    classInput="h-[48px]"
                />

                    <button type="submit" :disabled="processing" class="block w-full text-center btn btn-dark">
                        Change Password
                    </button>
                </form>
                <div
                    className=" relative border-b-[#9AA2AF] border-opacity-[16%] border-b pt-6"
                >

                </div>
                <div class="max-w-[242px] mx-auto mt-5 w-full">

                </div>
                <div
                    class="mx-auto mt-6 text-sm font-normal text-center uppercase text-slate-500 dark:text-slate-400 2xl:mt-12"
                >
                    Dont have an account?
                    <router-link
                        :to="{name:'login'}"
                        class="font-medium text-slate-900 dark:text-white hover:underline"
                    >
                        Sign In</router-link
                    >
                </div>
            </div>
        </div>
    </div>
</template>


<style scoped>

</style>
