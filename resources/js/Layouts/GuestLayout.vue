<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-grayC-500">

        <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-grayC-400 shadow-md overflow-hidden
            sm:rounded-lg">
            <div class="mt-2 flex justify-center">
                <Link href="/">
                <ApplicationLogo class="w-40 h-auto fill-current text-gray-500" />
                </Link>
            </div>
            <slot />
        </div>
    </div>
</template>
