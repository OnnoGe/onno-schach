<?php
class Board
{

    private array $board;
    private array $pieces;


    public function __construct(
    ) {
        for ($x = 0; $x <= 7; $x++) {
            for ($y = 0; $y <= 7; $y++) {
                $this->board[$x][$y] = "emp";
            }
        }
        $this->setup_pieces();
    }

    function setup_pieces()
    {
        //Bauern
        for ($i = 1; $i <= 8; $i++) {
            $pieces["PW" . $i] = new Piece("P", "W", $i, $i-1, 1);
            $pieces["PB" . $i] = new Piece("P", "B", $i, $i-1, 6);
        }
        //Türme
        $pieces["RW1"] = new Piece("R", "W", 1, 0, 0);
        $pieces["RW2"] = new Piece("R", "W", 2, 7, 0);
        $pieces["RB1"] = new Piece("R", "B", 1, 0, 7);
        $pieces["RB2"] = new Piece("R", "B", 2, 7, 7);
        //Springer
        $pieces["NW1"] = new Piece("N", "W", 1, 1, 0);
        $pieces["NW2"] = new Piece("N", "W", 2, 6, 0);
        $pieces["NB1"] = new Piece("N", "B", 1, 1, 7);
        $pieces["NB2"] = new Piece("N", "B", 2, 6, 7);
        //Läufer
        $pieces["BW1"] = new Piece("B", "W", 1, 2, 0);
        $pieces["BW2"] = new Piece("B", "W", 2, 5, 0);
        $pieces["BB1"] = new Piece("B", "B", 1, 2, 7);
        $pieces["BB2"] = new Piece("B", "B", 2, 5, 7);
        //Dame
        $pieces["QW1"] = new Piece("Q", "W", 1, 3, 0);
        $pieces["QB1"] = new Piece("Q", "B", 1, 3, 7);
        //König
        $pieces["KW1"] = new Piece("K", "W", 1, 4, 0);
        $pieces["KB1"] = new Piece("K", "B", 1, 4, 7);

        $this->pieces = $pieces;

        foreach ($pieces as $piece) {
            $this->board[$piece->get_position_x()][$piece->get_position_y()] = $piece;
        }
    }

    function set_board(array $board)
    {
        $this->board = $board;
    }

    function get_board(): array
    {
        return $this->board;
    }

    function get_pieces(): array
    {
        return $this->pieces;
    }

    function show($current_color, $active_piece = False)   
    {
        if($current_color == "W"){
            $y_list = [1, 9, 1];
            $x_list = [0, 8, 1];
            $i_list = [97, 105, 1];
        }else if ($current_color == "B"){
            $y_list = [8, 0, -1];
            $x_list = [7, -1, -1];
            $i_list = [104, 96, -1];
        }else{
            echo "color invalid";
            return;
        }

        echo '<table><tr><td class="border"></td>';
        for ($i = $i_list[0]; $i!=$i_list[1]; $i = $i+$i_list[2]){
            echo '<td class="border">'.strtoupper(chr($i)).'</td>';
        }
        echo '<td class="border"></td></tr>';
        for ($y = $y_list[0]; $y!=$y_list[1]; $y = $y+$y_list[2]){
            echo '<tr><td class="border"><b>'.(9-$y)."</b></td>";
            for ($x = $x_list[0]; $x!=$x_list[1]; $x = $x+$x_list[2]){
                $class = ($x+$y) % 2 == 1 ?"white":"black";                    #schwarzes oder weißes Feld
                if ($this->board[$x][7-($y-1)] == "emp" or 
                    $this->board[$x][7-($y-1)]->get_status()==0)               #steht niemand
                {
                    echo '<td class="'.$class;
                    echo '"><img src="assets/EM.png">';
                }else{                                                         #Steht eine Figur
                    $piece = $this->board[$x][7-($y-1)];
                    echo '<td class="'.$class;
                    if ($piece->get_type() == "K" and $this->check_check($piece)){echo '_check';}
                    echo '">';

                    if ($piece->get_color() == $current_color){

                        if ($active_piece == $piece->get_name()){
                        echo '<a href='. $_SERVER["PHP_SELF"]."?active_piece=".False.'>';}
                        else{echo '<a href='. $_SERVER["PHP_SELF"]."?active_piece=".$piece->get_name().'>';} #

                        echo '<button class="invis_active"><img src="assets/'.$piece->get_type().$piece->get_color().
                        '.png" style="position: relative;">
                        </button></a>';
                    }else{
                        echo '<button class="invis"><img src="assets/'.$piece->get_type().$piece->get_color().
                        '.png" style="position: relative;"></button>';
                    }
                }
                if ($active_piece){
                    $this->green_point($x, $y, $active_piece);

                echo '</td>';

                }
            }
            echo '<td class="border"><b>'.(9-$y).'</b></td>';
            echo "</tr>";
        }
        echo '<tr><td class="border"></td>';
        for ($i = $i_list[0]; $i!=$i_list[1]; $i = $i+$i_list[2]){
            echo '<td class="border">'.strtoupper(chr($i)).'</td>';
        }
        echo '<td class="border"></td></tr>';
        echo "</table>";

         
        
    }
    
