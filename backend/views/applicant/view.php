<?php

$this->title = 'View Profile';

echo $this->render('/common/applicant/view.php', [
    'guid' => $guid,
    'applicantModel' => $applicantModel,
    'applicantPostModel' => $applicantPostModel,
    'applicantDetailModel' => $applicantDetailModel,
    'applicantPermanentAddressModel' => $applicantPermanentAddressModel,
    'applicantCorrespondenceAddressModel' => $applicantCorrespondenceAddressModel,
    'applicantQualificationModel' => $applicantQualificationModel,
    'applicantEmploymentModel' => $applicantEmploymentModel,
    'applicantDocumentModel' => $applicantDocumentModel,
    'title' => $this->title
]);
?>

