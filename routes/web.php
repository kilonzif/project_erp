<?php
use Illuminate\Support\Facades\Mail;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/php', function () {
    dd( phpinfo());
});
Route::redirect('/', '/login', 301);

Auth::routes();

Route::get('/export', 'ExcelExportController@export');

Route::get('/spreadsheet', 'SpreadSheetController@spreadsheet');

Route::get('/sendbasicemail', 'MailController@basic_email');

// Route::get('sendbasicemail','MailController@basic_email');
// Route::get('sendhtmlemail','MailController@html_email');
// Route::get('sendattachmentemail','MailController@attachment_email');

Route::get('/send-mail', function () {

//    $send_mail = new \App\Classes\SystemMail();
//    try {
        $user = \App\User::find(2);
//        dd($user);
//        $send_mail->to('asowa45@gmail.com')
//            ->from('no-reply@aau@org')
//            ->subject('Account Confirmation & Password')
//            ->markdown('mail.email-confirmation', ['user' => $user])
//            ->send();
//        $data = "Hello, Testing mail";
        $subject = "Email Subject";
        $emails = ['asowa45@gmail.com'];
        $ccs = ['alfred.sowa@makeduconsult.com'];
//        $user = \App\User::find(2);
//        $fileName = 'testing_1546452037.pdf';
//        $attachLink = \Storage::url('app/public/workflow_attachments/'.$fileName);
//        $attachLink = base_path().''.$attachLink;
        $attachLink = null;
//        dd($user);
       Mail::send('mail.email-confirmation',['user' => $user],
            function ($message) use($emails,$subject,$ccs,$attachLink) {
                $message->to($emails)
                    ->subject($subject);
//                    ->cc($ccs);
//                if ($attachLink!=null){
//                    $message->attach($attachLink);
//                }
            });
//        dd($user);
    dd(Mail::failures());
//        return "Sent you";
//    } catch (\Throwable $exception) {
//        return "Hello";
//    }

}

);

Route::post('comments', 'CommentController@store');
Route::delete('comments/{comment}', 'CommentController@destroy');
Route::put('comments/{comment}', 'CommentController@update');
Route::post('comments/{comment}', 'CommentController@reply');

Route::get('/commenttest/{id}', 'CommentController@index')->name('comment');

Route::get('/user/account-activation/{token}', 'UserController@verify_user')->name('account_activation');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/calendar', 'HomeController@calendar')->name('calendar');
//Route::get('/reports', 'ReportsController@index')->name('reports');

Route::prefix('analytics')->name('analytics.')->group(function () {
    Route::get('/', 'AnalyticsController@index')->name('index');
    Route::get('/get-data-gender', 'AnalyticsController@getGenderDistribution')->name('getGenderDistribution');
    Route::get('/get-cumulative-pdo', 'AnalyticsController@getCumulativePDO')->name('getCumulativePDO');
    Route::get('/calculate-aggregate','AnalyticsController@calculateAggregate')->name('calculateAggregate');
    Route::post('/add-filter','AnalyticsController@add_filter')->name('add_filter');
    Route::get('/export-data','AnalyticsController@export_data')->name('export_data');
});


