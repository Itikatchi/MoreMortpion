<?php
class GameController {
    private $board;
    private $turn;

    public function __construct() {
        // Initialise un plateau vide et définit le premier joueur
        $this->board = [
            ["", "", ""],
            ["", "", ""],
            ["", "", ""]
        ];
        $this->turn = "X";  // X commence toujours
    }

    public function playMove($player, $row, $col) {
        // Vérifie si c'est bien le tour du joueur
        if ($player !== $this->turn) {
            return ["error" => "Ce n'est pas votre tour !"];
        }

        // Vérifie si la case est libre
        if ($this->board[$row][$col] !== "") {
            return ["error" => "Case déjà occupée !"];
        }

        // Place le symbole du joueur sur la grille
        $this->board[$row][$col] = $player;

        // Vérifie s'il y a un gagnant
        if ($this->checkWin($player)) {
            return ["winner" => "$player a gagné !", "board" => $this->board];
        }

        // Vérifie s'il y a un match nul
        if ($this->isDraw()) {
            return ["draw" => "Match nul !", "board" => $this->board];
        }

        // Change de joueur
        $this->turn = ($this->turn === "X") ? "O" : "X";

        return ["board" => $this->board, "turn" => $this->turn];
    }

    private function checkWin($player) {
        // Vérifie les lignes, colonnes et diagonales
        for ($i = 0; $i < 3; $i++) {
            if ($this->board[$i][0] === $player && $this->board[$i][1] === $player && $this->board[$i][2] === $player) return true; // Ligne
            if ($this->board[0][$i] === $player && $this->board[1][$i] === $player && $this->board[2][$i] === $player) return true; // Colonne
        }
        if ($this->board[0][0] === $player && $this->board[1][1] === $player && $this->board[2][2] === $player) return true; // Diagonale \
        if ($this->board[0][2] === $player && $this->board[1][1] === $player && $this->board[2][0] === $player) return true; // Diagonale /

        return false;
    }

    private function isDraw() {
        foreach ($this->board as $row) {
            if (in_array("", $row)) return false; // Il reste une case vide
        }
        return true;
    }
}
?>