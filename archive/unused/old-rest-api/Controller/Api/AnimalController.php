<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../Model/AnimalModel.php';

class AnimalController extends BaseController {

    public function readAction() {
        if (strtoupper($_SERVER["REQUEST_METHOD"]) === 'GET') {
            try {
                $animalModel = new AnimalModel();
                $value = $animalModel->getPurchases();
                $this->sendOutput(json_encode($value), ['Content-Type: application/json', 'HTTP/1.1 200 OK']);
            } catch (Error $e) {
                $this->sendOutput(json_encode(['error' => $e->getMessage() . ' Something went wrong! Please contact support.']), ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']);
            }
        } else {
            $this->sendOutput(json_encode(['error' => 'Method not supported']), ['Content-Type: application/json', 'HTTP/1.1 422 Unprocessable Entity']);
        }
    }

    public function createAction() {
        if (strtoupper($_SERVER["REQUEST_METHOD"]) === 'POST') {
            try {
                $input = json_decode(file_get_contents("php://input"), true);
                $animal = $input['animal'] ?? null;

                if (!$animal) {
                    $this->sendOutput(json_encode(['error' => 'Missing animal']), ['Content-Type: application/json', 'HTTP/1.1 400 Bad Request']);
                    return;
                }

                $animalModel = new AnimalModel();
                $sql = $animalModel->createPurchase(null, $animal);
                $this->sendOutput(json_encode($sql), ['Content-Type: application/json', 'HTTP/1.1 200 OK']);
            } catch (Error $e) {
                $this->sendOutput(json_encode(['error' => $e->getMessage() . ' Something went wrong! Please contact support.']), ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']);
            }
        } else {
            $this->sendOutput(json_encode(['error' => 'Method not supported']), ['Content-Type: application/json', 'HTTP/1.1 422 Unprocessable Entity']);
        }
    }

    public function deleteAction() {
        if (strtoupper($_SERVER["REQUEST_METHOD"]) === 'DELETE') {
            try {
                $input = json_decode(file_get_contents("php://input"), true);
                $purchase_id = $input['purchase_id'] ?? null;

                $animalModel = new AnimalModel();
                $value = $animalModel->deletePurchase($purchase_id);
                $this->sendOutput(json_encode($value), ['Content-Type: application/json', 'HTTP/1.1 200 OK']);
            } catch (Error $e) {
                $this->sendOutput(json_encode(['error' => $e->getMessage() . ' Something went wrong! Please contact support.']), ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']);
            }
        } else {
            $this->sendOutput(json_encode(['error' => 'Method not supported']), ['Content-Type: application/json', 'HTTP/1.1 422 Unprocessable Entity']);
        }
    }

    public function updateAction() {
        if (strtoupper($_SERVER["REQUEST_METHOD"]) === 'PATCH') {
            try {
                $input = json_decode(file_get_contents("php://input"), true);
                $purchase_id = $input['purchase_id'] ?? null;
                $animal = $input['animal'] ?? null;

                $animalModel = new AnimalModel();
                $value = $animalModel->updatePurchase($purchase_id, $animal);
                $this->sendOutput(json_encode($value), ['Content-Type: application/json', 'HTTP/1.1 200 OK']);
            } catch (Error $e) {
                $this->sendOutput(json_encode(['error' => $e->getMessage() . ' Something went wrong! Please contact support.']), ['Content-Type: application/json', 'HTTP/1.1 500 Internal Server Error']);
            }
        } else {
            $this->sendOutput(json_encode(['error' => 'Method not supported']), ['Content-Type: application/json', 'HTTP/1.1 422 Unprocessable Entity']);
        }
    }
}