Route::prefix('user-management')->name('user-management.')->group(function () {

    //Users Routes
    Route::get('users', 'UserController@index')->name('users');
    Route::get('user/create', 'UserController@create')->name('user.create');
    Route::post('user/save', 'UserController@save_user')->name('user.save_user');
    Route::get('user/json-view', 'UserController@edit_user_view')->name('user.edit_user_view');
    Route::post('user/update', 'UserController@update_user')->name('user.update_user');
    Route::get('user/my-profile', 'UserController@myprofile')->name('user.my_profile');
    Route::get('user/{id}/profile', 'UserController@profile')->name('user.profile');
    Route::put('user/{id}/edit_profile', 'UserController@edit_user')->name('user.edit_user');
    Route::put('user/{id}/change_password', 'UserController@save_password')->name('user.change_password');
    Route::post('user/{id}/permissions', 'UserController@permissions_save')->name('user.permissions_save');
    Route::post('user/{id}/roles', 'UserController@roles_save')->name('user.roles_save');
    Route::get('user/{user}', 'UserController@remove_user')->name('user.remove');
    Route::delete('user/{user}/status', 'UserController@status_user')->name('user.delete');
    //ACEs Routes
    Route::get('aces', 'AcesController@index')->name('aces');
    Route::post('ace/create', 'AcesController@create')->name('aces.create');
    Route::post('ace/{id}/add-courses','AcesController@add_courses')->name('ace.add_courses');
    Route::get('ace/{id}/delete-course/{course}','AcesController@delete_course')->name('ace.delete_course');

    Route::get('aces/json-view', 'AcesController@edit_view')->name('ace.edit-view');

    Route::get('ace/update', 'AcesController@update_ace')->name('ace.update');
    Route::get('aces/{id}', 'AcesController@ace_page')->name('aces.profile');
    Route::get('ace/{id}/baselines', 'AcesController@baselines')->name('ace.baselines');
    Route::get('ace/{id}/indicator_one', 'AcesController@indicator_one')->name('ace.indicator_one');
    Route::get('ace/{id}/targets/{year_id?}', 'AcesController@target_values')->name('ace.targets');
    Route::post('ace/{id}/baselines/save', 'AcesController@baselines_save')->name('ace.baselines.save');
    Route::post('ace/{id}/targets/save/{year_id?}', 'AcesController@targets_save')->name('ace.targets.save');
    Route::post('ace/{id}/indicator_one/save', 'AcesController@indicator_one_save')->name('ace.indicator_one.save');

    //Institutions Routes
    Route::get('institutions', 'InstitutionsController@index')->name('institutions');
    Route::post('institutions/create', 'InstitutionsController@create')->name('institutions.create');
    Route::get('institution/edit', 'InstitutionsController@edit')->name('institution.edit');
    Route::post('institution/update', 'InstitutionsController@update')->name('institution.update');
    Route::get('institution/delete/{id}', 'InstitutionsController@delete_institution')->name('institution.delete');

    //Permissions Routes
    Route::get('permissions', 'PermissionsController@index')->name('permissions');
    Route::post('permissions/create', 'PermissionsController@create')->name('permissions.create');
    Route::get('permissions/edit', 'PermissionsController@edit')->name('permissions.edit');
    Route::put('permissions/update', 'PermissionsController@update')->name('permissions.update');

    //Roles Routes
    Route::get('roles', 'RolesController@index')->name('roles');
    Route::post('roles/create', 'RolesController@create')->name('roles.create');
    Route::get('roles/edit', 'RolesController@edit')->name('roles.edit');
    Route::get('roles/newFormView', 'RolesController@emptyForm')->name('roles.emptyForm');
    Route::put('roles/update', 'RolesController@update')->name('roles.update');

    //Groups Route
    Route::get('groups', 'GroupsController@index')->name('groups');
    Route::post('groups/create', 'GroupsController@create')->name('groups.create');


    //Contacts (ACE) Route
    Route::post('mailinglist/save', 'ContactsController@save_mailing_list')->name('mailinglist.save');
    Route::get('mailinglist/edit_mailinglist', 'ContactsController@edit_mailinglist')->name('mailinglist.edit');
    Route::post('mailinglist/update/{id}', 'ContactsController@update_mailinglist')->name('mailinglist.update');
    Route::get('mailinglist/delete/{id}', 'ContactsController@destroy_mailinglist')->name('mailinglist.delete');

//    central contacts

    Route::get('contacts', 'ContactsController@index')->name('contacts');
    Route::get('contacts/edit_view', 'ContactsController@edit_view')->name('contacts.edit_view');
    Route::post('contacts/save', 'ContactsController@save_contact')->name('contacts.save');
    Route::post('contacts/update/{id}', 'ContactsController@update_contact')->name('contacts.update');


});

//Settings Routes

