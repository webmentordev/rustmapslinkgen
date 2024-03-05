export default defineNuxtRouteMiddleware(async() => {
    const api = useRuntimeConfig().public.api;
    const cookie = useCookie('token');
    
    if(cookie.value){
        try{
            await $fetch(`${api}/user`, {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer '+cookie.value.token
                }
            });
            return navigateTo('/');
        }catch(e){
            cookie.value = null;
        }
    }
})