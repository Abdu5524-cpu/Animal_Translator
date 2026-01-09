<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class AnimalModel extends Database {
    private function getUsernameFromToken(): ?string {
        $headers = getallheaders();
        if (!isset($headers['Authorization'])) return null;

        $authHeader = $headers['Authorization'];
        if (!str_starts_with($authHeader, "Bearer ")) return null;

        $token = trim(substr($authHeader, 7));
        $tokenPath = __DIR__ . "/../../tokens/$token.txt";

        if (!file_exists($tokenPath)) return null;
        return trim(file_get_contents($tokenPath));
    }

    public function getPurchases() {
        $username = $this->getUsernameFromToken();
        if (!$username) {
            http_response_code(401);
            echo json_encode(["error" => "Unauthorized or missing token."]);
            exit();
        }

        return $this->select(
            "SELECT * FROM `animals` WHERE username=?",
            ["s", $username]
        );
    }

    public function createPurchase($unused, $animal) {
        global $config;
        $username = $this->getUsernameFromToken();
        if (!$username) {
            http_response_code(401);
            echo json_encode(["error" => "Unauthorized or missing token."]);
            exit();
        }

        $result = $config->query("SELECT COUNT(*) AS count FROM animals");
        $row = $result->fetch_assoc();
        $purchase_id = $row['count'] + 1;

        $currentTimestamp = date('Y-m-d H:i:s');

        return $this->select(
            "INSERT INTO `animals` (`purchase_id`, `username`, `animal`, `time_date`) VALUES (?, ?, ?, ?)",
            ["isss", $purchase_id, $username, $animal, $currentTimestamp]
        );
    }

    public function deletePurchase($purchase_id) {
        $username = $this->getUsernameFromToken();
        if (!$username) {
            http_response_code(401);
            echo json_encode(["error" => "Unauthorized or missing token."]);
            exit();
        }

        return $this->select(
            "DELETE FROM `animals` WHERE purchase_id=? AND username=?",
            ["is", $purchase_id, $username]
        );
    }

    public function updatePurchase($purchase_id, $animal) {
        $username = $this->getUsernameFromToken();
        if (!$username) {
            http_response_code(401);
            echo json_encode(["error" => "Unauthorized or missing token."]);
            exit();
        }

        return $this->select(
            "UPDATE animals SET animal=? WHERE purchase_id=? AND username=?",
            ["sis", $animal, $purchase_id, $username]
        );
    }
}
