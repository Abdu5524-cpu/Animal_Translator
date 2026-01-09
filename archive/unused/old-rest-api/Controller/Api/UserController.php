<?php
class UserController extends BaseController {
    /** 
* "/user/list" Endpoint - Get list of users 
*/

    public function createAction() {
        $message = '';

        if(strtoupper($_SERVER["REQUEST_METHOD"]) == 'POST') {    
            try{   
                global $config;

                $input = json_decode(file_get_contents("php://input"), true);
                $username = $input['username'] ?? null;
                $password = $input['password'] ?? null;

                if (!$username || !$password) {
                    $message = 'Missing username or password';
                    $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                }
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


                $userModel = new UserModel();
                $sql = $userModel->createUser($username, $hashedPassword);
                $responseData = json_encode($sql);

            } catch (Error $e) {
                $message = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $message = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        if (!$message) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $message)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
    
    public function deleteAction() {
        $message = '';

        if(strtoupper($_SERVER["REQUEST_METHOD"]) == 'DELETE') {    
            try{
                global $config;

                $input = json_decode(file_get_contents("php://input"), true);
                $username = $input['username'] ?? null;

                if (!$username) {
                    $message = 'Missing username';
                    $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                }
                $userModel = new UserModel();
                $value = $userModel->deleteUser($username);
                $responseData = json_encode($value);

            } catch (Error $e) {
                $message = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $message = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }
        
        if (!$message) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $message)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    public function updateAction() {
        $message = '';

        if(strtoupper($_SERVER["REQUEST_METHOD"]) == 'PATCH') {    
            try{
                global $config;
        
                $input = json_decode(file_get_contents("php://input"), true);
                $username = $input['username'] ?? null;
                $password = $input['password'] ?? null;

                if (!$username || !$password) {
                    $message = 'Missing username or password';
                    $strErrorHeader = 'HTTP/1.1 400 Bad Request';
                }
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $userModel = new UserModel();
                $value = $userModel->updateUser($username, $hashedPassword);
                $responseData = json_encode($value);

            } catch (Error $e) {
                $message = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $message = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }



        if (!$message) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $message)), 
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

}


