<?php

namespace LittleGiant\SilverStripeImagePoints\Forms;

use SilverStripe\Forms\Form;
use SilverStripe\ORM\FieldType\DBHTMLText;
use Psr\Log\InvalidArgumentException;
use SilverStripe\Forms\FormField;
use SilverStripe\Forms\HiddenField;
use SilverStripe\View\Requirements;

/**
 * Class PointField
 * @package LittleGiant\SilverStripeImagePoints\Forms
 */
class PointField extends FormField
{
    /**
     * @var bool
     */
    protected $xPosField = false;

    /**
     * @var bool
     */
    protected $yPosField = false;

    /**
     * @var string
     */
    protected $image = '';

    /**
     * @var int
     */
    protected $width = 0;

    /**
     * @var int
     */
    protected $height = 0;

    /**
     * HotSpotPointField constructor.
     * @param $name
     * @param string $value
     * @param string $image
     * @param int $width
     * @param int $height
     */
    public function __construct($name, $title = null, $value = "", $image = '', $width = 0, $height = 0)
    {

        $this->xPosField = HiddenField::create($name . '[xPos]');
        $this->yPosField = HiddenField::create($name . '[yPos]');

        $this->image = $image;
        $this->width = $width;
        $this->height = $height;

        self::initFrontEndRequirements();

        parent::__construct($name, $title, $value);
    }

    /**
     * @param Form $form
     * @return $this
     */
    public function setForm($form)
    {
        parent::setForm($form);

        $this->xPosField->setForm($form);
        $this->yPosField->setForm($form);

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function Value(): string
    {
        $xPos = $this->xPosField->getValue();
        $yPos = $this->yPosField->getValue();

        return sprintf('%s,%s', $xPos, $yPos);
    }

    /**
     * @param array $properties
     * @return DBHTMLText
     */
    public function FieldHolder($properties = [])
    {
        return parent::FieldHolder($properties);
    }

    /**
     * @desc Add required css/javascript.
     */
    public function initFrontEndRequirements(): void
    {
        Requirements::css('blacksheepcreative/silverstripe-image-points:client/css/image-points.css');
        Requirements::javascript('blacksheepcreative/silverstripe-image-points:client/javascript/image-points.js');
    }

    private function getImageAspectRatio(): string
    {
        return (($this->height / $this->width) * 100) . '%';
    }

    /**
     * @param array $properties
     * @return DBHTMLText|string
     */
    public function Field($properties = [])
    {
        return
            $this->xPosField->FieldHolder() .
            $this->yPosField->FieldHolder() .
            "<div class='l-hot-spot'>" .
            sprintf("<div class='l-hot-spot__item js-hot-spot' style='width:%dpx; background-image: url(%s);'>", $this->width, $this->image) .
            sprintf("<div class='l-hot-spot__padding' style='width:100%%; padding-top: %s'></div>", $this->getImageAspectRatio()) .
            sprintf("<span class='c-hot-spot-point js-hot-spot-point' style='left: %s%%; top: %s%%;'></span>", $this->xPosField->Value(), $this->yPosField->Value()) .
            "</div>" .
            "</div>";
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value, $data = NULL)
    {
        if (is_array($value)) {
            $this->xPosField->setValue($value['xPos'], $data = NULL);
            $this->yPosField->setValue($value['yPos'], $data = NULL);
            $this->value = implode(",", $value);
        } else {
            $values = explode(",", $value);
            if (count($values) > 1) {
                $this->xPosField->setValue($values[0], $data = NULL);
                $this->yPosField->setValue($values[1], $data = NULL);
            }
        }

        return $this;
    }

    public function getXPos(): float
    {
        if (is_array($this->value)) {
            return $this->value['xPos'];
        }

        $values = explode(",", $this->value);
        if (count($values) > 1) {
            return $values[0];
        }

        return 0;
    }

    public function getYPos(): float
    {
        if (is_array($this->value)) {
            return $this->value['xPos'];
        }

        $values = explode(",", $this->value);
        if (count($values) > 1) {
            return $values[1];
        }

        return 0;
    }

    /**
     * @param $field
     */
    public function setXPosField($field): FormField
    {
        $expected = $this->getName() . '[xPos]';
        if ($field->getName() != $expected) {
            throw new InvalidArgumentException(sprintf(
                'Wrong name format for xPos field: "%s" (expected "%s")',
                $field->getName(),
                $expected
            ));
        }

        $field->setForm($this->getForm());
        $this->xPosField = $field;
        $this->setValue($this->value); // update value
    }

    public function getXPosField(): FormField
    {
        return $this->xPosField;
    }

    /**
     * @param FormField
     */
    public function setYPosField($field): void
    {
        $expected = $this->getName() . '[yPos]';
        if ($field->getName() != $expected) {
            throw new InvalidArgumentException(sprintf(
                'Wrong name format for yPos field: "%s" (expected "%s")',
                $field->getName(),
                $expected
            ));
        }

        $field->setForm($this->getForm());
        $this->yPosField = $field;
        $this->setValue($this->value); // update value
    }

    /**
     * @return FormField
     */
    public function getYPosField()
    {
        return $this->yPosField;
    }
}
