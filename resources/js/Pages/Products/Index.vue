<script setup>
import Paginator from "@/Components/Paginator.vue";
import { ref, watch } from "vue";
import { Link, router } from "@inertiajs/vue3";
import { throttle } from "lodash";
import Table from "@/Components/Table.vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import InputSearch from "@/Components/InputSearch.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";


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

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
            <div class="overflow-hidden shadow-sm rounded-lg px-6 bg-grayC-400">
                <div class="w-100 my-8 flex justify-between">
                    <PrimaryButton :href="route('products.index', { search: search, all: !all })">
                        {{ all ? "Show local products" : "Show all products" }}
                    </PrimaryButton>

                    <PrimaryButton :href="route('products.create')" v-if="$page.props.auth.user.role.type === 'administrator'">
                        Create new product
                    </PrimaryButton>

                    <form class="flex items-center">
                        <InputSearch v-model="search"/>
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
                            <th scope="col" class="px-6 py-3" v-if="$page.props.auth.user.role.type === 'administrator'">
                                <span class="sr-only">Edit</span>
                            </th>
                        </template>

                        <tr class="bg-grayC-400 border-b hover:bg-grayC-300"
                            v-for="product in products" :key="products.id">
                            <th scope="row" class="px-6 py-4 font-medium text whitespace-nowrap">
                                <Link :href="route('products.show', { id: product.id })" class="hover:underline">
                                    {{ product.name }}
                                </Link>
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

</AuthenticatedLayout>
</template>
