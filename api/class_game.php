<?php
class Game
{
    private Board $board;
    private Player $player_1;
    private Player $player_2;
    private int $status;
    //0 = Beginn; 1 = Spiel aktiv; 10 = Spielende
    private Player $active_player;
    private array $pieces;

    private $castling = True;
    private Piece $active_piece;

    function set_board(Board $board)
    {
        $this->board = $board;
    }

    function deactive_castling()
    {
        $this->castling = False;
    }

    function get_castling()
    {
        return $this->castling;
    }

    function set_player_1(Player $player_1)
    {
        $this->player_1 = $player_1;
    }

    function set_player_2(Player $player_2)
    {
        $this->player_2 = $player_2;
    }

    function set_status(int $status)
    {
        $this->status = $status;
    }

    function set_pieces(array $pieces)
    {
        $this->pieces = $pieces;
    }

    function set_active_player(Player $active_player)
    {
        $this->active_player = $active_player;
    }

    function set_active_piece(Piece $active_piece){
        $this->active_piece = $active_piece;
    }

    function get_board(): Board
    {
        return $this->board;
    }

    function get_player_1(): Player
    {
        return $this->player_1;
    }

    function get_player_2(): Player
    {
        return $this->player_2;
    }

    function get_status(): int
    {
        return $this->status;
    }

    function get_active_player(): Player
    {
        return $this->active_player;
    }

    function get_active_piece(){
        return $this->active_piece;
    }

    function change_active_player($last_turn)
    {
        $last_color = substr($last_turn, 1, 1);
        if ($last_color == $this->get_player_1()->get_color()) {
            $this->set_active_player($this->get_player_2());
        } else {
            $this->set_active_player($this->get_player_1());
        }
    }
}
?>