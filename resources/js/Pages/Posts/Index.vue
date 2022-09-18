<script setup>
import BreezeAuthenticatedLayout from "@/Layouts/Authenticated.vue";
import { Inertia } from "@inertiajs/inertia";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faEdit, faTrashCan } from "@fortawesome/free-regular-svg-icons";
import File from "@/Components/File.vue";

defineProps({ auth: Object, posts: Object });
const onEdit = (id) => {
    Inertia.get(route("posts.edit", id));
};
</script>
<template>
    <Head title="posts" />
    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    posts
                </h2>
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <Link
                            :href="route('posts.create')"
                            v-if="auth.user && route().current('posts.index')"
                            >New</Link
                        >
                    </div>
                    <div
                        v-for="({ text, media, id, user }, index) of posts"
                        :key="index"
                    >
                        <div class="flex flex-col border m-10">
                            <div class="flex flex-row-reverse justify-between">
                                <p class="text-end">
                                    {{
                                        user.id === auth.user?.id && !auth.user.isAdmin
                                            ? "you"
                                            : user?.name
                                    }}
                                </p>
                                <span
                                    v-if="
                                        user.id === auth.user?.id ||
                                        auth.user?.isAdmin
                                    "
                                    class="flex gap-4"
                                >
                                    <Link
                                        :href="route('posts.destroy', id)"
                                        method="delete"
                                    >
                                        <fontAwesomeIcon :icon="faTrashCan" />
                                    </Link>
                                    <button @click="onEdit(id)">
                                        <fontAwesomeIcon :icon="faEdit" />
                                    </button>
                                </span>
                            </div>
                            <div class="text-center" v-html="text"></div>

                            <div class="w-40 place-self-center">
                                <File :file="media" v-if="media" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
