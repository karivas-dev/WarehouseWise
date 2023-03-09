<script setup>
import { useForm, usePage } from '@inertiajs/vue3';


const props = defineProps(['categories', 'product', 'selected_categories']);

const form = useForm({
    name:'',
    description:'',
    unit_price:0.00,
    categories:[],
    quantity:1,
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

if (props.product != null)
{
    form.name = props.product.name;
    form.description = props.product.description;
    form.unit_price = props.product.unit_price;
    form.categories = props.selected_categories;
    form.quantity = props.product.quantity ?? 0;
    console.log(props.selected_categories);
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
</template>