Route::prefix('settings')->name('settings.')->group(function () {

//DLR Indicators
    Route::get('dlr_indicators', 'DlrIndicatorController@indicators')->name('dlr_indicators');
    Route::post('dlr_indicators/{ace_id}/cost/save', 'DlrIndicatorController@save_dlr_costs')->name('save_dlr_indicators_cost');
    Route::post('dlr_indicator_save', 'DlrIndicatorController@save_indicator')->name('dlr_indicator.save');

//DLR Indicator edit and update Routes
    Route::get('dlr_indicator/json/edit', 'DlrIndicatorController@edit_indicator')->name('dlr_indicator.edit');
    Route::post('dlr_indicator/update', 'DlrIndicatorController@update_indicator')->name('dlr_indicator.update');
    Route::delete('dlr_indicator/activate', 'DlrIndicatorController@activate_indicator')->name('dlr_indicator.activate');
    Route::delete('sub-dlr_indicator/activate', 'DlrIndicatorController@activate_sub_indicator')->name('dlr_sub_indicator.activate');

//DLR Sub-Indicator edit and update Routes
    Route::get('dlr_indicator/{id}/configure', 'DlrIndicatorController@config_indicator')->name('dlr_indicator.config');
    Route::post('dlr_indicator/{id}/sub-indicator/add', 'DlrIndicatorController@save_sub_indicator')->name('dlr_sub_indicator.save');
//    Route::post('indicator/{id}/unit-measure/add','DlrIndicatorController@save_unit_measure')->name('dlr_indicator.unit_measure.save');
    Route::get('sub-dlr_indicator/json/edit', 'DlrIndicatorController@edit_sub_indicator')->name('dlr_sub_indicator.edit');
    Route::post('sub-dlr_indicator/update', 'DlrIndicatorController@update_sub_indicator')->name('dlr_sub_indicator.update');

    //Indicator template upload
    Route::get('excelupload', 'ExcelUploadController@index')->name('excelupload.create');
    Route::post('excelupload/save', 'ExcelUploadController@save')->name('excelupload.save');
    Route::get('/excelupload/download/{id}', 'ExcelUploadController@download')->name('excelupload.download');
    Route::get('/excelupload/download-all','ExcelUploadController@downloadAll')->name('excelupload.download_all');
    Route::get('excelupload/delete/{id}', 'ExcelUploadController@delete')->name('excelupload.delete');

    //System Application Route
    Route::get('application', 'ApplicationSettingsController@index')->name('app_settings');
    Route::post('application/change-name', 'ApplicationSettingsController@setName')->name('app_settings.save_name');
    Route::post('application/change-email', 'ApplicationSettingsController@setEmail')->name('app_settings.save_email');
    Route::post('application/change-deadline', 'ApplicationSettingsController@setDeadline')->name('app_settings.save_deadline');
    Route::post('application/generation_status', 'ApplicationSettingsController@generation_status')->name('app_settings.save_generation_status');
    Route::get('application/change-deadline-status', 'ApplicationSettingsController@changeDeadlineStatus')->name('app_settings.change_deadline_status');

    //reporting period

    Route::post('application/reporting-period', 'ApplicationSettingsController@saveReportingPeriod')->name('app_settings.save_reporting_period');
    Route::get('reporting-period/delete/{id}', 'ApplicationSettingsController@deleteReportingPeriod')->name('app_settings.delete_reporting_period');
    Route::get('reporting-period/edit-view', 'ApplicationSettingsController@editReportingPeriod')->name('app_settings.edit_reporting_period');
    Route::post('reporting-period/update', 'ApplicationSettingsController@updateReportingPeriod')->name('app_settings.update_reporting_period');



    Route::get('projects', 'SettingsController@projects')->name('projects');
    Route::post('projects/save', 'SettingsController@save_project')->name('projects.save');
    Route::get('projects/{id}/edit_project', 'SettingsController@edit_project')->name('projects.edit');

    Route::get('settings/projects', 'SettingsController@projects')->name('settings.projects');
    Route::post('settings/projects/save', 'SettingsController@save_project')->name('projects.save');
    Route::post('settings/project/update/{id}', 'SettingsController@update_project')->name('projects.update');

    Route::get('settings/projects/view', 'SettingsController@view_project')->name('projects.view');

    //Indicator Form Creator
    Route::get('created-forms', 'IndicatorsGeneratorController@index')->name('indicator.generated_forms');
    Route::get('create-form/create', 'IndicatorsGeneratorController@create')->name('indicator.generate_form.create');
    Route::post('create-form/save', 'IndicatorsGeneratorController@save')->name('indicator.generate_form.save');
    Route::get('create-form/edit/{id}', 'IndicatorsGeneratorController@edit')->name('indicator.generate_form.edit');
    Route::patch('create-form/update/{id}', 'IndicatorsGeneratorController@update')->name('indicator.generate_form.update');

    //Courses
    Route::get('courses', 'SettingsController@courses')->name('courses');
    Route::get('course_edit', 'SettingsController@edit_course_view')->name('course_edit_view');
    Route::post('course/add', 'SettingsController@add_course')->name('course.add');
    Route::post('course/update', 'SettingsController@update_course')->name('course.update');

    Route::get('commentnotification', 'SettingsController@comment_notification')->name('commentnotification');
    Route::post('commentnotification/save', 'SettingsController@save_comment_notification')->name('commentnotification.save');
    Route::get('commentnotification/{id}/edit_commentnotification', 'SettingsController@edit_commentnotification')->name('commentnotification.edit');
    Route::post('commentnotification/update/{id}', 'SettingsController@update_commentnotification')->name('commentnotification.update');

//    Route::get('mailinglist', 'SettingsController@mailing_list')->name('mailinglist');
//    Route::post('mailinglist/save', 'SettingsController@save_mailing_list')->name('mailinglist.save');
//    Route::get('mailinglist/{id}/edit_mailinglist', 'SettingsController@edit_mailinglist')->name('mailinglist.edit');
//    Route::post('mailinglist/update/{id}', 'SettingsController@update_mailinglist')->name('mailinglist.update');
//    Route::get('mailinglist/delete/{id}', 'SettingsController@destroy_mailinglist')->name('mailinglist.delete');
});

