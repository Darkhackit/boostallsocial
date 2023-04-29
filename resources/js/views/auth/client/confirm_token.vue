<script setup>
import Textinput from "@/components/Textinput";

import { useRouter,useRoute } from "vue-router";
import { useToast } from "vue-toastification";
// Image Import
import logo from "@/assets/images/logo/logo.svg"
import {ref} from "vue";

const router = useRouter()
const route = useRoute()
const form = ref({
    email:route.params.email,
    code:'',
})
const errors = ref([])
const processing = ref(false)
const clearErrors = (val) => {
    delete errors.value[val]
}

const onSubmit = async () => {
    processing.value = true
    try {
        let response = await axios.post('/api/auth/code_confirm',form.value)
        router.push({name:'confirm_code',params:{email:response.data.email}})
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
        <div class="flex flex-col items-center justify-center w-full ">
            <div class="auth-box-3">
                <div class="block mb-6 text-center mobile-logo lg:hidden">
                    <router-link to="/"
                    ><img :src="logo" alt="" class="mx-auto"
                    /></router-link>
                </div>
                <div class="mb-5 text-center 2xl:mb-10">
                    <h4 class="font-medium">Code Confirmation</h4>

                </div>
                <form @submit.prevent="onSubmit" class="space-y-4">
                    <Textinput
                        label="Email"
                        type="email"
                        readonly
                        disabled
                        placeholder="Type your email"
                        name="emil"
                        @input="clearErrors('email')"
                        v-model="form.email"
                        :error="errors.email ? errors.email[0] : ''"
                        classInput="h-[48px]"
                    />
                    <Textinput
                        label="Code"
                        type="number"
                        placeholder="Enter code "
                        @input="clearErrors('code')"
                        v-model="form.code"
                        :error="errors.code ? errors.code[0] : ''"
                        classInput="h-[48px]"
                    />

                    <button type="submit" :disabled="processing" class="block w-full text-center btn btn-dark">
                        Verify
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
                        Sign Up</router-link
                    >
                </div>
            </div>
        </div>
    </div>
</template>


<style scoped>

</style>
