<template>
    <div class="flex items-center justify-center h-screen">
        <form
            @submit.prevent="sendForm"
            class="border-8 border-gray-200 rounded-xl w-[500px] -mt-14 p-8 flex flex-col gap-y-3"
        >
            <label
                class="flex flex-col gap-y-0.5 text-gray-500 focus-within:text-brand-2"
            >
                <span class="text-lg">Сервис</span>
                <select
                    v-model="data.serviceId"
                    class="px-3 py-2 border-2 border-gray-300 rounded-md shadow-inner focus-visible:outline-brand-2 outline-2"
                    @change="validateField('serviceId', data.serviceId)"
                    @blur="validateField('serviceId', data.serviceId)"
                >
                    <option
                        v-for="service in services"
                        :key="service.id"
                        :value="service.id"
                    >
                        {{ service.title }}
                    </option>
                </select>
                <span
                    class="text-red-500"
                    v-if="errors.serviceId && errors.serviceId.length > 0"
                >
                    {{ errors.serviceId[0] }}
                </span>
            </label>

            <label
                class="flex flex-col gap-y-0.5 text-gray-500 focus-within:text-brand-2"
            >
                <span class="text-lg">Оценка</span>
                <RatingComponent @updateRating="data.rating = $event" />
                <span
                    class="text-red-500"
                    v-if="errors.rating && errors.rating.length > 0"
                >
                    {{ errors.rating[0] }}
                </span>
            </label>

            <label
                class="flex flex-col gap-y-0.5 text-gray-500 focus-within:text-brand-2"
            >
                <span class="text-lg">Заголовок</span>
                <input
                    class="px-3 py-2 border-2 border-gray-300 rounded-md shadow-inner focus-visible:outline-brand-2 outline-2"
                    type="text"
                    v-model="data.title"
                    @focusout="validateField('title', data.title)"
                    @focusin="validateField('rating', data.rating)"
                />
                <span
                    class="text-red-500"
                    v-if="errors.title && errors.title.length > 0"
                >
                    {{ errors.title[0] }}
                </span>
            </label>
            <label
                class="flex flex-col gap-y-0.5 text-gray-500 focus-within:text-brand-2"
            >
                <span class="text-lg">Дата и время</span>
                <input
                    v-model="data.datetime"
                    class="px-3 py-2 border-2 border-gray-300 rounded-md shadow-inner focus-visible:outline-brand-2 outline-2"
                    type="datetime-local"
                    @keydown.prevent
                    @focusout="validateField('datetime', data.datetime)"
                />
                <span
                    class="text-red-500"
                    v-if="errors.datetime && errors.datetime.length > 0"
                >
                    {{ errors.datetime[0] }}
                </span>
            </label>

            <label
                class="flex flex-col gap-y-0.5 text-gray-500 focus-within:text-brand-2"
            >
                <span class="text-lg">Описание</span>
                <textarea
                    class="px-3 py-2 border-2 border-gray-300 rounded-md shadow-inner focus-visible:outline-brand-2 outline-2"
                    rows="6"
                    v-model="data.description"
                    @focusout="validateField('description', data.description)"
                />
                <span
                    class="text-red-500"
                    v-if="errors.description && errors.description.length > 0"
                >
                    {{ errors.description[0] }}
                </span>
            </label>
            <button
                class="grid place-content-center w-full p-2 mt-1 border-2 border-gray-300 rounded-md shadow-sm outline-none bg-white text-lg font-semibold tracking-wide text-gray-400 transition-all focus-visible:bg-brand-2 focus-visible:border-green-700 focus-visible:text-green-800 focus-visible:shadow-xl hover:bg-brand-2 hover:border-green-700 hover:text-green-800 hover:shadow-xl"
                type="submit"
                @click="validateForm"
            >
                Отправить
            </button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { onMounted, reactive, ref } from "vue";
import axios from "axios";
import env from "@/env.json";
import router from "@/router";
import type {
    StoreFeedbackResponse,
    TFeedbackPayload,
} from "@/types/feedbackTypes";
import type { TService } from "@/types/serviceTypes";
import RatingComponent from "@/components/RatingComponent.vue";
import { useValidation } from "@/composables/useValidation";
import type { Rule } from "@/types/validationTypes";

const data = reactive(<TFeedbackPayload>{
    description: "",
    title: "",
    datetime: "",
    serviceId: 0,
    rating: 0,
});

const services = ref(<TService[]>[]);
const { errors, validate } = useValidation();
const isFormValid = ref(false);

const titleRules: Rule[] = [
    { value: (v) => v.length > 0, message: "Заголовок не может быть пустым" },
];

const descriptionRules: Rule[] = [
    { value: (v) => v.length > 0, message: "Описание не может быть пустым" },
];

const serviceRules: Rule[] = [
    {
        value: () => data.serviceId > 0,
        message: "Необходимо выбрать сервис для отзыва",
    },
];

const ratingRules: Rule[] = [
    {
        value: () => data.rating > 0,
        message: "Необходимо поставить оценку",
    },
];

const datetimeRules: Rule[] = [
    {
        value: (v) => data.datetime.length > 0,
        message: "Необходимо выбрать дату и время",
    },
];

const validateField = (name: string, value: any) => {
    let rules: Rule[] = [];

    switch (name) {
        case "title":
            rules = titleRules;
            break;
        case "description":
            rules = descriptionRules;
            break;
        case "serviceId":
            rules = serviceRules;
            break;
        case "datetime":
            rules = datetimeRules;
            break;
        case "rating":
            rules = ratingRules;
            break;
        default:
            rules = [];
    }

    validate(rules, value, name);
};

const validateForm = () => {
    validateField("serviceId", data.serviceId);
    validateField("rating", data.rating);
    validateField("title", data.title);
    validateField("datetime", data.datetime);
    validateField("description", data.description);

    const allValid = Object.keys(errors.value).every(
        (key) => errors.value[key].length === 0
    );

    if (allValid) {
        isFormValid.value = true;
    }
};

const getServices = async () => {
    try {
        const response = await axios.get<TService[]>(
            env.backend_url + "/services"
        );
        services.value = response.data;
        return response.data;
    } catch (error) {
        alert(error);
    }
};

const sendForm = async () => {
    if (!isFormValid.value) return;
    try {
        const response = await sendFormImpl();
        if (!response.data.id) throw new Error("Ошибка сервера");
        await router.push({
            name: "feedback-show",
            params: { id: response.data.id },
        });
    } catch (error) {
        alert(error);
    }
};

const sendFormImpl = async () => {
    return await axios.post<StoreFeedbackResponse>(
        env.backend_url + "/feedbacks",
        {
            description: data.description,
            title: data.title,
            datetime: new Date(data.datetime).getTime(),
            serviceId: data.serviceId,
            rating: data.rating,
        }
    );
};

onMounted(async () => {
    await getServices();
});
</script>
