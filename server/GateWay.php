<?php
    class GateWay{

        private $conn;

        public function __construct(DataBase $database) {

            $this->conn=$database->connectDB();

        }
        public function deleteData(int $id, array $data): void {
            if (isset($data)) {
                try {
        
                    $query = "UPDATE Expense 
                              SET expenses = ?
                              WHERE user_id = ?";
        
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute([json_encode($data), $id]);
        
                    echo json_encode(['success' => true, 'message' => 'Deleted successfully']);
                } catch (PDOException $pdo_err) {
                    http_response_code(402);
                    echo json_encode(["success" => false, "message" => $pdo_err->getMessage()]);
                }
            } else {
                http_response_code(402);
                echo json_encode(['success' => false, 'message' => 'Invalid data is given']);
            }
        }
        
        public function addData(int $id, string $data): void {
            if (isset($data)) {
                try {
                    $decodedData = json_decode($data, true);
                    if ($decodedData === null && json_last_error() !== JSON_ERROR_NONE) {
                        http_response_code(400);
                        echo json_encode(["success" => false, "message" => "Invalid JSON data provided"]);
                        return;
                    }
        
                    $query = 'UPDATE Expense 
                              SET expenses = JSON_ARRAY_APPEND(expenses, "$", JSON_OBJECT("id", ?, "amount", ?, "category", ?, "description", ?, "date", ?))
                              WHERE user_id = ?';
        
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute([
                        (int)$decodedData['id'], 
                        (int)$decodedData['amount'], 
                        $decodedData['category'], 
                        $decodedData['description'], 
                        $decodedData['date'], 
                        $id
                    ]);
        
                    echo json_encode(['success' => true, 'message' => 'Added successfully']);
                } catch (PDOException $pdo_err) {
                    http_response_code(402);
                    echo json_encode(["success" => false, "message" => $pdo_err->getMessage()]);
                }
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'No data provided']);
            }
        }
        
        public function updateData(int $id, array $data): void {
            if (isset($data)) {
                try {
                    $expenses = json_encode($data);
                    $query = "UPDATE Expense SET expenses = ? WHERE user_id = ?";
                    $stmt = $this->conn->prepare($query);
                    $stmt->execute([$expenses, $id]);
        
                    echo json_encode(['success' => true, 'message' => 'Updated successfully']);
                } catch (PDOException $pdo_err) {
                    http_response_code(402);
                    echo json_encode(["success" => false, "message" => $pdo_err->getMessage()]);
                }
            } else {
                http_response_code(402);
                echo json_encode(['success' => false, 'message' => 'Invalid data is given']);
            }
        }
        
        public function getUserData($id) : array{
            $data=[];

            try {
                $query="SELECT user_id,user_name,expenses,budget FROM Expense WHERE user_id = {$id}";

                $stmt=$this->conn->query($query);

                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                while($row){
                    $row['expenses'] = json_decode($row['expenses'], true); 
                    array_push($data,$row);
                    $row=$stmt->fetch(PDO::FETCH_ASSOC);
                }
                return $data;
            } catch (PDOException $pdo) {
                http_response_code(402);
                echo json_encode(["success"=>false,"message"=>$pdo->getMessage()]);
            }
        }
        public function insertData(array $data) :void {
            if (isset($data)) {
                try {
                    $name=$data['username'];
                    $password=password_hash($data['password'],PASSWORD_DEFAULT);
                    $phoneno=$data['phoneno'];
                    $email=$data['email'];
                    $expense='[]';
                    $budget='[]';

                    $query="INSERT INTO Expense(user_name,pass_word,phone_no,email_id,expenses,budget)
                            VALUE(?,?,?,?,?,?)";

                    $stmt=$this->conn->prepare($query);

                    $stmt->execute([$name,$password,$phoneno,$email,$expense,$budget]);

                    echo json_encode(['success'=>true,'message'=>'Inserted successfully']);
                } catch (PDOException $exp) {
                    http_response_code(402);
                    echo json_encode(["success"=>false,"message"=>$exp->getMessage()]);
                }
            }else{
                http_response_code(402);
                echo json_encode(['success'=>false,'message'=>'Invalid user has been given']);
            }
        }
        public function getAllData():array{
            $data=[];

            try {
                $query="SELECT user_id,user_name,expenses,budget FROM Expense";

                $stmt=$this->conn->query($query);

                $row=$stmt->fetch(PDO::FETCH_ASSOC);
                while($row){
                    $row['expenses'] = json_decode($row['expenses'], true); 
                    $row['budget']=json_decode($row['budget'],true);
                    array_push($data,$row);
                    $row=$stmt->fetch(PDO::FETCH_ASSOC);
                }
                return $data;
            } catch (PDOException $pdo) {
                http_response_code(402);
                echo json_encode(["success"=>false,"message"=>$pdo->getMessage()]);
            }
        }
        public function validateUserLogin(string $username, string $password): void {
            try {
                ob_start(); 
                header('Content-Type: application/json');
        
                $query = "SELECT * FROM Expense WHERE user_name = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([$username]);
        
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
                if ($user && password_verify($password, $user['pass_word'])) {
                    unset($user['pass_word']); 
                    $expenses = json_decode($user['expenses'], true);  
                    $budget=json_decode($user['budget'],true);
            
                    $user['expenses'] = $expenses;
                    $user['budget']=$budget;
                    echo json_encode(['success' => true, 'message' => 'Login successful', 'data' => $user]);
                } else {
                    http_response_code(401);
                    echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
                }
        
                ob_end_flush(); 
            } catch (PDOException $e) {
                http_response_code(500);
                error_log($e->getMessage()); 
                echo json_encode(['success' => false, 'message' => 'An internal server error occurred.']);
            }
        }
        public function createBudget(array $data){
            try {
                $budget = json_encode($data['budget']);
                $id = $data['id'];
        
                $query = 'UPDATE Expense
                          SET budget = ?
                          WHERE user_id = ?';
        
                $stmt = $this->conn->prepare($query);
                $stmt->execute([$budget, $id]);
        
                echo json_encode(["success" => true, "message" => 'Budget updated successfully']);
            } catch (PDOException $err) {
                http_response_code(500); 
                echo json_encode(['success' => false, 'message' => $err->getMessage()]);
            }
        }
        
    
    }
    
?>