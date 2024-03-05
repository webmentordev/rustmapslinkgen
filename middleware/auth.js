export default defineNuxtRouteMiddleware(async() => {
    const api = useRuntimeConfig().public.api;
    const cookie = useCookie('token');

    if(cookie.value){
        try{
            await $fetch(`${api}/api/user`, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer '+cookie.value.token
                }
            });
        }catch(e){
            return navigateTo('/login');
        }
    }else{
        return navigateTo('/login');
    }
})