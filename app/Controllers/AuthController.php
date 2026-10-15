<?php
/**
 * Stonk Games Store - Auth Controller
 * Xử lý xác thực đăng nhập, đăng ký tài khoản và đăng xuất
 */

class AuthController extends Controller {

    /**
     * Xử lý đăng nhập
     */
    public function login(): void {
        if ($this->isLoggedIn()) {
            if ($this->isAdmin()) {
                $this->redirect('admin');
            } else {
                $this->redirect('');
            }
        }

        $error = null;
        $username = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {
                $error = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.';
            } else {
                $userModel = $this->model('User');
                $user = $userModel->findByUsername($username);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user'] = [
                        'id' => (int)$user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'role' => $user['role']
                    ];

                    $this->setFlash('success', 'Đăng nhập thành công!');

                    if ($user['role'] === 'admin') {
                        $this->redirect('admin');
                    } else {
                        $this->redirect('');
                    }
                } else {
                    $error = 'Tên đăng nhập hoặc mật khẩu không chính xác.';
                }
            }
        }

        $this->view('auth/login', [
            'pageTitle' => __('nav_login') . ' | Stonk Games Store',
            'error' => $error,
            'username' => $username
        ]);
    }

    /**
     * Xử lý đăng ký tài khoản mới
     */
    public function register(): void {
        if ($this->isLoggedIn()) {
            $this->redirect('');
        }

        $error = null;
        $data = [
            'username' => '',
            'email' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');

            $data['username'] = $username;
            $data['email'] = $email;

            if (empty($username) || empty($email) || empty($password)) {
                $error = 'Vui lòng điền đầy đủ thông tin.';
            } elseif (strlen($password) < 6) {
                $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
            } elseif ($password !== $confirmPassword) {
                $error = 'Mật khẩu xác nhận không khớp.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Địa chỉ email không hợp lệ.';
            } else {
                $userModel = $this->model('User');

                if ($userModel->findByUsername($username)) {
                    $error = 'Tên đăng nhập đã tồn tại trên hệ thống.';
                } elseif ($userModel->findByEmail($email)) {
                    $error = 'Email này đã được sử dụng bởi tài khoản khác.';
                } else {
                    $userModel->create([
                        'username' => $username,
                        'email' => $email,
                        'password' => $password,
                        'role' => 'user'
                    ]);

                    $this->setFlash('success', 'Đăng ký tài khoản thành công! Vui lòng đăng nhập.');
                    $this->redirect('auth/login');
                }
            }
        }

        $this->view('auth/register', [
            'pageTitle' => __('nav_register') . ' | Stonk Games Store',
            'error' => $error,
            'data' => $data
        ]);
    }

    /**
     * Xử lý đăng xuất
     */
    public function logout(): void {
        unset($_SESSION['user']);
        $this->setFlash('info', 'Bạn đã đăng xuất tài khoản thành công.');
        $this->redirect('auth/login');
    }

    /**
     * Xem thông tin hồ sơ tài khoản hiện tại
     */
    public function profile(): void {
        if (!$this->isLoggedIn()) {
            $this->setFlash('danger', 'Vui lòng đăng nhập để xem thông tin tài khoản.');
            $this->redirect('auth/login');
        }

        $userId = (int)$_SESSION['user']['id'];
        $userModel = $this->model('User');
        $user = $userModel->findById($userId);

        if (!$user) {
            unset($_SESSION['user']);
            $this->setFlash('danger', 'Tài khoản không tồn tại hoặc đã bị khóa.');
            $this->redirect('auth/login');
        }

        $orderModel = $this->model('Order');
        $userOrders = $orderModel->getByUserId($userId);

        $this->view('auth/profile', [
            'pageTitle'  => 'Thông Tin Tài Khoản | Stonk Games Store',
            'user'       => $user,
            'orders'     => $userOrders,
            'orderCount' => count($userOrders)
        ]);
    }

    /**
     * Cập nhật thông tin hồ sơ tài khoản (Email, Mật khẩu)
     */
    public function updateProfile(): void {
        if (!$this->isLoggedIn()) {
            $this->redirect('auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('profile');
        }

        $userId = (int)$_SESSION['user']['id'];
        $userModel = $this->model('User');
        $user = $userModel->findById($userId);

        if (!$user) {
            unset($_SESSION['user']);
            $this->redirect('auth/login');
        }

        $email = trim($_POST['email'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash('danger', 'Địa chỉ email không hợp lệ.');
            $this->redirect('profile');
        }

        // Kiểm tra email trùng lặp với tài khoản khác
        $existingUser = $userModel->findByEmail($email);
        if ($existingUser && (int)$existingUser['id'] !== $userId) {
            $this->setFlash('danger', 'Email này đã được sử dụng bởi một tài khoản khác.');
            $this->redirect('profile');
        }

        $passwordToUpdate = null;
        // Nếu người dùng yêu cầu đổi mật khẩu
        if (!empty($newPassword)) {
            if (empty($currentPassword)) {
                $this->setFlash('danger', 'Vui lòng nhập mật khẩu hiện tại để xác nhận thay đổi mật khẩu.');
                $this->redirect('profile');
            }

            if (!password_verify($currentPassword, $user['password'])) {
                $this->setFlash('danger', 'Mật khẩu hiện tại không chính xác.');
                $this->redirect('profile');
            }

            if (strlen($newPassword) < 6) {
                $this->setFlash('danger', 'Mật khẩu mới phải có độ dài tối thiểu 6 ký tự.');
                $this->redirect('profile');
            }

            if ($newPassword !== $confirmPassword) {
                $this->setFlash('danger', 'Mật khẩu xác nhận không khớp với mật khẩu mới.');
                $this->redirect('profile');
            }

            $passwordToUpdate = $newPassword;
        }

        $userModel->updateProfile($userId, $email, $passwordToUpdate);
        $_SESSION['user']['email'] = $email;

        $this->setFlash('success', 'Cập nhật thông tin tài khoản thành công!');
        $this->redirect('profile');
    }
}
