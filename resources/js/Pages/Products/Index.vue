<script setup>
import Paginator from "@/Components/Paginator.vue";
import { ref, watch } from "vue";
import {Link, router, usePage} from "@inertiajs/vue3";
import { throttle } from "lodash";
import Table from "@/Components/Table.vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import InputSearch from "@/Components/InputSearch.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps(['products', 'warehouse', 'categories', 'filters', 'links']);
const search = ref(props.filters.search ?? "");
const all = ref(props.filters.all === "1");
const category = ref(props.filters.category ?? 'all');
const deleted = ref(props.filters.deleted === "1");

const user = ref(usePage().props.auth.user);

const addingToOrder = ref(false);
const modalProduct = ref(null);
const modalMaxQuantity = ref(1);
const quantity = ref(1);
const showModal = (productName, maxQuantity) => {
    modalProduct.value = productName;
    modalMaxQuantity.value = maxQuantity;
    quantity.value = 1;
    addingToOrder.value = true;
}

const add = () => {
    router.post(route('products.order.add', { product: modalProduct.value.id}), {
        quantity: quantity.value,
    });
    addingToOrder.value = false;
}

watch([search, category], throttle(function () {
    router.get('/products', { search: search.value, all: all.value, category: category.value, deleted: deleted.value }, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    });
}, 1000));
</script>

<template>
    <Head title="Products" />

    <AuthenticatedLayout>
        <template #header>
            <div class="w-100 flex justify-between">
                <h2 class="font-semibold text-xl leading-tight">Products Table</h2>
                <PrimaryButton :href="route('products.index', { search: search, all: !deleted, category: category, deleted: !deleted })" color="neutral"
                               v-if="user.role.type === 'director' || user.role.type === 'administrator'">
                    {{ !deleted ? 'Show disabled products' : 'Show normal products' }}
                </PrimaryButton>
            </div>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
            <div class="overflow-hidden shadow-sm rounded-lg px-6 bg-grayC-400">
                <div class="w-100 my-8 flex justify-between">
                    <PrimaryButton :href="route('products.index', { search: search, all: !all, category: category, deleted: deleted })" v-if="!deleted && user.role.type !== 'director'">
                        {{ all ? "Show local products" : "Show all products" }}
                    </PrimaryButton>

                    <PrimaryButton :href="route('products.create')" v-if="user.role.type === 'director' || user.role.type === 'administrator'">
                        Create new product
                    </PrimaryButton>

                    <select class="select w-60" v-model="category">
                        <option value="all">
                            All categories
                        </option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">
                            {{ category.name }}
                        </option>
                    </select>

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
                            <th scope="col" class="px-6 py-3" v-if="user.role.type !== 'director'">
                                Available quantity
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Unit Price
                            </th>
                            <th scope="col" class="px-6 py-3" v-if="user.role.type === 'employee' || (user.role.type !== 'administrator' && deleted)">
                                <span class="sr-only">Action</span>
                            </th>
                        </template>

                        <tr class="bg-grayC-400 border-b hover:bg-grayC-300"
                            v-for="product in products" :key="products.id">
                            <th scope="row" class="px-6 py-4 font-medium text whitespace-nowrap">
                                <Link :href="route('products.show', { id: product.id })" class="hover:underline" v-if="product.deleted_at === null">
                                    {{ product.name }}
                                </Link>
                                <span v-if="product.deleted_at !== null">
                                    {{ product.name }}
                                </span>
                            </th>
                            <td class="px-6 py-4" v-if="user.role.type !== 'director'">
                                {{ product.quantity ?? "Not available at local WW" }}
                            </td>
                            <td class="px-6 py-4">
                                $ {{ product.unit_price }}
                            </td>
                            <td class="px-6 py-4 text-right" v-if="user.role.type !== 'administrator' && user.role.type !== 'director'">
                                <span @click="showModal(product, product.quantity)" v-if="product.quantity != null"
                                      class="text-pinkC-100 hover:text-pinkC-400 hover:font-semibold hover:underline">
                                    {{ 'Add to order' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right" v-if="user.role.type === 'director' && product.deleted_at !== null">
                                <Link :href="route('products.restore', { id: product.id })"
                                      class="text-pinkC-100 hover:text-pinkC-400 hover:font-semibold hover:underline"
                                      :method="'put'" as="button">
                                    {{ 'Restore' }}
                                </Link>
                            </td>
                        </tr>
                    </Table>
                </div>

                <Modal :show="addingToOrder" @close="addingToOrder = false" @trueResponse="add">
                    <span class="text-2xl text-white">
                        {{ modalProduct?.name }}
                    </span>
                    <br>
                    How much do you want to add to the order?
                    <br>
                    <TextInput type="number" v-model="quantity" min="1" :max="modalMaxQuantity" class="mt-5 mb-2"/>
                </Modal>
                <Paginator :links="links"/>
        </div>
    </div>

</AuthenticatedLayout>
</template>
