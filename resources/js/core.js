import alerts from './components/alerts/routes';
import roles from './components/roles/routes';
import teams from './components/teams/routes';
import users from './components/users/routes';
import store from 'belt/core/js/store/index';
import tinymce_directive from './directives/tinymce';

import column_sorter from './components/base/column-sorter';
import pagination from './components/base/pagination.vue';
import modals from './components/base/modals/modals.vue';
import modalDelete from './components/base/modals/modal-delete.vue';

Vue.component('column-sorter', column_sorter);
Vue.component('pagination', pagination);
Vue.component('modals', modals);
Vue.component('modal-delete', modalDelete);
Vue.directive('tinymce', tinymce_directive);

window.Events = new Vue({});

export default class BeltCore {

    constructor(components = []) {
        this.components = [];

        _(components).forEach((value, index) => {
            this.addComponent(value);
        });

        if ($('#belt-core').length > 0) {

            const router = new VueRouter({
                mode: 'history',
                base: '/admin/belt/core',
                routes: []
            });

            router.addRoutes(alerts);
            router.addRoutes(roles);
            router.addRoutes(teams);
            router.addRoutes(users);

            const app = new Vue({router, store}).$mount('#belt-core');
        }

        let modals = new Vue({
            el: '#vue-modals'
        });
    }

    addComponent(Class) {
        this.components[Class.name] = new Class();
    }
}