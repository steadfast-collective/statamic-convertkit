<template>
    <div>
        <div>
            <label v-for="form in meta.forms" :key="form.value">
                <input type="checkbox" v-model="form.checked" :value="form.value" @change="handleFormSelection">
                {{ form.label }}
            </label>
        </div>
        <div v-if="input.selectedForms.length" class="field-inner mt-2">
            <div class="flex flex-col gap-2">
                <field-mapping v-for="form in input.selectedForms" :key="form.value" :form="form" :form_fields="meta.forms[form.value].fields" @mappingUpdated="handleUpdatedMapping($event, form)" :meta="meta" />
            </div>
        </div>
    </div>
</template>

<script>
import FieldMapping from '@/components/FieldMapping.vue'

export default {
    mixins: [Fieldtype],

    components: {
        FieldMapping,
    },

    data() {
        return {
            input: {
                selectedForms: [],
            }
        };
    },
    methods: {
        handleFormSelection(e) {
            if(e.target.checked) {
                this.input.selectedForms.push(this.meta.forms[e.target.value])
            } else {
                let index = this.input.selectedForms.findIndex(obj => {
                    return obj.value === e.target.value
                });

                this.input.selectedForms.splice(index, 1)
            }

            this.update(this.input)
        },
        handleUpdatedMapping(e, form) {
            form.mappings = e
            this.update(this.input)
        }
    },
    mounted() {
        if(this.value?.selectedForms) {
            this.value.selectedForms.forEach(value => {
                this.input.selectedForms.push(value);
            });
        }

    }

};
</script>
