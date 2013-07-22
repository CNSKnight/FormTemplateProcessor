<?
/**
 * Actual /contact/ page template
 * Makes use of the FormTemplateProcessorMailer module for 
 * static contact pages and async'd overlays on other pages
 */

$ftpm = $modules->get('FormTemplateProcessorMailer');
// specify the to-call url especially for async handled overlay usage
$ftpm->set('formAction', $page->url);
// the demo uses PHPMailer
$ftpm->set('usePHPMailer', true);
// the template assignment is required
$ftpm->set('template', $templates->get('contact-form'));
// If you have any additional site-specific, globally required fields that are 
// not related to the form, you will want to include them in skipFields 
# $ftpm->set('skipFields', array('title' ));
// the parent page for saving these child pages to the database (if desired)
// the demo just reuses the static /contact page as the parent but you could use any
// @note our /contact page is set as status: 1025 (Hidden: Excluded from lists and searches) 
# $ftpm->set('parent', $pages->get('/contact'));

if ($config->ajax) {
    include './includes/contact-form.php';
    
    return;
}

ob_start();
?>
<section class="container">
<div>
<? include './includes/contact-form.php';?>
</div>
</section>

<?$page->body .= ob_get_clean();?>

<?php 

include './includes/head.inc';

echo $page->body;

include './includes/foot.inc';

/**
* @note Additionally, position and work these into your head (css) and bodybottom (js) for
* any page that will use the contact-form
* /<pw>/ == your-relative-path-to-processwire
* See http://icons.marekventur.de/ to download the icon font
* 
<link rel="stylesheet" href="/<pw>/site/templates/fonts/raphaelicons.css">
<link rel="stylesheet" href="/<pw>/site/templates/styles/contact-form.css">

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/<pw>/site/templates/scripts/contact-form.js"></script>

* And for the overlay launcher, work something like this into your navigation scheme:

<?
    $contact = $pages->get('/contact');
    echo "<li><a class=\"contact overlay\" href='{$contact->url}'>".strtoupper($contact->title).'</a></li>';
?>

* The javascript looks for a.contact.overlay, so that bit will be important unless you change the
* selector in the script.
*/

