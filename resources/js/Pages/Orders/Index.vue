<script setup>
import {Head, Link, router} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SingleOrder from "@/Pages/Orders/SingleOrder.vue";
import Card from "@/Components/Card.vue";
import Table from "@/Components/Table.vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";
import Paginator from "@/Components/Paginator.vue";
import InputSearch from "@/Components/InputSearch.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {ref, watch} from "vue";
import {throttle} from "lodash";

const props = defineProps(['currentOrder', 'orders', 'links', 'filters']);
const search = ref(props.filters.search ?? "");

watch(search, throttle(function () {
    router.get(route('orders.index'), { search: search.value }, {
        replace: true,
        preserveState: true,
        preserveScroll: true,
    });
}, 1000));
</script>

<template>
    <Head title="Orders" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Orders</h2>
        </template>

        <SingleOrder :order="currentOrder" :current="true" v-if="currentOrder != null && currentOrder.item_count > 0"/>

        <Card>
            <div class="w-100 mb-8 flex justify-between content-center">
                <span class="text-4xl font-bold">
                    Past Orders
                </span>

                <form class="flex items-center">
                    <InputSearch v-model="search" type="number"/>
                </form>
            </div>

            <div class="mt-5 relative overflow-x-auto shadow-md sm:rounded-lg">
                <Table>
                    <template #heading>
                        <th scope="col" class="px-6 py-3">
                            #
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Items quantity
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Sub-Total
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Shipping
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Taxes
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Total
                        </th>
                    </template>

                    <tr class="bg-grayC-400 border-b hover:bg-grayC-300"
                        v-for="order in orders" :key="order.id">
                        <th scope="row" class="px-6 py-4 font-medium text whitespace-nowrap">
                            <Link :href="route('orders.show', { id: order.id })" class="hover:underline">
                                Order #{{ order.id }}
                            </Link>
                        </th>
                        <td class="px-6 py-4">
                            {{ order.item_count }}
                        </td>
                        <td class="px-6 py-4">
                            $ {{ order.sub_total }}
                        </td>
                        <td class="px-6 py-4">
                            $ {{ order.shipping_cost }}
                        </td>
                        <td class="px-6 py-4">
                            $ {{ order.taxes }}
                        </td>
                        <td class="px-6 py-4">
                            $ {{ order.total }}
                        </td>
                    </tr>
                </Table>
            </div>
            <Paginator :links="links"/>
        </Card>
    </AuthenticatedLayout>
</template>
