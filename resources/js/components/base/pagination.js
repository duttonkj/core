global._ = require('lodash');

export default {
    props: ['route', 'paginator'],
    data() {
        return {
            max: 5
        }
    },
    computed: {

        results() {
            return this.$parent.items
        },
        showPager() {

            // hide if no data to show
            if (this.paginator == undefined || this.paginator.length == 0) {
                return false;
            }

            // hide if total data is less than perPage limit
            if (this.paginator.total <= this.paginator.per_page) {
                return false;
            }

            return true;
        },
        isNotFirst() {
            return this.paginator.current_page != 1
        },
        hasNext() {
            return this.paginator.last_page - this.paginator.current_page > 0;
        },
        indexes() {

            let first = this.paginator.current_page;

            let values = [];

            let midpoint = Math.ceil(this.max / 2);

            if (this.paginator.last_page - this.max > 0) {

                if (this.paginator.current_page >= midpoint) {
                    first = this.paginator.current_page - (this.max % midpoint);
                }

                if (this.paginator.last_page - this.paginator.current_page < midpoint) {
                    first = this.paginator.last_page - (this.max - 1)
                }
            }

            if (this.paginator.last_page - this.max <= 0) {
                first = 1;
                this.max = this.paginator.last_page;
            }

            let i = first;
            while (i < first + this.max) {
                values.push(i);
                i++;
            }

            return values;
        },
        hasPrevious() {
            return this.paginator.current_page - 1 > 0;
        },
        hasLast() {
            return this.paginator.current_page != this.paginator.last_page;
        }
    },
    methods: {
        getParams(type, number = null) {

            let params = {};
            _(this.$parent.$route.query).forEach((value, key) => {
                params[key] = value;
            });

            if (typeof params['page'] == 'undefined') {
                params['page'] = this.paginator.current_page;
            }

            switch (type) {
                case 'first':
                    params['page'] = 1;
                    break;
                case 'previous':
                    params['page'] = this.paginator.current_page - 1;
                    break;
                case 'next':
                    params['page'] = this.paginator.current_page + 1;
                    break;
                case 'last':
                    params['page'] = this.paginator.last_page;
                    break;
                case 'page':
                    params['page'] = number;
                    break;
            }

            return params;
        },
        isActive(id) {
            return id == this.paginator.current_page;
        }
    },
    template: `
        <div v-if="showPager">
            <div class="row">
                <div class="col-md-5">
                    <div class="pagination-meta-data" role="status" aria-live="polite">
                        Showing {{ paginator.from }} to {{ paginator.to }} of {{ paginator.total }} entries
                    </div>
                </div>
                <div class="col-md-7">
                    <span class="pull-right">
                        <ul class="pagination-sm pagination">
                            <li v-if="isNotFirst">
                                <router-link :to="{ name: route, query: getParams('first') }"><i class="fa fa-step-backward" aria-hidden="true"></i></router-link>
                            </li>
                            <li v-else class="disabled">
                                <span aria-hidden="true"><i class="fa fa-step-backward" aria-hidden="true"></i></span>
                            </li>
                            <li v-if="hasPrevious">
                                <router-link :to="{ name: route, query: getParams('previous') }"><i class="fa fa-backward" aria-hidden="true"></i></router-link>
                            </li>
                            <li v-else class="disabled">
                                <span aria-hidden="true"><i class="fa fa-backward" aria-hidden="true"></i></span>
                            </li>
                            <template v-for="number in indexes">
                                <li v-bind:class="{ active: isActive(number) }">
                                    <router-link :to="{ name: route, query: getParams('page', number) }">{{ number }}</router-link>
                                </li>
                            </template>
                            <li v-if="hasNext">
                                <router-link :to="{ name: route, query: getParams('next') }"><i class="fa fa-forward" aria-hidden="true"></i></router-link>
                            </li>
                            <li v-else class="disabled">
                                <span aria-hidden="true"><i class="fa fa-forward" aria-hidden="true"></i></span>
                            </li>
                            <li v-if="hasLast">
                                <router-link :to="{ name: route, query: getParams('last') }"><i class="fa fa-step-forward" aria-hidden="true"></i></router-link>
                            </li>
                            <li v-else class="disabled">
                                <span aria-hidden="true"><i class="fa fa-step-forward" aria-hidden="true"></i></span>
                            </li>
                        </ul>
                    </span>
                </div>
            </div>
        </div>
    `
}