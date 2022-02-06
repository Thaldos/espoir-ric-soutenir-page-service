<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

const EMAIL_FROM = 'noreply@domain.com';
const EMAIL_SUBJECT = 'Participer à Espoir RIC 2022';
const EMAIL_HEADERS = 'De :' . EMAIL_FROM;

// Matching between possibles activity and template to be used in email response :
const TEMPLATES_BY_ACTIVITY = [
    'rechercher des parrainages' => 'sponsorships.html',
    'contacter les médias' => 'media.html',
    'former des partenariats (avec d\'autres candidats, avec des groupes et association qui bénéficieraient du RIC)' => 'partners.html',
    'aider à la diffusion sur les réseaux sociaux' => 'socialnetworks.html',
    'concevoir des communications (monter des vidéos, rédiger du contenu...)' => 'communications.html',
    'je ne sais pas (pour l\'instant)' => 'idontknow.html',
];

// Get params :
$activities = $_POST['activity'] ?? '';
$skills = $_POST['skills'] ?? '';
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$zipcode = $_POST['zipcode'] ?? '';
$discover = $_POST['discover'] ?? '';
$message = $_POST['message'] ?? '';
$issearcher = $_POST['issearcher'] ?? '';

// Get templates for given activity :
$templateForActivities = '';
foreach (TEMPLATES_BY_ACTIVITY as $activity => $template) {
    if (strpos($activities, $activity) !== false && file_exists('templates/' . $template)) {
        $templateForActivities .= file_get_contents('templates/' . $template) . '<br>';
    }
}

// Get email content :
$html = file_get_contents('templates/email.html');

// Replace tokens by values :
$html = str_replace('{username}', $username, $html);
$html = str_replace('{activity}', $activities, $html);
$html = str_replace('{templates-for-activities}', $templateForActivities, $html);

// Send response by email :
if (!empty($email) && !empty($html)) {
    mail($email, EMAIL_SUBJECT, $html, EMAIL_HEADERS);
}
