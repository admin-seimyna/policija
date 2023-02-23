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

                <div v-for="(permissionsGroup, groupIndex) in permissions"
                     :key="`permission-group-${permissionsGroup.name}`"
                     class="flex flex-1 py-4 hover:bg-gray-100"
                >
                    <input type="hidden"
                           :name="`permissions[${groupIndex}][name]`"
                           :value="permissionsGroup.name"
                    />
                    <div class="flex w-1/3 px-2">
                        <span class="ellipsis font-semibold text-xs">
                            {{ $t(`permission.title.group.${permissionsGroup.name}`) }}
                        </span>
                    </div>

                    <div v-for="(permission, index) in permissionsGroup.permissions"
                         :key="`${permission}-${index}`"
                         class="flex flex-1"
                    >
                        <template v-if="!permission.skip">
                            <input type="hidden"
                                   :name="`permissions[${groupIndex}][permissions][${index}][name]`"
                                   :value="permission.name"
                            />
                            <Popper hover
                                    :content="permission.tooltip"
                                    offset-distance="0"
                                    open-delay="300"
                                    class="tooltip"
                            >
                                <Switch disable-title
                                        :class="{ 'opacity-50': permissionsGroup.disabled }"
                                        :disabled="permissionsGroup.disabled"
                                        v-model="group.mapped_permissions[permissionsGroup.name][permission.name]"
                                        :name="`permissions[${groupIndex}][permissions][${index}][value]`"
                                        :danger="permission.name === 'delete'"
                                />
                            </Popper>
                        </template>
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
import Popper from 'vue3-popper';

export default {
    name: 'UserGroupForm',
    components: {Switch, VInput, CrudForm, Popper},
    props: {
        group: Object,
    },
    setup(props, {emit}){
        const { t, te } = useI18n();
        const store = useStore();
        const actions = computed(() => store.getters['permission/actions']);
        const permissions = computed(() => store.getters['permission/list']);

        if (!props.group.id) {
            props.group.mapped_permissions = {};
            Object.keys(permissions.value).forEach((key) => {
                props.group.mapped_permissions[key] = {};
                permissions.value[key].actions.forEach((action) => {
                    props.group.mapped_permissions[key][action] = 0;
                });
            });
        }

        function checkHasPermissions(permissions) {
            for(const key in permissions) {
                for(const action of permissions[key]) {
                    if (!props.group.mapped_permissions[key][action]) {
                        return false;
                    }
                }
            }
            return true;
        }

        return {
            emit,
            actions,
            permissions: computed(() => {
                const data = [];
                Object.keys(permissions.value).forEach((key) => {
                    let disabled = false;
                    if (permissions.value[key].require) {
                        disabled = !checkHasPermissions(permissions.value[key].require);
                    }
                    data.push({
                        name: key,
                        disabled,
                        permissions: [...actions.value]
                            .map((action) => {
                                if (permissions.value[key].actions.indexOf(action) === -1) {
                                    return { skip: true };
                                }
                                const translationKey = `permission.message.${key}.${action}`;
                                let tooltip = null;
                                if (disabled) {
                                    tooltip = t('permission.message.blocked_by_other_permission');
                                } else {
                                    tooltip = te(translationKey) ? t(translationKey) : null;
                                }
                                return {
                                    name: action,
                                    value: 0,
                                    tooltip,
                                }
                            })
                    })
                });
                return data;
            }),

            title: computed(() => {
                return props.group ? t('user-group.title.create') : t('user-group.title.update');
            }),
        };
    },
}
</script>
