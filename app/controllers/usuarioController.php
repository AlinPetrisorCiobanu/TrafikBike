<?php

require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/user.php';

class UsuarioController extends Controller {

    public function index()
    {
        if (!isset($_SESSION['token'])) {
            return $this->redirect('/login');
        }

        $userModel = new User();
        $userId = $_SESSION['id'] ?? null;

        $user = $userId ? $userModel->getUserById($userId) : null;

        if (!$user) {
            return $this->redirect('/');
        }

        return $this->view("usuario/index", [
            "styles" => ["usuario.css"],
            "usuario" => $user
        ]);
    }

    public function update()
    {
        if (!isset($_SESSION['token'])) {
            return $this->redirect('/login');
        }

        $userModel = new User();

        // 🔹 Obtener ID desde GET
        $id_user = $_GET['id_user'] ?? null;

        if (!$id_user) {
            return $this->redirect('/');
        }

        // 🔹 Permisos
        $currentUser = $userModel->getUserById($_SESSION['id']);
        if ($currentUser['id_user'] != $id_user && $currentUser['id_role'] != 1) {
            return $this->redirect('/');
        }

        // 🔹 POST → actualizar
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;

            try {
                $userModel->updateUser($id_user, $data);
                $_SESSION['mensaje'] = "Usuario actualizado correctamente.";
            } catch (Exception $e) {
                $_SESSION['error'] = "Error al actualizar: " . $e->getMessage();
            }

            return $this->redirect("/usuario/update?id_user=$id_user");
        }

        // 🔹 GET → cargar datos
        $user = $userModel->getUserById($id_user);

        return $this->view("usuario/modificar/index", [
            "styles" => ["usuario.css"],
            "usuario" => $user
        ]);
    }
}