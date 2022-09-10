<script setup>
import { computed, onUnmounted } from "vue";

const { file } = defineProps({ file: Object, class: String });
const media = computed(() => {
    if (file instanceof File) {
        return {
            path: URL.createObjectURL(file),
            type: file.type,
        };
    } else {
        return file;
    }
});

onUnmounted(() => {
    URL.revokeObjectURL(media.path);
});
</script>
<template>
    <div :class="class">
        <img
            :src="media.path"
            alt="media"
            v-if="['image/jpeg', 'image/jpg', 'image/png'].includes(media.type)"
        />
        <video
            :src="media.path"
            controls
            v-else-if="['video/mp4'].includes(media.type)"
        ></video>
        <audio
            class="w-40 h-40"
            controls
            v-else-if="['audio/mpeg'].includes(media.type)"
            :src="media.path"
        ></audio>
        <div v-else>format not supported</div>
    </div>
</template>
