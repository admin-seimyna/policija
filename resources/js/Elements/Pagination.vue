<template>
    <ul class="flex items-center">
        <li class="pagination__page"
            :class="{
                'pagination__page--disabled': isFirstPage
            }"
            @click="first"
        >
            <i class="icon-step-backward mt-1" />
        </li>
        <li class="pagination__page"
            :class="{
                'pagination__page--disabled': isFirstPage
            }"
            @click="prev"
        >
            <i class="icon-angle-left text-xl" />
        </li>
        <li v-for="item in range"
            :key="item"
            class="pagination__page"
            :class="{
                'pagination__page--current': item === modelValue,
            }"
            @click="change(item)"
        >
            {{ item }}
        </li>
        <li class="pagination__page"
            :class="{
                'pagination__page--disabled': isLastPage
            }"
            @click="next"
        >
            <i class="icon-angle-right text-xl" />
        </li>
        <li class="pagination__page"
            :class="{
                'pagination__page--disabled': isLastPage
            }"
            @click="last"
        >
            <i class="icon-step-forward mt-1" />
        </li>
    </ul>
</template>
<script>
import {computed, reactive, watch} from 'vue';

export default {
    name: 'Pagination',
    props: {
        modelValue: [String, Number],
        total: [String, Number],
        pages: {
            type: [String, Number],
            default: 5,
        }
    },
    emits: ['update:modelValue', 'change'],
    setup(props, { emit }) {
        if (props.pages % 2 === 0) {
            console.error('[pagination] property "pages" should be not even number');
        }
        const range = reactive([]);
        const total = computed(() => parseInt(props.total));
        const pagesToShow = parseInt(props.pages) - 1;

        function generateRage() {
            if (range.length) {
                const index = range.indexOf(props.modelValue);
                if (index > 0 && index < range.length - 1) {
                    return;
                }
                range.length = 0;
            }

            let ratio = Math.floor(pagesToShow / 2);
            let min = props.modelValue - ratio;
            let max = props.modelValue + ratio;

            if (min < 1) {
                min = 1;
                max = min + pagesToShow;
                max = max >= total ? total.value : max;
            }

            if (max > total.value) {
                max = total.value;
                min = total.value - pagesToShow;
                min = min < 1 ? 1 : min;
            }

            for(let x = min; x <= max; x++) {
                range.push(x);
            }
        }

        function onChange(page) {
            emit('update:modelValue', page);
            emit('change', page);
        }

        watch(() => props.modelValue, generateRage);
        watch(() => props.total, generateRage);

        generateRage();

        return {
            range,
            isFirstPage: computed(() => {
                return props.modelValue === 1;
            }),
            isLastPage: computed(() => {
                return props.modelValue === total.value;
            }),
            change(page) {
                if (page === props.modelValue) return;
                emit('update:modelValue', page);
                onChange(page);
            },
            prev() {
                if (props.modelValue === 1) return;
                onChange(props.modelValue - 1);
            },
            next() {
                if (props.modelValue === total) return;
                onChange(props.modelValue + 1);
            },
            first() {
                onChange(1);
            },
            last() {
                onChange(total.value);
            }
        }
    }
}
</script>
