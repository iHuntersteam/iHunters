<?php

namespace Bakautov\Auth;

class AuthProvider
{
    /**
     * Метод аутентификации пользователя по логину/паролю
     * credentials имеет вид [ username => 'example', password => 'example_passw' ]
     * @param $credentials
     * @return array
     * @throws \Exception
     */
    public function login($credentials)
    {
        if (!$this->validateCredentials($credentials)) {
            throw new \Exception("Для авторизации нужны username и password");
        }

        $user = $this->getUserFromBase($credentials);

        if (!password_verify($credentials['password'], $user->password)) {
            throw new \Exception("Не верные аутентификационные данные");
        }

        return [
            'id' => $user->id,
            'username' => $user->username,
            'email'    => $user->email,
            'is_admin' => $user->is_admin,
            'my_admin' => $user->my_admin,
        ];
    }

    /**
     * Проверяет наличие данных аутентификации
     * @param $credentials
     * @return bool
     * @throws \Exception
     */
    private function validateCredentials($credentials)
    {
        if (!is_array($credentials)) {
            throw new \Exception("Данные для аутентификации должны быть в виде массива");
        }

        return isset($credentials['username']) and isset($credentials['password']);
    }

    /**
     * Метод подключения к базе
     * @return \mysqli
     * @throws \Exception
     */
    private function makeDbConnection()
    {
        $host = 'localhost';
        $engine = 'mysql';
        $user = 'root';
        $password = 'Nsk!2015';
        $dbname = 'ihunters';
        $dsn = "$engine:dbname=$dbname;host=$host";

        $dbRes = new \mysqli($host, $user, $password, $dbname);

        if (mysqli_connect_errno()) {
            throw new \Exception("Не удалось подключиться: %s\n", mysqli_connect_error());
        }

        return $dbRes;
    }

    /**
     * Получение пользователя из базы
     * Если нет пользователя, кидается исключение
     * @param $credentials
     * @return object|\stdClass
     * @throws \Exception
     */
    private function getUserFromBase($credentials)
    {
        $dbResource = $this->makeDbConnection();
        $stmt = $dbResource->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param('s', $credentials['username']);
        if (!$stmt->execute()) {
            print_r($dbResource->error_list);
            exit();
        }
        $stmt->execute();

        $result = $stmt->get_result();

        if (!$result->num_rows) {
            throw new \Exception("В базе нет пользователя с username {$credentials['username']}");
        }

        return $row = $result->fetch_object();
    }
}