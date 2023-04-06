<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Card from "@/Components/Card.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {Head} from '@inertiajs/vue3';
import {router} from "@inertiajs/vue3";
import {Link} from "@inertiajs/vue3";

const props = defineProps(['product', 'available', 'warehouse_id']);

const destroy = () => {
    router.delete(route('products.destroy', {id: props.product.id}));
}

const remove = () => {
    router.delete(route('products.remove', {id: props.product.id}), {
        preserveScroll: true,
        onSuccess: () => {
            router.reload({only: ['product']});
        }
    });
}
</script>
<template>
    <Head title="Product detail"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Product details</h2>
        </template>

        <Card>
            <div class="flex justify-between items-center">
                <div class="flex justify-start items-center">
                    <img src="/img/box1.png" class="rounded w-28">
                    <div class="ml-10 max-w-2xl">
                        <span class="inline text-5xl h-fit">{{ product.name }}</span>
                        <div class="mt-2 text-lg">
                            <span class="font-semibold">Unit price:</span> $ {{ product.unit_price }}
                            <br>
                            <span
                                class="font-semibold">{{
                                    available ? 'Available quantity: ' + product.quantity : 'Not available'
                                }}</span>
                            <br>
                        </div>
                        <div class="text-zinc-400 text-base mt-2 mr-8 max-w-3xl text-justify">
                            <span class="font-semibold">Description:</span> {{ product.description }}
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-center items-center">
                    <PrimaryButton :href="route('products.edit', { id: product.id })" class="w-full">
                        Edit product
                    </PrimaryButton>
                    <PrimaryButton type="button" color="red" @click="destroy" class="mt-3 w-full"
                                   v-if="$page.props.auth.user.role.type === 'administrator'">
                        Delete product
                    </PrimaryButton>
                    <PrimaryButton type="button" color="red" @click="remove" class="mt-3 w-full"
                                   v-if="available && $page.props.auth.user.role.type === 'administrator'">
                        Disassociate WW
                    </PrimaryButton>
                    <!-- Make a button for adding it to actual order -->
                </div>
            </div>
        </Card>

        <div class="max-w-7xl mx-auto text-center my-4">
            <h2 class="text-3xl font-semibold">Warehouses availability</h2>
        </div>

        <div class="grid grid-cols-3 mx-auto max-w-7xl">
            <Card v-for="warehouse in product.warehouses" class="text-base">
                <div class="w-full text-center">
                    <Link as="button" :href="route('warehouses.show', { id: warehouse.id })"
                          class="inline text-4xl hover:underline">
                        {{ warehouse.name }}
                    </Link>
                    <div class="my-1.5">
                        <span class="font-semibold">Quantity available:</span> {{ warehouse.pivot.quantity }}
                    </div>
                </div>
                <div class="mx-auto text-center my-3">Warehouse details</div>
                <span class="font-semibold">Phone:</span> {{ warehouse.phone }}
                <br>
                <span class="font-semibold">Email:</span> {{ warehouse.email }}
                <br>
                <span class="font-semibold">Location:</span> {{ warehouse.location }}
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
