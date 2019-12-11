<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
@php
    $reporting_period = \App\Http\Controllers\ReportFormController::getReportingName($report->reporting_period_id);
@endphp

<p>Hello,</p>
<p>A report has been submitted under ACE: {{$the_ace->pluck('acronym')}}</p>
<p>Here are the details of the submission</p>
<p>
    ACE: {{$the_ace->pluck('name')}}<br>
    Reporting Period : {{$reporting_period}}
</p>

<br>
<p>Best regards.</p>

</body>
</html>