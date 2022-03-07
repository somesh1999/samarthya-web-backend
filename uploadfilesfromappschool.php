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

    $pattern = '{<start>([^<]+)<\/start><end>([^<]+)<\/end>.*?<Date_of_Report>([^<]+)<\/Date_of_Report><Disability>([^<]+)<\/Disability>.*?<District>([^"]+)<\/District><School>([^<]+)<\/School>.*?<Pictorial_Presentation>([^<]+)<\/Pictorial_Presentation><Knowledge_Gained>([^<]+)<\/Knowledge_Gained><Understanding_of_the_Topic>([^<]+)<\/Understanding_of_the_Topic><Modality_Used>([^<]+)<\/Modality_Used><Advantages_of_using_ty_specific_Modality>([^<]+)<\/Advantages_of_using_ty_specific_Modality><Challenges>([^<]+)<\/Challenges><Students_Feedback>([^<]+)<\/Students_Feedback><Teachers_Feedback>([^<]+)<\/Teachers_Feedback>.*?<instanceID>([^"]+)<\/instanceID>}';
    if (preg_match($pattern, $xml, $matches)) {
        
        $sql = "INSERT INTO schoolhead_data (start_date, end_date, date_of_report, disability, district, school, pictorial_presentation, knowledge_gained, understanding_of_the_topic, modality_used, 	advantages_of_using_disability_specific_modality, challenges,students_feedback,	teachers_feedback, meta_instanceid)
    VALUES ('$matches[1]','$matches[2]','$matches[3]','$matches[4]','$matches[5]','$matches[6]','$matches[7]','$matches[8]','$matches[9]','$matches[10]','$matches[11]','$matches[12]','$matches[13]','$matches[12]', '$matches[15]')";
    
        if ($conn->query($sql) === TRUE) {
            echo "fail";
        }
        else {
            echo "Fail";
        }
    }
}else{
    echo "Fail";    
} 




?>