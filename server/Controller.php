<?php
    class Controller{
        public function __construct(private GateWay $gateWay) {
        }

        public function getRequestType(string $method,? string $id): void {
            if ($id == "login") {
                $this->handleLoginRequest($method);
            } elseif ($id == "") {
                $this->getCollectionRequest($method);
            } else if($id=="budget"){
                $this->handleBudget($method);
            }else {
                $this->getResourceRequest($method,$id);
            }
        }
    
        public function handleLoginRequest(string $method): void {
            if ($method === 'POST') {
                $data = json_decode(file_get_contents("php://input"), true);
                if (isset($data['username']) && isset($data['password'])) {
                    $this->gateWay->validateUserLogin($data['username'], $data['password']);
                } else {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Username and password are required']);
                }
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            }
            
        }
        public function handleBudget(string $method): void {
            if ($method === 'POST') {
                $data = json_decode(file_get_contents("php://input"), true);
                
                if ($data !== null) {  
                    $this->gateWay->createBudget($data);
                } else {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Invalid data provided']);
                }
            } else {
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            }
        }
        
        public function getResourceRequest($method,$id) {
            switch ($method) {
                case 'GET':
                    $data=$this->gateWay->getUserData($id);

                    echo json_encode(['success'=>true,'data'=>$data]);
                case 'PUT':
                    $request = file_get_contents("php://input");
                    $data = json_decode($request, true);
                    if ($data['type'] == 'update') {
                        $this->gateWay->updateData((int)$id, $data['data']);
                    }else if($data['type']=='delete'){
                        $this->gateWay->deleteData((int)$id,$data['data']);
                    }else {
                        $this->gateWay->addData((int)$id,$data['data']);
                    }
                    break;
                default:
                    http_response_code(402);
                    echo json_encode(['success'=>false,'message'=>'Invalid request method']);
                    break;
            }
        }
        public function getCollectionRequest(string $method):void{
            switch ($method) {
                case 'GET':
                    $data=$this->gateWay->getAllData();

                    echo json_encode(['success'=>true,'data'=>$data]);
                    break;
                case 'POST':
                    $data=(array)json_decode(file_get_contents("php://input"), true);
                    
                    $this->gateWay->insertData($data);
                    break;
                default:
                    http_response_code(402);
                    echo json_encode(['success'=>false,'message'=>'Invalid request method']);

                    break;
            }
        }
    }
    
?>