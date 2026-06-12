<?php
#include ("schach_player.php");
class Piece
{

    private Player $corresponding_player;
    private string $type;
    private string $color;
    private int $number;
    private int $position_x;
    private int $position_y;
    private int $status;
    private array $moves = [
        [false, false, false, false, false, false, false, false],
        [false, false, false, false, false, false, false, false],
        [false, false, false, false, false, false, false, false],
        [false, false, false, false, false, false, false, false],
        [false, false, false, false, false, false, false, false],
        [false, false, false, false, false, false, false, false],
        [false, false, false, false, false, false, false, false],
        [false, false, false, false, false, false, false, false]
    ];



    function __construct($type, $color, $number, $x = 0, $y = 0, $status = 1)
    {
        $this->type = $type;
        $this->color = $color;
        $this->number = $number;
        $this->position_x = $x;
        $this->position_y = $y;
        $this->status = $status;
    }


    function get_moves()
    {
        return $this->moves;
    }

    function reset_moves()
    {
        $this->moves = [
            [false, false, false, false, false, false, false, false],
            [false, false, false, false, false, false, false, false],
            [false, false, false, false, false, false, false, false],
            [false, false, false, false, false, false, false, false],
            [false, false, false, false, false, false, false, false],
            [false, false, false, false, false, false, false, false],
            [false, false, false, false, false, false, false, false],
            [false, false, false, false, false, false, false, false]
        ];
    }

    function set_corresponding_player(Player $corresponding_player)
    {
        $this->corresponding_player = $corresponding_player;
    }

    function set_type(string $type)
    {
        $this->type = $type;
    }
    function set_color(string $color)
    {
        $this->color = $color;
    }

    function set_number(int $number)
    {
        $this->number = $number;
    }

    function set_position_x(int $position_x)
    {
        $this->position_x = $position_x;
    }

    function set_position_y(int $position_y)
    {
        $this->position_y = $position_y;
    }

    function set_status(int $status)
    {
        $this->status = $status;
    }

    function set_moves_array(array $moves)
    {
        $this->moves = $moves;
    }

    function set_move(int $x, int $y){
        $this->moves[$x][$y] = true;
    }

    function get_corresponding_player(): Player
    {
        return $this->corresponding_player;
    }

    function get_type(): string
    {
        return $this->type;
    }

    function get_name(): string
    {
        return $this->get_type().$this->get_color().$this->get_number();
    }

    function get_color(): string
    {
        return $this->color;
    }

    function get_number(): int
    {
        return $this->number;
    }

    function get_position_x(): int
    {
        return $this->position_x;
    }

    function get_position_y(): int
    {
        return $this->position_y;
    }

    function get_status(): int
    {
        return $this->status;
    }

    function get_move(Piece $piece, int $x, int $y){
        return $piece->moves[$x][$y];
    }
}


?>