    function green_point($x, $y, $active_piece){
        
        if ($this->get_moves($active_piece)[$x][8-$y]){ #kann figur hin
            echo '<a href='. $_SERVER["PHP_SELF"].'?turn='.$active_piece.$x.(8-$y).'><div class="overlay"></div></a>';
        }
    }
    


    function get_moves($piece_name)
    {   
        $piece = $this->get_piece($piece_name);
        $x = $piece->get_position_x();
        $y = $piece->get_position_y();
        $color = $piece->get_color();
        $type = $piece->get_type();
        $piece->reset_moves();

        if ($type == "R" or $type == "Q")
        {
            for ($i = 1; $i <= 7; $i++)
            {
                if ($this->check_square($piece, 0, $i) == "emp"){
                    $piece->set_move($x, $y + $i);
                } else if ($this->check_square($piece, 0, $i) == "opp"){
                    $piece->set_move($x, $y + $i);
                    break;
                } else {
                    break;
                }
            }
            for ($i = 1; $i <= 7; $i++) 
            {
                if ($this->check_square($piece, 0, -$i) == "emp"){
                    $piece->set_move($x, $y - $i);
                } else if ($this->check_square($piece, 0, -$i) == "opp"){
                    $piece->set_move($x, $y - $i);
                    break;
                } else {
                    break;
                }
            }
            for ($i = 1; $i <= 7; $i++) 
            {
                if ($this->check_square($piece, -$i, 0) == "emp"){
                    $piece->set_move($x - $i, $y);
                } else if ($this->check_square($piece, -$i, 0) == "opp"){
                    $piece->set_move($x - $i, $y);
                    break;
                } else {
                    break;
                }
            }
            for ($i = 1; $i <= 7; $i++) 
            {
                if ($this->check_square($piece, $i, 0) == "emp"){
                    $piece->set_move($x + $i, $y);
                } else if ($this->check_square($piece, $i, 0) == "opp"){
                    $piece->set_move($x + $i, $y);
                    break;
                } else {
                    break;
                }
            }
        }
        if ($type == "B" or $type == "Q")
        {
            for ($i = 1; $i <= 7; $i++) 
            {
                if ($this->check_square($piece, $i, $i) == "emp"){
                    $piece->set_move($x + $i, $y + $i);
                } else if ($this->check_square($piece, $i, $i) == "opp"){
                    $piece->set_move($x + $i, $y + $i);
                    break;
                } else {
                    break;
                }
            }
            for ($i = 1; $i <= 7; $i++) 
            {
                if ($this->check_square($piece, $i, -$i) == "emp"){
                    $piece->set_move($x + $i, $y - $i);
                } else if ($this->check_square($piece, $i, -$i) == "opp"){
                    $piece->set_move($x + $i, $y - $i);
                    break;
                } else {
                    break;
                }
            }
            for ($i = 1; $i <= 7; $i++)
            {
                if ($this->check_square($piece, -$i, $i) == "emp"){
                    $piece->set_move($x - $i, $y + $i);
                } else if ($this->check_square($piece, -$i, $i) == "opp"){
                    $piece->set_move($x - $i, $y + $i);
                    break;
                } else {
                    break;
                }
            }
            for ($i = 1; $i <= 7; $i++) 
            {
                if ($this->check_square($piece, -$i, -$i) == "emp"){
                    $piece->set_move($x - $i, $y - $i);
                } else if ($this->check_square($piece, -$i, -$i) == "opp"){
                    $piece->set_move($x - $i, $y - $i);
                    break;
                } else {
                    break;
                }
            }
        }
        else if ($type == "P") 
        {
            $po = 1;
            if ($color == "B") {
                $po *= -1; // pawn_orientation
            }
            
            if ($this->check_square($piece, 0, $po) == "emp"){
                $piece->set_move($x, $y + $po);
                if ($y == 1 and $color == "W" or $y == 6 and $color == "B"){
                    $piece->set_move($x, $y + $po);
                    
                    if ($this->check_square($piece, 0, 2 * $po) == "emp"){
                        $piece->set_move($x, $y + 2 * $po);
                    }
                }
            }
            for ($i = -1; $i <= 1; $i += 2){ // einmal mit 1, einmal mit -1
                if ($this->check_square($piece, $i, $po) == "opp"){
                    $piece->set_move($x + $i, $y + $po);
                }
            }
            
        }
        else if ($type == "N") 
        {        
            $x_change_list = [2, 2, 1, 1, -1, -1, -2, -2];
            $y_change_list = [1, -1, 2, -2, -2, 2, -1, 1];
            for ($i = 0; $i <= 7; $i++){
                $x_change = $x_change_list[$i];
                $y_change = $y_change_list[$i];
                if ($this->check_square($piece, $x_change, $y_change) == "emp" or
                    $this->check_square($piece, $x_change, $y_change) == "opp"){
                        $piece->set_move($x + $x_change, $y + $y_change);
                }
            }
        }
        else if ($type == "K") 
        {        
            $x_change_list = [1, 1, 1, 0, -1, -1, -1, 0];
            $y_change_list = [-1, 0, 1, 1, 1, 0, -1, -1];
            for ($i = 0; $i <= 7; $i++){
                if ($this->check_square($piece, $x_change_list[$i], $y_change_list[$i]) == "emp" or
                    $this->check_square($piece, $x_change_list[$i], $y_change_list[$i]) == "opp"){
                        $piece->set_move($x + $x_change_list[$i], $y + $y_change_list[$i]);
                }
            }
        }

        return $piece->get_moves();
    }
//Wer das liest ist doof

