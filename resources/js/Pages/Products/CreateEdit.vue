<script setup>
import { useForm, usePage } from '@inertiajs/vue3';

const props = defineProps(['categories', 'product', 'selected_categories']);

const form = useForm({
    name: props.product?.name ?? '',
    description: props.product?.description ?? '',
    unit_price: props.product?.unit_price ?? 0.00,
    categories: props.selected_categories ?? [],
    quantity: props.product?.quantity ?? 0,
});

const store = ()=>{
    form.post(route('products.store'), {
        onSuccess:()=>{
            form.reset();
            alert(usePage().props.flash.message);
        }
    });
};

const update = ()=> {
    form.patch(route('products.update', {id:props.product.id}), {
        onSuccess:()=>{
            alert(usePage().props.flash.message);
        }
    });
};

const destroy = ()=> {
    form.delete(route('products.destroy', {id:props.product.id}), {
        onSuccess:()=>{
            alert(usePage().props.flash.message);
        }
    });
}

const remove = ()=> {
    form.delete(route('products.remove', {id:props.product.id}), {
        onSuccess:()=>{
            alert(usePage().props.flash.message);
        }
    });
}

</script>

<template>
    <form @submit.prevent="(product==null ? store() : update())">
        <input v-model="form.name" type="text" maxlength="255">
        <textarea v-model="form.description" maxlength="5000"></textarea>
        <input v-model="form.unit_price" type="number" step="0.01" min="0.01">

        <input v-model="form.quantity" type="number" min="0">
        <select v-model="form.categories" multiple>
            <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
            </option>
        </select>

        <input type="submit">
    </form>
    <button @click="destroy" v-if="product!=null">Global Delete</button>
    <button @click="remove" v-if="product!=null">Delete From This Warehouse</button>
</template>
