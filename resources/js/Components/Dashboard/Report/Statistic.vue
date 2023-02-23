<template>
    <ul class="flex items-center gap-x-2 mb-5">
       <li v-for="card in cards"
           class="flex flex-col w-full rounded-md hover:shadow-xl shadow h-32 p-5"
           :class="[card.class,{
                'bg-white': !card.class
           }]"
       >
           <div class="flex h-10">
               <span class="font-semibold ellipsis-2">
                   {{ $t(`field.title.${card.name}`) }}
               </span>
           </div>

           <div class="flex mt-3">
               <span v-if="!loading" class="font-bold text-2xl">
                   {{ card.count }}
               </span>
               <VSpinner v-else class="w-10 h-10" />
           </div>
       </li>
    </ul>
</template>
<script>
import {computed, reactive, ref} from 'vue';
import {useStore} from 'vuex';
import axios from 'axios';
import VSpinner from '@/Elements/Spinner';

export default {
    name: 'ReportStatistic',
    components: {VSpinner},
    setup(props) {
        const loading = ref(false);
        const currentFilters = reactive({ values: {} });
        const store = useStore();

        function load(filters) {
            if (filters) {
                currentFilters.values = filters;
            }

            loading.value = true;
            axios.get(`/report/statistic?filters=${JSON.stringify(currentFilters.values)}`).then(() => {
                loading.value = false;
            });
        }

        return {
            load,
            loading,
            cards: computed(() => store.getters['report/statistic']),
        }
    }
}
</script>
