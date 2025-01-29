<?php
    class DataBase{
        public function __construct(private string $host,
                                    private string $user,
                                    private string $name,
                                    private string $pass) {
        }

        public function connectDB():PDO{
            try {
                $pdo="mysql:host={$this->host};dbname={$this->name}";

                $db=new PDO($pdo,$this->user,$this->pass);

                return $db;
            } catch (PDOException $th) {

                http_response_code(500);
                
                echo json_encode(['message'=>$th->getMessage()]);
            }
        }
    }
    
?>