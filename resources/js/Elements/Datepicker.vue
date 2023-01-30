<template>
    <div class="flex">
        <div class="flex flex-col">
            <div class="flex-center">
                <span class="cursor-pointer hover:bg-gray-100 p-2"
                      @click="prevMonth"
                >
                    Prev
                </span>
                <div class="flex-center justify-center w-full">
                    <span>
                        {{ currentDate.format('YYYY') }}
                    </span>
                    <span class="px-1">/</span>
                    <span class="font-semibold">
                        {{ currentDate.format('MMMM') }}
                    </span>
                </div>
                <span class="cursor-pointer hover:bg-gray-100 p-2"
                      @click="nextMonth"
                >
                    Next
                </span>
            </div>
            <div class="flex">
                <div v-for="weekday in weekdays"
                     :key="weekday"
                    class="w-10 h-10 flex-center"
                >
                    <span class="text-xs font-semibold">
                        {{ weekday }}
                    </span>
                </div>
            </div>
            <div class="flex flex-wrap w-70">
                <div v-for="(day, index) in dates"
                     :key="index"
                     class="w-10 h-10 flex-center cursor-pointer rounded text-xs"
                     :class="{
                         'opacity-50': !day.isCurrentMonth,
                         'border border-primary-500 font-semibold': day.isCurrentMonth && day.isCurrentDay,
                         'border border-primary-200 font-semibold': !day.isCurrentMonth && day.isCurrentDay,
                         'hover:bg-gray-100': !day.isSelected,
                         'bg-primary-500 text-white': day.isSelected,
                     }"
                     @click="select(day)"
                >
                    {{ day.date.format('DD')}}
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import {computed, reactive, ref} from 'vue';
import moment from 'moment';

export default {
    name: 'Datepicker',
    props: {
        multiple: Boolean,
        rangeSelect: {
            type: Boolean,
            default: true
        },
    },
    setup(props) {
        let currentDate = ref(moment());
        const selected = reactive([]);

        return {
            currentDate,
            weekdays: computed(() => {
                const weekdays = [];
                const date = moment().startOf('week');
                for(let weekday = 0; weekday < 7; weekday++) {
                    weekdays.push(date.add(weekday, 'day').format('dd'));
                }
                return weekdays;
            }),
            dates: computed(() => {
                const dates = [];
                const now = moment();
                const today = now.format('YYYY-MM-DD');
                const currentYm = currentDate.value.format('YYYY-MM');
                const start = currentDate.value.clone().startOf('month');
                const startDay = start.day();
                start.subtract(startDay === 0 ? 6 : (startDay - 1), 'days');
                const rangeStart = selected[0] || null;
                const rangeEnd = selected[1] || null;

                for(let day = 0; day < 42; day++) {
                    const date = start.clone();
                    const formatted = date.format('YYYY-MM-DD');
                    let isSelected = false;
                    if (props.rangeSelect) {
                        isSelected = date.isBetween(rangeStart, rangeEnd, '[]');
                    } else {
                        isSelected = selected.findIndex(selectedDate => selectedDate.date === date) !== -1;
                    }
                    dates.push({
                        isCurrentMonth: currentYm === date.format('YYYY-MM'),
                        isCurrentDay: today === formatted,
                        isSelected,
                        date
                    });
                    start.add(1, 'day');
                }

                return dates;
            }),
            prevMonth() {
                currentDate.value = currentDate.value.clone().subtract(1, 'month');
            },
            nextMonth() {
                currentDate.value = currentDate.value.clone().add(1, 'month');
            },
            select(date) {
                if (props.rangeSelect) {
                    if (selected.length < 3) {
                        selected.push(date.date);
                    } else {
                        selected.splice(0, 2);
                    }
                } else {
                    if (!props.multiple) {
                        selected.splice(0,1);
                    }
                    selected.push(date.date);
                }
            }
        }
    }
}
</script>
