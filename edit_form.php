<?php
 
class block_tokenlink_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
 
        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
 
        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_tokenlink'));
        $mform->setDefault('config_title', 'Token link');
        $mform->setType('config_title', PARAM_TEXT);

        // Send token to this url
        $mform->addElement('text', 'config_targeturl', get_string('targeturl', 'block_tokenlink'));
        $mform->setDefault('config_targeturl', '');
        $mform->setType('config_targeturl', PARAM_RAW);
        $mform->addHelpButton('config_targeturl', 'targeturl', 'block_tokenlink');
        
        $mform->addElement('text', 'config_urlimage', get_string('urlimage', 'block_tokenlink'));
        $mform->setDefault('config_urlimage', '');
        $mform->setType('config_urlimage', PARAM_RAW);
        $mform->addHelpButton('config_urlimage', 'urlimage', 'block_tokenlink');

        $mform->addElement('text', 'config_imagewidth', get_string('imagewidth', 'block_tokenlink'));
        $mform->setDefault('config_imagewidth', '100%');
        $mform->setType('config_imagewidth', PARAM_RAW);
        $mform->addHelpButton('config_imagewidth', 'imagewidth', 'block_tokenlink');

        $mform->addElement('text', 'config_imageheight', get_string('imageheight', 'block_tokenlink'));
        $mform->setDefault('config_imageheight', '');
        $mform->setType('config_imageheight', PARAM_RAW);
        $mform->addHelpButton('config_imageheight', 'imageheight', 'block_tokenlink');

        $mform->addElement('text', 'config_webservicename', get_string('webservicename', 'block_tokenlink'));
        $mform->setDefault('config_webservicename', '');
        $mform->setType('config_webservicename', PARAM_RAW);
        $mform->addHelpButton('config_webservicename', 'webservicename', 'block_tokenlink');

        $mform->addElement('text', 'config_nameparamget', get_string('nameparamget', 'block_tokenlink'));
        $mform->setDefault('config_nameparamget', 'data');
        $mform->setType('config_nameparamget', PARAM_RAW);
        $mform->addHelpButton('config_nameparamget', 'nameparamget', 'block_tokenlink');

        $mform->addElement('text', 'config_cipherkey', get_string('cipherkey', 'block_tokenlink'));
        $mform->setDefault('config_cipherkey', '');
        $mform->setType('config_cipherkey', PARAM_RAW);
        $mform->addHelpButton('config_cipherkey', 'cipherkey', 'block_tokenlink');


        $mform->addElement('advcheckbox', 'config_tokencheck', get_string('tokencheck', 'block_tokenlink'), null, array('group' => 1));
        $mform->setDefault('config_tokencheck', '1');
        $mform->addElement('advcheckbox', 'config_usernamecheck', get_string('usernamecheck', 'block_tokenlink'), null, array('group' => 1));
        $mform->setDefault('config_usernamecheck', '1');
        $mform->addElement('advcheckbox', 'config_emailcheck', get_string('emailcheck', 'block_tokenlink'), null, array('group' => 1));
        $mform->setDefault('config_emailcheck', '0');


    }
}
