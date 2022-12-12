import FormMapping from './components/fieldtypes/FormMapping.vue'

Statamic.booting(() => {
    Statamic.component('form_mapping-fieldtype', FormMapping)
})
