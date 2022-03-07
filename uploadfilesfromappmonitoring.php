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

    $imagePattern = '{<Image>([^<]+)<\/Image>}';
    if (preg_match($imagePattern, $xml, $matches)) {
        $image = $matches[1];
    }
    $pattern = '{<start>([^<]+)<\/start><end>([^<]+)<\/end>.*?<Date_of_Report>([^<]+)<\/Date_of_Report><Disability>([^<]+)<\/Disability>.*?<District>([^"]+)<\/District><School>([^<]+)<\/School>.*?Name>([^"]+)<\/Name><Designation>([^<]+)<\/Designation><Purpose_Of_Visit>([^"]+)<\/Purpose_Of_Visit><Whom_do_you_meet>([^<]+)<\/Whom_do_you_meet>.*?<Age_Group>([^<]+)<\/Age_Group><Chapter>([^"]+)<\/Chapter>.*?<Section>([^"]+)<\/Section><Modality_Used>([^"]+)<\/Modality_Used>.*?<Evaluators_Observation>([^"]+)<\/Evaluators_Observation><Follow_up_Actions>([^"]+)<\/Follow_up_Actions>.*?<instanceID>([^"]+)<\/instanceID>}';
    $pattern1 = '{<start>([^<]+)<\/start><end>([^<]+)<\/end>.*?<Date_of_Report>([^<]+)<\/Date_of_Report><Disability>([^<]+)<\/Disability>.*?<District>([^"]+)<\/District><School>([^<]+)<\/School>.*?Name>([^"]+)<\/Name><Designation>([^<]+)<\/Designation><Purpose_Of_Visit>([^"]+)<\/Purpose_Of_Visit><Whom_do_you_meet>([^<]+)<\/Whom_do_you_meet>.*?<Class_Age_Group>([^<]+)<\/Class_Age_Group><Chapter>([^"]+)<\/Chapter>.*?<Section>([^"]+)<\/Section><Modality_Used>([^"]+)<\/Modality_Used>.*?<Evaluators_Observation>([^"]+)<\/Evaluators_Observation><Follow_up_Actions>([^"]+)<\/Follow_up_Actions>.*?<instanceID>([^"]+)<\/instanceID>}';
    if (preg_match($pattern, $xml, $matches)) {
        if($image != ""){
            $image = $matches[17]."_".$image;
        }
        $instance_id= $matches[17];
        $sql = "INSERT INTO monitoring_data (start_date, end_date, date_of_report, disability, district, school, name, designation, purpose_of_visit, whom_do_you_meet, evaluators_observation, follow_up_actions,image, meta_instanceid)
    VALUES ('$matches[1]','$matches[2]','$matches[3]','$matches[4]','$matches[5]','$matches[6]','$matches[7]','$matches[8]','$matches[9]','$matches[10]','$matches[15]','$matches[16]','$image','$matches[17]')";
        
        
        
        if ($conn->query($sql) === TRUE) {
            $patternGroup = '{<Age_Group>([^<]+)<\/Age_Group><Chapter>([^<]+)<\/Chapter>.*?<Section>([^<]+)<\/Section><Modality_Used>([^<]+)<\/Modality_Used>}';
            if (preg_match_all($patternGroup, $xml, $groupmatches, PREG_SET_ORDER)) {
                foreach ($groupmatches as $groupmatch) {
                     $groupSql = "INSERT INTO monitoring_group_data (fk_meta_instance_id, age_group, chapter, section, modality_used)
                        VALUES ('$instance_id','$groupmatch[1]','$groupmatch[2]','$groupmatch[3]','$groupmatch[4]')";
                        $conn->query($groupSql);
                }
            }
       
            echo $image;
        }
        else {
            echo "Fail";
        }
    }else {
         if (preg_match($pattern1, $xml, $matches)) {
            if($image != ""){
                $image = $matches[17]."_".$image;
            }
            $instance_id= $matches[17];
            $sql = "INSERT INTO monitoring_data (start_date, end_date, date_of_report, disability, district, school, name, designation, purpose_of_visit, whom_do_you_meet, evaluators_observation, follow_up_actions,image, meta_instanceid)
        VALUES ('$matches[1]','$matches[2]','$matches[3]','$matches[4]','$matches[5]','$matches[6]','$matches[7]','$matches[8]','$matches[9]','$matches[10]','$matches[15]','$matches[16]','$image','$matches[17]')";
        
            if ($conn->query($sql) === TRUE) {
                $patternGroup = '{<Class_Age_Group>([^<]+)<\/Class_Age_Group><Chapter>([^<]+)<\/Chapter>.*?<Section>([^<]+)<\/Section><Modality_Used>([^<]+)<\/Modality_Used>}';
                if (preg_match_all($patternGroup, $xml, $groupmatches, PREG_SET_ORDER)) {
                    foreach ($groupmatches as $groupmatch) {
                         $groupSql = "INSERT INTO monitoring_group_data (fk_meta_instance_id, age_group, chapter, section, modality_used)
                            VALUES ('$instance_id','$groupmatch[1]','$groupmatch[2]','$groupmatch[3]','$groupmatch[4]')";
                            $conn->query($groupSql);
                    }
                }
                echo $image;
            }
            else {
                echo "Fail";
            }
         }
    }
}else{
    echo "Fail";    
} 




?>