import headingTemplate from 'belt/core/js/templates/base/heading.html';
import userService from './service';
import userFormTemplate from './templates/form';
import roles from './role/ctlr-edit';

export default {
    data() {
        return {
            morphable_type: 'users',
            morphable_id: '[auth.id]',
        }
    },
    components: {
        'heading': {
            data() {
                return {
                    title: 'My Profile',
                    subtitle: '',
                    crumbs: [],
                }
            },
            'template': headingTemplate
        },
        'user-form': {
            mixins: [userService],
            template: userFormTemplate,
            mounted() {
                this.item.id = '[auth.id]';
                this.get();
            },
        },
        roles
    },
    template: `
        <div>
            <heading></heading>
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs pull-right">
                                <li class="active"><a href="#tab_1-1" data-toggle="tab" aria-expanded="false">Main</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_1-1">
                                    <user-form></user-form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        `
}