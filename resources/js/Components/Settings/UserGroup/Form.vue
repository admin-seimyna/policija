<template>
    <CrudForm
        action="/settings/user-group"
        :title="title"
        :data="group"
    >
        <template #default="{data,errors}">
            <VInput v-model="data.title"
                    name="title"
                    :errors="errors"
            />

            <div class="w-full flex flex-col mt-5">
                <div class="flex flex-1 border-b-2 py-2 font-semibold text-xs">
                    <div class="flex w-1/3 px-2">
                        {{ $t('permission.title.permissions') }}
                    </div>

                    <div v-for="name in actions"
                         :key="name"
                         class="flex flex-1"
                    >
                        {{ $t(`permission.title.action.${name}`) }}
                    </div>
                </div>

                <div v-for="(group, groupIndex) in permissions"
                     :key="`permission-group-${group.name}`"
                     class="flex flex-1 py-4 hover:bg-gray-100"
                >
                    <input type="hidden"
                           :name="`permissions[${groupIndex}][name]`"
                           :value="group.name"
                    />
                    <div class="flex w-1/3 px-2">
                        <span class="ellipsis font-semibold text-xs">
                            {{ $t(`permission.title.group.${group.name}`) }}
                        </span>
                    </div>

                    <div v-for="(permission, index) in group.permissions"
                         :key="`${permission}-${index}`"
                         class="flex flex-1"
                    >
                        <input type="hidden"
                               :name="`permissions[${groupIndex}][permissions][${index}][name]`"
                               :value="permission.name"
                        />
                        <Switch disable-title
                                :value="getValue(group.name, permission.name)"
                                :name="`permissions[${groupIndex}][permissions][${index}][value]`"
                                :danger="permission.name === 'delete'"
                        />
                    </div>
                </div>
            </div>
        </template>
    </CrudForm>
</template>
<script>
import CrudForm from '@/Elements/Crud/From';
import VInput from '@/Elements/Input';
import {computed} from 'vue';
import {useI18n} from 'vue-i18n';
import {useStore} from 'vuex';
import Switch from '@/Elements/Switch';
export default {
    name: 'UserGroupForm',
    components: {Switch, VInput, CrudForm},
    props: {
        group: Object,
    },
    setup(props, {emit}){
        const t = useI18n().t;
        const store = useStore();
        const actions = computed(() => store.getters['permission/actions']);

        return {
            emit,
            actions,
            permissions: computed(() => {
                const permissions = [];
                const list = store.getters['permission/list'];
                Object.keys(list).forEach((key) => {
                    permissions.push({
                        name: key,
                        permissions: [...actions.value].map((action) => {
                            return {
                                name: action,
                                value: 0
                            }
                        })
                    })
                });
                return permissions;
            }),

            getValue(groupName, permissionName) {
                if (!props.group.id) return 0;
                return parseInt(props.group.mapped_permissions[groupName][permissionName]);
            },

            title: computed(() => {
                return props.group ? t('user-group.title.create') : t('user-group.title.update');
            }),
        };
    },
}
</script>
