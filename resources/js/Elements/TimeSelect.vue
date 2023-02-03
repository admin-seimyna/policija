<template>
    <div class="timepicker w-72 justify-center">
        <div v-if="overlay"
             class="bg-white flex items-center justify-center flex-wrap z-10"
        >
            <ul class="w-full flex justify-center flex-wrap gap-1">
                <li v-for="item in list"
                    :key="item"
                    class="w-10 h-10 flex-center border rounded hover:bg-gray-100 cursor-pointer"
                    @click="setValue(item)"
                >
                    <span class="text-xs font-semibold">
                        {{ item }}
                    </span>
                </li>
            </ul>
        </div>
        <div v-else
             class="flex flex-col items-center">
            <div v-if="date"
                 class="flex items-center mb-3"
            >
                <span class="font-semibold">
                    {{ formattedDate }}
                </span>
            </div>
            <div class="flex items-center">
                <div class="flex flex-col items-center">
                    <div class="timepicker__arrow"
                         @click="increaseHour"
                    >
                        <i class="icon-angle-up text-xl" />
                    </div>
                    <div class="timepicker__input"
                         @click="turnOnHourMode"
                    >
                        {{ hour.format('HH') }}
                    </div>
                    <div class="timepicker__arrow"
                         @click="decreaseHour"
                    >
                        <i class="icon-angle-down text-xl" />
                    </div>
                </div>
                <span class="mx-2 font-bold">:</span>
                <div class="flex flex-col items-center">
                    <div class="timepicker__arrow"
                         @click="increaseMinutes"
                    >
                        <i class="icon-angle-up text-xl" />
                    </div>
                    <div class="timepicker__input"
                         @click="turnOnMinutesMode"
                    >
                        {{ minutes.format('mm') }}
                    </div>
                    <div class="timepicker__arrow"
                         @click="decreaseMinutes"
                    >
                        <i class="icon-angle-down text-xl" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import {computed, ref, watch} from 'vue';
import moment from 'moment';

export default {
    name: 'TimeSelect',
    props: {
        modelValue: [String, Object],
        date: [String, Object],
        minutesIncrement: {
            type: [Number,String],
            default: 5,
        },
        hourIncrement: {
            type: [Number,String],
            default: 1,
        },
    },
    setup(props, { emit }) {
        let date = moment();
        const hour = ref(null);
        const minutes = ref(null);
        const overlay = ref(false);
        const hoursMode = ref(false);


        function setValues(date) {
            if (!date) return;
            hour.value = date.clone();
            minutes.value = date.clone();
        }

        function update() {
            emit('update:modelValue', hour.value.clone().set({
                minute: minutes.value.get('minutes')
            }));
        }

        if (props.modelValue) {
            date = moment(Array.isArray(props.modelValue) ? props.modelValue[0] : props.modelValue);
        }

        setValues(date);

        watch(
            () => props.modelValue,
            setValues
        );

        return {
            hour,
            minutes,
            overlay,
            formattedDate: computed(() => {
                return moment(props.date).set({
                    hour: hour.value.get('hour'),
                    minute: minutes.value.get('minutes')
                }).format('YYYY-MM-DD');
            }),
            list: computed(() => {
                const list = [];
                let add = parseInt(props.hourIncrement);
                let min = 0;
                let max = 24;

                if (!hoursMode.value) {
                    min = 0;
                    max = 60;
                    add = parseInt(props.minutesIncrement);
                }

                for(let value = min; value < max; value+= add) {
                    list.push(value.toString().padStart(2, '0'));
                }
                return list;
            }),
            increaseHour() {
                hour.value = hour.value.clone().add(1, 'hour');
                update();
            },
            decreaseHour() {
                hour.value = hour.value.clone().subtract(1, 'hour');
                update();
            },
            increaseMinutes() {
                minutes.value = minutes.value.clone().add(1, 'minute');
                update();
            },
            decreaseMinutes() {
                minutes.value = minutes.value.clone().subtract(1, 'minute');
                update();
            },
            turnOnHourMode() {
                hoursMode.value = true;
                overlay.value = true;
                update();
            },
            turnOnMinutesMode() {
                hoursMode.value = false;
                overlay.value = true;
                update();
            },
            setValue(value) {
                if (hoursMode.value) {
                    hour.value = hour.value.clone().set('hour', value);
                } else {
                    minutes.value = minutes.value.clone().set('minutes', value);
                }
                hoursMode.value = false;
                overlay.value = false
                update();
            }
        }
    }
}
</script>
