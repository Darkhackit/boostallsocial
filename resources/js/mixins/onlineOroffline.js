import {ref} from "vue";

export const onlineOfflinePlugin = {
    install(app) {
        const isOnline = ref(navigator.onLine); // Create a reactive data property

        const handleOnlineStatus = () => {
            isOnline.value = navigator.onLine; // Update the value of isOnline
            navigator.onLine ? document.getElementsByTagName( 'html' )[0].classList.remove('grayscale') : document.getElementsByTagName( 'html' )[0].classList.add('grayscale');

        };

        window.addEventListener('online', handleOnlineStatus);
        window.addEventListener('offline', handleOnlineStatus);

        // Define a global property
        app.config.globalProperties.$isOnline = isOnline;

        // Alternatively, define a global mixin
        // app.mixin({
        //     computed: {
        //         $isOnline() {
        //             return isOnline.value;
        //         }
        //     }
        // });
    }
};

