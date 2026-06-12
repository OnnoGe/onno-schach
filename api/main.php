<?php
include_once('class_player.php');
include_once('class_board.php');
include_once('class_game.php');
include_once('class_piece.php');
session_start();


# -------- Ausgabe
echo '<!DOCTYPE html>
    <html>
    <head>
    <link rel="stylesheet" href="/chess.css">
    </head>
    <body>';



if (isset($_GET["active_piece"])){
    $chess = $_SESSION["chess"];
    $chess->get_board()->show($chess->get_active_player()->get_color(), $_GET['active_piece']);
    echo "<br><br><br><br><a href=".$_SERVER["PHP_SELF"]."><button class='neustart'>Neustart</button></a>";
    $_SESSION["chess"] = $chess;

} else if (isset($_GET["turn"])){
    $chess = $_SESSION["chess"];
    $active_piece = substr($_GET['turn'], 0, 3);
    $selcted_x = substr($_GET['turn'], 3, 1);
    $selected_y = substr($_GET['turn'], 4, 1);
    $Win_color = $chess->get_board()->move_piece_and_check_win($chess->get_board()->get_piece($active_piece), $selcted_x, $selected_y);
    if($Win_color){                                                               #Wenn Spiel endet
        $Winner = $chess->get_player_1()->get_color() == $Win_color ? $chess->get_player_1()->get_name() : $chess->get_player_2()->get_name();
    }else{
    $chess->change_active_player($_GET['turn']);                           #Im Spiel
    $chess->get_board()->show($chess->get_active_player()->get_color());
    echo "<br><br><br><br><a href=".$_SERVER["PHP_SELF"]."><button class='neustart'>Neustart</button></a>";
    }                              
    if ($Win_color){
        echo "<div class='darker_background'></div>
        <<div class='you_won'><video src='/assets/win.mp4' class='win_vid' type='video/mp4' autoplay></video><br>Herzlichen Glückwunsch ".$Winner.",<br> du hast gewonnen!<br><a href=".$_SERVER["PHP_SELF"]."><button style='transform = translate(-50%, -100%);' class='neustart'>Neustart</button></a></div>";
    }
    $_SESSION["chess"] = $chess;

} else {
    if (isset($_GET["name1"]) && isset($_GET["name2"])){
        $chess = new Game();
        $chess->set_status(0);
        $chess->set_player_1(new Player($_GET["name1"], "W"));
        $chess->set_player_2(new Player($_GET["name2"], "B"));
        $chess->set_board(new Board());
        $chess->set_active_player($chess->get_player_1());
        $chess->set_status(1);
        $chess->get_board()->show($chess->get_active_player()->get_color());
        $_SESSION["chess"] = $chess;
    } else {
        echo <<<EOT
        <style>
            body {
                font-family: 'Inter', sans-serif;
                margin: 40px;
                color: #222;
                margin: 0;
            }
            main {
                height: 100dvh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            div.content {
                text-align: center;
                width: 700px;
                border-radius: 30px;
                background-color: rgb(230, 201, 169);
                padding: 30px;
            }
            h1 {text-align: center;}
            input, button {
                font-size: 25px;
                border-radius: 15px;
                padding: 10px;
                border: 2px solid rgb(31, 19, 5);
                background-color: rgb(230, 208, 185)
            }
            button {background-color: rgb(228, 183, 135);}
            #tag {position: absolute; top: 30px; left: 30px; cursor: pointer; color: black;}
            #tag:hover {color: gray;}
        </style>
        <main>
            <a href="https://onnog.netlify.app/" target="_blank"><div id="tag">von Onno Gellermann</div></a>
            <div class="content">
                <h1>Namen eingeben</h2>
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <input type="text" placeholder="Max Mustermann" id="name1">
                    <input type="text" placeholder="Franz Badstübner" id="name2">
                    <button id="enter">✓</button>
                </div>
                <script>
                    function enter() {
                        const name1 = document.getElementById("name1").value.trim();
                        const name2 = document.getElementById("name2").value.trim();
                        if (name1 === "") {
                            document.getElementById("name1").style.backgroundColor = "red";
                            return;
                        }
                        if (name2 === "") {
                            document.getElementById("name2").style.backgroundColor = "red";
                            return;
                        }
                        window.open("/?name1=" + encodeURIComponent(name1) + "&name2=" + encodeURIComponent(name2), "_self");
                    }

                    document.getElementById("enter").addEventListener("click", function() {
                        enter();
                    })

                    document.addEventListener("keydown", function(event) {
                        if (event.key === "Enter") {
                            enter();
                        }
                    })
                </script>
            </div>
        </main>
        EOT;
    }
}



echo '</body>
</html> ';


?>
