<template>
    <div class="input"
         :class="{
            'input--focus': focused,
            'input--error': hasError,
        }"
    >
        <div class="editor input__wrapper">
            <label :for="name"
                   class="text-xs font-semibold mt-2 ml-3 text-text-light"
            >
                {{ label }}
            </label>

            <ul v-if="ready"
                class="flex flex-wrap p-2"
            >
                <li v-for="menu in menuList"
                    :key="menu.name"
                    class="w-8 h-8 flex-center cursor-pointer rounded mr-1 border"
                    :class="{
                       'bg-gray-600 text-white': editor.isActive(menu.name),
                       'hover:bg-gray-100': !editor.isActive(menu.name),
                    }"
                    @click="applyStyle(menu)"
                >
                    <i :class="menu.icon"
                       class="mt-1"
                    />
                </li>
            </ul>

            <div class="w-full editor__content">
                <EditorContent :editor="editor" class="p-2" />
            </div>

            <input type="hidden"
                   :name="name"
                   :value="inputValue"
            />
        </div>
        <InputError
            v-if="hasError"
            :message="errorMessage"
            class="mt-1"
        />
    </div>
</template>
<script>
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Placeholder from '@tiptap/extension-placeholder'
import {computed, onMounted, ref, watch} from 'vue';
import { fieldProps, useField } from '@/Elements/Field';
import InputError from '@/Elements/InputError';

export default {
    name: 'TipTapEditor',
    components: {
        InputError,
        EditorContent,
    },
    props: {
        modelValue: String,
        ...fieldProps,
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        const ready = ref(false);
        const focused = ref(false);
        const inputValue = ref(props.modelValue || null);
        const field = useField(props);
        const updateTimer = null;

        const editor = useEditor({
            content: inputValue.value,
            extensions: [
                StarterKit,
                Placeholder.configure({
                    placeholder: field.placeholder.value,
                }),
            ],
            onUpdate({ editor }) {
                inputValue.value = editor.getHTML();
                emit('update:modelValue', inputValue.value);
            },
            onFocus() {
                focused.value = true;
            },
            onBlur() {
                focused.value = false;
            }
        });

        watch(
            () => props.modelValue,
            (value) => {
                if (value === inputValue.value) return;
                inputValue.value = value;
                editor.value.commands.setContent(value);
            }
        );

        onMounted(() => {
            ready.value = true;
        });


        return {
            ...field,
            focused,
            inputValue,
            editor,
            ready,
            menuList: computed(() => {
                return [
                    {
                        name: 'bold',
                        method: 'toggleBold',
                        icon: 'icon-bold',
                    },{
                        name: 'italic',
                        method: 'toggleItalic',
                        icon: 'icon-italic',
                    },{
                        name: 'strike',
                        method: 'toggleStrike',
                        icon: 'icon-strikethrough',
                    },{
                        name: 'bulletList',
                        method: 'toggleBulletList',
                        icon: 'icon-list-unordered',
                    },{
                        name: 'orderedList',
                        method: 'toggleOrderedList',
                        icon: 'icon-list-ordered',
                    }
                ]
            }),

            applyStyle(menu) {
                editor.value.chain().focus()[menu.method]().run();
            }
        };
    }
}
</script>
