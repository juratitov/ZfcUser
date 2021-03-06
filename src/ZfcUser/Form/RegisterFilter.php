<?php

namespace ZfcUser\Form;

use ZfcBase\InputFilter\ProvidesEventsInputFilter;
use ZfcUser\Options\RegistrationOptionsInterface;
use Zend\Validator\ValidatorInterface;

class RegisterFilter extends ProvidesEventsInputFilter
{

    /**
     * @var ValidatorInterface
     */
    protected $emailValidator;

    /**
     * @var ValidatorInterface
     */
    protected $usernameValidator;
   
    /**
     * @var ValidatorInterface
     */
    protected $phoneValidator;
   

    /**
     * @var RegistrationOptionsInterface
     */
    protected $options;

    public function __construct(ValidatorInterface $emailValidator, ValidatorInterface $usernameValidator, ValidatorInterface $phoneValidator, RegistrationOptionsInterface $options)
    {
        $this->setOptions($options);
        $this->emailValidator = $emailValidator;
        $this->usernameValidator = $usernameValidator;
        $this->phoneValidator = $phoneValidator;

        if ($this->getOptions()->getEnableUsername()) {
            $this->add(array(
                'name' => 'username',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'max' => 255,
                        ),
                    ),
                    $this->usernameValidator,
                ),
            ));
        }

        if ($this->getOptions()->getEnableEmail()) {
            $this->add(array(
                'name' => 'email',
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'EmailAddress'
                    ),
                    $this->emailValidator
                ),
            ));
        }
        
        if ($this->getOptions()->getEnablePhone()) {
            $this->add(array(
                'name' => 'phone',
                'required' => true,
                'validators' => array(                    
                    $this->phoneValidator
                ),
            ));
        }

        if ($this->getOptions()->getEnableDisplayName()) {
            $this->add(array(
                'name' => 'display_name',
                'required' => true,
                'filters' => array(array('name' => 'StringTrim')),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'max' => 128,
                        ),
                    ),
                ),
            ));
        }

        $this->add(array(
            'name' => 'password',
            'required' => true,
            'filters' => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 6,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'passwordVerify',
            'required' => true,
            'filters' => array(array('name' => 'StringTrim')),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'min' => 6,
                    ),
                ),
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'password',
                    ),
                ),
            ),
        ));

        $this->getEventManager()->trigger('init', $this);
    }

    public function getEmailValidator()
    {
        return $this->emailValidator;
    }

    public function setEmailValidator($emailValidator)
    {
        $this->emailValidator = $emailValidator;

        return $this;
    }

    public function getUsernameValidator()
    {
        return $this->usernameValidator;
    }

    public function setUsernameValidator($usernameValidator)
    {
        $this->usernameValidator = $usernameValidator;

        return $this;
    }
    
    public function getPhoneValidator()
    {
        return $this->phoneValidator;
    }

    public function setPhoneValidator($phoneValidator)
    {
        $this->phoneValidator = $phoneValidator;

        return $this;
    }

    /**
     * set options
     *
     * @param RegistrationOptionsInterface $options
     */
    public function setOptions(RegistrationOptionsInterface $options)
    {
        $this->options = $options;
    }

    /**
     * get options
     *
     * @return RegistrationOptionsInterface
     */
    public function getOptions()
    {
        return $this->options;
    }

}
