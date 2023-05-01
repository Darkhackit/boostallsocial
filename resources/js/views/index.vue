<script setup>
import ProgressBar from "@/components/ProgressBar/index.vue";
import VueSelect from "@/components/Select/VueSelect.vue";
import {useRouter} from "vue-router";
import Icon from "@/components/Icon";
import vSelect from "vue-select";
const loadingService = ref(false)
import {ref} from "vue";
import {debounce} from "lodash";
const  services= ref([])
const service = ref("")
const router = useRouter()
const selectedOption = (val) => {
    if (val) {
        router.push({name:'social-details',params:{id:val.name}})
    }
}
const getService = debounce(async (e) => {
    if (e === "") return
    loadingService.value = true
    try {
        let response = await axios.get(`/api/get-service?q=${e}`)
        loadingService.value = false
        services.value = response.data.data
    }catch (err) {
        console.log(err.response)
        loadingService.value = false
    }
},500)
</script>
<template>
  <div class="grid sm:grid-cols-3 text-center justify-center items-center mt-3">
      <div></div>
      <div>
          <h1 class="text-4xl font-bold">Service</h1>
          <div class="mt-6 font-medium">
              <p class="text-muted text-slate-500">Please Choose The Website Or App</p>
          </div>
          <div>
              <ProgressBar
                  max="w-2"
                  :value="33.3"
                  height="h-3"
                  title="1/3 step"

              />
          </div>
          <div class="mt-5">
              <VueSelect >
                  <vSelect :option:selected="selectedOption(service)" @search="getService" v-model="service" :loading="loadingService" :options="services" label="name"  placeholder="Search Service" >
                      <template #no-options>
                          type to search Service..
                      </template>
                      <template #option="{ name, image }">
                          <span class="flex items-center">
                            <span class="inline-block mr-2">
                                <img style="width: 25px;border-radius: 50%" :src="image" />
                            </span>
                            <span class="text-[1rem]">{{ name }}</span>
                          </span>
                      </template>
                  </vSelect>
              </VueSelect>
          </div>
      </div>
      <div></div>
  </div>
</template>
<style lang=""></style>
