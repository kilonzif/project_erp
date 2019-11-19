<div class="table-responsive p-10">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Disbursement-linked Indicators <br>(DLIs)</th>
            <th>{{$start_period." - ".$end_period}} <br>Actual Results</th>
            <th>Project Cumulative Results</th>
            <th>End Target PAD</th>
            <th>Status of Project End Targets</th>
        </tr>
        </thead>
        <tbody>
        @php
            $result="ACHIEVED";
            if($target_values_total_students > $cum_total_students){
                $res=$cum_total_students/$target_values_total_students*100;
                $result=round($res,2).'%';
            }
            $result2="ACHIEVED";
             if($accreditation_target > $cum_total_accreditation){
                $res2=$cum_total_accreditation/$accreditation_target*100;
                $result2=round($res2).'%';
            }
             $result3="ACHIEVED";
             if($internship_target > $cum_total_internships){
                $res3=$cum_total_internships/$internship_target*100;
                $result3=round($res3,2).'%';
            }
             $result4="ACHIEVED";
             if($external_revenue_target > $cum_external_revenue){
                $res4=$cum_external_revenue/$external_revenue_target*100;
                $result4=round($res4,2).'%';
            }
            $result1="ACHIEVED";
            if($regional_students_target > $cum_regional_students){
                    $res1=$cum_regional_students/$regional_students_target*100;
                    $result1=round($res1,2).'%';
                }

        @endphp

            <tr>
                <td colspan="5" class="text-center"><strong>PROJECT DEVELOPMENT OBJECTIVES (PDO)</strong></td>
            </tr>

            <tr>
                <td><strong>Total Students</strong></td>
                <td>{{$total_students}}</td>
                <td>{{$cum_total_students}}</td>
                <td>{{$target_values_total_students}}</td>
                <td>{{$result}}</td>
            </tr>
            <tr>
                <td><strong>Regional Students</strong></td>
                <td>{{$regional_students}}</td>
                <td>{{$cum_regional_students}}</td>
                <td>{{$regional_students_target}}</td>
                <td>{{$result1}}</td>
            </tr>
            <tr>
                <td><strong>Accreditation</strong></td>
                <td>{{$total_accreditation}}</td>
                <td>{{$cum_total_accreditation}}</td>
                <td>{{$accreditation_target}}</td>
                <td>{{$result2}}</td>
            </tr>
            <tr>
                <td><strong>Internships</strong></td>
                <td>{{$total_internships}}</td>
                <td>{{$cum_total_internships}}</td>
                <td>{{$internship_target}}</td>
                <td>{{$result3}}</td>
            </tr>
            <tr>
                <td><strong>External Revenue</strong></td>
                <td>{{$external_revenue}}</td>
                <td>{{$cum_external_revenue}}</td>
                <td>{{$external_revenue_target}}</td>
                <td>{{$result4}}</td>
            </tr>
            <tr>
                <td colspan="5" class="text-center"><strong>INTERMEDIATE RESULTS</strong></td>
            </tr>
            <tr>
                <td><strong>Faculty Trained</strong></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>New/Revised Curricula</strong></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>Research Publications</strong></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>New Partnerships</strong></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>Project Meetings</strong></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>