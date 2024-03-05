<template>
    <button type="submit" class="w-full text-center py-3 px-4 inline-block bg-black text-white7 text-white font-semibold rounded-md">
        {{ text }}
    </button>
</template>

<script setup>
    defineProps(['text'])
</script>