<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/models/Area.php';

class AreaController {

    public function index() {
        $areaModel = new Area();
        $stmt = $areaModel->readAll();
        require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/areas/list_area.php';
    }

    public function add() {
        require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/areas/add_area.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $areaModel = new Area();
            $areaModel->name = $_POST['name'];

            if ($areaModel->create()) {
                header("Location: /cultureconnect/areas");
                exit();
            } else {
                echo "Error creating area.";
            }
        }
    }

    public function edit() {
        if (isset($_GET['id'])) {
            $areaModel = new Area();
            $areaModel->id = $_GET['id'];
            $stmt = $areaModel->readOne();
            $area = $stmt->fetch(PDO::FETCH_ASSOC);
            require $_SERVER['DOCUMENT_ROOT'] . '/cultureconnect/views/areas/edit_area.php';
        } else {
            header("Location: /cultureconnect/areas");
            exit();
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $areaModel = new Area();
            $areaModel->id = $_POST['id'];
            $areaModel->name = $_POST['name'];

            if ($areaModel->update()) {
                header("Location: /cultureconnect/areas");
                exit();
            } else {
                echo "Error updating area.";
            }
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $areaModel = new Area();
            $areaModel->id = $_GET['id'];
            if ($areaModel->delete()) {
                header("Location: /cultureconnect/areas");
                exit();
            } else {
                echo "Error deleting area.";
            }
        }
    }
}
?>
