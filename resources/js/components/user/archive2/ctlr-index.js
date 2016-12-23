import headingTemplate from 'ohio/core/js/templates/base/heading';
import userService from './service';
import userIndexTemplate from './templates/index';

export default {

    components: {
        'heading': {
            data() {
                return {
                    title: 'User Manager',
                    subtitle: '',
                    crumbs: [],
                }
            },
            'template': headingTemplate
        },
        'user-index': {
            mixins: [userService],
            template: userIndexTemplate,
            mounted() {
                this.users.params = this.getUrlQuery();
                this.paginateUsers();
            },
        },
    },

    template: `
        <div>
            <heading></heading>
            <section class="content">
                <user-index></user-index>
            </section>
        </div>
        `
}