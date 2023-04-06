<script setup>
import {ref, watch} from "vue";
import InputSearch from "@/Components/InputSearch.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Link, router} from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";
import Paginator from "@/Components/Paginator.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {throttle} from "lodash";

const props = defineProps(['categories', 'links', 'filters']);
const search = ref(props.filters.search);

watch(search, throttle(function (value) {
    router.get(route('categories.index'), { search: value }, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    });
}, 1000));
</script>

<template>
    <Head title="Categories table" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Categories Table</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
            <div class="overflow-hidden shadow-sm rounded-lg px-6 bg-grayC-400">
                <div class="w-100 my-8 flex justify-between">
                    <PrimaryButton :href="route('categories.create')">
                        Create new category
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
                                Description
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <span class="sr-only">Edit</span>
                            </th>
                        </template>

                        <tr class="bg-grayC-400 border-b hover:bg-grayC-300"
                            v-for="category in categories" :key="category.id">
                            <th scope="row" class="px-6 py-4 font-medium text whitespace-nowrap">
                                <Link :href="route('categories.show', { id: category.id })" class="hover:underline">
                                    {{ category.name }}
                                </Link>
                            </th>
                            <td class="px-6 py-4">
                                {{ category.description }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <Link :href="route('categories.edit', { id: category.id })"
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
