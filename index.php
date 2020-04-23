<?php

abstract class GrandFather
{
    public $body = 'Нормальное';

    private $nose = 'Кривой';

    public function eat($calories)
    {
        if ($calories > 500) {
            $this->body = 'Толстое';

        } else {
            $this->body = 'Худое';
        }
    }

    protected function showGrandFatherNose()
    {
        return $this->nose;
    }
}

class Father extends GrandFather
{
    protected $hair = 'Русые';

    public function showGrandFatherNose()
    {
        $nose = parent::showGrandFatherNose();
        $nose .= ' не очень';
        echo $nose;
    }

    public function reColorHair($color)
    {
        $this->hair = $color;
    }

    public function getHair()
    {
        return $this->hair;
    }
}

$masha = new Father();
$masha->reColorHair('Белые');
echo $masha->getHair() . '<br>';

$ivan = new Father();
$ivan->showGrandFatherNose();
