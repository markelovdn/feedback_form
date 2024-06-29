import { ref } from "vue";
import type { Rule, Errors } from "@/types/validationTypes";

export function useValidation() {
    const errors = ref<Errors>({});

    const validate = (rules: Rule[], value: any, name: string): boolean => {
        errors.value[name] = [];
        rules.forEach((rule) => {
            if (!rule.value(value)) {
                errors.value[name].push(rule.message);
            }
        });
        return errors.value[name].length === 0;
    };

    return { errors, validate };
}
