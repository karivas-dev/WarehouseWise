<script setup>

const props = defineProps(['product', 'available', 'warehouses']);
</script>
<template>
    <h1 class="text-4xl">{{ product.name }}</h1>
    <h1 class="text-xl">{{ product.type }}</h1>
    <h1 class="text-base">{{ product.description }}</h1>
    <h1 class="text-base">{{ available }}</h1>
    <h1 class="text-base">{{ warehouses }}</h1>
</template>
