<template>
    <nav class="w-full top-0 left-0 sticky border-b border-gray-200 bg-white">
        <div class="max-w-3xl m-auto flex items-center justify-between py-3 px-4">
            <NuxtLink to="/"><img src="https://files.facepunch.com/lewis/1b2911b1/rust-marque.svg" /></NuxtLink>
            <ul class="font-semibold flex items-center">
                <NuxtLink class="px-6" to="/">Home</NuxtLink>
                <div v-if="!auth">
                    <NuxtLink class="px-6" to="/login">Login</NuxtLink>
                    <NuxtLink class="px-6" to="/signup">Signup</NuxtLink>
                </div>
                <button v-else @click="logoutHandler">Logout</button>
                <NuxtLink class="ml-6 bg-black text-white py-2 px-4" to="/upload">Upload 😁</NuxtLink>
            </ul>
        </div>
    </nav>
</template>

<script setup>
    const auth = ref(false);

    const api = useRuntimeConfig().public.api;
    const cookie = useCookie('token');

    watchEffect(() => {
        if(cookie.value){
            auth.value = true;
        }else{
            auth.value = false;
        }
    })

    async function logoutHandler(){
        try{
            const data = await $fetch(`${api}/api/logout`, {
                method: "delete",
                headers: {
                    'Authorization': 'Bearer '+cookie.value.token
                }
            });
            if(data.status == 201){
                cookie.value = null;
                navigateTo('/login');
            }
        }catch(e){
            console.log(e.message);
        }
    }
</script>