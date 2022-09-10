<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import BreezeButton from "@/Components/Button.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeInputError from "@/Components/InputError.vue";
import BreezeLabel from "@/Components/Label.vue";
import { Inertia } from "@inertiajs/inertia";
import File from "@/Components/File.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faTrashCan } from "@fortawesome/free-regular-svg-icons";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

const { post } = defineProps({ post: Object });

const form = useForm({
    text: post?.text ?? "",
    media: post?.media ?? null,
});

const addPost = () => {
    if (post) {
        Inertia.post(route("posts.update", post.id), {
            _method: "put",
            text: form.text,
            media: form.media,
        });
    } else form.post(route("posts.store"));
};
</script>
<template>
    <Head :title="post ? 'edit' : 'create'" />
    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    posts create
                </h2>
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                    </div>
                    <div class="flex justify-center min-h-screen">
                        <form
                            @submit.prevent="addPost"
                            style="display: flex; flex-direction: column"
                        >
                            <div>
                                <BreezeLabel for="text" value="Text" />
                                <QuillEditor
                                    :toolbar="['bold', 'italic', 'underline']"
                                    theme="snow"
                                    contentType="html"
                                    :content="form.text"
                                    @update:content="form.text = $event"
                                />
                                <BreezeInputError
                                    class="mt-2"
                                    :message="form.errors.email"
                                />
                            </div>
                            <div class="pt-20">
                                <BreezeLabel for="media" value="Media" />
                                <BreezeInput
                                    id="media"
                                    type="file"
                                    class="mt-1 block w-full"
                                    accept=".jpg,.png,.avi,.mp4,.mp3"
                                    @input="form.media = $event.target.files[0]"
                                    v-if="!form.media"
                                />
                                <div v-else>
                                    <File :file="form.media" class="w-40" />
                                    <button
                                        @click="form.media = ''"
                                        type="button"
                                    >
                                        <fontAwesomeIcon :icon="faTrashCan" />
                                    </button>
                                </div>
                                <BreezeInputError
                                    class="mt-2"
                                    :message="form.errors.email"
                                />
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <BreezeButton
                                    type="submit"
                                    class="ml-4"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing"
                                    >{{
                                        post ? "edit" : "create"
                                    }}</BreezeButton
                                >
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
