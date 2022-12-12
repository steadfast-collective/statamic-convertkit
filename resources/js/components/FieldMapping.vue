<template>
    <div class="border rounded-lg p-2 shadow-sm">
        {{ __('convertkit::settings.field_mapping.form_title', {'name': form.label}) }}

        <div class="flex flex-col items-start">
            <div v-for="(field, index) in mappedFields" :key="index" class="w-full border p-1 my-1 rounded-lg relative grid grid-cols-2 gap-2 pr-2">

                <div class="w-full">
                    <label v-text="__('convertkit::settings.field_mapping.convertkit_field')" />
                    <select class="input-text" v-model="field.convertkit_name" @input="update" :disabled="!field.can_remove" required>
                        <option v-for="(convertkit_field, index) in convertkit_fields" :key="index" :value="convertkit_field.value" v-text="convertkit_field.label" />
                    </select>
                </div>

                <div class="w-full">
                    <label v-text="__('convertkit::settings.field_mapping.form_field')" />
                    <select v-if="field.convertkit_name != 'form'" class="input-text" v-model="field.form_field" @input="update" required>
                        <option v-for="(form_field, index) in form_fields" :key="index" :value="form_field.handle" v-text="form_field.display" />
                        <option value="custom_value" v-text="__('convertkit::settings.field_mapping.custom_value')" />
                    </select>
                    <select v-else class="input-text" v-model="field.form_field" @input="update" required>
                        <option v-for="(form) in forms" :key="form.id" :value="form.id" v-text="form.name" />
                    </select>
                </div>

                <div class="w-full" v-if="field.convertkit_name == 'custom_field'">
                    <label v-text="__('convertkit::settings.field_mapping.custom_field_key')" />
                    <select class="input-text" v-model="field.custom_key" @input="update" required>
                        <option v-for="custom_field in custom_fields" :key="custom_field.id" :value="custom_field.key" v-text="custom_field.name" />
                    </select>
                </div>

                <div
                    v-if="field.form_field == 'custom_value' && field.convertkit_name != 'tags'"
                    class="w-full"
                    :class="{
                        'col-span-2': field.convertkit_name != 'custom_field'
                    }"
                >
                    <label v-text="__('convertkit::settings.field_mapping.custom_value')" />
                    <text-input v-model="field.custom_value" :placeholder="getPlaceholder(field)" />
                </div>

                <div
                    v-if="field.convertkit_name == 'tags' && field.form_field == 'custom_value'"
                    class="w-full col-span-2"
                >
                    <label v-for="tag in tags" :key="tag.id">
                        <input type="checkbox" :id="tag.id" :value="tag.id" v-model="field.selected_tags">
                        {{ tag.name }}
                    </label>
                </div>

                <button v-if="field.can_remove" @click="removeField(index)" aria-label="Remove Field" class="absolute -right-1.5 top-[calc(50%-12px)] group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="bg-white w-6 h-6 text-gray-700">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
            </div>
            <button @click="addField" class="btn inline-block ml-auto mr-0" v-text="__('convertkit::settings.field_mapping.add_field')"/>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    props: ["form", "form_fields", "meta"],
    data() {
        return {
            convertkit_fields: [
                {
                    value: 'form',
                    label: 'Form'
                },
                {
                    value: 'email',
                    label: 'Email'
                },
                {
                    value: 'tags',
                    label: 'Tags'
                },
                {
                    value: 'first_name',
                    label: 'First Name'
                },
                {
                    value: 'custom_field',
                    label: 'Custom Field'
                }
            ],
            mappedFields: [
                {
                    label: 'Form',
                    convertkit_name: 'form',
                    can_remove: false,
                    form_field: null,
                    custom_value: null
                },
                {
                    label: 'Email',
                    convertkit_name: 'email',
                    can_remove: false,
                    form_field: null,
                    custom_value: null
                }
            ],
            forms: [],
            tags: [],
            custom_fields: []
        }
    },
    methods: {
        addField() {
            this.mappedFields.push({
                label: null,
                convertkit_name: null,
                can_remove: true,
                form_field: null,
                custom_value: null,
                selected_tags: [],
            })
        },

        removeField(index) {
            this.mappedFields.splice(index, 1);
        },

        update() {
            this.$emit('mappingUpdated', this.mappedFields)
        },

        getForms() {
            if(!this.forms.length) {
                axios.get(cp_url('/convertkit/get-forms'))
                .then(res => {
                    this.forms = res.data
                })
                .catch(err => {
                    console.error(err);
                });
            }
        },

        getTags() {
            if(!this.tags.length) {
                axios.get(cp_url('/convertkit/get-tags'))
                .then(res => {
                    console.log(typeof(res.data))

                    this.tags = res.data
                })
                .catch(err => {
                    console.error(err);
                });
            }
        },

        getCustomFields() {
            if(!this.custom_fields.length) {
                axios.get(cp_url('/convertkit/get-custom-fields'))
                .then(res => {
                    console.log(typeof(res.data))
                    this.custom_fields = res.data
                })
                .catch(err => {
                    console.error(err);
                });
            }
        },

        getPlaceholder(field) {
            return __('convertkit::settings.field_mapping.custom_value');
        }
    },
    mounted() {
        if(this.form?.mappings) {
            this.mappedFields = this.form.mappings
        }

        this.getForms()
        this.getTags()
        this.getCustomFields()
    }
}
</script>
