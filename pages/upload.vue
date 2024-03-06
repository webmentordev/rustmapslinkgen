<template>
    <section class="h-[70vh] flex items-center">
        <div class="max-w-2xl w-full m-auto py-12">
            <h1 class="text-4xl mb-2">Upload your naked's world😸</h1>
            <p class="mb-3 p-5 bg-gray-100 rounded-lg">The file must be less than <strong class="p-1 rounded-md bg-gray-200">60MB</strong> and must end with <strong class="p-1 rounded-md bg-gray-200">.map</strong>. Only <strong class="p-1 rounded-md bg-gray-200">10Maps</strong> are allowed per user!</p>
            <form @submit.prevent="uploadMapHandler" class="mb-3">
                <Failed v-if="failed" :text="failed" />
                <Success v-if="success" :text="success" />
                <Processing v-if="processing" :text="processing" />
                <div class="w-full mb-3">
                    <Label for="name" text="Map Name" />
                    <Input id="name" type="text" v-model="name" />
                    <Error v-if="errors.name" :text="errors.name" />
                </div>
                <div class="w-full mb-3">
                    <Label for="map" text="Map File" />
                    <Input id="map" type="file" @change="uploadFile" required />
                </div>
                <Button text="Upload 🚀" />
            </form>

            <div class="flex flex-col" v-if="maps.length">
                <p class="border border-yellow-500 border-l-4 p-6 bg-yellow-500/10 rounded-lg mb-3">Simply right click Download and Copy link address and use it 👍</p>
                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-100 mb-1" v-for="map in maps" :key="map.id">
                    <h3>{{ map.name }}</h3>
                    <div class="flex">
                        <NuxtLink :to="map.file" class="py-2 px-3 bg-blue-600 text-white text-sm rounded-md">Download</NuxtLink>
                        <button @click="deleteMapHandler(map.id)" class="py-2 px-3 bg-red-600 text-white text-sm rounded-md ml-3">Delete</button>
                    </div>
                </div>
            </div>
            <p v-else>Loading maps...</p>
        </div>
    </section>
</template>

<script setup lang="js">

    const file = ref(null);
    const maps = ref([]);
    const name = ref("");

    const api = useRuntimeConfig().public.api;
    const cookie = useCookie('token');

    const failed = ref("");
    const success = ref("");
    const processing = ref("");
    
    definePageMeta({
        middleware: 'auth'
    });

    const errors = ref({
        name: null,
        file: null,
        count: 0
    });

    const data = await $fetch(`${api}/api/maps`, {
        method: "GET",
        headers: {
            'Authorization': 'Bearer '+cookie.value.token
        }
    });
    if(data){
        maps.value = data;
    }

    function uploadFile(event){
        file.value = event.target.files[0];
    }

    async function uploadMapHandler(){
        failed.value = null;
        success.value = null;
        processing.value = "Uploading...";
        const formData = new FormData();

        if(!name.value){
            errors.value.name = "Map name is required!";
            errors.value.count = errors.value.count + 1;
        }
        
        if(errors.value.count == 0){
            formData.append('map_file', file.value);
            formData.append('name', name.value);
            try{
                const data = await $fetch(`${api}/api/upload/map`, {
                    method: "POST",
                    headers: {
                        'Authorization': 'Bearer '+cookie.value.token
                    },
                    body: formData
                });
                success.value = "Map has been uploaded";
                processing.value = null;
                maps.value.unshift(data.data);
            }catch(e){
                processing.value = null;
                failed.value = "Something is wrong";
            }
        }
    }

    async function deleteMapHandler(id){
        processing.value = "Deleting the map...";
        try{
            await $fetch(`${api}/api/upload/map/delete/${id}`, {
                method: "DELETE",
                headers: {
                    'Authorization': 'Bearer '+cookie.value.token
                }
            });
            success.value = "Map has been deleted";
            processing.value = null;
        }catch(e){
            processing.value = null;
            failed.value = "Unauthorized to delete this map!";
        }
    }
</script>