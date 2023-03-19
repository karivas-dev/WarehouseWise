<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Card from "@/Components/Card.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {router} from "@inertiajs/vue3";

const props = defineProps(['product', 'available', 'warehouse_id']);

const destroy = () => {
    router.delete(route('products.destroy', { id: props.product.id }));
}

const remove = () => {
    router.delete(route('products.remove', { id: props.product.id }), {
        preserveScroll: true,
        onSuccess: () => {
            router.reload({ only: ['product'] });
        }
    });
}
</script>
<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">Product</h2>
        </template>

        <Card>
            <div class="flex justify-between items-center">
                <div class="flex justify-start items-center">
                    <img src="/img/box.webp" class="rounded w-20">
                    <div class="ml-7 max-w-3xl">
                        <span class="inline text-5xl h-fit">{{ product.name }}</span>
                        <div class="mt-2 text-lg">
                            Unit price: {{ product.unit_price }}
                            <br>
                            <span>{{ available ? 'Quantity available: ' + product.quantity : 'Not available'}}</span>
                            <br>
                        </div>
                        <div class="text-zinc-400 text-lg mt-2 max-w-3xl text-justify">
                            Description: {{ product.description }}
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-center items-center">
                    <PrimaryButton :href="route('products.edit', { id: product.id })" class="w-full">
                        Edit product
                    </PrimaryButton>
                    <PrimaryButton type="button" color="red" @click="destroy" class="mt-3 w-full" v-if="$page.props.auth.user.role.type === 'administrator'">
                        Delete product
                    </PrimaryButton>
                    <PrimaryButton type="button" color="red" @click="remove" class="mt-3 w-full" v-if="available && $page.props.auth.user.role.type === 'administrator'" >
                        Disassociate WW
                    </PrimaryButton>
                    <!-- Make a button for adding it to actual order -->
                </div>
            </div>
        </Card>

        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-3xl">Warehouses</h2>
        </div>

        <div class="grid grid-cols-3 mx-auto max-w-7xl">
            <Card v-for="warehouse in product.warehouses" class="text-lg">
                <div class="w-full text-center mb-3">
                    <span class="text-2xl">
                        {{ warehouse.name }}
                    </span>
                    <br>
                    Quantity available: {{ warehouse.pivot.quantity }}
                </div>
                Location: {{ warehouse.location }}
                <br>
                Phone: {{ warehouse.phone }}
                <br>
                Email: {{ warehouse.email }}
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
