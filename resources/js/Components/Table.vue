<script setup>

</script>

<template>
    <div class="bg-grayC-400 relative overflow-x-auto shadow-xl sm:rounded-xl shadow-grayC-500">
        <table class="w-full text-left">
            <thead class="text uppercase">
                <tr class="bg-[#383838] border-b">
                    <slot name="heading"/>
                </tr>
            </thead>
            <tbody>
                <slot />
            </tbody>
        </table>
    </div>
</template>
