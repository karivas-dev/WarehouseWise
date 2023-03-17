<script setup>
import Paginator from "@/Components/Paginator.vue";
import { ref, watch } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { throttle } from "lodash";
import Table from "@/Components/Table.vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps(['products', 'warehouse', 'filters', 'links']);
const search = ref(props.filters.search);
const all = ref(props.filters.all === "1");

watch(search, throttle(function (value) {
    router.get('/products', { search: value, all: all.value }, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    });
}, 1000));
</script>

<template>
    <Head title="Products table" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Products Table</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
            <div class="overflow-hidden shadow-sm rounded-lg px-6 bg-grayC-400">
                <div class="w-100 my-8 flex justify-between">
                    <Link :href="route('products.index', { search: search, all: !all })" type="button"
                    class="bg-pinkC-400 border border-whiteC-400 rounded-full
                    font-extrabold text-white font-mukta uppercase tracking-widest hover:bg-pinkC-300
                    focus:ring-2 focus:ring-pinkC-400 transition ease-in-out
                    duration-150 px-5 py-2.5 mr-2 mb-2">
                    {{ all ? "Show local products" : "Show all products" }}
                    </Link>

                    <form class="flex items-center">
                        <label for="simple-search" class="sr-only">Search</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-900" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input v-model="search" type="text" id="simple-search" class="
                                border border-gray-300 text-sm rounded-full focus:ring-pinkC-400
                                focus:border-pinkC-400 block w-full pl-10 p-2.5 text-black" placeholder="Search" required>
                        </div>
                    </form>
                </div>


                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <Table>
                        <template #heading>
                            <th scope="col" class="px-6 py-3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Available quantity
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Unit Price
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">Edit</span>
                            </th>
                        </template>

                        <tr class="bg-grayC-400 border-b hover:bg-grayC-300"
                            v-for="product in products" :key="products.id">
                            <th scope="row" class="px-6 py-4 font-medium text whitespace-nowrap">
                                <Link :href="route('products.show', { id: product.id })" class="hover:underline">{{
                                    product.name }}</Link>
                            </th>
                            <td class="px-6 py-4">
                                {{ product.quantity ?? "Not available at local WW" }}
                            </td>
                            <td class="px-6 py-4">
                                $ {{ product.unit_price }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <Link :href="route('products.edit', { id: product.id })"
                                    class="text-pinkC-100 hover:text-pinkC-400 hover:font-semibold hover:underline">Edit</Link>
                            </td>
                        </tr>
                    </Table>
                </div>

                <Paginator :links="links"/>
        </div>
    </div>

</AuthenticatedLayout></template>
