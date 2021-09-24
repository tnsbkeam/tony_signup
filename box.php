<?php
    class box
    {
        //成員
        public $color;
        public $size = 100;
        //建構屬性(預設值)
        public function __construct($size, $color = 'blue')
        {
            $this->size = $size;
            $this->color = $color;
        }
        //成員方法(呈現結果)
        public function render()
        {
            return "<p style='width: {$this->size}px; height: {$this->size}px; background: {$this->color};'></p>";
        }
    }

    $box1 = new box(80, 'red');
    echo $box1->render();

    $box2 = new box(120);
    echo $box2->render();

    $box3 = new box(200,'yellow');
    echo $box3->render();