//Indicators Routes

Route::prefix('indicators')->group(function () {

    Route::get('/', 'SettingsController@indicators')->name('indicators');
    Route::post('save', 'SettingsController@save_indicator')->name('indicators.save');

    Route::get('/getIndicatorFields', 'UploadIndicatorsController@getFields')->name('getIndicatorFields');
});

//Indicator edit and update Routes
Route::get('indicator/json/edit', 'SettingsController@edit_indicator')->name('indicator.edit');
Route::post('indicator/update', 'SettingsController@update_indicator')->name('indicator.update');
Route::delete('indicator/activate', 'SettingsController@activate_indicator')->name('indicator.activate');

Route::get('indicator/{id}/configure', 'SettingsController@config_indicator')->name('indicator.config');
Route::post('indicator/{id}/sub-indicator/add', 'SettingsController@save_sub_indicator')->name('indicator.sub_indicator.save');
Route::post('indicator/{id}/unit-measure/add', 'SettingsController@save_unit_measure')->name('sub_indicator.unit_measure.save');

//Sub-Indicator edit and update Routes
Route::get('sub-indicator/json/edit', 'SettingsController@edit_sub_indicator')->name('sub_indicator.edit');
Route::post('sub-indicator/update', 'SettingsController@update_sub_indicator')->name('sub_indicator.update');

//Unit of Measure edit and update Routes
Route::get('sub-indicator/unit-measure/json/edit', 'SettingsController@edit_unit_measure')->name('sub_indicator.unit_measure.edit');
Route::post('sub-indicator/unit-measure/update', 'SettingsController@update_unit_measure')->name('sub_indicator.unit_measure.update');

Route::get('sub-indicators', 'SettingsController@ace_level_indicators')->name('ace_level_indicators');
Route::get('sub-indicator/{id}', 'SettingsController@ace_level_indicators_details')->name('ace_level_indicators.details');
Route::post('sub-indicator/child/unit-measure', 'SettingsController@save_unit_measures')->name('unit_measure.save');
Route::post('sub-indicator/child', 'SettingsController@save_specific')->name('specific.save');
Route::post('ace-level-indicators/specific', 'SettingsController@save_specific')->name('sub_indicator.save');
//Report Form Submission Routes

