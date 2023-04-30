import {defineStore} from "pinia";
import {computed, ref} from "vue";

export const useAuthStore = defineStore('auth',() => {

    const authenticated = ref(false);
    const changePasswordEmail = ref("")
    const user = computed(() => JSON.parse( window.localStorage.getItem('boost_user')));

    const token = computed(() => JSON.parse(window.localStorage.getItem('boost_token')));

    token.value ? authenticated.value = true : authenticated.value = false

    return {
        authenticated,
        user,
        token,
        changePasswordEmail
    }
})
