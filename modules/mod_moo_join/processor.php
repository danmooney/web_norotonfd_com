<?php

class MooJoinProcessor
{
    /**
     * @var MooJoinValidator
     */
    private $_validator;

    /**
     * @var MooJoinModel
     */
    private $_model;

    /**
     * @param MooJoinModel $model
     * @param MooJoinValidator $validator
     */
    public function __construct(MooJoinModel $model, MooJoinValidator $validator)
    {
        $this->_validator = $validator;
        $this->_model = $model;
        $this->_validator->validator_map = $model->field_validator_map;
    }

    public function validate()
    {
        return $this->_validator->validate($this->_model->field_value_map);
    }

    public function storeFields()
    {
        require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . '_moo_component_maker' . DS . 'table.php');

        $this->_model->formatFields();

        $table = new MooTable($this->_model->table);
        $table->bind($this->_model->field_value_map);

        $table->store();

        return $this;
    }

    public function sendEmail()
    {
        $params = $this->_model->params;

        $field_values = $this->_model->field_value_map;

        $body  = '<h1>Contact Submission</h1>';
        $body .= '<table cellpadding="0" cellspacing="5" border="0" align="left" width="500">';

        foreach ($field_values as $key => &$value) {
            if (empty($value)) {
                continue;
            }
            $body .= '<tr><td align="left" width="100" valign="top"><strong>' . ucwords(str_replace('_', ' ', $key)) . '</strong></td><td align="left" valign="top">' . $value . '</td></tr>';
        }
        $body .= '</table>';

        $mailer =& JFactory::getMailer();
        $config =& JFactory::getConfig();
        $sender = array(
            $config->getValue('config.mailfrom'),
            $config->getValue('config.fromname')
        );
        $mailer->setSender($sender);

        $mailer->addRecipient($params->get('email_to'));
        $mailer->setSubject($params->get('subject'));
        $mailer->setBody($body);
        $mailer->isHTML(true);
        $mailer->encoding = 'base64';
        $mailer->send();

        return $this;
    }
}