Route::name('report_generation.')->group(function () {
    Route::prefix('report-generation')->group(function () {

        Route::get('general', 'GenerateReportController@general_report_page')->name('general');
        Route::post('general/generate', 'GenerateReportController@general_report')->name('general_report');

        Route::get('general/generate-table', 'GenerateReportController@general_report_table')->name('general_report_table');

// 		Route::get('general/generate-table/export', 'GenerateReportController@generalspreadsheetexport
        // ')->name('general_report_table.generalspreadsheetexport');

///////

        // Route::get('general/generate-excel', 'GenerateReportController@general_summary_excel')->name('general_report_excel');

        Route::get('milestone/create', 'milestoneController@create')->name('milestone.create');

        Route::post('milestone/save', 'milestoneController@save')->name('milestone.save');

        //Verification Indicators Status

        Route::get('verification/indicator-status', 'GenerateReportController@indicator_verification')->name('indicator_status');

        Route::get('verification/indicator-status-report', 'GenerateReportController@indicator_verification_report')->name('indicator_status_report');

        Route::get('verificationletter/generate', 'VerificationLetterController@gen')->name('verificationletter.generate');

        Route::get('verificationletter/report/{id}', 'VerificationLetterController@report_verify_letter_logs')->name('verificationletter.report');

        Route::post('verificationletter/report/{id}/save', 'VerificationLetterController@report_verify_letter_logs_save')->name('verificationletter.report_save');

        Route::get('verificationletter/{id}/edit', 'VerificationLetterController@edit')->name('verificationletter.edit');

        Route::post('verificationletter/{id}/update', 'VerificationLetterController@update')->name('verificationletter.update');

        Route::get('verificationletter/{id}/delete', 'VerificationLetterController@delete')->name('verificationletter.delete');

        Route::get('verificationletter/create', 'VerificationLetterController@create')->name('verificationletter.create');

        Route::post('verificationletter/save', 'VerificationLetterController@save')->name('verificationletter.save');

        Route::get('verificationletter/list', 'VerificationLetterController@list')->name('verificationletter.list');

        // Route::get('verificationletter/dummylist','VerificationLetterController@dummylist')->name('verificationletter.dummylist');

        Route::get('verificationletter/verification', 'VerificationLetterController@verificationpage')->name('verificationletter.verificationpage');

        // Route::post('verificationletter/dummypage/save', 'VerificationLetterController@dummypagesave')->name('verificationletter.dummypagesave');

        Route::get('verificationletter/verificationpagereport', 'VerificationLetterController@verificationpagereport')->name('verificationletter.verificationpagereport');

        // Route::get('verificationletter/verificationpagereport/export', 'VerificationLetterController@export')->name('verificationletter.verificationpagereport.excelexport');

        // Route::get('verificationletter/verificationpagereport/export', 'VerificationLetterController@')->name('verificationletter.verificationpagereport');
        //report-generation/verification/indicator-status
        //generate-report.verificationletterreport.dummypagereport

        // Route::get('verificationletter/dummypage','VerificationLetterController@dummypage')->name('verificationletter.dummypage');

        // Route::post('verificationletter/dummypage/save','VerificationLetterController@dummypagereport')->name('verificationletter.dummypage.report');
        //report-generation

        //generate-report.milestones.generated-result

        //Milestones Routes
        Route::get('report/{id}/milestones', 'GenerateReportController@milestones')->name('report.milestones');
        Route::get('milestones', 'GenerateReportController@milestones_report_page')->name('generate.milestones');

        Route::get('milestones/generate-result', 'GenerateReportController@generate_milestones_report')->name('generate.milestones.report');

        Route::post('report/{id}/milestones/save', 'GenerateReportController@milestones_save')->name('report.milestones_save');
    });

});



Route::name('report_submission.')->group(function () {

    Route::get('reports-submitted', 'ReportFormController@index')->name('reports');
    Route::get('reports-submitted/delete/{id}', 'ReportFormController@delete')->name('reports.delete');


    Route::prefix('report-submitted')->group(function () {

        Route::get('edit/{id}', 'ReportFormController@edit_report')->name('edit');
        Route::get('view/{id}', 'ReportFormController@view_report')->name('view');
        Route::get('indicators-status/{id}', 'ReportFormController@indicators_status')->name('indicators_status');
        Route::get('set-review-mode/{id}', 'ReportFormController@report_review')->name('report_review_mode');
        Route::post('update-report', 'ReportFormController@update_report')->name('update_report');

        Route::post('report-status/{report_id}', 'ReportFormController@report_status_save')->name('report_status_save');
        Route::post('indicators-status/{report_id}/{id}', 'ReportFormController@indicators_status_save')->name('indicators_status_save');

    });

    Route::prefix('report-submission')->group(function () {

        Route::get('new', 'ReportFormController@add_report')->name('add');
        Route::post('save-report', 'ReportFormController@save_report')->name('save_report');
        Route::post('continue-report', 'ReportFormController@save_continue_report')->name('save_continue_report');

        Route::get('indicators-download', 'UploadIndicatorsController@downloadIndicators')->name('downloadIndicators');
        Route::get('indicator-details/read/{id}', 'UploadIndicatorsController@read')->name('view_indicator_details');
        Route::get('indicator-upload/{report_id}/{indicator?}', 'UploadIndicatorsController@index')->name('upload_indicator');
        Route::post('indicator-upload/save', 'UploadIndicatorsController@excelUpload')->name('save_excel_upload');

    });

});

////FAQs
Route::group(['prefix' => 'setup', 'middleware' => ['ability:administrator|webmaster,setup-faqs']], function() {
    Route::get('/faqs', 'FaqsController@index')->name('faqs');
    Route::get('/faq/new', 'FaqsController@create')->name('faq.new');
    Route::get('/faq/edit/{id}', 'FaqsController@edit')->name('faq.edit');
    Route::post('/faq/save', 'FaqsController@save')->name('faq.save');
    Route::post('/faq/update/{id}', 'FaqsController@update')->name('faq.update');
    Route::delete('/faq/remove/{id}', 'FaqsController@destroy')->name('faq.delete');
});
Route::get('faqs', 'FaqsController@faqs')->name('read.faqs');
Route::get('download/guidelines', 'FaqsController@getDownload')->name('download_guideline');
