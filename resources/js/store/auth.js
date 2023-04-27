import {defineStore} from "pinia";
import {computed, ref} from "vue";

export const useAuthStore = defineStore('auth',() => {

    const authenticated = ref(false);
    const user = computed(() => window.localStorage.getItem('boost_user'));

    const token = computed(() => window.localStorage.getItem('boost_token'));

    return {
        authenticated,
        user,
        token
    }
})
