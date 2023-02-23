<template>
    <ul class="flex items-center">
        <li v-for="item in mappedPayload"
            class="flex items-center"
            :class="{
                'border-t-1 border-b-1 border-l-1': !item.last,
                'rounded-l': item.first,
                'border rounded-r': item.last,
            }"
        >
            {{ item }}
        </li>
    </ul>
</template>
<script>
import {computed} from 'vue';

export default {
    name: 'ButtonGroup',
    props: {
        payload: Array,
    },
    setup(props, { emit }) {
        return {
            mappedPayload: computed(() => {
                return [...props.payload].map((item, index) => {
                    return Object.assign(item, {
                        first: !index,
                        last: props.payload.length - 1 === index,
                    })
                });
            }),
        }
    }
}
</script>
