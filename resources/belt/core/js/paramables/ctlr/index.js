import Config from 'belt/core/js/templates/config';
import Table from 'belt/core/js/paramables/table';
import shared from 'belt/core/js/paramables/ctlr/shared';
import create from 'belt/core/js/paramables/ctlr/create';
import edit from 'belt/core/js/paramables/ctlr/edit';
import html from 'belt/core/js/paramables/templates/index.html';

export default {
    mixins: [shared],
    props: {
        _config: {default: null},
        morphable: {default: null},
    },
    data() {

        let paramable = this.morphable;
        let morphable_type = paramable.morph_class;
        let morphable_id = paramable.id;

        return {
            config: this._config ? this._config : {},
            dirty: false,
            eventBus: new Vue(),
            morphable_type: morphable_type,
            morphable_id: morphable_id,
            paramable: paramable,
            detached: new Table({
                morphable_type: morphable_type,
                morphable_id: morphable_id,
                query: {not: 1},
            }),
            table: new Table({
                morphable_type: morphable_type,
                morphable_id: morphable_id,
            }),
        }
    },
    computed: {
        canCreateParams() {
            return _.get(this.config, 'can_create_params', false);
        }
    },
    watch: {
        'paramable.id': function (new_paramable_id) {
            if (new_paramable_id && !this.config) {
                this.config = new Config({type: this.morphable_type, template: this.paramable.template});
                this.config.load()
                    .then((response) => {

                    });
            }
        }
    },
    mounted() {
        this.table.index();
    },
    methods: {
        scan() {
            this.dirty = false;
            this.eventBus.$emit('scan');
        },
        update() {
            this.eventBus.$emit('update');
            this.dirty = false;
        }
    },
    components: {
        create: create,
        edit: edit,
    },
    template: html
}