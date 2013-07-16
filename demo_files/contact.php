<?
/**
 * Actual /contact/ page template
 * Makes use of the FormTemplateProcessorMailer module for 
 * static contact pages and async'd overlays on other pages
 */

$ftpm = $modules->get('FormTemplateProcessorMailer');
$ftpm->set('formAction', $page->url);
$ftpm->set('template', $templates->get('contact-form')); // required
$ftpm->set('skipFields', array('pageTeaser', 'pageSubTeaser'));

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
* 
<link rel="stylesheet" href="/ProcessWire/site/templates/styles/contact-form.css">

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
</script><script type="text/javascript" src="/ProcessWire/site/templates/scripts/contact-form.js"></script>

* And for the overlay launcher, work something like this into your navigation scheme:

<?
    $contact = $pages->get('/contact');
    echo "<li><a class=\"contact overlay\" href='{$contact->url}'>".strtoupper($contact->title).'</a></li>';
?>

* The javascript looks for a.contact.overlay, so that bit will be important unless you change the
* selector in the script.
*/

