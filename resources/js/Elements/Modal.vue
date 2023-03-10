<template>
    <teleport to="body">
        <transition v-for="(modal, index) in modals"
                    :key="`${modal.value.id}-${index}`"
                    appear
                    name="modal"
        >
            <div v-if="modal.value.active"
                 ref="modalRef"
                 class="modal"
                 :class="[modal.value.zIndex, modal.value.options.class]"
                 :style="{
                     'transition-duration': transitionDuration
                 }"
                 @click="closeOnOutside($event)"
            >
                <div class="modal-content">
                    <component :is="modal.value.component"
                               ref="componentRef"
                               v-bind="modal.value.props"
                               @close="close"
                               @true=onTrue
                               @update="update"
                    />
                </div>
            </div>
        </transition>
    </teleport>
</template>
<script>
import {computed, inject, onBeforeUnmount, reactive, ref} from 'vue';
import Prompt from '@/Elements/Modal/Prompt';

export default {
    name: 'Modal',
    props: {
        transition: {
            type: Number,
            default: 250
        }
    },

    setup(props) {
        const app = inject('app');
        const modals = reactive([]);
        const componentRef = ref(null);
        const modalRef = ref(null);
        const currentIndex = ref(0);

        function open(modal) {
            return new Promise((resolve, reject) => {
                modal = ref(modal);
                const id = Math.random().toString(16).slice(2);
                modal.value.id = id;
                modal.value.zIndex = 1000 + modals.length;
                modal.value.active = true;
                modal.value.options = Object.assign({
                    // options
                },modal.value.options || {})
                modal.value.props = ref(modal.value.props);
                modals.push(modal);
                currentIndex.value = modals.length - 1;

                setTimeout(() => {
                    const index = modals.findIndex(modal => modal.value.id === id);
                    resolve(componentRef.value[index]);
                }, props.transition);
            });
        }

        function prompt(title, message, action) {
            return new Promise((resolve, reject) => {
                open({
                    component: Prompt,
                    props: {
                        title,
                        message,
                        action
                    },
                    options: {
                        class: 'modal--prompt'
                    },
                    onTrue: resolve,
                });
            });
        }

        function close() {
            const index = currentIndex.value;
            const modal = modals[index];
            if (!modal) {
                return;
            }
            modals[index].value.active = false;
            currentIndex.value = modals.length ? modals.length - 1 : 0;

            setTimeout(() => {
                if (typeof modal.value.onClose === 'function') {
                    modal.value.onClose();
                }
                modals.splice(index, 1);
            }, props.transition);
        }

        function onTrue(index) {
            modals[currentIndex.value].value.onTrue();
            close();
        }

        app.register('modal', open).then((options) => {
            // do something if you want
        });

        app.register('prompt', prompt).then((options) => {
            // do something if you want
        });

        app.bus.on('modal:closeAll', () => {
            modals.forEach((m, index) => close(index));
        });

        onBeforeUnmount(() => {
            app.bus.off('modal:closeAll');
        });

        return {
            componentRef,
            modalRef,
            modals,
            close,
            onTrue,
            transitionDuration: computed(() => {
                return `${props.transition / 1000}s`;
            }),

            closeOnOutside(e, index) {
                if (modalRef.value[index] === e.target) {
                    close(index);
                }
            },

            update(props) {
                Object.keys(props).forEach((key) => {
                    if (!modals[currentIndex.value].value.props[key]) return;
                    modals[currentIndex.value].value.props[key] = props[key];
                });
            }
        }
    }
}
</script>
