import headingTemplate from 'ohio/core/js/templates/base/heading';
import teamService from './service';
import teamIndexTemplate from './templates/index';

export default {

    components: {
        'heading': {
            data() {
                return {
                    title: 'Team Manager',
                    subtitle: '',
                    crumbs: [],
                }
            },
            'template': headingTemplate
        },
        'team-index': {
            mixins: [teamService],
            template: teamIndexTemplate,
            mounted() {
                this.paginateTeams();
            },
            watch: {
                '$route' (to, from) {
                    this.paginateTeams();
                }
            },
        },
    },

    template: `
        <div>
            <heading></heading>
            <section class="content">
                <team-index></team-index>
            </section>
        </div>
        `
}