    function update_board()
    {
        $this->clear_board();
        foreach ($this->pieces as $piece){
            $this->board[$piece->get_position_x()][$piece->get_position_y()] = $piece;
        }
        
    }

    function clear_board()
    {
        for ($x = 0; $x <= 7; $x++) 
        {
            for ($y = 0; $y <= 7; $y++) 
            {
                $this->board[$x][$y] = "emp";
            }
        }
    }

    

    function check_square($piece, $x_change, $y_change) 
    {
        $x = $piece->get_position_x();
        $y = $piece->get_position_y();
        $color = $piece->get_color();
        if (0<=$x+$x_change and $x+$x_change<=7 and 0<=$y+$y_change and $y+$y_change<=7){
            if($this->board[$x+$x_change][$y+$y_change] == "emp"){
                return "emp";
            } else if($this->board[$x+$x_change][$y+$y_change]->get_color() == $color){
                return "mate";
            } else {
                return "opp";
            }
        } else {
            return "outside";
        }
    }


    
    function get_piece(string $piece): mixed
    {
        return $this->pieces[$piece];
    }



    function move_piece_and_check_win(Piece $piece, int $x, int $y) 
    {
        if ($this->board[$x][$y] instanceof Piece){
            $loser = $this->board[$x][$y]->get_type() == "K" ? $this->board[$x][$y]->get_color() : False;
            $this->board[$x][$y]->set_position_x(-1);
        }
        $this->get_piece($piece->get_name())->set_position_x($x);
        $this->get_piece($piece->get_name())->set_position_y($y);
        $this->update_board();
        if (isset($loser) and $loser){
            return $loser == "W" ? "B" : "W"; #=Winner
        }
    }
    function check_check($king_piece){
        $king_name = $king_piece->get_name();
        $x = $king_piece->get_position_x();
        $y = $king_piece->get_position_y();
        $color = $king_piece->get_color();


        $king_piece->set_type("R");
        $moves = $this->get_moves($king_name);
        for ($i = 0; $i <= 7; $i++){for ($j = 0; $j <= 7; $j++){
            if ($moves[$i][$j] and $this->board[$i][$j]!="emp"){if ($this->board[$i][$j]->get_type() == "R" or $this->board[$i][$j]->get_type() == "Q"){
                if ($this->board[$i][$j]->get_color() != $color){
                    $king_piece->set_type('K');
                    return "True";}}}}}

        $king_piece->set_type("B");
        $moves = $this->get_moves($king_name);
        for ($i = 0; $i <= 7; $i++){for ($j = 0; $j <= 7; $j++){
            if ($moves[$i][$j] and $this->board[$i][$j]!="emp"){if ($this->board[$i][$j]->get_type() == "B" or $this->board[$i][$j]->get_type() == "Q"){
                if ($this->board[$i][$j]->get_color() != $color){
                    $king_piece->set_type('K');
                    return "True";}}}}}

        $king_piece->set_type("N");
        $moves = $this->get_moves($king_name);
        for ($i = 0; $i <= 7; $i++){for ($j = 0; $j <= 7; $j++){
            if ($moves[$i][$j] and $this->board[$i][$j]!="emp"){if ($this->board[$i][$j]->get_type() == "N"){
                if ($this->board[$i][$j]->get_color() != $color){
                    $king_piece->set_type('K');
                    return "True";}}}}}
        
        
        if (0<=$x-1 and $x-1<=7 and 0<=$y+1 and $y+1<=7 and $this->board[$x-1][$y+1]!="emp"){
            if ($this->board[$x-1][$y+1]->get_type() == "P" and $this->board[$x+1][$y+1]->get_color() != $color){return "True";}}
        if (0<=$x+1 and $x+1<=7 and 0<=$y+1 and $y+1<=7 and $this->board[$x-1][$y+1]!="emp"){   
            if ($this->board[$x+1][$y+1]->get_type() == "P" and $this->board[$x+1][$y+1]->get_color() != $color){return "True";}}
        
        $king_piece->set_type('K');
        return False;
    }
}
?>