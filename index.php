<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization'); 
    header("Content-Type:Application/json");

    require __DIR__ . '/vendor/autoload.php';

    use Dotenv\Dotenv;

    try {
        $dotenv=Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        spl_autoload_register(function($class) {
            require __DIR__ . "/server/{$class}.php";  
        });

        $parts=explode('/',$_SERVER['REQUEST_URI']);

        if($parts[1]!='expense'){

            http_response_code(404);

            echo json_encode(['message'=>'Endpoint not valid']);
        }else {
            $id = $parts[2] ;
            if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
                header('Access-Control-Allow-Origin: *');  
                header('Access-Control-Allow-Methods: POST,PUT,GET,OPTIONS');
                header('Access-Control-Allow-Headers: Content-Type, Authorization');
                header('Access-Control-Allow-Credentials: true');
                http_response_code(201); 
                exit(); 
            }
            $database=new DataBase($_ENV['DB_HOST'],$_ENV['DB_USER'],$_ENV['DB_NAME'],"");

            $gateWay=new GateWay($database);

            $controller=new Controller($gateWay);

            $controller->getRequestType($_SERVER['REQUEST_METHOD'],$id);
        }
    } catch (PDOException $th) {
        http_response_code(404);
        echo json_encode(['sucess'=>false,'message'=>$th->getMessage()]);
    }
?>