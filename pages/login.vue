<template>
    <section class="h-[70vh] flex items-center">
        <div class="max-w-lg w-full m-auto py-12">
            <h1 class="text-4xl mb-2">Login into account!🧐</h1>
            <p class="mb-3 p-5 bg-gray-100 rounded-lg">Due to a lack of storage, we suggest creating an account and logging in to use the service. The free tier has ended due to the high number of file uploads 😫</p>
            <form @submit.prevent="loginHandler">
                <Failed v-if="failed" :text="failed" />
                <Success v-if="success" :text="success" />
                <Processing v-if="processing" :text="processing" />
                <div class="w-full mb-3">
                    <Label for="email" text="Email Address" />
                    <Input id="email" type="text" v-model="email" />
                    <Error v-if="errors.email" :text="errors.email" />
                </div>
                <div class="w-full mb-3">
                    <Label for="password" text="Password" />
                    <Input id="password" type="password" v-model="password" />
                    <Error v-if="errors.password" :text="errors.password" />
                </div>
                <div class="w-full mb-3 ml-2 flex' items-center">
                    <input type="checkbox" id="remember" class="mr-1" />
                    <Label for="remember" text="Remember me" />
                </div>
                <Button text="Login" />
            </form>
        </div>
    </section>
</template>

<script setup>
    const email = ref("");
    const password = ref("");

    const api = useRuntimeConfig().public.api;
    const cookie = useCookie('token');

    const failed = ref(null);
    const success = ref(null);
    const processing = ref(null);

    const errors = ref({
        email: null,
        password: null,
        count: 0
    });

    definePageMeta({
        middleware: 'guest'
    });

    async function loginHandler(){
        failed.value = null;
        success.value = null;
        processing.value = "Logging in...";
        errors.value = {
            email: null,
            password: null,
            count: 0
        };
        if(!email.value || !email.value.includes('@')){
            errors.value.email = "Email format is required!";
            errors.value.count = errors.value.count + 1;
        }
        if(!password.value){
            errors.value.password = "Password is required!";
            errors.value.count = errors.value.count + 1;
        }
        if(errors.value.count == 0){
            try{
                const data = await $fetch(`${api}/api/login`, {
                    method: "POST",
                    body: {
                        email: email.value,
                        password: password.value
                    }
                });
                cookie.value = data;
                processing.value = null;
                return navigateTo('/');
            }catch(e){
                if(e.status == 401){
                    processing.value = null;
                    failed.value = "Invalid Login credientials!";
                }
            }
        }
    }
</script>