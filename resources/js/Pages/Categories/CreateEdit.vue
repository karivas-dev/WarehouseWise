<script setup>
import InputError from "@/Components/InputError.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Card from "@/Components/Card.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {useForm} from "@inertiajs/vue3";

const props = defineProps(['category']);

const form = useForm({
    name: props.category?.name ?? '',
    description: props.category?.description ?? '',
});

const store = () => {
    form.post(route('categories.store'))
}

const update = ()=> {
    form.patch(route('categories.update', {id: props.category.id}));
};
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl leading-tight">
                {{ category !== undefined ? 'Edit category' : 'Add category' }}
            </h2>
        </template>

        <Card>
            <form @submit.prevent="(category === undefined ? store() : update())">
                <div>
                    <InputLabel for="name" value="Name" />
                    <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus/>
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="my-4">
                    <InputLabel for="description" value="Description" />
                    <textarea id="message" rows="4"
                      class="block w-full border-grayC-500 focus:border-pinkC-400 focus:ring-pinkC-400 rounded-lg shadow-sm bg-grayC-300 p-2.5 text"
                      placeholder="Write the category description here..." v-model="form.description"></textarea>
                    <InputError class="mt-2" :message="form.errors.description" />
                </div>

                <div class="flex justify-end">
                    <PrimaryButton :back="true">
                        Return back
                    </PrimaryButton>

                    <PrimaryButton class="ml-4" :disabled="form.processing">
                        {{ category === undefined ? 'Add' : 'Update' }}
                    </PrimaryButton>
                </div>
            </form>
        </Card>
    </AuthenticatedLayout>
</template>
