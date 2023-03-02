<template>
    <div class="flex flex-col p-8">
        <div class="flex">
            <span class="text-xxl font-bold">
                {{ $t('common.title.file_export') }}
            </span>

            <VButton type="span"
                     rounded
                     basic
                     soft
                     class="w-10 h-10 ml-auto"
                     @click="emit('close')"
            >
                <i class="icon-cross-mark mt-1" />
            </VButton>
        </div>

        <VInput
            v-model="fileName"
            name="file_name"
            class="mt-5"
        >
            <template #append>
                <span class="font-semibold text-gray-500">
                    .xlsx
                </span>
            </template>
        </VInput>

        <div class="flex items-center justify-end mt-8">
            <VButton basic
                     class="mr-2"
                     @click="emit('close')"
            >
                {{ $t('common.button.cancel') }}
            </VButton>

            <VButton primary
                     shadow
                     :progress="loading"
                     @click="download"
            >
                {{ $t('common.button.download') }}
            </VButton>
        </div>
    </div>
</template>
<script>
import VInput from '@/Elements/Input';
import VButton from '@/Elements/Button';
import {ref} from 'vue';
import axios from 'axios';
import {useI18n} from 'vue-i18n';

export default {
    name: 'VTableExport',
    components: {
        VButton,
        VInput
    },
    props: {
        url: String,
    },
    setup(props, { emit }) {
        const { t } = useI18n();
        const fileName = ref(t('report.title.default_file_name'));
        const loading = ref(false);

        return {
            fileName,
            emit,
            loading,
            download() {
                loading.value = true;
                axios.post(props.url, {}, {
                    responseType: 'blob'
                }).then((response) => {
                    // create file link in browser's memory
                    const href = URL.createObjectURL(response.data);

                    // create "a" HTML element with href to file & click
                    const link = document.createElement('a');
                    link.href = href;
                    link.setAttribute('download', `${fileName.value}.xlsx`);
                    document.body.appendChild(link);
                    link.click();

                    // clean up "a" element & remove ObjectURL
                    document.body.removeChild(link);
                    URL.revokeObjectURL(href);
                    loading.value = false;
                    emit('close');
                });
            }

        };
    }
}
</script>
