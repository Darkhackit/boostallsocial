import { createRouter, createWebHistory } from "vue-router";
import {useAuthStore} from "@/store/auth";

import route from "./route.js";
import admin from "./admin.js";
import {computed} from "vue";


let routes = [];

const host = window.location.host;
const parts = host.split('.');
const domainLength = 2

if (parts.length === (domainLength - 1) || parts[0] === 'www') {
    routes = route;
} else if (parts[0] === 'administrator') {
    routes = admin;
} else if (parts[0] === 'route2') {
    routes = admin;
} else {
    // If you want to do something else just comment the line below
    routes = route;
}
const router = createRouter({
  history: createWebHistory(import.meta.BASE_URL),
  base: import.meta.BASE_URL,
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition;
    } else {
      return { top: 0 };
    }
  },
});
router.beforeEach((to, from, next) => {
    const store = useAuthStore()
    const token = store.authenticated
    console.log(token)
    if(!token && to.name !== 'login' && to.name !== 'register' && to.name !== 'confirm_code' && to.name !== 'forget_password' && to.name !== 'change_password') {
         next({name:'login'})
        return
    }
    if (to.meta.requireAuth && token) {
         next({name:'home'})
        return
    }
      const titleText = to.name;
      const words = titleText.split(" ");
      const wordslength = words.length;
      for (let i = 0; i < wordslength; i++) {
        words[i] = words[i][0].toUpperCase() + words[i].substr(1);
      }

  document.title = "Boostallsocial  - " + words;

  next();
});

router.afterEach(() => {
  // Remove initial loading
  const appLoading = document.getElementById("loading-bg");
  if (appLoading) {
    appLoading.style.display = "none";
  }
});

export default router;
