<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="navigation-header">
                {{--<span>Menu</span><i class=" ft-minus" data-toggle="tooltip" data-placement="right" data-original-title="Menu"></i>--}}
            </li>
            @ability('webmaster|super-admin|admin', 'admin-dashboard')
            <li class="nav-item {{isRouteActive('home')}}">
                <a href="{{route('home')}}"><i class="ft-home"></i>
                    <span class="menu-title" data-i18n="">{{__('Dashboard')}}</span>
                </a>
            </li>
            @endability

            @ability('webmaster|super-admin|admin', 'admin-dashboard')
            <li class="nav-item {{isRouteActive('analytics.index')}}">
                <a href="{{route('analytics.index')}}"><i class="ft-pie-chart"></i>
                    <span class="menu-title" data-i18n="">{{__('Analytics')}}</span>
                </a>
            </li>
            @endability

            @ability('webmaster|super-admin|admin', 'calendar')
            <li class="nav-item {{isRouteActive('calendar')}}">
                <a href="{{route('calendar')}}"><i class="ft-calendar"></i>
                    <span class="menu-title" data-i18n="">{{__('Reports Calendar')}}</span>
                </a>
            </li>
            @endability

            @ability('webmaster|super-admin|admin|ace-officer', 'submit-report|view-report')
            <li class="nav-item has-sub {{isRouteActive('report_submission','true','open')}}">
                <a href="#"><i class="ft-file-text"></i>
                    <span class="menu-title" data-i18n="">{{__('Report Submission')}}</span>
                </a>
                <ul class="menu-content">
                    @ability('webmaster|super-admin', 'view-report')
                    <li class="nav-item {{isRouteActive('report_submission.reports')}}">
                        <a href="{{route('report_submission.reports')}}" class="menu-item">
                            <span class="menu-title" data-i18n="">{{__('All Reports')}}</span>
                        </a>
                    </li>
                    @endability

                    @ability('webmaster|super-admin', 'submit-report')
                    <li class="nav-item {{isRouteActive('report_submission.add')}}">
                        <a href="{{route('report_submission.add')}}" class="menu-item">
                            <span class="menu-title" data-i18n="">{{__('New Report')}}</span>
                        </a>
                    </li>
                    <li class="nav-item {{isRouteActive('report_submission.downloadIndicators')}}">
                        <a href="{{route('report_submission.downloadIndicators')}}" class="menu-item">
                            <span class="menu-title" data-i18n="">{{__('Indicator Templates')}}</span>
                        </a>
                    </li>
                    @endability


                </ul>
            </li>
            @endability

            @ability('webmaster', 'generate-report')
            <li class=" nav-item {{isRouteActive('report_generation','false','open')}}"><a href="#">
                    <i class="ft-bar-chart-2"></i><span class="menu-title" data-i18n="">{{__('Report Generation')}}</span></a>
                <ul class="menu-content">
                    <li class="{{isRouteActive('report_generation.general')}}">
                        <a href="{{route('report_generation.general')}}">
                            <span class="menu-title" data-i18n="">{{__('General Report')}}</span>
                        </a>
                    </li>

                    <li class="{{isRouteActive('report_generation.indicator_status')}}">
                        <a href="{{route('report_generation.indicator_status')}}">
                            <span class="menu-title" data-i18n="">{{__('Indicator Status Report')}}</span>
                        </a>
                    </li>
                    <li class="{{isRouteActive('report_generation.generate.milestones')}}">
                        <a href="{{route('report_generation.generate.milestones')}}">
                            <span class="menu-title" data-i18n="">{{__('DLR 2.8 Report')}}</span>
                        </a>
                    </li>
                    <li class="{{isRouteActive('report_generation.verificationletter.verificationpage')}}">
                        <a href="{{route('report_generation.verificationletter.verificationpage')}}">
                            <span class="menu-title" data-i18n="">{{__('    Verification Report')}}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endability

            @ability('webmaster|super-admin', 'add-courses|app-settings')
            <li class=" nav-item {{isRouteActive('indicators','false','open')}} {{isRouteActive('settings','true','open')}}"><a href="#">
                    <i class="ft-settings"></i><span class="menu-title" data-i18n="">{{__('System Settings')}}</span></a>
                <ul class="menu-content">

                    @role('webmaster|admin|super-admin')
                    <li class="{{isRouteActive('settings.projects')}}">
                        <a class="menu-item" href="{{route('settings.projects')}}">{{__('Projects')}}</a>
                    </li>
                    <li class="{{isRouteActive('indicators')}}">
                        <a class="menu-item" href="{{route('indicators')}}">{{__('Indicators')}}</a>
                    </li>
                    <li class="{{isRouteActive('settings.indicator.generated_forms')}}">
                        <a class="menu-item" href="{{route('settings.indicator.generated_forms')}}">{{__('Indicators Form')}}</a>
                    </li>
                    @endrole

                    @ability('webmaster|super-admin', 'add-dlr-indicators')
                    <li class="{{isRouteActive('settings.dlr_indicators')}}">
                        <a class="menu-item" href="{{route('settings.dlr_indicators')}}">{{__('DLR Indicators')}}</a>
                    </li>
                    @endability

                    @ability('webmaster|super-admin', 'upload-indicators-template')
                    <li class="{{isRouteActive('settings.excelupload.create')}}">
                        <a class="menu-item" href="{{route('settings.excelupload.create')}}">{{__('Upload Templates')}}</a>
                    </li>
                    @endability
                    @ability('webmaster|super-admin', 'app-settings')
                    <li class="{{isRouteActive('settings.app_settings')}}">
                        <a class="menu-item" href="{{route('settings.app_settings')}}">{{__('Application Settings')}}</a>
                    </li>
                    @endability
                </ul>
            </li>
            @endability

            @ability('webmaster|super-admin|admin', 'add-user|add-roles|add-institutions|add-aces')
            <li class="nav-item has-sub {{isRouteActive('user-management','true','open')}}">
                <a href="#"><i class="ft-users"></i>
                    <span class="menu-title" data-i18n="">{{__('User Management')}}</span>
                </a>
                <ul class="menu-content">
                    @ability('webmaster|super-admin', 'add-user')
                    <li class="{{isRouteActive('user-management.users')}}">
                        <a href="{{route('user-management.users')}}">
                            <span class="menu-title" data-i18n="">{{__('Users')}}</span>
                        </a>
                    </li>
                    @endability

                    @role('webmaster')
                    <li class="{{isRouteActive('user-management.permissions')}}">
                        <a href="{{route('user-management.permissions')}}">
                            <span class="menu-title" data-i18n="">{{__('Permissions')}}</span>
                        </a>
                    </li>
                    @endrole

                    @ability('webmaster|super-admin', 'add-roles')
                    <li class="{{isRouteActive('user-management.roles')}}">
                        <a href="{{route('user-management.roles')}}">
                            <span class="menu-title" data-i18n="">{{__('Roles')}}</span>
                        </a>
                    </li>
                    @endability

                    @ability('webmaster|super-admin|admin', 'add-institutions')
                    <li class="{{isRouteActive('user-management.institutions')}}">
                        <a href="{{route('user-management.institutions')}}">
                            <span class="menu-title" data-i18n="">{{__('Institutions')}}</span>
                        </a>
                    </li>
                    @endability

                    @ability('webmaster|super-admin|admin', 'add-aces')
                    <li class="{{isRouteActive('user-management.aces')}}">
                        <a href="{{route('user-management.aces')}}">
                            <span class="menu-title" data-i18n="">{{__('ACEs')}}</span>
                        </a>
                    </li>

                    <li class="{{isRouteActive('user-management.contacts')}}">
                        <a href="{{route('user-management.contacts')}}">
                            <span class="menu-title" data-i18n="">{{__('Contacts')}}</span>
                        </a>
                    </li>
                    @endability
                </ul>
            </li>
            @endability

            @ability('webmaster|super-admin|admin', 'setup-faq')
            <li class="nav-item {{isRouteActive('faqs')}}">
                <a href="{{route('faqs')}}"><i class="ft-message-circle"></i>
                    <span class="menu-title" data-i18n="">{{__('FAQs')}}</span>
                </a>
            </li>
            @endability

            @ability('ace-officer','faq')
            <li class="nav-item {{isRouteActive('read.faqs')}}">
                <a href="{{route('read.faqs')}}"><i class="ft-message-circle"></i>
                    <span class="menu-title" data-i18n="">{{__('FAQs')}}</span>
                </a>
            </li>
            @endability


        </ul>
    </div>
</div>