<template>
    <ContextMenu v-if="hasPermissions">
        <template #default="{close}">
            <ul class="w-full flex flex-col">
                <li v-for="action in actions"
                    :key="action"
                    class="w-full py-2 px-3 cursor-pointer"
                    :class="{
                        'hover:bg-gray-100': action !== 'delete',
                        'hover:bg-danger-100': action === 'delete',
                    }"
                    @click="doAction(action, close)"
                >
                <span :class="{
                         'text-danger-700 font-semibold': action === 'delete',
                       }"
                >
                    {{ $t(`context-menu.button.${action}`)}}
                </span>
                </li>
            </ul>
        </template>
    </ContextMenu>
</template>
<script>
import ContextMenu from '@/Elements/ContextMenu';
import {computed, inject} from 'vue';
import {useI18n} from 'vue-i18n';
import axios from 'axios';

export default {
    name: 'CrudContextMenu',
    components: {ContextMenu},
    emits: ['edit', 'delete'],
    props: {
        deleteUrl: String,
        permission: String,
        canDelete: Boolean,
        canEdit: Boolean
    },
    setup(props, { emit }) {
        const app = inject('app');
        const t = useI18n().t;

        return {
            hasPermissions: computed(() => {
                if (!props.permission) return props.canDelete || props.canEdit;
                return app.permission.hasAny([
                    `${props.permission}.edit`,
                    `${props.permission}.delete`,
                ]);
            }),
            actions: computed(() => {
                return [
                    'edit',
                    'delete'
                ].filter((action) => {
                    if (!props.permission) {
                        switch (action) {
                            case 'edit': return props.canEdit;
                            case 'delete': return props.canDelete;
                        }
                    }
                    return app.permission.has(`${props.permission}.${action}`)
                });
            }),
            doAction(action, close) {
                close();
                if (action === 'delete') {
                    app.prompt(
                        t('common.title.delete_prompt'),
                        t('common.message.delete_prompt'),
                        action
                    ).then(() => {
                        if (props.deleteUrl) {
                            axios.delete(props.deleteUrl).then(() => {
                                emit(action);
                            });
                        } else {
                            emit(action);
                        }
                    });
                    return;
                }
                emit(action);
            }
        };
    }
}
</script>
