<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);
require __DIR__ . '/db.php';
$conn = returnDBCon();
$image = "";
$file_path = "forms/";
$file_path = $file_path . basename( $_FILES['uploaded_file']['name']);
if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {
    $xml = file_get_contents($file_path);

    $pattern = '{<start>([^<]+)<\/start><end>([^<]+)<\/end>.*?<Date_of_Report>([^<]+)<\/Date_of_Report><Disability>([^<]+)<\/Disability>.*?<District>([^"]+)<\/District><School>([^<]+)<\/School>.*?<Name>([^<]+)<\/Name>.*?<Whether_Monthly_Plan_Prepared>([^<]+)<\/Whether_Monthly_Plan_Prepared>.*?<No_of_LSE_topics_completed>([^<]+)<\/No_of_LSE_topics_completed>.*?<No_of_class_room_activities_Conducted>([^<]+)<\/No_of_class_room_activities_Conducted><No_of_Home_assignment_Completed>([^<]+)<\/No_of_Home_assignment_Completed>.*?<Any_Other_Information>([^<]+)<\/Any_Other_Information><Class_room_Image_1>([^<]+)<\/Class_room_Image_1><Class_room_Image_2>([^<]+)<\/Class_room_Image_2>.*?<instanceID>([^<]+)<\/instanceID>}';
    if (preg_match($pattern, $xml, $matches)) {
        
        $instance_id= $matches[15];
        $image1 = $instance_id ."_". $matches[13];
        $image2 = $instance_id ."_". $matches[14];
        $sql = "INSERT INTO teacher_data (start_date, end_date, date_of_report, disability, district, school, name, whether_monthly_plan_prepared, no_of_lse_topics_completed, no_of_class_room_activities_conducted, no_of_home_assignment_completed, any_other_Information,class_room_image_1, class_room_image_2, meta_instanceid)
    VALUES ('$matches[1]','$matches[2]','$matches[3]','$matches[4]','$matches[5]','$matches[6]','$matches[7]','$matches[8]','$matches[9]','$matches[10]','$matches[11]','$matches[12]','$image1', '$image2','$matches[15]')";
        
        
        
        if ($conn->query($sql) === TRUE) {
            $patternGroup = '{(<Age_Group>([^<]+)<\/Age_Group>|<Class_Age_Group>([^<]+)<\/Class_Age_Group>)<Number_of_Periods_Conducted>([^<]+)<\/Number_of_Periods_Conducted><No_of_Boys>([^<]+)<\/No_of_Boys><No_of_Girls>([^<]+)<\/No_of_Girls><Chapter>([^<]+)<\/Chapter>.*?<Section>([^<]+)<\/Section><Modality_Used>([^<]+)<\/Modality_Used>(<If_Other_Modalities_Used>([^<]+)<\/If_Other_Modalities_Used>|([\d]*)).*?<Response_of_Students>([^<]+)<\/Response_of_Students>}';
            if (preg_match_all($patternGroup, $xml, $groupmatches, PREG_SET_ORDER)) {
                foreach ($groupmatches as $groupmatch) {
                    $age_group = "";
                    $is_other_modalities_used = "";
                    if(array_key_exists(2,$groupmatch) && $groupmatch[2] != ""){
                        $age_group = $groupmatch[2];
                    }else{
                        $age_group = $groupmatch[3];
                    }
                    if(array_key_exists(11,$groupmatch)  && $groupmatch[11] != ""){
                        $is_other_modalities_used = $groupmatch[11];
                    }else{
                        $is_other_modalities_used = $groupmatch[12];
                    }
                     $groupSql = "INSERT INTO teacher_group_data (fk_meta_instance_id, age_group, 	number_of_periods_conducted, no_of_boys, no_of_girls, chapter, section, modality_used, 	if_other_modalities_used, response_of_students)
                        VALUES ('$instance_id','$age_group','$groupmatch[4]','$groupmatch[5]','$groupmatch[6]','$groupmatch[7]','$groupmatch[8]','$groupmatch[9]','$is_other_modalities_used','$groupmatch[13]')";
                        $conn->query($groupSql);
                }
            }
       
            echo $image1."+".$image2;
        }
        else {
            echo "Fail";
        }
    }else{
        echo "Fail";
    }
}else{
    echo "Fail";    
} 




?>