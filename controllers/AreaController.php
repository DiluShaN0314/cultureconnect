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
            $errors = [];
            $areaModel = new Area();
            
            // Duplicate Check
            $stmtCheck = $areaModel->conn->prepare("SELECT id FROM areas WHERE name = :name LIMIT 1");
            $stmtCheck->execute([':name' => $_POST['name']]);
            if ($stmtCheck->rowCount() > 0) {
                $errors['name'] = "An area with this name already exists.";
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                $_SESSION['old_input'] = $_POST;
                header("Location: /cultureconnect/areas/add");
                exit();
            }

            $areaModel = new Area();
            $areaModel->name = $_POST['name'];

            if ($areaModel->create()) {
                $_SESSION['success'] = "Area created successfully.";
                header("Location: /cultureconnect/areas");
                exit();
            } else {
                $_SESSION['errors'] = ["Error creating area."];
                header("Location: /cultureconnect/areas/add");
                exit();
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
            $id = $_POST['id'];
            $errors = [];
            $areaModel = new Area();
            
            // Duplicate Check
            $stmtCheck = $areaModel->conn->prepare("SELECT id FROM areas WHERE name = :name AND id != :id LIMIT 1");
            $stmtCheck->execute([':name' => $_POST['name'], ':id' => $id]);
            if ($stmtCheck->rowCount() > 0) {
                $errors['name'] = "Another area with this name already exists.";
            }

            if (!empty($errors)) {
                $_SESSION['errors'] = $errors;
                header("Location: /cultureconnect/areas/edit?id=" . $id);
                exit();
            }

            $areaModel = new Area();
            $areaModel->id = $id;
            $areaModel->name = $_POST['name'];

            if ($areaModel->update()) {
                $_SESSION['success'] = "Area updated successfully.";
                header("Location: /cultureconnect/areas");
                exit();
            } else {
                $_SESSION['errors'] = ["Error updating area."];
                header("Location: /cultureconnect/areas/edit?id=" . $id);
                exit();
            }
        }
    }

    public function delete() {
        if (isset($_GET['id'])) {
            $areaModel = new Area();
            $areaModel->id = $_GET['id'];
            if ($areaModel->delete()) {
                $_SESSION['success'] = "Area deleted successfully.";
                header("Location: /cultureconnect/areas");
                exit();
            } else {
                echo "Error deleting area.";
            }
        }
    }
}
?>
