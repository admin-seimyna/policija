<template>
    <div class="w-full h-full flex-center flex-col">
        <VForm action="/login"
               class="w-1/3 max-w-md min-w-96 shrink-0 rounded p-8 shadow-lg bg-white"
               @success="redirect"
        >
            <template #default="{data, errors, progress}">
                <span class="font-bold text-2xl mb-8">
                    {{ $t('auth.title.login') }}
                </span>
                <VInput name="email"
                        :errors="errors"
                />
                <VInput name="password"
                        type="password"
                        :errors="errors"
                        class="mt-5"
                />
                <div class="flex items-center mt-8">
                    <VButton primary
                             shadow
                             :progress="progress"
                             class="w-full"
                    >
                        {{ $t('auth.button.login') }}
                    </VButton>
                </div>
            </template>
        </VForm>
    </div>
</template>
<script>
import VForm from '@/Elements/Form';
import VInput from '@/Elements/Input';
import VButton from '@/Elements/Button';
import {useRouter} from 'vue-router';

export default {
    name: 'Login',
    components: {VButton, VInput, VForm},
    setup(props) {
        const router = useRouter();
        return {
            redirect() {
                router.push({ name: 'dashboard'});
            }
        }
    }
}
</script>
