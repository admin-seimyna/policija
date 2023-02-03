<template>
    <div class="flex w-72">
        <div v-if="overlay"
             class="bg-white w-full h-full absolute top-0 left-0 flex items-center justify-center flex-wrap z-10"
        >
            <ul v-if="monthMode"
                class="w-full flex justify-center flex-wrap gap-1"
            >
                <li v-for="(month, index) in months"
                    :key="month"
                    class="w-1/3 h-10 flex-center border rounded hover:bg-gray-100 cursor-pointer"
                    @click="selectMonth(index)"
                >
                    <span class="text-xs font-semibold">
                        {{ month }}
                    </span>
                </li>
            </ul>
        </div>
        <div class="flex flex-col">
            <div class="flex-center">
                <span class="cursor-pointer hover:bg-gray-100 p-2"
                      @click="prevMonth"
                >
                    <i class="icon-angle-left" />
                </span>
                <div class="flex-center justify-center w-full">
                    <span>
                        {{ currentDate.format('YYYY') }}
                    </span>
                    <span class="px-1">/</span>
                    <span class="font-semibold"
                          @click="openMonthSelect"
                    >
                        {{ currentDate.format('MMMM') }}
                    </span>
                </div>
                <span class="cursor-pointer hover:bg-gray-100 p-2"
                      @click="nextMonth"
                >
                    <i class="icon-angle-right" />
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
                     class="w-10 h-10 flex-center cursor-pointer text-xs"
                     :class="{
                         'opacity-50': !day.isCurrentMonth,
                         'rounded-l': day.isStartOfRange,
                         'rounded-r': day.isEndOfRange,
                         'rounded': !day.isSelected || (day.isSelected && selected.length === 1),
                         'border border-primary-500 font-semibold': day.isCurrentMonth && day.isCurrentDay,
                         'border border-primary-200 font-semibold': !day.isCurrentMonth && day.isCurrentDay,
                         'hover:bg-gray-100': !day.isSelected,
                         'bg-primary-500 text-white': day.isSelected,
                     }"
                     @click="select(day)"
                >
                    {{ day.output }}
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import {computed, reactive, ref, watch} from 'vue';
import moment from 'moment';

export default {
    name: 'Calendar',
    props: {
        modelValue: [String, Object, Array],
        activeDate: Number,
        rangeSelect: Boolean,
    },
    emits: ['update:modelValue', 'update:activeDate'],
    setup(props, { emit }) {
        let currentDate = ref(moment());
        const activeDateInRange = ref(0);
        const selected = reactive([]);
        const ignoreChanges = ref(false);
        const overlay = ref(false);
        const monthMode = ref(false);

        function setValues(value) {
            if (ignoreChanges.value) {
                ignoreChanges.value = false;
                return;
            }
            selected.splice(0, 2);
            if (!value) {
                return;
            }
            (Array.isArray(value) ? value : [value]).forEach((date) => {
                selected.push(date.startOf('day'));
                currentDate.value = date.clone().startOf('day');
            });
        }

        function update() {
            emit('update:modelValue', props.rangeSelect ? selected : selected[0]);
            if (props.rangeSelect) {
                emit('update:activeDate', activeDateInRange.value);
            }
        }

        watch(
            () => props.modelValue,
            setValues
        );

        setValues(props.modelValue);

        return {
            overlay,
            currentDate,
            selected,
            monthMode,
            weekdays: computed(() => {
                const weekdays = [];
                const date = moment().startOf('week');
                for(let weekday = 0; weekday < 7; weekday++) {
                    weekdays.push(date.clone().add(weekday, 'day').format('dd'));
                }
                return weekdays;
            }),
            months: computed(() => {
                const list = [];
                const date = moment().startOf('year');
                for(let month = 0; month < 12; month++) {
                    list.push(date.format('MMMM'));
                    date.add(1, 'month');
                }
                return list;
            }),
            dates: computed(() => {
                const dates = [];
                const now = moment();
                const today = now.format('YYYY-MM-DD');
                const currentYm = currentDate.value.format('YYYY-MM');
                const start = currentDate.value.clone().startOf('month');
                const startDay = start.day();
                start.subtract(startDay === 0 ? 7 : startDay, 'days');
                const rangeStart = selected[0] || null;
                const rangeEnd = selected[1] || null;

                for(let day = 0; day < 42; day++) {
                    start.add(1, 'day');
                    const date = start.clone();
                    const formatted = date.format('YYYY-MM-DD');
                    let isSelected = false;
                    let isStartOfRange = false;
                    let isEndOfRange = false;

                    if (props.rangeSelect) {
                        if (rangeStart && rangeEnd) {
                            isStartOfRange = date.isSame(rangeStart);
                            isEndOfRange = date.isSame(rangeEnd);
                            isSelected = date.isBetween(rangeStart, rangeEnd, null, '[]');
                        } else {
                            isSelected = date.isSame(rangeStart ? rangeStart : rangeEnd);
                        }
                    } else if (selected.length) {
                        isSelected = selected[0].isSame(date);
                    }
                    dates.push({
                        date: formatted,
                        output: date.format('DD'),
                        isSelected,
                        isInRange: isSelected && !isStartOfRange && !isEndOfRange,
                        isCurrentMonth: currentYm === date.format('YYYY-MM'),
                        isCurrentDay: today === formatted,
                        isEndOfRange,
                        isStartOfRange
                    });
                }

                return dates;
            }),
            prevMonth() {
                currentDate.value = currentDate.value.clone().subtract(1, 'month');
            },
            nextMonth() {
                currentDate.value = currentDate.value.clone().add(1, 'month');
            },
            select(day) {
                ignoreChanges.value = true;
                const date = moment(day.date);

                if (props.rangeSelect) {
                    if (selected.length === 2) {
                        selected.splice(0, 2);
                        activeDateInRange.value = 0;
                    }
                    if (!selected.length) {
                        selected.push(date);
                        activeDateInRange.value = 0;
                    } else {
                        if(date.isSame(selected[0])) {
                            return;
                        }

                        if (date.isBefore(selected[0])) {
                            selected.unshift(date);
                            activeDateInRange.value = 0;
                        } else {
                            selected.push(date);
                            activeDateInRange.value = 1;
                        }
                    }
                } else {
                    selected.splice(0, 1);
                    selected.push(date);
                    activeDateInRange.value = 0;
                }

                if (!currentDate.value.isSame(date, 'month')) {
                    currentDate.value = date.startOf('month');
                }
                update();
            },

            selectMonth(index) {
                const date = selected[activeDateInRange.value].clone().set({ month: index  });
                if (!props.rangeSelect) {
                    currentDate.value = date;
                }
                selected[activeDateInRange.value] = date.clone();
                overlay.value = false;
                monthMode.value = false;
            },

            openMonthSelect() {
                overlay.value = true;
                monthMode.value = true;
            }
        }
    }
}
</script>
