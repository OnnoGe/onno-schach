<?php
class Player
{
    // Properties
    public string $name;
    public string $color;

    // Methods
    function __construct($name = "", $color = "")
    {
        $this->name = $name;
        $this->color = $color;
    }

    function set_name(string $name)
    {
        $this->name = $name;
    }

    function set_color(string $color)
    {
        $this->color = $color;
    }

    function get_name(): string
    {
        return $this->name;
    }

    function get_color(): string
    {
        return $this->color;
    }
}
?>