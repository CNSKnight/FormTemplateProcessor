<?
/**
* This is the templette used by contact.php in the demo.
* It is used for custom rendering the form markup, and
* also controls async output on ajax'd form requests.
* Requires an FormTemplateProcessorMailer object, in
* this case created in contact.php.
* @note Copy this into your own templates tree before
* cloning or customizing.
* - As this is not a template that yields a complete page, 
* the author places it and others in templates/includes/
* @see demo_files/contact.php
*
* @author Codename: Steeve Knight | cookbook.DharmiWeb.net
* @see contact.php
*/
$ftpm->set('requiredFields', array('sender_name', 'email', 'message'));
$ftpm->set('toName', 'Codename: Steeve Knight'); // optional
$ftpm->set('toAddress', 'valid.address@server.net'); // optional, sends form as email
$ftpm->set('parent', null); // optional, saves form as page

// This example returns the form object for custom rendering
$form = $ftpm->render(true);

if (! $form instanceof InputfieldForm) {
    // we have a stdClass or a string likely (not used in the demo)
    header("Content-Type: application/json");
    echo json_encode($form); 

    return;
} elseif ($config->ajax) {
    // with an async POSTed submit and no errors, just return the mailerMsg
    if ($input->post()->count() && ! count($form->getErrors())) {
        header("Content-Type: application/json");
        echo json_encode(array('mailerMsg' => $form->get('mailerMsg')));

        return;
    }
}

// re-render the form in every other case
$fields = $form->get('children');

/*
    This may be useful:
    foreach($fields as $field) {
        print_r($field->get('attributes'); 
    }
*/
    ?>

    <form id="<?=$form->id;?>" class="contact-form" method="<?=$form->method;?>" action="<?=$form->action;?>">
        <h1>Write to Steeve...</h1>
        <div id="<?$form->id+'_msg';?>" class="msgBloc"><?=$form->get('mailerMsg');?></div>
        <fieldset class="detailsBloc">	

            <?foreach($fields as $field) {
                if (! $field instanceof InputfieldTextarea && ! $field instanceof InputfieldSubmit && $field->name != 'subject') {
                    if(! $field instanceof InputfieldWrapper) {
                        $errors = $field->getErrors(true);
                        foreach($errors as $error) {
                            echo $this->entityEncode($error, true);
                        }
                    }?>
                    <label for="<?$field->id;?>:"><?=$field->label;?>:</label>
                    <input id="<?=$field->id;?>" class="<?=$field->class;?>" type="<?=$field->type;?>" name="<?=$field->name;?>" value="<?=$field->value;?>" placeholder="<?=$field->placeholder;?>" <?=$field->required ? 'required' : '';?>>
                    <?}?>
                    <?}?>

                </fieldset>

                <fieldset class="messageBloc">
                    <?foreach($fields as $field) {
                        if ($field instanceof InputfieldTextarea) {?>
                        <label for="<?=$field->id;?>">Your Message:</label>
                        <?/*maintain this on a single line or your placeholder will be replaced by \n */;?>
                        <textarea id="<?=$field->id;?>" name="<?=$field->name;?>" rows="<?=$field->rows;?>" cols="0" placeholder="<?=$field->placeholder;?>" <?=$field->required ? 'required' : '';?>><?=$field->value;?></textarea> 
                        <?}?>

                        <?if ($field instanceof InputfieldSubmit) {?>
                        <div class="controls"><button  id="<?=$field->id;?>" class="<?=$field->class;?>" type="<?=$field->type;?>" name="<?=$field->name;?>" value="<?=$field->value;?>" ><span class="icon">M</span> <?=$field->label;?></button></div>
                        <?}?>
                    <?}?>
                    </fieldset>

                    <div class="closer"><span class="icon">Â</span></div>
                    <input type="hidden" name="subject" value="Contact via <?=$config->httpHost;?>">
                    <?
                    if($form->protectCSRF && $form->attr('method') == 'post') {
                       $tokenName = wire('session')->CSRF->getTokenName();
                       $tokenValue = wire('session')->CSRF->getTokenValue();
                       echo "<input type='hidden' id='_post_token' name='$tokenName' value='$tokenValue'>";
                   }?>
               </form>	
