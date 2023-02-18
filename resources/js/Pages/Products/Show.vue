<script setup>
import Paginator from "@/Components/Paginator.vue";
import {ref, watch} from "vue";
import {router} from "@inertiajs/vue3";
import {throttle} from "lodash";

const props = defineProps(['products', 'filters']);
const search = ref(props.filters.search);

watch(search, throttle(function (value) {
    console.log(value);
    router.get('/products', { search: value }, {
        preserveState: true,
        preserveScroll: true,
    });
}, 1000));
</script>

<template>
    <div class="w-100 px-10 flex flex-col justify-center">
        <div class="w-100 my-8 flex justify-between">
            <span class="text-5xl font-bold">Products Table</span>

            <form class="flex items-center">
                <label for="simple-search" class="sr-only">Search</label>
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                    </div>
                    <input v-model="search" type="text" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search" required>
                </div>
            </form>
        </div>


        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Type
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Unit Price
                    </th>
                    <!--<th scope="col" class="px-6 py-3">
                        <span class="sr-only">Edit</span>
                    </th>-->
                </tr>
                </thead>
                <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600" v-for="product in products.data" :key="products.id">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ product.name }}
                    </th>
                    <td class="px-6 py-4">
                        {{ product.type }}
                    </td>
                    <td class="px-6 py-4">
                        {{ product.unit_price }}
                    </td>
                    <!--<td class="px-6 py-4 text-right">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    </td>-->
                </tr>
                </tbody>
            </table>
        </div>



        <Paginator :links="products.links"/>
    </div>
</template>
