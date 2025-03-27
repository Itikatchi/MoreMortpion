<?php
require 'vendor/autoload.php';
require 'GameController.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class MorpionServe implements MessageComponentInterface {
    protected $clients;
    protected $game;

    public function __construct() {
        $this->clients = new \SplObjectStorage();
        $this->game = new GameController(); // Instance du jeu
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "Nouvelle connexion ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo "Message reçu : $msg\n";

        $data = json_decode($msg, true);
        if (!$data || !isset($data["player"], $data["row"], $data["col"])) {
            $from->send(json_encode(["error" => "Format invalide"]));
            return;
        }

        $response = $this->game->playMove($data["player"], $data["row"], $data["col"]);

        // Envoie la mise à jour à tous les joueurs
        foreach ($this->clients as $client) {
            $client->send(json_encode($response));
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connexion fermée ({$conn->resourceId})\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Erreur : {$e->getMessage()}\n";
        $conn->close();
    }
}

// Lancer le serveur
$server = \Ratchet\Server\IoServer::factory(
    new \Ratchet\Http\HttpServer(
        new \Ratchet\WebSocket\WsServer(
            new TicTacToeServer()
        )
    ),
    8080
);

echo "Serveur WebSocket en cours d'exécution sur ws://localhost:8080\n";
$server->run();