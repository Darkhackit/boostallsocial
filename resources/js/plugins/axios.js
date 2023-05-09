import axios from "axios";
import {useAuthStore} from "@/store/auth";
import {useRouter} from "vue-router";
import {computed} from "vue";

const axiosClient = axios.create({

})

const responseHandler = async (response) => {
    return  response
}
const requestHandler = async (request) => {
    let token = JSON.parse(window.localStorage.getItem('boost_token'))
    request.headers.Authorization = 'Bearer ' + token;

    return request
}
const errorHandler = async (error) => {
    const router = useRouter()
    if(error.response.status === 403) {
        return   await router.replace({name:'not-authorized'})
    }
    if(error.response.status === 401) {
        const store = useAuthStore()
        store.authenticated = false
        window.localStorage.removeItem("boost_token");
        window.localStorage.removeItem("boost_user");
        return   await router.replace({name:'login'})
    }
    if (error.response.status === 500) {
        return   await router.replace({name:'internal-server-error'})
    }

    return Promise.reject(error)
}

axiosClient.interceptors.request.use(
    (request) => requestHandler(request),
    (error) => errorHandler(error)
)

axiosClient.interceptors.response.use(
    (response) => responseHandler(response),
    (error) => errorHandler(error)
)
export default axiosClient;
