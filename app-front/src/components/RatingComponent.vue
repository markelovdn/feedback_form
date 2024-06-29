<template>
    <div class="flex space-x-1">
        <span
            v-for="star in 5"
            :key="star"
            class="star"
            :class="{
                'text-green-500':
                    (initialRating ? star <= initialRating : star <= rating) ||
                    star <= hoverRating,
                'text-gray-400':
                    star > (initialRating ? initialRating : rating) &&
                    star > hoverRating,
            }"
            :style="{ cursor: initialRating ? 'default' : 'pointer' }"
            @click="initialRating ? null : setRating(star)"
            @mouseover="initialRating ? null : (hoverRating = star)"
            @mouseleave="initialRating ? null : (hoverRating = 0)"
        >
            &#9733;
        </span>
    </div>
</template>

<script setup lang="ts">
import { ref, defineProps, defineEmits } from "vue";

const props = defineProps({
    initialRating: {
        type: Number,
        default: 0,
    },
});

const rating = ref(0);
const hoverRating = ref(0);
const emit = defineEmits(["updateRating"]);

const setRating = (newRating: number) => {
    if (!props.initialRating) {
        rating.value = newRating;
        emit("updateRating", newRating);
    }
};
</script>

<style scoped>
.star {
    cursor: pointer;
    transition: color 0.2s;
    font-size: 30px;
}
.star:hover {
    color: #6aaca1;
}
</style>
